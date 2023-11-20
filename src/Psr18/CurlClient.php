<?php

namespace Joussin\Component\HttpClient\Psr18;


class CurlClient
{


    private function curl_exec(array $options = [])
    {
        $ch = curl_init();

        curl_setopt_array($ch, $options);

        if( ! $result = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        $httpCode = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

        $response = [
            'content' => $result,
            'code' => $httpCode
        ];

        curl_close($ch);
        return $response;
    }


    function curl_exec_with_options($url, array $options = [])
    {
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,

            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_FORBID_REUSE => 1,



//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_ENCODING => "",
//            CURLOPT_HTTPHEADER => array(
//                "cache-control: no-cache",
//                "content-type: application/json",
//                "x-api-key: whateveriyouneedinyourheader"
//            ),
        );

        return $this->curl_exec($options + $defaults);
    }

    function curl_get($url, array $params = null, array $options = [])
    {
        $url = $url. (strpos($url, '?') === false ? '?' : ''). http_build_query($params);

        return $this->curl_exec_with_options($url, $options);
    }

    function curl_post($url, array $params = null, array $options = [])
    {
        $options[CURLOPT_POST] = 1;
        $options[CURLOPT_POSTFIELDS] = http_build_query($params);
        return $this->curl_exec_with_options($url, $options);
    }


    function curl_put($url, array $params = null, array $options = [])
    {
        $options[CURLOPT_CUSTOMREQUEST] = "PUT";
        $options[CURLOPT_POSTFIELDS] = http_build_query($params);
        return $this->curl_exec_with_options($url, $options);
    }

    function curl_patch($url, array $params = null, array $options = [])
    {
        $options[CURLOPT_CUSTOMREQUEST] = "PATCH";
        $options[CURLOPT_POSTFIELDS] = http_build_query($params);
        return $this->curl_exec_with_options($url, $options);
    }


    function curl_delete($url, array $params = null, array $options = [])
    {
        $options[CURLOPT_CUSTOMREQUEST] = "DELETE";
        return $this->curl_exec_with_options($url, $options);
    }

    
    public function request($method, $url, $params): array
    {
    
        if($method == 'GET')
        {
            $result = $this->curl_get($url, $params);
        }

        else if($method == 'POST')
        {
            $result = $this->curl_post($url, $params);
        }

        else if($method == 'PUT')
        {
            $result = $this->curl_put($url, $params);
        }
        else if($method == 'PATCH')
        {
            $result = $this->curl_patch($url, $params);
        }
        else if($method == 'DELETE')
        {
            $result = $this->curl_delete($url, $params);
        }

        return $result;
        
    }

}