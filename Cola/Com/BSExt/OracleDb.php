<?php
/********************************************************************************
 *
 * oracle 操作类基于e21老类修改,
 *
 * 主要用于需要同时连接2个异地数据库的情况。
 *
 * @author soft456@gmail.com
 * @datetime 2012-08-4
 *
 * @copyright  Copyright (c) 2012 Wuhan Bo Sheng Education Information Co., Ltd.
 ********************************************************************************/

class Cola_Com_BSExt_OracleDb {

	//var $id, $rows, $data, $a_rows;
	
	protected $_connection = null;
	
	protected $_config = array();
	
	function __construct(){
		$this->_config = Cola::$config->get('_oracledb');
		$this->connect();
	}

	//连接oracle数据库
	function connect() {
				
		if (null === $this->_connection) {
        	$this->_connection = oci_connect($this->_config['user'],$this->_config['password'],$this->_config['sid']) or $this->oracle_die("Unable to connect to Oracle server");    
        }		
		return $this->_connection;
		
	}

	//该函数执行一个SQL语句（例如创建表或者修改添加字段等）
	function Execute($query) {
		$query_id = oci_parse($this->_connection, $query);
		oci_execute($query_id) or $this->oracle_die($query);
		return $query_id;
	}

	function db_query($query) {
		echo $this->_connection;
		$query_id = oci_parse($this->_connection, $query);
		oci_execute($query_id) or $this->oracle_die($query);
		return $query_id;
	}

	# Use this function is the query will return multiple rows.
	#这个函数将返回多行的结果，

	function Query($query) {
		$res = $this->db_query($query);
		$count = 0;
		$ary = array();
		if ($res) {
			while (($row = oci_fetch_array($res, OCI_ASSOC))) {
				$ary[$count] = $row;
				$count++;
			}
		}

		return $ary;
	}

	// 分页函数;$sql要查询的SQL语句$page为当前面码;$rows_per_page为每页多少行;
	function page_query($sql, $page, $rows_per_page) {
		if ($page < 1) {
			$page = 1;
		}

		$start_row = ($page - 1) * $rows_per_page;
		$end_row = $start_row + $rows_per_page;

		$sqls = "SELECT * FROM (SELECT r.*, ROWNUM as row_number  FROM  ($sql) r WHERE ROWNUM <= $end_row)
         WHERE $start_row< row_number";
		return $this->Query($sqls);
	}

	# 返回一个查询query的结果行数
	function db_count($query) {  //count(*) as count
		$res = $this->db_query($query);
		oci_fetch($res);
		$count = oci_result($res, 1);
		return $count;
	}

	# Use this function if the query will only return a
	# single data element.
	#这个函数将返回一个数
	function QueryItem($query, $key) {
		$res = $this->Query($query);
		$this->rows = count($res);
		if ($this->rows > 0) {
			$this->data = $res[0][$key];
			return($this->data);
		}
	}

	# This function is useful if the query will only return a
	# single row.
	#这个函数返回一行
	function QueryRow($query) {
		$res = $this->Query($query);
		$this->rows = count($res);
		if ($this->rows > 0) {
			$this->data = $res[0];
			return($this->data);
		}
	}

	# This function is useful if the query will only insert some
	# row.
	#这个函数用于添加记录
	function Insert($query) {
		$query_id = oci_parse($this->_connection, $query);
		oci_execute($query_id) or $this->oracle_die($query);
		InsertLog($query, $this->_connection);
		return $query_id;
	}

	# This function is useful if the query will only update some
	# row.
	#这个函数用于更新记录
	function Update($query) {
		$query_id = oci_parse($this->_connection, $query);
		oci_execute($query_id) or $this->oracle_die($query);
		InsertLog($query, $this->_connection);
		return $query_id;
	}

	# This function is useful if the query will only delete some
	# row.
	#这个函数用于删除记录
	function Delete($query) {
		$query_id = oci_parse($this->_connection, $query);
		oci_execute($query_id) or $this->oracle_die($query);
		InsertLog($query, $this->_connection);
		return $query_id;
	}

