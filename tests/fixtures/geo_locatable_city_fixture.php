<?php
class GeoLocatableCityFixture extends CakeTestFixture {

    var $name = 'GeoLocatableCity';

    var $table = 'geo_locatable_cities';

    var $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'region_id' => array('type' => 'integer', 'length' => 11),
        'name' => array('type' => 'string', 'length' => 100),
        'latitude' => 'text',
        'longitude' => 'text'
    );

}
