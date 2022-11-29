<?php

namespace WorldNewsGroup\Marketo;

use WorldNewsGroup\Marketo\Exception\ErrorException;

class Client {
    /**
     * send
     * 
     * @param   mixed[]    $params
     * @param   String[]   $headers
     * 
     * @return Result | ErrorException | bool | string | null
     */
    public static function send(String $method, String $path, $params = [], Environment $env = null, $headers = [], $return_raw = false) {
        if( is_null($env) ) {
            $env = Environment::currentEnvironment();
        }

        if( is_null($env) ) {
            throw new \Exception('The client API has not been configured properly.  Please call Environment::configure() before making calls.');
        }

        $token = self::getToken($env);

        $query = [];
        if( isset($params['query']) ) {
            foreach($params['query'] as $key=>$vals ) {
                if( is_array($vals) ) {
                    $query[$key] = implode(',', $vals);
                }
                else {
                    $query[$key] = $vals;
                }
            }
        }

        $query['access_token'] = $token['access_token'];

        $url = $env->getEndpoint() . "/$path?" . \http_build_query($query);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if( isset($params['body']) && count($params['body']) > 0 && in_array($method, ['POST', 'DELETE'])) {
            $headers[] = "Content-Type: application/json";
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params['body']));
        }
        else if( isset($params['form-encode']) && count($params['form-encode']) > 0 && in_array($method, ['POST', 'DELETE']) ) {
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params['form-encode']));  
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $rawResponse = curl_exec($ch);
        curl_close($ch);

        if( $return_raw ) {
            return $rawResponse;
        }

        $json = json_decode($rawResponse, true);

        if( $json['success'] ) {
            return new Result($json);
        }
        else {
            throw new ErrorException($json['errors']);
        }

    }

    /**
     * @internal
     */
    private static function getToken(Environment $env) {
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