<?php

/**
 * Cola_Com_Queue_Httpsqs
 *
 * use http://code.google.com/p/httpsqs/
 */
class Cola_Com_Queue_Httpsqs extends Cola_Com_Queue_Abstract
{

    private $_uri = null;

    public function __construct($params)
    {
        $params += array('host' => '127.0.0.1', 'port' => 1212);
        parent::__construct($params);
        $this->_uri = 'http://' . $params['host'] . ':' . $params['port'];
    }

    /**
     * Config
     *
     * Set or get configration
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function config($name = null, $value = null)
    {
        if (null == $name) {
            return $this->_params;
        }
        if (null == $value) {
            return isset($this->_params[$name]) ? $this->_name[$name] : null;
        }
        $this->_params[$name] = $value;

        return $this;
    }

    /**
     * Put data to queue.
     * @param string $data json data
     * @param string $uri queue uri
     * @param string $name queue name
     * @return Boolean
     */
    public function put($data, $uri = null, $name = null)
    {
        try {
            if (null == $uri) {
                $uri = $this->_uri;
            }

            if (null == $name) {
                $name = $this->_name;
            }

            $context = array(
                'opt' => 'put',
                'name' => $name,
                'data' => $data
            );

            $data = Cola_Com_Http::get($uri, $context);
            if ('HTTPSQS_PUT_OK' == $data) {
                return TRUE;
            }
            return FALSE;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get a queue data
     * @param string $name queue name
     * @return mixed
     */
    public function get($uri = null, $name = null)
    {
        if (null == $name) {
            $name = $this->_name;
        }

        if (null == $uri) {
            $uri = $this->_uri;
        }

        $context = array(
            'opt' => 'get',
            'name' => $name
        );

        return Cola_Com_Http::get($uri, $context);
    }

}