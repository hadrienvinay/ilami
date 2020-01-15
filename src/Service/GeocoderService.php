<?php
namespace App\Service;

class GeocoderService
{
    private $arrContextOptions = array(
                       "ssl" => array(
                           "verify_peer" => false,
                           "verify_peer_name" => false,
                       ),
                   );
    private $geocoder = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyA0wuGfkqLD67jR6NfcC8mm4EuUROGis_I&address=%s&sensor=false";
           
    public function convertAddress($address)
    {
        $pos = array();
         // Get latitude and longitude of the event address
        if(!empty($address)){
            $query = sprintf($this->geocoder, urlencode(utf8_encode($address)));
            $result = json_decode(file_get_contents($query, false, stream_context_create($this->arrContextOptions)));

            if (empty($result->results)) {
                return [0,0];
            } else {
                $json = $result->results[0];
                $pos[0] = (float)$json->geometry->location->lat;
                $pos[1] = (float)$json->geometry->location->lng;
                return $pos;
            }
        }
    }
}

