<?php
class GeoLocatableRegion extends GeoLocatableAppModel {

    var $hasMany = array(
        'City' => array(
            'className' => 'GeoLocatable.GeoLocatableCity',
            'foreignKey' => 'region_id'
        )
    );

    function getRegionId($data) {
        if (!isset($data['GeoLocatableRegion'])) {
            return 0;
        }
        foreach (array('country_code', 'name', 'code') as $field) {
            if (!isset($data['GeoLocatableRegion'][$field])) {
                return 0;
            }
        }
        $id = $this->find('first', array(
            'conditions' => array(
                $this->alias . '.country_code' => $data['GeoLocatableRegion']['country_code'],
                $this->alias . '.name' => $data['GeoLocatableRegion']['name'],
                $this->alias . '.code' => $data['GeoLocatableRegion']['code']
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
            $this->alias => $data['GeoLocatableRegion']
        ))) {
            return $this->id;
        }
        return 0;
    }

}
