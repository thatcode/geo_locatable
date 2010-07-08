<?php
App::import('Core', 'Model');
App::import('Model', 'ModelBehaviour');
require_once APP . DS . 'plugins' . DS . 'geo_locatable' . DS . 'tests' . DS . 'libs' . DS . 'http_socket.php';

class GeoLocatableRegionTestCase extends CakeTestCase {

    var $fixtures = array('plugin.geo_locatable.geo_locatable', 'plugin.geo_locatable.geo_locatable_city', 'plugin.geo_locatable.geo_locatable_region');

    function skip() {
        $this->skipUnless(App::import('Behavior', 'Convertable.Convertable'), 'Convertable Plugin must be loaded');
    }

    function startTest() {
        $this->GeoLocatableRegion = ClassRegistry::init('GeoLocatable.GeoLocatableRegion');
    }

    function testGetCityId() {

        $result = $this->GeoLocatableRegion->getRegionId(array(
            'GeoLocatableRegion' => array(
                'name' => 'Some Region',
                'code' => '00',
                'country_code' => '00'
            )
        ));
        $expected = 1;
        $this->assertEqual($result, $expected);

        $result = $this->GeoLocatableRegion->getRegionId(array(
            'GeoLocatableRegion' => array(
                'name' => 'Some Region',
                'code' => '00',
                'country_code' => '00'
            )
        ));
        $this->assertEqual($result, $expected);

    }

}
