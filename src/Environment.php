<?php

namespace WorldNewsGroup\Marketo;

class Environment {

    /** @internal */
    private static Environment $myself;
    /** @internal */
    private String $client_id;
    /** @internal */
    private String $client_secret;
    /** @internal */
    private String $munchkin_id;
    /** @internal */
    private String $endPoint;

    /**
     * @internal
     */
    public function __construct(String $client_id, String $client_secret, String $munchkin_id) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->munchkin_id = $munchkin_id;

        $this->endPoint = "https://" . $munchkin_id . ".mktorest.com/";
    }

    public static function configure(String $client_id, String $client_secret, String $munchkin_id) {
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