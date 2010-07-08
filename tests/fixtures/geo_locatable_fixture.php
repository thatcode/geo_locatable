<?php
class GeoLocatableFixture extends CakeTestFixture {

    var $name = 'GeoLocatable';

    var $table = 'geo_locatables';

    var $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'modified' => array('type' => 'integer', 'length' => 10),
        'ip' => 'text',
        'city_id' => array('type' => 'integer', 'length' => 11),
    );

}
