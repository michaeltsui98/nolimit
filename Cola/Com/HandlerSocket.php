<?php

Class Cola_Com_HandlerSocket
{

    /**
     * Configuration
     *
     * @var array
     */
    protected $_config = array();

    /**
     * Database name
     * @var string
     */
    protected $_db;

    /**
     * Table
     * @var string
     */
    protected $_table;

    /**
     * only read port
     * @var integer
     */
    protected $_port = 9998;

    /**
     * write and read port
     * @var integer
     */
    protected $_portWr = 9999;

    /**
     * Database read connection
     *
     * @var object|resource|null
     */
    protected $_connection = null;

    /**
     * Database write and read  connection
     *
     * @var object|resource|null
     */
    protected $_writeConnection = null;

    /**
     *  Last error log
     *
     * @var string
     */
    protected $_errorLog;

    /**
     * Constructor.
     *
     * $config is an array of key/value pairs
     * containing configuration options.
     *
     * host           => (string) What host to connect to, defaults to localhost
     * port           => (string) The port of the database read
     * port_wr           => (string) The port of the database write, update, delete
     * database       => (string) The name of the database to user
     *
     * @param  array $config
     */

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->_config = (array) $config + array('host' => '127.0.0.1', 'port' => 9998, 'port_wr' => 9999, 'options' => array());

        extract($config);

        if (isset($port)) {
            $this->_port = $port;
        }

        if (isset($port_wr)) {
            $this->_port = $port_wr;
        }

        if (isset($database)) {
            $this->_db = $this->db($database);
        }

        if (isset($table)) {
            $this->_table = $this->table($table);
        }

        $this->connection();
    }

    /**
     * Get db connection
     *
     * @return resource
     */
    public function connection()
    {
        if (null === $this->_connection) {
            $this->_connection = $this->_connect(array('host' => $this->_config['host'], 'port' => $this->_port, 'options' => $this->_config['options']));
        }

        return $this->_connection;
    }

    /**
     * Get db write connection
     *
     * @return resource
     */
    public function WriteConnection()
    {
        if (null === $this->_writeConnection) {
            $this->_writeConnection = $this->_connect(array('host' => $this->_config['host'], 'port' => $this->_portWr, 'options' => $this->_config['options']));
        }

        return $this->_writeConnection;
    }

    /**
     * Create a HandlerSocket object and connects to the database.
     *
     * @param array $config
     * @return resource
     */
    protected function _connect($params)
    {
        extract($params);
        $connection = new HandlerSocket($host, $port, $options);
        $this->_connection = $connection;
        if (isset($this->_config['password'])) {
            if (!$this->_connection->auth($this->_config['password'])) {
                throw new Cola_Exception('HandlerSocket Auth Failed: bad password.');
            }
        }
        return $this->_connection;
    }

    /**
     * Select Database
     *
     * @param string $db
     * @return database name
     */
    public function db($db = null)
    {
        if ($db) {
            return $this->_db->$db;
        }

        return $this->_db;
    }

    /**
     * Select table
     *
     * @param string $table
     * @return table name
     */
    public function talbe($table = NULL)
    {
        if ($table) {
            return $this->_table->$table;
        }

        return $this->_table;
    }

    /**
     * select and return query result array.
     *
     * Pass the query and options as array objects (this is more convenient than the standard HandlerSocket API especially when caching)
     *
     * $query may contain:
     *   op - comparison operator, supported '=', '<', '<=', '>', '>='
     *   criteria - comparison values
     *   limit - limit number
     *   skip - skip number
     *
     * $options may contain:
     *   dbname - database name
     *   table - select table name
     *   fields - the fields to retrieve
     *   index - index name.
     *
     * @param array $query
     * @param array $options
     * @return mixed
     * */
    public function select($query = array(), $options = array())
    {
        $query += array('op' => '=', 'criteria' => array(), 'limit' => 1, 'skip' => 0,);
        $options += array('dbname' => '', 'table' => '', 'fields' => '', 'index' => '');

        extract($query);
        extract($options);
        if (empty($dbname)) $dbname = $this->db();
        if (empty($table)) $table = $this->talbe();
        if (empty($index)) $index = HandlerSocket::PRIMARY;

        if (!($this->_connection->openIndex(1, $dbname, $table, $index, $fields))) {
            $msg = $this->_connection->getError();
            $this->error($msg);
            throw new Cola_Exception($msg);
        }

        return $this->_connection->executeSingle(1, $op, $criteria, $limit, $skip);
    }

    /**
     * selectMulti and return query result array.
     *
     * Pass the query and options as array objects (this is more convenient than the standard HandlerSocket API especially when caching)
     *
     * $query may multi executeSingle parameter
     *
     * $options may contain:
     *   dbname - database name
     *   table - select table name
     *   fields - the fields to retrieve
     *   index - index name.
     *
     * @param array $query
     * @param array $options
     * @return mixed
     * */
    public function selectMulti($querys = array(), $options = array())
    {
        $options += array('dbname' => '', 'table' => '', 'fields' => '', 'index' => '');

        foreach ($querys as $key => $value) {
            $query[$key] = array(1, $value['op'], $value['criteria'], $value['limit'], $value['skip']);
        }

        extract($options);
        if (empty($dbname)) $dbname = $this->db();
        if (empty($table)) $table = $this->talbe();
        if (empty($index)) $index = HandlerSocket::PRIMARY;

        if (!($this->_connection->openIndex(1, $dbname, $table, $index, $fields))) {
            $msg = $this->_connection->getError();
            $this->error($msg);
            throw new Cola_Exception($msg);
        }

        return $this->_connection->executeMulti($query);
    }

    /**
     * Insert data
     *
     *  $data is insert fields array values
     *
     * $options may contain:
     *   dbname - database name
     *   table - select table name
     *   fields - the fields to retrieve
     *   index - index name.
     *
     * @param array $data
     * @param array $options
     * @return boolean
     * */
    public function insert($data, $options = array())
    {
        $options += array('dbname' => '', 'table' => '', 'fields' => '', 'index' => '');
        extract($options);
        if (empty($dbname)) $dbname = $this->db();
        if (empty($table)) $table = $this->talbe();
        if (empty($index)) $index = HandlerSocket::PRIMARY;

        if (NULL == $this->_writeConnection) $this->WriteConnection();
        if (!($this->_writeConnection->openIndex(3, $dbname, $table, $index, $fields))) {
            $msg = $this->_writeConnection->getError();
            $this->error($msg);
            throw new Cola_Exception($msg);
        }

        return $this->_writeConnection->executeInsert(3, $data);
    }

    /**
     * Update data
     *
     * @param array $query
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public function update($query, $data, $options = array())
    {
        $query += array('op' => '=', 'criteria' => array(), 'limit' => 1, 'skip' => 0,);
        $options += array('dbname' => '', 'table' => '', 'fields' => '', 'index' => '');

        extract($query);
        extract($options);

        if (empty($dbname)) $dbname = $this->db();
        if (empty($table)) $table = $this->talbe();
        if (empty($index)) $index = HandlerSocket::PRIMARY;

        if (NULL == $this->_writeConnection) $this->WriteConnection();
        if (!($this->_writeConnection->openIndex(2, $dbname, $table, $index, $fields))) {
            $msg = $this->_writeConnection->getError();
            $this->error($msg);
            throw new Cola_Exception($msg);
        }

        return $this->_writeConnection->executeUpdate(2, $op, $criteria, $data, $limit, $skip);
    }

    /**
     * delete data
     *
     * @param array $query
     * @param array $options
     * @return mixed
     */
    public function delete($query, $options = array())
    {
        $query += array('op' => '=', 'criteria' => array(), 'limit' => 1, 'skip' => 0,);
        $options += array('dbname' => '', 'table' => '', 'fields' => '', 'index' => '');

        extract($query);
        extract($options);

        if (empty($dbname)) $dbname = $this->db();
        if (empty($table)) $table = $this->talbe();
        if (empty($index)) $index = HandlerSocket::PRIMARY;

        if (NULL == $this->_writeConnection) $this->WriteConnection();
        if (!($this->_writeConnection->openIndex(4, $dbname, $table, $index, $fields))) {
            $msg = $this->_writeConnection->getError();
            $this->error($msg);
            throw new Cola_Exception($msg);
        }

        return $this->_writeConnection->ExecuteDelete(4, $op, $criteria, $limit, $skip);
    }

    /**
     * Creates a new index objects
     *
     * @param array $params
     * @return mixed
     */
    public function createIndex($params = array())
    {
        $params += array('dbname' => '', 'table' => '', 'index' => '', 'fields' => array(), 'options' => array());

        extract($params);

        if (empty($dbname)) $dbname = $this->db();
        if (empty($table)) $table = $this->talbe();
        if (empty($index)) $index = 'PRIMARY';

        if (NULL == $this->_writeConnection) $this->WriteConnection();
        if (!($this->_writeConnection->createIndex(3, $dbname, $table, $index, $fields, $options))) {
            $msg = $this->_writeConnection->getError();
            $this->error($msg);
            throw new Cola_Exception($msg);
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Last error
     *
     * @return array
     */
    public function error($log = NULL)
    {
        if (NULL == $log) {
            return $this->_errorLog;
        }
        $this->_errorLog = $log;
    }

}