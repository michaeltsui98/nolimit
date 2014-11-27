<?php
/**
 * html工具类
 * @author liujie <ljyf5593@gmail.com>
 */
class Helper_HTML {
	/**
	 * 表单下拉选项
	 * Helper_HTML::select('subject', array('1' => '语文', '2' => '数学'), '2', array('class' => 'subject-span', 'data-user' => 'test'))
	 * 
	 * @param  string $name       下拉选项在表单中的名称
	 * @param  array  $options    下拉选项数组
	 * @param  [type] $select     默认选中项
	 * @param  array  $attributes 其他扩展html属性
	 * @return html
	 */
	public static function select($name, array $options, $select = NULL, array $attributes = array()){
		$html = '<select name="'.$name.'" '.self::attributes($attributes).'>';
		foreach ($options as $key => $value) {
			$selected = ($select == $key)?'selected="selected"':'';
			$html .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		$html .= '</select>';
		return $html;
	}

	public static function attributes(array $attributes){
		return self::array_implode('="', '" ', $attributes).'"';
	}

	private static function array_implode( $glue, $separator, $array ) {
		if ( ! is_array( $array ) ) {
			return $array;
		}
		$string = array();
		foreach ( $array as $key => $val ) {
		if ( is_array( $val ) )
		    $val = implode( ',', $val );
		 	$string[] = "{$key}{$glue}{$val}";
		}
		return implode( $separator, $string );
	}

}