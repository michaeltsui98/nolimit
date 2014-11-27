<?php

/**
 * 年级数据模型
 */
class Models_Grade
{

    // 小学年级代码
    const XX1 = 'GO003';
    // const XX1_1 = 'GO003-1';
    const XX2 = 'GO004';
    const XX3 = 'GO005';
    const XX4 = 'GO006';
    const XX5 = 'GO007';
    const XX6 = 'GO008';
    // 初中年级代码
    const CZ1 = 'GO009';
    const CZ2 = 'GO010';
    const CZ3 = 'GO011';
    // 高中年级代码
    const GZ1 = 'GO012';
    const GZ2 = 'GO013';
    const GZ3 = 'GO014';

    public static $stage_grade_list = array(
        Models_Stage::XX => array(
            self::XX1 => '小学一年级',
            // self::XX1_1 => '小学一年级上',
            self::XX2 => '小学二年级',
            self::XX3 => '小学三年级',
            self::XX4 => '小学四年级',
            self::XX5 => '小学五年级',
            self::XX6 => '小学六年级',
        ),
        Models_Stage::CZ => array(
            self::CZ1 => '初中一年级',
            self::CZ2 => '初中二年级',
            self::CZ3 => '初中三年级',
        ),
        Models_Stage::GZ => array(
            self::GZ1 => '高中一年级',
            self::GZ2 => '高中二年级',
            self::GZ3 => '高中三年级',
        ),
    );
    //年级列表
    public static $grade_list = array(
        Models_Stage::XX => array(
            self::XX1 => '一年级',
            self::XX2 => '二年级',
            self::XX3 => '三年级',
            self::XX4 => '四年级',
            self::XX5 => '五年级',
            self::XX6 => '六年级',
        ),
        Models_Stage::CZ => array(
            self::CZ1 => '一年级',
            self::CZ2 => '二年级',
            self::CZ3 => '三年级',
        ),
        Models_Stage::GZ => array(
            self::GZ1 => '一年级',
            self::GZ2 => '二年级',
            self::GZ3 => '三年级',
        ),
    );

    /**
     * 根据学段和入学年获取年级代码
     * @param  string $stage 学段代码
     * @param  int $year  入学年
     * @return string        年级代码
     */
    public static function getGradeCodeByYear($stage, $year)
    {
        $this_year = date('Y');
        $this_month = date('n');
        // 开学时间是9月份，如果在9月之前，则属于上一年
        if ($this_month < 9) {
            $this_year -= 1;
        }
        $offset = $this_year - $year;
        if ($offset >= 0) {
            $stage_grade_code = array_keys(self::$stage_grade_list[$stage]);
            return $stage_grade_code[$offset];
        }
        return NULL;
    }

    /**
     * 根据年级代码获取学段和入学年
     * @param  string $grade_code 年级代码
     * @return array             返回的入学年和学段信息
     */
    public static function getYearByGradeCode($grade_code)
    {
        foreach (self::$grade_list as $stage => $grade_list) {
            $offset = 0;
            foreach ($grade_list as $code => $grade) {
                if ($code == $grade_code) {
                    $this_year = date('Y');
                    $this_month = date('n');
                    // 开学时间是9月份，如果在9月之前，则属于上一年
                    if ($this_month < 9) {
                        $this_year -= 1;
                    }
                    return array(
                        'stage' => $stage,
                        'year' => $this_year - $offset
                    );
                }
                $offset++;
            }
        }
    }

    /**
     * 根据学段获取年级下拉菜单
     * @param  string $stage 学段
     * @param  string $selected      默认选中的年级
     * @param  array  $attributes 	 下拉菜单的额外属性 class id 等
     * @return string html
     */
    public static function gradeSelect($stage, $selected = '', array $extend_options = array(), array $attributes = array())
    {
        return Helper_HTML::select('grade', $extend_options + self::$stage_grade_list[$stage], $selected, $attributes);
    }

    /**
     * 根据学段获取年级下拉菜单
     * @param  string $stage 学段
     * @param  string $selected      默认选中的年级
     * @param  array  $attributes 	 下拉菜单的额外属性 class id 等
     * @return string html
     */
    public static function gradesSelect($stage, $selected = '', array $extend_options = array(), array $attributes = array())
    {
        return Helper_HTML::select('grade', $extend_options + self::$grade_list[$stage], $selected, $attributes);
    }

}
