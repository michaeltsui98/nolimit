<?php
/**
 * 课程资源相关操作类
 * @author liujie <ljyf5593@gmail.com>
 */
class Models_Lesson_Resource extends Cola_Model {
	protected $_table = "lesson_resource";

	const TYPE_TEXT = 0;

	const TYPE_PPT = 1;
	const TYPE_PHOTO = 2;
	const TYPE_DOC = 3;
	const TYPE_VIDEO = 4;

	public static $type_list = array(
		self::TYPE_TEXT => '富文本',
		self::TYPE_PHOTO => '相册',
		self::TYPE_DOC => '文档',
		self::TYPE_VIDEO => '视频',
	);

	public static $ext_type_maps = array(
		'txt' => self::TYPE_TEXT,
		'doc' => self::TYPE_DOC,
		'docx' => self::TYPE_DOC,
		'xls' => self::TYPE_DOC,
		'xlsx' => self::TYPE_DOC,
		'pdf' => self::TYPE_DOC,
		'ppt' => self::TYPE_PPT,
		'pptx' => self::TYPE_PPT,
		'mp4' => self::TYPE_VIDEO,
	);

	/**
	 * 资源类型对应的css文件样式名称
	 * @var array
	 */
	public static $type_style_maps = array (
		self::TYPE_TEXT => 'icon-typeTxt',
		self::TYPE_PHOTO => 'icon-typePic',
		self::TYPE_DOC => 'icon-typeDoc',
		self::TYPE_PPT => 'icon-typeDoc',
		self::TYPE_VIDEO => 'icon-typeVideo',
	);

	public static $type_ico_maps = array (
		self::TYPE_TEXT => 'icon_txt',
		self::TYPE_PHOTO => 'icon_img',
		self::TYPE_DOC => 'icon_docx',
		self::TYPE_PPT => 'icon_pptx',
        self::TYPE_VIDEO => 'icon_mp4',
	);

	/**
	 * 资源类型下拉菜单
	 * @param  [type] $selected   [description]
	 * @param  array  $attributes [description]
	 * @return [type]             [description]
	 */
	public static function typeSelect($name = 'type', $selected = NULL, array $attributes = array()){
		return Helper_HTML::select($name, self::$type_list, $selected, $attributes);
	}

	/**
	 * 根据课程夹ID获取课程资源列表
	 * @param  int $folder_id 课程夹ID
	 * @return array          课程资源列表
	 */
	public function getListByFolder($folder_id, $limit = 100){
		
		return $this->find(array(
			'where' => "`folder_id`='{$folder_id}'",
			'order' => "`sort` ASC",
			'start' => 0,
			'limit' => $limit,
		));
	}

	/**
	 * 获取用户上传的资源列表
	 * @param  string $user_id 用户ID
	 * @param  string $subject 学科代码
	 * @return array          资源列表
	 */
	public function getUserUploadList($user_id, $subject, $start = 0, $limit = 10) {
		$query = " user_id:{$user_id} AND node_id:{$subject}";
		$resources = Helper_Search::init()->indexQuery($query,false,'on_time',false,false,$start,$limit, true);
		if ($resources AND isset($resources['data'])) {
			return $resources['data'];
		}
	}

	/**
	 * 获取资源上传者的头像信息
	 * @param  [type] $item [description]
	 * @return [type]       [description]
	 */
	public function getResourceUserInfo($item) {
		static $DDClientModel = null;
		if ($DDClientModel === null) {
			$DDClientModel = new Models_DDClient();
		}

		if (is_object($item)) {
			$item = current((array)$item);
		}

		if (isset($item['user_id'])) {
			$item['user_info'] = $DDClientModel->viewUserInfo($item['user_id']);
		}
		return $item;
	}

	/**
	 * 判断是否是原始资源，只要不是备课夹类型资源就是原始资源
	 * @param  [type] $item [description]
	 * @return bool         [description]
	 */
	public static function isOriginResource($item) {
		return $item['resource_type'] != Models_Lesson_Folder::SEARCH_TYPE;
	}
}