<?php
/**
 * eapi client
 * @author sizz<sizzflair87430@gmail.com>
 * @version 1.0 2014/11/18
 */
class Helper_Eapi {
    
    //到时候会换
    private  $_clientKey = 'f00723b8d95f968a85978bb5bb7a6eff';
    private  $_eapiUrl = 'http://dev-yanxun.dodoedu.com/index.php/eapi/';
    
    public  function  getClientKey()
    {
        return $this->_clientKey;
    }
    
    public  function getEapiUrl()
    {
        return $this->_eapiUrl;
    }
    
    
    //构建CURL
    /**
     * 请求入口
     * @param $method
     * @param $url
     * @param $parameters
     */
    public  function EApiRequest($method, $url, $objectId,  $parameters)
    {
        switch ($method) {
            case 'GET':
                $headers = array();
                $body = http_build_query($parameters);
                return $this->_httpCurl($url, $method, $objectId, $body, $headers);
                break;
            case 'POST':
                $headers = array();
                $body = http_build_query($parameters);
                return $this->_httpCurl($url, $method, $objectId, $body, $headers);
                break;
            case 'PUT':
                $headers = array();
                $body = http_build_query($parameters);
                return $this->_httpCurl($url, $method, $objectId, $body, $headers);
                break;
            case 'DELETE':
                $headers = array();
                $body = http_build_query($parameters);
                return $this->_httpCurl($url, $method, $objectId, $body, $headers);
                break;
            default :
                echo "尚不支持这种请求模式";
        }
    }

    private function _httpCurl($url, $method, $objectId = '',$postfields = '', $headers = array())
    {

        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, 'EApi');
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'POST');
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields.'&sign='.$this->_genRequestSign($postfields, $this->_clientKey, $objectId));
                }
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($postfields)) {
                    $url = "{$url}?{$postfields}".'&sign='.$this->_genRequestSign($postfields, $this->_clientKey, $objectId);
                }
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields.'&sign='.$this->_genRequestSign($postfields, $this->_clientKey, $objectId));
                break;
            case 'GET':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'GET');
                if (!empty($postfields)) {
                    $url = "{$url}?{$postfields}".'&sign='.$this->_genRequestSign($postfields, $this->_clientKey, $objectId);
                }
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields.'&sign='.$this->_genRequestSign($postfields, $this->_clientKey, $objectId));
                break;
            case 'PUT':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'PUT');
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields.'&sign='.$this->_genRequestSign($postfields, $this->_clientKey, $objectId));
                }
                break;
        }
        //$headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
        curl_setopt($ci, CURLOPT_URL, $url );
    //    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($ci,CURLOPT_HTTPHEADER,array("X-HTTP-Method-Override: $method"));
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
        $response = curl_exec($ci);
        return  json_decode($response,TRUE);
    }

    /**
     * @param $inQueryString
     * @param $inClientKey
     * @param $inObjectId
     * @return string
     */
    private function _genRequestSign($inQueryString, $inClientKey, $inObjectId)
    {
        $returnString = md5($inQueryString.$inClientKey.$inObjectId);
        return $returnString;
    }
}

