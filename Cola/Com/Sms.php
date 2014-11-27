<?php

class Cola_Com_Sms
{

    /**
     *
     * @var string host
     * @var string name
     * @var int port
     * @var int $appId
     */
    protected static $_config = array();

    private static $_uri = null;

    public function __construct($config)
    {
        self::$_config = $config;
        self::$_uri = 'http://' . $config['host'] . ':' . $config['port'];
    }

    /**
     * Config
     *
     * Set or get configration
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public static function config($name = null, $value = null)
    {
        if (null == $name) {
            return self::$_config;
        }
        if (null == $value) {
            return isset(self::$_config[$name]) ? self::$_config[$name] : null;
        }
        self::$_config[$name] = $value;

        return $this;
    }

    /**
     * Put data to queue.
     * @param string $data json data
     * @param string $uri queue uri
     * @param string $name queue name
     * @return Boolean
     */
    public static function put($phoneNumber, $data, $uri = null, $name = null, $appId = null)
    {
        try {

            if (empty($phoneNumber)) return FALSE;

            null == $uri && $uri = self::$_uri;

            null == $name && $name = self::config('name');

            null == $appId && $appId = self::config('appId');

            $data = '@@' . $appId . '@@' . $data . ':' . $phoneNumber;
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

}