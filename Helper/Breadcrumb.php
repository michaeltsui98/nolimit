<?php
/**
 * 站点导航类（面包屑）
 * 使用示例:
 * // 在控制器的构造方法中加入
 * // 增加一个面包屑，参数一连接地址出去学科，参数二连接地址名称
 * $this->breadcrumb->append('/lesson', '备课夹');
 *
 * // 在控制器的每个action中加入，该方法可以多次调用，后调用的在渲染的时候
 * $this->breadcrumb->append('/lesson/record', '我的备课夹');
 *
 * // 在相应的视图层
 * {$breadcrumb}
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 */
class Helper_Breadcrumb {
    public  $role_item_maps = array(
        1 => array(
            'uri' => 'student',
            'title' => '学生',
        ),
        2 => array(
            'uri' => 'teacher',
            'title' => '教师',
        ),
        3 => array(
            'uri' => 'parent',
            'title' => '家长',
        ),
    );

    private $breadcrumb = array();

    // 学科信息
    private $subject = '';

    private static $instance = NULL;

    /**
     * 面包屑的单例
     * @param  [type] $user_role [description]
     * @param  [type] $subject   [description]
     * @return self
     */
    public static function getInstance($subject) {
        if (self::$instance instanceof self) {

        } else {
            self::$instance = new self($subject);
        }

        return self::$instance;
    }

    private function __construct($subject){
        // 获取当前用户的角色信息
        $user_role = Cola_Request::cookie('perview_role_'.$subject);
        if (isset($_SESSION['user']['role_code'])) {
            $user_role = $_SESSION['user']['role_code'];
        }
        $this->subject = $subject;
        if (isset($this->role_item_maps[$user_role])) {
            $item = $this->role_item_maps[$user_role];
            $this->append($item['uri'], $item['title']);
        }
        return $this;
    }

    /**
     * 添加新的面包屑
     * @param  string $uri        [地址]
     * @param  string $title      [名称]
     * @param  array $attributes [额外的属性]
     * @return self
     */
    public function append($uri, $title = '', array $attributes = NULL){
        $this->breadcrumb[] = array('uri' => $uri, 'title' => $title, 'attributes' => $attributes);
        return $this;
    }

    /**
     * 导航栏渲染
     * @return null|string
     */
    public function render(){
         
        if(count($this->breadcrumb) > 1){
            // 最后一条面包屑不需要加链接，所以单独处理
            $current_item = array_pop($this->breadcrumb);
            $view = new Cola_View();
            $view->subject = $this->subject;
            $view->breadcrumb = $this->breadcrumb;
            $view->current_item = $current_item;
            return $view->tpl('views/Breadcrumb/layout', NULL, TRUE);
        }

        return '';
    }

    public function __toString() {
        return $this->render();
    }
}
