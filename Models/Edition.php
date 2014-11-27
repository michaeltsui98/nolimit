<?php
/**
 * 版本相关数据模型
 */
class Models_Edition {
    const RJ = 'v01';
    const EJ = 'v02';
    const SJ = 'v03';
    const HJ = 'v04';
    const ZJ = 'v05';
    const XJ = 'v06';
    const BSD= 'v07';
    const LJ = 'v08';
    const JJ = 'v09';
    const HSD = 'v10';
    const EK = 'v11';

    public static $edition_list = array(
        self::RJ =>'人教版',
        self::EJ =>'鄂教版',
        self::SJ =>'苏教版',
        self::HJ =>'沪教版',
        self::ZJ =>'浙教版',
        self::XJ =>'湘教版',
        self::BSD=>'北师大版',
        self::LJ =>'鲁教版',
        self::JJ =>'冀教版',
        self::BSD=>'华师大版',
        self::EK => '鄂科版',
    );

    /**
     * 获取学科下拉菜单
     * @param  string $selected      默认选中的学科名称
     * @param  array  $attributes   下拉菜单的额外属性 class id 等
     * @return html
     */
    public static function editionSelect($selected = '', array $attributes = array()){
        return Helper_HTML::select('edition', self::$edition_list, $selected, $attributes);
    }
}