<?php
class GeoLocatableRegionFixture extends CakeTestFixture {

    var $name = 'GeoLocatableRegion';

    var $table = 'geo_locatable_regions';

    var $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'country_code' => array('type' => 'string', 'length' => 2),
        'code' => array('type' => 'string', 'length' => 5),
        'name' => array('type' => 'string', 'length' => 50),
    );

}
