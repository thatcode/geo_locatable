<?php
App::import('Core', 'Model');
App::import('Model', 'ModelBehaviour');
require_once APP . DS . 'plugins' . DS . 'geo_locatable' . DS . 'tests' . DS . 'libs' . DS . 'http_socket.php';

class GeoLocatableCityTestCase extends CakeTestCase {

    var $fixtures = array('plugin.geo_locatable.geo_locatable', 'plugin.geo_locatable.geo_locatable_city', 'plugin.geo_locatable.geo_locatable_region');

    function skip() {
        $this->skipUnless(App::import('Behavior', 'Convertable.Convertable'), 'Convertable Plugin must be loaded');
    }

    function startTest() {
        $this->GeoLocatableCity = ClassRegistry::init('GeoLocatable.GeoLocatableCity');
    }

    function testGetCityId() {

        $result = $this->GeoLocatableCity->getCityId(array(
            'GeoLocatableCity' => array(
                'name' => 'Some City',
                'latitude' => '12.209400177',
                'longitude' => '-5.64949989319',
                'region_id' => 1
            )
        ));
        $expected = 1;
        $this->assertEqual($result, $expected);

        $result = $this->GeoLocatableCity->getCityId(array(
            'GeoLocatableCity' => array(
                'name' => 'Some City',
                'latitude' => '12.209400177',
                'longitude' => '-5.64949989319',
                'region_id' => 1
            )
        ));
        $this->assertEqual($result, $expected);

    }

}
