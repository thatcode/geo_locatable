<?php
App::import('Core', 'Model');
App::import('Model', 'ModelBehaviour');
require_once APP . DS . 'plugins' . DS . 'geo_locatable' . DS . 'tests' . DS . 'libs' . DS . 'http_socket.php';

class GeoLocatableTestCase extends CakeTestCase {

    var $fixtures = array('plugin.geo_locatable.geo_locatable', 'plugin.geo_locatable.geo_locatable_city', 'plugin.geo_locatable.geo_locatable_region');

    function skip() {
        $this->skipUnless(App::import('Behavior', 'Convertable.Convertable'), 'Convertable Plugin must be loaded');
    }

    function startTest() {
        $this->GeoLocatable = ClassRegistry::init('GeoLocatable.GeoLocatable');
    }

    function testSaveIp() {

        $result = $this->GeoLocatable->saveIp();
        $expected = array(
            'GeoLocatable' => array(
                'ip' => '127.0.0.1',
                'city_id' => 1,
                'id' => 1,
                'modified' => time()
            ),
            'GeoLocatableCity' => array(
                'name' => 'Some City',
                'latitude' => '12.209400177',
                'longitude' => '-5.64949989319',
                'region_id' => 1,
                'id' => 1,
                'GeoLocatableRegion' => array(
                    'country_code' => '00',
                    'name' => 'Some Region',
                    'code' => 11,
                    'id' => 1
                )
            )
        );
        $this->assertEqual($result, $expected);

        sleep(2);
        $result = $this->GeoLocatable->saveIp();
        $this->assertEqual($result, $expected);

        $result = $this->GeoLocatable->saveIp('123.123.123.123');
        $expected = array(
            'GeoLocatable' => array(
                'ip' => '123.123.123.123',
                'city_id' => 2,
                'id' => 1,
                'modified' => time()
            ),
            'GeoLocatableCity' => array(
                'name' => 'Some Other City',
                'latitude' => '12.209400177',
                'longitude' => '-5.64949989319',
                'region_id' => 1,
                'id' => 2,
                'GeoLocatableRegion' => array(
                    'country_code' => '00',
                    'name' => 'Some Region',
                    'code' => 11,
                    'id' => 1
                )
            )
        );
        $this->assertEqual($result, $expected);

        sleep(2);
        $result = $this->GeoLocatable->saveIp('123.123.123.123');
        $this->assertEqual($result, $expected);

    }

}
