<?php
class GeoLocatableCity extends GeoLocatableAppModel {

    var $belongsTo = array(
        'Region' => array(
            'className' => 'GeoLocatable.GeoLocatableRegion',
            'foreignKey' => 'region_id'
        )
    );

    var $hasMany = array(
        'GeoLocatable' => array(
            'className' => 'GeoLocatable.GeoLocatable',
            'foreignKey' => 'city_id'
        )
    );

    function getCityId($data) {
        if (!isset($data['GeoLocatableCity'])) {
            return 0;
        }
        foreach (array('region_id', 'name', 'latitude', 'longitude') as $field) {
            if (!isset($data['GeoLocatableCity'][$field])) {
                return 0;
            }
        }
        $id = $this->find('first', array(
            'conditions' => array(
                $this->alias . '.region_id' => $data['GeoLocatableCity']['region_id'],
                $this->alias . '.name' => $data['GeoLocatableCity']['name'],
                $this->alias . '.latitude' => $data['GeoLocatableCity']['latitude'],
                $this->alias . '.longitude' => $data['GeoLocatableCity']['longitude'],
            ),
            'fields' => array(
                $this->alias . '.id'
            )
        ));
        if ($id !== false) {
            return (int)$id[$this->alias]['id'];
        }
        $this->create();
        if ($this->save(array(
            $this->alias => $data['GeoLocatableCity']
        ))) {
            return $this->id;
        }
        return 0;
    }

}