	//---------------------------------------------------------------------绑定变量执行
	function db_query_b($query, $aPname, $aPvalue) {
		$stmt = oci_parse($this->_connection, $query);
		$count = count($aPname);
		for ($i = 0; $i < $count; $i++) {
			oci_bind_by_name($stmt, $aPname[$i], $aPvalue[$i]);
		}
		oci_execute($stmt) or $this->oracle_die($query);
		return $stmt;
	}

	function Query_b($query, $aPname, $aPvalue) {
		$res = $this->db_query_b($query, $aPname, $aPvalue);
		$count = 0;
		$ary = array();
		while ($rs = oci_fetch_array($res, OCI_ASSOC)) {
			$ary[$count] = $rs;
			$count++;
		}
		return $ary;
	}

	function QueryRow_b($query, $aPname, $aPvalue) {
		$res = $this->Query_b($query, $aPname, $aPvalue);
		$this->rows = count($res);
		if ($this->rows > 0) {
			$this->data = $res[0];
			return($this->data);
		}
	}

	function QueryItem_b($query, $key, $aPname, $aPvalue) {
		$res = $this->Query_b($query, $aPname, $aPvalue);
		$this->rows = count($res);
		if ($this->rows > 0) {
			$this->data = $res[0][$key];
			return($this->data);
		}
	}

	function DML_b($query, $aPname, $aPvalue) {
		$stmt = oci_parse($this->_connection, $query);
		for ($i = 0; $i < count($aPname); $i++) {
			oci_bind_by_name($stmt, $aPname[$i], $aPvalue[$i]);
		}
		oci_execute($stmt) or $this->oracle_die($query);
		return $stmt;
	}

	function DML_b_ts($query, $aPname, $aPvalue) {
		$stmt = oci_parse($this->_connection, $query);
		for ($i = 0; $i < count($aPname); $i++) {
			oci_bind_by_name($stmt, $aPname[$i], $aPvalue[$i]);
		}
		$result = oci_execute($stmt, OCI_DEFAULT) or $this->oracle_die($query);
		if (!$result) {
			$this->ROLLBAK();
		}
		return $stmt;
	}

	//-----------------------------------结束
	/////////////////////***************事物处理方法，为增、删、改*******/////////////
	#这个函数用于添加记录
	function Insert_TS($query) {
		$query_id = oci_parse($this->_connection, $query);
		$result = oci_execute($query_id, OCI_DEFAULT) or $this->oracle_die($query);
		if (!$result) {
			$this->ROLLBAK();
		}
		InsertLog($query, $this->_connection);
		return $query_id;
	}

	# This function is useful if the query will only update some
	# row.
	#这个函数用于更新记录
	function Update_TS($query) {
		$query_id = oci_parse($this->_connection, $query);
		$result = oci_execute($query_id, OCI_DEFAULT) or $this->oracle_die($query);
		if (!$result) {
			$this->ROLLBAK();
		}
		InsertLog($query, $this->_connection);
		return $query_id;
	}

	# This function is useful if the query will only delete some
	# row.
	#这个函数用于删除记录
	function Delete_TS($query) {
		$query_id = oci_parse($this->_connection, $query);
		$result = oci_execute($query_id, OCI_DEFAULT) or $this->oracle_die($query);
		if (!$result) {
			$this->ROLLBAK();
		}
		InsertLog($query, $this->_connection);
		return $query_id;
	}

	function ROLLBAK() {
		oci_rollback($this->_connection); //不成功则回滚
		oci_free_statement($query_id);   //释放资源
		oci_close($this->_connection);
	}

	function COMMIT() {
		oci_commit($this->_connection); //如果都成功则提交
		//OCIFreeStatement($query_id);   //释放资源
		oci_close($this->_connection);
	}

	function COMMIT1() {
		oci_commit($this->_connection); //如果都成功则提交
		//OCIFreeStatement($query_id);   //释放资源
		//OCILogoff($this->_connection);
	}

	///////////////////////***********事物处理结束************//////////////////////////
	# This function is useful colse the connect of oracle database.
	#这个函数用于关闭与Oracle数据库的连接
	function Close() {
		oci_close($this->_connection);
	}

	function oracle_die($query) {
		//echo "<br>" . OCIError();
		//echo "<b>SQL Query:</b><br>";
		//echo "$query<br>";
		//echo "<br><br>";
		exit;
	}

}
