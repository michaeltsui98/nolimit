<?php

class Cola_Com_Email
{

    /**
     *
     * @var string host
     * @var int port
     * @var string name
     */
    protected static $_config = array();

    /**
     *
     * @var string $_uri
     */
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
    public static function put($mailTo, $subject, $data, $mailType = 'HTML', $uri = null, $name = null)
    {
        try {
            if (empty($mailTo)) return FALSE;

            null == $uri && $uri = self::$_uri;

            null == $name && $name = self::config('name');

            $context = array(
                'opt' => 'put',
                'name' => $name,
                'data' => json_encode(
                        array(
                            'email' => $mailTo,
                            'subject' => $subject,
                            'data' => $data,
                            'type' => $mailType
                        )
                )
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