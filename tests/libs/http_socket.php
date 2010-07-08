<?php
class HttpSocket extends Object {

    var $locations = array(
        'local' => 'a:14:{s:14:"geoplugin_city";s:9:"Some City";s:16:"geoplugin_region";s:11:"Some Region";s:18:"geoplugin_areaCode";s:1:"0";s:17:"geoplugin_dmaCode";s:1:"0";s:21:"geoplugin_countryCode";s:2:"00";s:21:"geoplugin_countryName";s:12:"Some Country";s:23:"geoplugin_continentCode";s:2:"00";s:18:"geoplugin_latitude";s:12:"12.209400177";s:19:"geoplugin_longitude";s:14:"-5.64949989319";s:20:"geoplugin_regionCode";s:2:"11";s:20:"geoplugin_regionName";s:11:"Some Region";s:22:"geoplugin_currencyCode";s:3:"000";s:24:"geoplugin_currencySymbol";s:3:"000";s:27:"geoplugin_currencyConverter";d:0.66015214219999995837184769698069430887699127197265625;}',
        '123.123.123.123' => 'a:14:{s:14:"geoplugin_city";s:15:"Some Other City";s:16:"geoplugin_region";s:11:"Some Region";s:18:"geoplugin_areaCode";s:1:"0";s:17:"geoplugin_dmaCode";s:1:"0";s:21:"geoplugin_countryCode";s:2:"00";s:21:"geoplugin_countryName";s:18:"Some Other Country";s:23:"geoplugin_continentCode";s:2:"00";s:18:"geoplugin_latitude";s:12:"12.209400177";s:19:"geoplugin_longitude";s:14:"-5.64949989319";s:20:"geoplugin_regionCode";s:2:"11";s:20:"geoplugin_regionName";s:11:"Some Region";s:22:"geoplugin_currencyCode";s:3:"000";s:24:"geoplugin_currencySymbol";s:3:"000";s:27:"geoplugin_currencyConverter";d:0.66015214219999995837184769698069430887699127197265625;}'
    );

    var $called = false;

    function get($url, $ip) {
        if ($ip['ip'] === env('REMOTE_ADDR')) {
            $ip['ip'] = 'local';
        }
        if (isset($this->locations[$ip['ip']])) {
            $this->called = true;
            return $this->locations[$ip['ip']];
        }
    }

    function reset() {
        $this->called = false;
    }

}
