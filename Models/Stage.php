<?php

/**
 * 学段相关数据模型
 * @author ljyf5593 <ljyf5593@gmail.com>
 */
class Models_Stage
{

    const XX = 'xd001';
    const CZ = 'xd002';
    const GZ = 'xd003';

    public static $stage_list = array(
        self::XX => '小学',
        self::CZ => '初中',
        self::GZ => '高中'
    );

    /**
     * 获取学段下拉菜单
     * @param  string $selected      默认选中的学科名称
     * @param  array  $attributes 	下拉菜单的额外属性 class id 等
     * @return string html
     */
    public static function stageSelect($selected = '', array $extend_options = array(), array $attributes = array())
    {
        return Helper_HTML::select('stage', $extend_options + self::$stage_list, $selected, $attributes);
    }

    /**
     * 教材学段与社区学段的映射关系
     * @var type 
     */
    public static $xd_to_sqxd_map = array(
        self::XX => 1,
        self::CZ => 2,
        self::GZ => 3,
    );

}
