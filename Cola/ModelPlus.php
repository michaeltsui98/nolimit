<?php

/**
 * 数据库Model的加强版
 * 封装了数据列表分页的方法
 * 增加了一行代码内可以对表单进行基本的操作
 * @example Cola_ModelPlus::init()
 *         ->setTable('doc')
 *         ->setPk('doc_id')
 *         ->load(70);
 * @author michael
 *
 */
class Cola_ModelPlus extends Cola_Model
{

    protected static $_instance = array();

    /**
     * @return self
     */
    public static function init()
    {
        $cls = get_called_class();

        if (isset(self::$_instance[$cls]) && is_object(self::$_instance[$cls])) {
            return self::$_instance[$cls];
        }
        return self::$_instance[$cls] = new static();
    }

    /**
     * 获取方法缓存key
     *
     * @param string $func
     *            方法名称
     * @param array $args
     *            方法参数数组
     * @return string
     */
    public function getCacheKey($func, $args = array(), $callClassName = '')
    {
        if ($callClassName) {
            $cls = $callClassName;
        } else {
            $cls = get_called_class();
        }
        return $key = md5($cls . $func . serialize($args));
    }

    /**
     * 设置表名
     *
     * @param string $table_name
     *            表名
     * @return Cola_ModelPlus
     */
    public function setTable($table_name)
    {
        $this->_table = $table_name;
        return $this;
    }

    /**
     * 设置主键
     *
     * @param string $pk
     * @return Cola_ModelPlus
     */
    public function setPk($pk)
    {
        $this->_pk = $pk;
        return $this;
    }

    /**
     * 获取sql 的分页代码 limi xx ,xxx
     *
     * @param int $count
     * @param int $limit
     * @param int $page
     * @return string
     */
    function getLimit($count, $limit, $page)
    {
        $count = intval($count);
        $limit = intval($limit);

        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }

        $start = $limit * $page - $limit;
        if ($start < 0) {
            $start = 0;
        }
        return " LIMIT " . $start . " ," . $limit;
    }

    /**
     * 获取分页数据
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页数量
     * @param string $fid
     *            字段名
     * @param string $cond
     *            <,>,= 条件
     * @param string $val
     *            字段值
     * @param string $url
     *            http://www.xx.com/sss
     * @param string $order
     *            order id desc
     * @return multitype:array string
     */
    function dataList($page = 1, $pageSize = 20, $fid, $cond, $val, $url, $order = '')
    {
        $wh = '1';

        if ($val and $cond == 'like') {
            $wh .= " AND $fid   $cond '%$val%'";
        } elseif ($val) {
            $wh .= " AND $fid   $cond '$val'";
        }

        $where = $wh;

        $page = intval($page);

        $pageSize = intval($pageSize);

        $count = $this->count($where);

        $limit = $this->getLimit($count, $pageSize, $page);

        $paper = new Cola_Com_Pager($page, $pageSize, $count, $url);

        $pageHtml = $paper->html();

        $sql = "SELECT * FROM $this->_table WHERE {$where} {$order} {$limit}";

        $data = (array) $this->sql($sql);

        $result = array(
            'data' => $data,
            'page' => $pageHtml
        );
        return $result;
    }

    /**
     * 通过sql 获取数据与分页信息
     *
     * @param string $sql
     *            不要带limit子句，本方法会依据参数生成
     * @param int $page
     *            1
     * @param int $limit
     *            20
     * @param string $url
     *            BASE_PATH."/index.php/Admin_Log/index/page/%page%/fid/$fid/cond/$cond/val/$val";
     * @return boolean multitype:string <mixed, resource>
     */
    public function sqlPager($sql, $page, $limit, $url, $ajax = 0)
    {
        (int) $page or $page = 1;
        (int) $limit or $limit = 20;

        if ($page > 0) {
            $start = ($page - 1) * $limit;
            $limits = ' limit ' . $start . ',' . $limit;
        }
        $data = $this->sql($sql . $limits);
        $sql = "select count(*) from (" . $sql . ") as sy";
        $count = $this->db()->col($sql);

        $pager = new Cola_Com_Pager($page, $limit, $count, $url, $ajax);
        $html = $pager->html();
        if (!$data)
            return false;
        return array(
            'data' => $data,
            'page' => $html,
            'count' => $count
        );
    }

    /**
     * 分页时，获取当前页的url地址.
     *
     * @return string
     */
    public function getPageUrl()
    {
        $uri_arr = parse_url($_SERVER['REQUEST_URI']);
        if (isset($uri_arr['query'])) {
            parse_str($uri_arr['query'], $query_arr);
            unset($query_arr['page']);
            if ($query_arr) {
                $url = $uri_arr['path'] . '?' . http_build_query($query_arr) .
                        '&page=%page%';
            } else {
                $url = $uri_arr['path'] . '?page=%page%';
            }
        } else {
            $url = $uri_arr['path'] . '?page=%page%';
        }
        return $url;
    }

}
