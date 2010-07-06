<?php
App::import('Libs', 'GeoLocatable.Settings');
class GeoLocatable extends GeoLocatableAppModel {

    var $actsAs = array('Convertable.Convertable' => array(
        'ip' => array(
            'beforeSave' => 'ipToLong',
            'afterFind' => 'longToIp'
        )
    ));

    var $belongsTo = array(
        'City' => array(
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

    function save($ip = null) {
        if ($ip === null) {
            $ip = env('REMOTE_ADDR');
        }
        $recent = $this->find('first', array(
            'conditions' => array(
                'GeoLocatable.ip' => $ip,
                'GeoLocatable.modified >=' => (time() - $this->__settings['cache'])
            ),
            'order' => 'GeoLocatable.id DESC'
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
        $geo_data['GeoLocatableCity']['region_id'] = $this->City->Region->getRegionId(array(
            'GeoLocatableRegion' => $geo_data['GeoLocatableRegion']
        ));
        $geo_data['GeoLocatable']['city_id'] = $this->City->getCityId(array(
            'GeoLocatableCity' => $geo_data['GeoLocatableCity']
        ));
        return $this->save(array(
            'GeoLocatable' => $geo_data['GeoLocatable']
        ));
    }

}
