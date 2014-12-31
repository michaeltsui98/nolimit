<?php   
/*
	模板文件夹在 tpl 目录下， 默认是default文件夹。
	模板缓存在 data/tpl_c目录下。
*/
global  $_K;
$_K ['i'] = 0;
$_K ['block_search'] = $_K ['block_replace'] = array ();
define('CHARSET', 'utf-8');
//define('TPL_CACHE', FALSE);

class Cola_Tpl {
	
	static private  $tpl_path = 'views/tpl_c';
	
	
	static function parse_code($tag_code, $tag_id, $tag_type = 'tag') {
		 
		$tplfile = 'db/' . $tag_type . '_' . $tag_id;
		$objfile = S_ROOT . static::$tpl_path . str_replace ( '/', '_', $tplfile ) . '.php';
		//read
		$tag_code = Cola_Tpl::parse_rule ( $tag_code );
		//write
		if(!is_dir(self::$tpl_path)){
			if(!mkdir(self::$tpl_path,07777,TRUE)){
				throw new Cola_Exception( 'Directory tpl_c must be writable');
			}
		}
		Cola_Tpl::swritefile ( $objfile, $tag_code ) or exit ( "File: $objfile can not be write!" );
		
		return $objfile;
	
	}
	static function parse_template($tpl) {
		//包含模板
		$tplfile = S_ROOT .  $tpl . '.htm';
		$objfile = S_ROOT . self::$tpl_path .DIRECTORY_SEPARATOR. str_replace ( '/', '_', $tpl ) . '.php';
		//var_dump($tpl,$tplfile,$objfile);die;
		//read
		if (! is_file( $tplfile )) {
			throw  new Cola_Exception($tplfile.'文件不存在！');
		}
		
		$template = Cola_Tpl::sreadfile ( $tplfile );
		empty ( $template ) and exit ( "Template file : $tplfile Not found or have no access!" );
		
		$template = Cola_Tpl::parse_rule ( $template, $tpl );
		//write
		if(!is_dir(self::$tpl_path)){
			if(!mkdir(self::$tpl_path,07777,TRUE)){
				throw new Cola_Exception ( 'Directory tpl_c must be writable');
			}
		}
		Cola_Tpl::swritefile ( $objfile, $template ) or exit ( "File: $objfile can not be write!" );
	
	}
	/**
	 * 
	 * 解析规则
	 * @param string $content  -html
	 * @param array  $sub_tpls 
	 * @param string $tpl
	 * @return string
	 */
	public static function parse_rule($template, $tpl = null) {
		global $_K;
		 
		//$template = preg_replace ( "/{include\s+([a-z0-9_\/]+)\}/ie", "Cola_Tpl::readtemplate('\\1')", $template );
		$template = preg_replace_callback( "/{include\s+([a-z0-9_\/]+)\}/i", function($p){return Cola_Tpl::readtemplate($p[1]);}, $template );
		
		//处理子页面中的代码
		$template = preg_replace_callback("/{include\s+([a-z0-9_\/]+)\}/i", function($p){return Cola_Tpl::readtemplate($p[1]);}, $template );
		
		//时间处理
		$template = preg_replace_callback ( '/\{date\((.+?),(.+?)\)\}/i', function($m){
		    return Cola_Tpl::datetags($m[1],$m[2]);}, $template 
		);
		//货币显示
		//$template = preg_replace ( '/{c\:(.+?)(,?)(\d?)\}/ie', "Curren::currtags('\\1','\\3')", $template );
		////头像处理
		//$template = preg_replace ( '/\{avatar\((.+?),(.+?)\)\}/ie', "Cola_Tpl::avatar('\\1','\\2')", $template );
		//widget 处理
		$template = preg_replace_callback ( '/\{widget\((.+?),(.+?)\)\}/i', 
		        function($m){
		           return  Cola_Tpl::widget($m[1],$m[2]);
		        } ,
		        $template );
		//widget 可以带参数的
		$template = preg_replace_callback ( '/\{widgetp\((.+?),(.+?),(.+?)\)\}/i', 
		        function ($m){
		              return Cola_Tpl::widgetp($m[1],$m[2],$m[3]);
		        }, $template );
		
		//文字裁剪
		$template = preg_replace_callback ( '/\{cutstr\((.+?),(.+?)\)\}/i', 
		        function($m){return Cola_Tpl::cutstrtags($m[1],$m[2]);}, $template 
		        );
		
		//PHP代码
		$template = preg_replace_callback ( "/\<\!\-\-\{eval\s+(.+?)\s*\}\-\-\>/is",
                function ($p){
		          return Cola_Tpl::evaltags($p[1]);
                }, $template );
				
		//开始处理
		//变量
		$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
		$template = preg_replace ( "/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template );
		$template = preg_replace ( "/([\n\r]+)\t+/s", "\\1", $template );
		//数组变量
		$template = preg_replace ( "/(\\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/s", "\\1['\\2']", $template );
		
		//对象变量
		$template = preg_replace('/\{\$this-\>(.*)\}/Uis', '<?php echo -this->\\1 ;?>', $template);
		
		$template = preg_replace ( "/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template );
		
		
		$template = preg_replace_callback ( "/\<\?\=\<\?\=$var_regexp\?\>\?\>/s", 
		        function($m){
		          return Cola_Tpl::addquote("<?php echo $m[1];?>");
		        }, $template );
		//逻辑
		$template = preg_replace_callback ( "/\{elseif\s+(.+?)\}/",function ($p){ 
		    return Cola_Tpl::stripvtags("<?php } elseif($p[1]) { ?>",'');
		}, $template );
		$template = preg_replace ( "/\{else\}/is", "<?php } else { ?>", $template );
		
		//循环
		for($i = 0; $i < 6; $i ++) {
			$template = preg_replace_callback ( "/\{loop\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/is", 
			        function($m){
			         return Cola_Tpl::stripvtags("<?php if(!empty($m[1])){ foreach($m[1] as $m[2]) { ?>","$m[3]<?php } }?> ");}, 
			        $template );
			$template = preg_replace_callback ( "/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/is", 
			        function($m){
			        return Cola_Tpl::stripvtags("<?php  foreach($m[1] as $m[2] => $m[3]) { ?>","$m[4]<?php } ?>"); 
			        },
			        $template );
			$template = preg_replace_callback ( "/\{if\s+(.+?)\}(.+?)\{\/if\}/is", 
			        function($m){
			        return Cola_Tpl::stripvtags("<?php if($m[1]) { ?>","$m[2]<?php } ?>");
			        }, $template );
		}
		//常量
		$template = preg_replace_callback ( "/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/s",
                function($m){
		        return "<?php echo $m[1];?>";
                }, $template );
		//换行
		$template = preg_replace ( "/ \?\>[\n\r]*\<\? /s", " ", $template );
		
		$timestamp = $_SERVER['REQUEST_TIME'];
		//附加处理
		$template = "<?php Cola_Tpl::checkrefresh('$tpl', '$timestamp' );?>$template";
		
		//替换
		if(!empty($_K['block_search'])){ 
			$arr = array_combine(array_values($_K ['block_search']),array_values($_K['block_replace']));
			$template = strtr($template, $arr);
		}
		 
		

		$template = strtr($template, array('<?='=>'<?php echo '));
		//var_dump($template);die;
		
		$template = preg_replace('/-this-\>(.*)/Uis', '$this->\\1', $template);
		
		return $template;
	}
	
	static function addquote($var) {
		$var =  strtr (preg_replace ( '/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s', "['\\1']", $var ),"\\\"", "\"");
		return $var;
	}
	/**
	 * 转义页面输入的字符中,防止sql注入
	 * @param string,array $value
	 * @param bool $double_encode
	 * @return string 安全的字符
	 */
	public static function chars($value, $double_encode = FALSE)
	{
		
		if(is_array($value) or is_object($value)){
			foreach ($value as $k=>$v){
			   $value[$k]=Cola_Tpl::chars($v,$double_encode);
			}
		}else{
			if(CHARSET==='gbk'){
				$charset = 'iso-8859-1';
			}else{
				$charset = CHARSET;
			}
			$value = htmlspecialchars( (string) $value, ENT_QUOTES, $charset, $double_encode);
		}
		return $value;
	}
	static function striptagquotes($expr) {
		$expr = preg_replace ( '/\<\?\=(\\\$.+?)\?\>/s', "\\1", $expr );
		$expr = strtr (preg_replace ( '/\[\'([a-zA-Z0-9_\-\.\x7f-\xff]+)\'\]/s', "[\\1]", $expr ),"\\\"", "\"" );
		return $expr;
	}
	
	static function evaltags($php) {
		global $_K;
		$_K ['i'] ++;
		$search = "<!--EVAL_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		$_K ['block_replace'] [$_K ['i']] = "<?php " . Cola_Tpl::stripvtags ( $php ) . " ?>";
		return $search;
	}
	/**
	 * 日期标签
	 * @param string $parameter 格式
	 * @param int $value 
	 * @return string
	 */
	static function datetags($parameter, $value) {
		global $_K;
		$_K ['i'] ++;
		$search = "<!--DATE_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		$_K ['block_replace'] [$_K ['i']] = "<?php if((int){$value}){echo date({$parameter},{$value}); } ?>";
		return $search;
	}
	
	/**
	 * 广告/位标签
	 * @param $target 广告位置名称
	 */
	static function ad_tag($target_name) {
		global $_K;
		$_K ['i'] ++;
		$search = "<!--AD_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		$_K ['block_replace'] [$_K ['i']] = "<?php Sys_tag::ad_tag('$target_name') ?>";
		return $search;

	}
	/**
	 * 数据标签
	 * @param string $name
	 * @return string
	 */
	static function readtag($name) {
		global $_K;
		$_K ['i'] ++;
		$search = "<!--READ_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		$_K ['block_replace'] [$_K ['i']] = "<?php Sys_tag::factory()->readtag($name) ?>";
		return $search;
	}
	
	/**
	 * 头像调用
	 */
	static function avatar($uid, $size) {
		global $_K;
		$_K ['i'] ++;
		$search = "<!--READ_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		$_K ['block_replace'] [$_K ['i']] = "<?php echo \"<img class=pic_$size src=\". Keke_user::instance()->get_avatar($uid,'$size').'>'?>";
		return $search;
	}
	/**
	 * 执行一个挂件
	 * @param string $c
	 * @param string $a
	 * @return string
	 */
	static function widget($c, $a) {
		global $_K;
		$_K ['i'] ++;
		$search = "<!--READ_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		
		$_K ['block_replace'] [$_K ['i']] = "<?php echo Cola_Tpl::widget_out($c,$a) ?>";
		return $search;
	}
	public static function widget_out($c,$a){
		
		$arr = explode('_', $c);
		 
		if($arr[0]!='Modules'){
		    $c = "Controllers_$c";
		}
		$cls_name = new $c;
	    if(method_exists($cls_name, $a)){
	        //call_user_func_array(array($cls_name,$a),$p);
	        return call_user_func(array($cls_name,$a));
	    }elseif(method_exists($cls_name, $a.'Action')){
	        return call_user_func(array($cls_name,$a.'Action'));
	    }else{
	        throw new Exception("not fond class $c mothed $a ");
	    }
	}
	/**
	 * 带参数的部件输出
	 * @param string $class 不带controllers_
	 * @param string $func  带Action
	 * @param array $param
	 */
	public static function widgetByParam($class,$func,$params){
		$arr = explode('_', $class);
			
		if($arr[0]!='Modules'){
			//$c = "controllers_$c";
		    $class = "controllers_$class";
		}
		
	    $cls = new $class;
	    $action = $func."Action";
	    if(!is_array($params)){
	        throw new Exception('$params->'.$params.' is not array');
	    }
	    if(method_exists($cls, $action)){
	       return  call_user_func_array(array($cls, $action), $params);
	        //call_user_method_array($method_name, $obj, $params)
	    }else{
	        throw new Exception($cls.'->'.$action.'not exists');
	    }
	}
	/**
	 * 标签替换
	 * @param string $c
	 * @param string $a
	 * @param string $p
	 * @return string
	 */
	public static function widgetp($c, $a,$p) {
	    global $_K;
	    $_K ['i'] ++;
	    $search = "<!--READ_TAG_{$_K['i']}-->";
	    $_K ['block_search'] [$_K ['i']] = $search;
	
	    $_K ['block_replace'] [$_K ['i']] = "<?php echo Cola_Tpl::widgetByParam($c,$a,$p) ?>";
	    return $search;
	}
	/**
	 * 文字裁剪标签
	 * @param string $string
	 * @param int $length
	 * @return string
	 */
	static function cutstrtags($string,$length){
		global $_K;
		$_K ['i'] ++;
		$search = "<!--CUTSTR_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		$_K ['block_replace'] [$_K ['i']] = "<?php echo  Cola_Tpl::cutstr($string,'$length') ?>";
		return $search;
	}
	/**
	 * 文字裁剪
	 * @param string $string
	 * @param int $length
	 * @return Ambigous <string, string>
	 */
	static function cutstr($string,$length){
	    return Cola_View::truncate($string, $length);
	}
	static function stripvtags($expr, $statement = '') {
	   
		return $expr.$statement ; 
	   
	}
	
	static function readtemplate($name) {
		global $_K;
		$tpl = Cola_Tpl::tpl_exists ( $name );
		$tplfile = S_ROOT . './' . $tpl . '.htm';
		//$sub_tpls [] = $tpl;
		//var_dump($name,$tplfile);die;
		/* if (! file_exists ( $tplfile )) {
			$tplfile = strtr ( $tplfile,'/' . $_K ['template'] . '/', '/default/');
		} */
		
		$content = trim ( Cola_Tpl::sreadfile ( $tplfile ) );
		return $content;
	}
	
	//获取文件内容
	static function sreadfile($filename) {
		
		if (function_exists ( 'file_get_contents' )) {
			   $content =   file_get_contents ( $filename );
		} elseif ($fp = fopen ( $filename, 'r' )) {
			 	$content = fread ( $fp, filesize ( $filename ) );
				fclose ( $fp );
 		}
		return $content;
	}
	//写入文件
	static function swritefile($filename, $writetext, $openmod = 'w') {
		if(function_exists('file_put_contents')){
			return file_put_contents($filename, $writetext,LOCK_EX);
		}elseif($fp = fopen ( $filename, $openmod )) {
			flock ( $fp, 2 );
			fwrite ( $fp, $writetext );
			fclose ( $fp );
			return true;
		}
	}
	//判断字符串$haystack中是否存在字符$needle 返回第一次出现的位置   三个等号 判断绝对相等  uican 2009-12-03
	static function strexists($haystack, $needle) {
		return ! (strpos ( $haystack, $needle ) === FALSE);
	}
	
	static function tpl_exists($tplname) {

		if(file_exists(S_ROOT.$tplname)){
		    $tpl =  strtr($tplname,array('.htm'=>''));
		}elseif(file_exists(S_ROOT.$tplname.'.htm')){
			$tpl = $tplname;
		}else if(file_exists( S_ROOT . "views/$tplname.htm" )){
			$tpl = "views/$tplname";
		}else{
			throw new Cola_Exception('模板文件不存在：'.$tplname.'   file is not exists,plase check');
		}
		return $tpl;
	}
	
	static function template($name) {
		global $_K;
		
		$tpl = Cola_Tpl::tpl_exists ( $name );
		 
		$objfile = S_ROOT . 'views/tpl_c/' . strtr ( $tpl,'/', '_') . '.php';
		 
		
		if(! file_exists ( $objfile ) OR ! TPL_CACHE){
			Cola_Tpl::parse_template ( $tpl );
		}

		return $objfile;
	}
	
	
	/**
	 * //子模板更新检查 
	 *
	 * @param string $subfiles 模板路径
	 * @param int $mktime 时间  
	 * @param string $tpl  当前页面模板
	 */
	static function checkrefresh($tpl, $mktime) {
		if ($tpl) {
			$tplfile = S_ROOT . './' . $tpl . '.htm';
			//(! file_exists ( $tplfile )) and $tplfile = strtr ( $tplfile,'/' . $_K ['template'] . '/', '/default/');
			$submktime = filemtime ( $tplfile );
			($submktime > $mktime) and Cola_Tpl::parse_template ( $tpl );
		}
	}
	
	//调整输出
	static function ob_out() {
		global $_K,$_lang;
		
		$content = ob_get_contents ();
		
		$preg_searchs = $preg_replaces = $str_searchs = $str_replaces = array();
		
		
		if (Cola_Request::isAjax()) {
			$preg_searchs [] = '/([\x01-\x09\x0b-\x0c\x0e-\x1f])+/';
			$preg_replaces [] = ' ';
			
			$str_searchs [] = ']]>';
			$str_replaces [] = ']]&gt;';
		}
		
		if ($preg_searchs) {
			$content = preg_replace ( $preg_searchs, $preg_replaces, $content );
		}
		if ($str_searchs) {
			$content = trim ( str_replace ( $str_searchs, $str_replaces, $content ) );
		}
		 
		Cola_Tpl::obclean ();
		(Cola_Request::isAjax()) and self::xml_out ( $content );
		
		//echo $content;

	}
	static function obclean() {
		global $_K;
		 if($_K['inajax']==1){
		 	ob_end_clean();
		 	ob_start();
		 }
		 
	}
	static function rewrite_url($pre, $para, $hot = '') {
		$str = '';
		parse_str ( $para, $joint );
	 
		$s = array_filter ( $joint );
		$url = http_build_query ( $s );
		
		$url = str_replace ( array ("do=", '&', '=' ), array ("", '-', '-' ), $url );
		 
		$hot = $hot ? "#" . $hot : '';
		return '<a href="'.$url . '.html' . $hot . '"';
	}
	static function xml_out($content) {
		
		header ( "Expires: -1" );
		header ( "Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/xml; charset=".CHARSET );
		echo '<' . "?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n";
		echo "<root><![CDATA[" . trim ( $content ) . "]]></root>";
		exit ();
	}

}