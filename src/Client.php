<?php

namespace WorldNewsGroup\Marketo;

use WorldNewsGroup\Marketo\Exception\ErrorException;

class Client {
    public static function send($method, $path, $params = [], $env = null, $headers = []) {
        if( is_null($env) ) {
            $env = Environment::currentEnvironment();
        }

        if( is_null($env) ) {
            throw new \Exception('The client API has not been configured properly.  Please call Environment::configure() before making calls.');
        }
        
        $opts = [
            'allow_redirects'=>true,
            'http_errors'=>false
        ];

        if( $method == 'GET' ) {
            if( count($params) > 0 ) {
                $opts['query'] = \http_build_query($params);
            }
        }
        else if( $method == 'POST' ) {
            $opts['form_params'] = \http_build_query($params);
        }
        else {
            throw new \Exception("Invalid http method $method");
        }

        $token = self::getToken($env);

        $query = [];
        if( isset($params['query']) ) {
            foreach($params['query'] as $key=>$vals ) {
                if( count($vals) > 0 ) {
                    $query[$key] = $vals;
                }
            }
        }

        $query['access_token'] = $token['access_token'];

        $url = $env->getEndpoint() . "/$path?" . \http_build_query($query);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if( isset($params['body']) && count($params['body']) > 0 ) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_POSTFIELDS, \http_build_query($params['body']));
        }

        $rawResponse = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($rawResponse, true);

        if( $json['success'] ) {
            return new Result($rawResponse);
        }
        else if( !$json['success'] && isset($json['errors']) ) {
            throw new ErrorException($json['errors']);
        }
    }

    private static function getToken($env) {
        $url = "https://" . $env->getMunchkinId() . ".mktorest.com/identity/oauth/token";

        $params = [
            'grant_type'=>'client_credentials',
            'client_id'=>$env->getClientId(),
            'client_secret'=>$env->getClientSecret()
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url . "?" . \http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $rawResponse = curl_exec($ch);

        curl_close($ch);

        return json_decode($rawResponse, true);
    }
}