<?php 

namespace App\Models\WebApi;

class GoogleApi {

    protected $urlGeoCode;
    protected $key;
    protected $url;

    public function __construct()
    {
        $this->urlGeoCode = 'https://maps.googleapis.com/maps/api/geocode/';
        $this->key='AIzaSyBjihpY7kHj5JVVwdo_ADRWcEAIsOKmq-k';
    }

    public function getLocationGoogle($endereco)
    {
        $url = $this->urlGeoCode."json?address=" . urlencode($endereco) . "&key=".$this->key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $geoloc = json_decode(curl_exec($ch), true);

        return $geoloc;
    }
    public function getInverseLocationGoogle($endereco)
    {
        $url = $this->urlGeoCode."json?latlng=" . preg_replace("/[^0-9.,\-+]/", "",$endereco) . "&key=".$this->key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $geoloc = json_decode(curl_exec($ch), true);
        
        return $geoloc;
    }

    public function findAddressGoogle($geoloc)
    {
        $array['result']=false;
        $count=0;
        if(is_array($geoloc['results']))
        {
            foreach($geoloc['results'] as $result)
            {
                if(isset($result['address_components'][6]['long_name']))
                {
                    if(strlen($result['address_components'][6]['long_name'])>6)
                    {
                        $array['result']=true;
                        $array[$count]['logradouro']=$result['address_components'][0]['long_name'];
                        $array[$count]['logradouro'].=", ".$result['address_components'][1]['long_name'];
                        $array[$count]['logradouro'].=", ".$result['address_components'][2]['long_name'];
                        $array[$count]['logradouro'].=", ".$result['address_components'][3]['long_name'];
                        $array[$count]['logradouro'].=", ".$result['address_components'][4]['short_name'];
                        $array[$count]['logradouro'].=", ".$result['address_components'][6]['long_name'];
                    }
                }
                $count++;
            }
        }
        return $array;
    }

    public function returnAddressListGoogle($endereco)
    {
        $result=$this->findAddressGoogle($this->getLocationGoogle($endereco));
        if($result['result']==false){
            $result=$this->findAddressGoogle($this->getInverseLocationGoogle($endereco));
        }
        return $result;
    }
}