<?php
/**
 * 后台公共方法
 */

function menu($data, $pId = 0, $layer = 0, $column_id = null, $c, $a)
{
	if (!isset($data))
		return '暂无栏目';
	$html = '';
	++$layer;
	$ui_class = 'ui-nav-main';
	$li_class = 'ui-nav-item';
	$li_cur = ' ui-nav-item-current';
	switch ($layer) {
		case 2:
			$ui_class = 'ui-nav-submain';
			$li_class = 'ui-nav-subitem';
			$li_cur = ' ui-nav-subitem-current';
			break;
	}

	foreach ($data as $k => $v) {
		if ($v['module_pid'] == $pId) {
			 	$url = url($v['module_controller'] , $v['module_action']);
				$target = ' target="_self" ';
			 
			if (($v['module_controller'] == $c) && ($v['module_action'] == $a)) {
				$a_class = $li_class . $li_cur;
			} else {
				$a_class = $li_class;
			}
			$ul_html = menu($data, $v['module_id'], $layer, $column_id, $c, $a);
			if (strpos($ul_html, 'current')){
				$a_class = $li_class . $li_cur;
			}else {
				if($v['module_controller'] == $c){
					$a_class = $li_class . $li_cur;
				}
			}
			if($layer==1){
				$html .= '<div title="' . $v['module_title'] . '" style="padding:5px;"><ul class="easyui-tree" data-options="lines:true">';
			}else{
			                       
				$html .= ' <li data-options="iconCls:\''.$v['module_icon'].'\'"><span><a href="#" onclick="addTab(\''.$v['module_title'].'\',\'' . $url . '\',\''.$v['module_icon'].'\')">' .
				$v['module_title'] . '</a></span>';
			}
			$html .= $ul_html;
			if($layer==1){
				$html .= "</ul></div>";
			}else{
				$html .= "</li>";
			}
		}
	}
	return $html ? $html : $html;
}
/**
 * 拼url 
 * @param string $c
 * @param string $a
 * @return string
 */
function url($c,$a){
    ///df/index.php/Admin/Index/index/xk/sm
    //Modules_Admin_Controllers_Index   
    //indexAction 
     
    $tmp = explode('_', $c);
    $prefix = current($tmp);
    $controller = '';
    if($prefix=='Modules'){
    	$controller = next($tmp).'/'.end($tmp);
    }elseif($prefix == 'Controllers'){
    	$controller = ltrim(strtr($c,array('Controllers'=>'','_'=>'/')),'/');
    } 
    
    return BASE_PATH.$controller.'/'.strtr($a,array('Action'=>'')).'/xk/'.XK;
    
	 
}

function generateTree($items,$id = 'id', $pid = 'pid'){
	foreach($items as $item)
		$items[$item[$pid]]['children'][$item[$id]] = &$items[$item[$id]];
	return isset($items[0]['children']) ? $items[0]['children'] : array();
}