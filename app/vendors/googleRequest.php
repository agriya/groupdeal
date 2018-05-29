<?php
class googleRequest
{
    var $gKey;
    var $code;
    var $Accuracy;
    var $latitude;
    var $longitude;
    var $address;
    var $city;
    var $country;
    var $error;
    var $zip;
    function GetRequest() 
    {
        if (strlen($this->gKey) > 1) {
            $q = str_replace(' ', '_', $this->address . ',' . $this->zip . '+' . $this->city . ',' . $this->country);
            if ($d = @fopen("http://maps.google.com/maps/geo?q=$q&output=csv", "r")) {
                $gcsv = @fread($d, 30000);
                @fclose($d);
                $output = array();
                $tmp = explode(",", $gcsv);
                // $this->code      = $tmp[0];
                // $this->Accuracy  = $tmp[1];
                $output[0] = $this->latitude = $tmp[2];
                $output[1] = $this->longitude = $tmp[3];
                return $output;
            } else {
                $error = "NO_CONNECTION";
            }
        } else {
            $error = "No Google Maps Api Key";
        }
    }
    function getMaxmindInfo() 
    {
        if ($f = @fopen("http://j.maxmind.com/app/geoip.js", "r")) {
            $gjs = @fread($f, 30000);
            @fclose($f);
            return ($gjs);
        }
    }
}
?>
