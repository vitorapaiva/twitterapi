<?php

namespace App\Models\WebApi;

use Abraham\TwitterOAuth\TwitterOAuth;



class TwitterApi 
{

    private $oauth_access_token;
    private $oauth_access_token_secret;
    private $consumer_key;
    private $consumer_secret;
    
    public function __construct(){
        $this->oauth_access_token = "48435300-FCaFI5ArOeMWby14spFlSBmHIlpuQ1SzbyiG6BLi3";
        $this->oauth_access_token_secret = "OA3hVHiciutspkLPs02syEDFKB242hkD5OczhFVifMviS";
        $this->consumer_key = "LxpOVle8anNQ4xul4J7LxBzPF";
        $this->consumer_secret = "JTphkrctVWAMrZPUtrNVH6PAHROsuszGRGbXDxXAh7pM0r9oiX";
    }

    public function connectTwitter(){
        return new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->oauth_access_token, $this->oauth_access_token_secret);
    }


    public function getUserInfo($user){
        $connection = $this->connectTwitter();
        return $connection->get("account/settings", ["q" => $user]);
    }
}


