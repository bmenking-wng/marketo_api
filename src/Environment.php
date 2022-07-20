<?php

namespace WorldNewsGroup\Marketo;

class Environment {

    private static $myself;

    private $client_id;
    private $client_secret;
    private $munchkin_id;

    private $endPoint;

    public function __construct($client_id, $client_secret, $munchkin_id) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->munchkin_id = $munchkin_id;

        $this->endPoint = "https://" . $munchkin_id . ".mktorest.com/rest/v1/";
    }

    public static function configure($client_id, $client_secret, $munchkin_id) {
        self::$myself = new self($client_id, $client_secret, $munchkin_id);
    }

    public static function currentEnvironment() {
        return self::$myself;
    }

    public function getEndpoint() {
        return $this->endPoint;
    }

    public function getMunchkinId() {
        return $this->munchkin_id;
    }

    public function getClientId() {
        return $this->client_id;
    }

    public function getClientSecret() {
        return $this->client_secret;
    }
}