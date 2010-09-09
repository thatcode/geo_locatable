<?php
Configure::load('GeoLocatable.config');
class GeoLocatable extends GeoLocatableAppModel {

    var $actsAs = array('Convertable.Convertable' => array(
        'ip' => array(
            'beforeSave' => 'ipToLong',
            'afterFind' => 'longToIp'
        )
    ), 'Containable');

    var $belongsTo = array(
        'GeoLocatableCity' => array(
            'className' => 'GeoLocatable.GeoLocatableCity',
            'foreignKey' => 'city_id'
        )
    );

    var $__settings = array(
        'cache' => 43200
    );

    function __construct($id = false, $table = null, $ds = null) {
        $settings = Configure::read('GeoLocatable.Settings');
        if (is_array($settings)) {
            $this->__settings = array_merge($this->__settings, $settings);
        }
        parent::__construct($id, $table, $ds);
    }

    function saveIp($ip = null) {
        if ($ip === null) {
            $ip = env('REMOTE_ADDR');
        }
        $recent = $this->find('first', array(
            'conditions' => array(
                'GeoLocatable.ip' => $ip,
                'GeoLocatable.modified >=' => (time() - $this->__settings['cache'])
            ),
            'order' => 'GeoLocatable.id DESC',
            'contain' => array(
                'GeoLocatableCity' => array(
                    'GeoLocatableRegion' => array(
                    )
                )
            )
        ));
        if ($recent !== false) {
            return $recent;
        }
        return $this->_getGeoData($ip);
    }

    function _getGeoData($ip = null) {
        if ($ip === null) {
            $ip = env('REMOTE_ADDR');
        }
        App::import('Core', 'HttpSocket');
        $HttpSocket = new HttpSocket();
        $geo_data = unserialize($HttpSocket->get('http://www.geoplugin.net/php.gp', array('ip' => $ip)));
        foreach (array('city', 'countryCode', 'regionName', 'regionCode') as $key) {
            if (!isset($geo_data['geoplugin_' . $key]) || strlen($geo_data['geoplugin_' . $key]) === 0) {
                $geo_data['geoplugin_' . $key] = '';
            }
        }
        foreach (array('latitude', 'longitude') as $key) {
            if (!isset($geo_data['geoplugin_' . $key]) || strlen($geo_data['geoplugin_' . $key]) === 0) {
                $geo_data['geoplugin_' . $key] = 0;
            }
        }
        $geo_data = array(
            'GeoLocatable' => array(
                'ip' => $ip
            ),
            'GeoLocatableCity' => array(
                'name' => $geo_data['geoplugin_city'],
                'latitude' => $geo_data['geoplugin_latitude'],
                'longitude' => $geo_data['geoplugin_longitude']
            ),
            'GeoLocatableRegion' => array(
                'country_code' => $geo_data['geoplugin_countryCode'],
                'name' => $geo_data['geoplugin_regionName'],
                'code' => $geo_data['geoplugin_regionCode']
            )
        );
        $geo_data['GeoLocatableCity']['region_id'] = $this->GeoLocatableCity->GeoLocatableRegion->getRegionId(array(
            'GeoLocatableRegion' => $geo_data['GeoLocatableRegion']
        ));
        $geo_data['GeoLocatableRegion']['id'] = $geo_data['GeoLocatableCity']['region_id'];
        $geo_data['GeoLocatable']['city_id'] = $this->GeoLocatableCity->getCityId(array(
            'GeoLocatableCity' => $geo_data['GeoLocatableCity']
        ));
        $geo_data['GeoLocatableCity']['id'] = $geo_data['GeoLocatable']['city_id'];
        $geo = $this->find('first', array(
            'conditions' => array(
                'GeoLocatable.ip' => $ip,
                'GeoLocatable.city_id' => $geo_data['GeoLocatable']['city_id']
            )
        ));
        if ($geo === false) {
            $this->save(array(
                'GeoLocatable' => $geo_data['GeoLocatable']
            ));
            $geo_data['GeoLocatable']['id'] = $this->id;
        } else {
            $this->save(array(
                'GeoLocatable' => array(
                    'id' => $geo['GeoLocatable']['id']
                )
            ));
            $geo_data['GeoLocatable']['id'] = $geo['GeoLocatable']['id'];
        }
        $geo_data['GeoLocatable']['modified'] = time();
        $geo_data['GeoLocatableCity']['GeoLocatableRegion'] = $geo_data['GeoLocatableRegion'];
        unset($geo_data['GeoLocatableRegion']);
        return $geo_data;
    }

}
