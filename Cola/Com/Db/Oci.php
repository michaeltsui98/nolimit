<?php

/**
 *
 */
class Cola_Com_Db_Oci extends Cola_Com_Db_Abstract
{

    /**
     * Connect to Oracle
     *
     * @return resource connection
     */
    protected function _connect($params)
    {
        if (!extension_loaded('oci8')) {
            throw new Cola_Com_Db_Exception('Can not find oci extension.');
        }

        $func = ($params['persistent']) ? 'oci_pconnect' : 'oci_connect';

        $connection = $func(
                $params['user'], $params['password'], '(DESCRIPTION =
       (ADDRESS = (PROTOCOL = TCP)(HOST = ' . $params['host'] . ')(PORT = ' . $params['port'] . '))
       (CONNECT_DATA =
         (SERVER = DEDICATED)
         (SERVICE_NAME = ' . $params['database'] . ')
       )
      )', $params['charset']
        );

        if (is_resource($connection)) {
            $this->_connection = $connection;
            return $this->_connection;
        }

        throw new Cola_Com_Db_Exception($this->error());
    }

    /**
     * Close connection
     *
     */
    public function close()
    {
        if (is_resource($this->_connection)) {
            oci_close($this->_connection);
        }
    }

    /**
     * Free result
     *
     */
    public function free()
    {
        oci_free_statement($this->_query);
    }

    /**
     * Query sql
     *
     * @param string $sql
     * @return Cola_Com_Db_Oci
     */
    public function query($sql)
    {
        $this->_writeLog($sql);
        $this->_lastSql = $sql;

        if ($this->_debug) {
            $this->log($sql . '@' . date('Y-m-d H:i:s'));
        }
        $this->_query = oci_parse($this->_connection, $sql);
        if ($this->_query) {
            return oci_execute($this->_query);
        }

        $msg = $this->error() . '@' . $sql . '@' . date('Y-m-d H:i:s');

        $this->log($msg);

        throw new Cola_Com_Db_Exception($msg);
    }

    /**
     * Return the rows affected of the last sql
     *
     * @return int
     */
    public function affectedRows()
    {
        return oci_num_rows($this->_query);
    }

    /**
     * Fetch one row result
     *
     * @param string $type
     * @return mixd
     */
    public function fetch($type = 'ASSOC')
    {
        $type = strtoupper($type);

        switch ($type) {
            case 'ASSOC':
                $func = 'oci_fetch_assoc';
                break;
            case 'NUM':
                $func = 'oci_fetch_row';
                break;
            case 'OBJECT':
                $func = 'oci_fetch_object';
                break;
            default:
                $func = 'oci_fetch_array';
        }

        return $func($this->_query);
    }

    /**
     * Fetch All result
     *
     * @param string $type
     * @return array
     */
    public function fetchAll($type = 'ASSOC')
    {
        switch ($type) {
            case 'ASSOC':
                $func = 'oci_fetch_assoc';
                break;
            case 'NUM':
                $func = 'oci_fetch_row';
                break;
            case 'OBJECT':
                $func = 'oci_fetch_object';
                break;
            default:
                $func = 'mysql_fetch_array';
        }
        $result = array();
        while ($row = $func($this->_query)) {
            $result[] = $row;
        }
        oci_free_statement($this->_query);
        return $result;
    }

    /**
     * 该函数执行一个SQL语句（例如创建表或者修改添加字段等）
     * @param string $query 要执行的SQL语句；
     * @return boolean execute result or error.
     */
    public function execute($query)
    {
        $this->_writeLog($query);
        $this->_query = oci_parse($this->__connection, $query);
        if (oci_execute($this->_query)) {
            return true;
        } else {
            $this->oracleDie($query);
        }
    }

    /**
     * 执行SQL并返回数组——分页；注意排序字段如果不是唯一的请在后面加一个唯一的字段，否则数据可能有问题
     * @param string $sql 要执行的SQL语句；
     * @param int $page 第几页
     * @param int $pageNum 每页多少条
     * @return array
     */
    public function pageQuery($sql, $page, $pageNum = 10)
    {
        $page = intval($page);
        if ($page < 1) $page = 1;
        $pageNum = intval($pageNum);
        $start = ($page - 1) * $pageNum;
        $sql = "SELECT z2.*
            FROM (
                SELECT z1.*, ROWNUM AS \"db_rownum\"
                FROM (
                    " . $sql . "
                ) z1
            ) z2
            WHERE z2.\"db_rownum\" BETWEEN " . ($start + 1) . " AND " . ($start + $pageNum);

        if ($this->query($sql)) {
            return $this->fetchAll();
        }
        $this->error();
    }

    /**
     * 绑定变量基本方法
     * @param string $query 要执行的SQL语句
     * @param array $bindData 绑定数据
     * @return int 返回影响的记录
     */
    public function dbQueryBind($query, array $bindData)
    {
        $this->_writeLog($query, $bindData);
        $this->_query = oci_parse($this->_connection, $query);
        foreach ($bindData as $key => $val) {
            oci_bind_by_name($this->_query, $key, $val);
        }

        return oci_execute($this->_query);
    }

    /**
     * 绑定变量，返回多行
     * @param string $query 要执行的SQL语句
     * @param array $bindData  变量
     * @return array
     */
    public function queryBing($query, $bindData)
    {
        if ($this->dbQueryBind($query, $bindData)) {
            $result = array();
            while (($row = oci_fetch_array($this->_query, OCI_ASSOC))) {
                $result[] = $row;
            }
            return $result;
        }

        $this->error();
    }

    /**
     * Get last insert id
     *
     * @return int
     */
    public function lastInsertId()
    {
        return oci_num_rows($this->_query);
    }

    /**
     * Beging transaction
     *
     */
    public function beginTransaction()
    {

    }

    /**
     * Commit transaction
     *
     * @return boolean
     */
    public function commit()
    {
        $result = oci_commit($this->_connection);
        if ($result) {
            return true;
        }

        throw new Cola_Com_Db_Exception($this->error());
    }

    /**
     * Roll back transaction
     *
     * @return boolean
     */
    public function rollBack()
    {
        $result = oci_rollback($this->_connection);
        if ($result) {
            return true;
        }

        throw new Cola_Com_Db_Exception($this->error());
    }

    /**
     * Escape string
     *
     * @param string $str
     * @return string
     */
    public function escape($str)
    {
        return "'" . addslashes($str) . "'";
    }

    /**
     * Get error
     *
     * @return string|array
     */
    public function error($type = 'STRING')
    {
        $type = strtoupper($type);

        if (is_resource($this->_query)) {
            $error = oci_error($this->_query);
        } else {
            $error = mysql_error();
        }

        if ('ARRAY' == $type) {
            return array('code' => $error['code'], 'msg' => $error['message']);
        }
        return $error['code'] . ':' . $error['message'];
    }

    /**
     * 写日志函数
     * @param string $query
     * @param array $data 绑定类型数据
     */
    private function _writeLog($query, $data = array())
    {
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $query = str_replace($key, $val, $query);
            }
        }
        //排除查询记录
        $tags = explode(' ', $query, 2);
        if ('SELECT' == strtoupper(trim($tags[0]))) return;

        $query = addcslashes(str_replace(array("'", '"'), '', $query));

        $logQuery = "insert into e_operate_log(FILEPATH,MSG,USERS,IP,TIME)values('" . $_SERVER['PHP_SELF'] . "','" . $query . "','" . $_SESSION['dzdauserid'] . "','" . $_SERVER['REMOTE_ADDR'] . "',sysdate)";

        $this->_query = oci_parse($this->_connection, $logQuery);
        if (oci_execute($this->_query)) {
            return oci_num_rows($this->_query);
        }
        $this->error();
    }

}