<?php

/**
 * 学科相关数据模型
 */
class Models_Subject
{

    const SM = 'GS0024'; // 生命安全学科代码
    const XL = 'GS0025'; // 心理健康学科代码

    public static $subject_list = array(
        self::SM => '生命安全',
        self::XL => '心理健康'
    );

    /**
     * 获取学科下拉菜单
     * @param  string $selected      默认选中的学科名称
     * @param  array  $attributes 	下拉菜单的额外属性 class id 等
     * @return string html
     */
    public static function subjectSelect($selected = '', array $extend_options = array(), array $attributes = array())
    {
        return Helper_HTML::select('subject', $extend_options + self::$subject_list, $selected, $attributes);
    }

    //科目与社区圈子的映射关系
    static public $subject_map_for_circle = array(
        'xl' => array(
            'xd001' => 221,
            'xd002' => 322,
            'xd003' => 424,
        ),
        'sm' => array(
            'xd001' => 222,
            'xd002' => 323,
            'xd003' => 425,
        ),
    );

}
