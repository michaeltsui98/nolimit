<?php
/**********************************************
 * 
 * 接口基类，主要从义教和高中学籍获取数据
 * 或者从远程信息发布系统获取数据
 * soft456@gmail.com 2012年8月3日 
 * mod soft456 2012-08-27
 *
 **********************************************/
class Cola_Com_BSExt_Interface {
		
	private $cfgRs = array();

	public function __construct($rs=null)
	{
		if(null === $rs){
			$this->cfgRs = Cola::$config->get('_interface');
		}else{
			$this->cfgRs = $rs;
		}
	}
	
	/**
	 * 用来调用学籍系统的数据，
	 *
	 * @param $aRs 封装接口所需参数,接口名，学段，入学年份，等
	 * @param
	 * @return 成功返回数据集；否则返回错误码或者具体报错信息
	 */
	public function getData($aRs)
	{
		if(!is_array($aRs))die('908');

		for($i=0; $i < 10; $i++){
			$retRs = null;
			$ret = $this->getRemoteData($aRs);

			//匹配是否是正则串
			$isJsonStr = preg_match("/^\[\{(.*?):(.*?)\}\]$/", $ret);

			if($isJsonStr){
				$retRs = (array)json_decode($ret);				
				break;
			}else{
				$retRs = $ret;
			}
			sleep(1);
		}
		return $retRs;
	}
	
	/**
	 * 加密封装数据，调用学籍接口获取数据
	 *
	 * @param
	 * @param
	 * @return 返回JSON数据串或错误码
	 */
	private function getRemoteData($aRs)
	{
		$mcrypt = new Cola_Com_BSExt_Mcrypt($this->cfgRs["key"],$this->cfgRs["timeOut"]);
		$url = $this->cfgRs["serverUrl"]."?c=".$this->cfgRs["client"];
		$s = $mcrypt->Encode($aRs);				
		$url = $url.'&p='.$s;
		//header('location:'.$url);
		//exit;
		
		$ret = file_get_contents($url);	
		return $ret;			
	}
	
	/**
	 * 信息发布系统——执行远程接口程序
	 *
	 * @param
	 * @param
	 * @return boolean
	 */
	public function doRemoteAction($aRs)
	{
		$mcrypt = new Cola_Com_BSExt_Mcrypt($this->cfgRs["key"],$this->cfgRs["timeOut"]);
		$s = $mcrypt->Encode($aRs);
		
		$goUrl = $this->cfgRs["serverUrl"]."/index.php";
		$goUrl = $goUrl."?client=".$this->cfgRs["client"]."&s=".$s;		
		
		//header('location:'.$url);
		//exit;
		
		$ret = file_get_contents($goUrl);	
		return $ret;			
	}
	
	/**
	 * 获取远程信息发布系统的数据, controll 调用此方法,
	 *
	 * @param
	 * @param
	 * @return 成功返回数据集；否则返回错误码或者具体报错信息
	 */
	public function getArticleData($aRs)
	{
		if(!is_array($aRs))die('908');

		for($i=0; $i < 1; $i++){
			$retRs = null;
			$ret = $this->getRemoteArticleData($aRs);

			//匹配是否是正则串
			$isJsonStr = preg_match("/^\[\{(.*?):(.*?)\}\]$/", $ret);

			if($isJsonStr){
				$retRs = (array)json_decode($ret);
				break;
			}else{
				$isJsonStr = preg_match("/^\{(.*?):(.*?)\}$/", $ret);
				if($isJsonStr){
					$retRs = (array)json_decode($ret);
					break;
				}else{
					$retRs = $ret;
				}				
			}
			sleep(1);			
		}
		return $retRs;
	}
	
	/**
	 * 加密封装数据，调用远程信息发布系统
	 *
	 * @param
	 * @param
	 * @return 返回JSON数据串或错误码
	 */
	private function getRemoteArticleData($aRs)
	{
		$mcrypt = new Cola_Com_BSExt_Mcrypt($this->cfgRs["key"],$this->cfgRs["timeOut"]);
		$s = $mcrypt->Encode($aRs);			

		$goUrl = $this->cfgRs["serverUrl"]."/index.php";
		$goUrl = $goUrl."?client=".$this->cfgRs["client"]."&s=".$s;		
		
		$ret = file_get_contents($goUrl);	
		return $ret;			
	}
	
	
	/**
	 * 跳转到远程信息发布系统
	 *
	 * @param
	 * @param
	 * @return url
	 */
	public function getArticleUrl($rs, $t)
	{
		$mcrypt = new Cola_Com_BSExt_Mcrypt($this->cfgRs["key"],$this->cfgRs["timeOut"]);
		$mcrypt -> SetDigFunc('sha1');
		$s = $mcrypt->Encode($rs);		

		//写
		$goUrl = $this->cfgRs["serverUrl"]."/remoteLogin.php";
		$goUrl = $goUrl."?client=".$this->cfgRs["client"]."&t=".$t."&s=".$s;
		
		return $goUrl;		
	}
	
	/**
	 * 根据新华书店里的年级编码计算入学年份
	 *
	 * @param $grade 新华书店的njbm ，如高三是 46
	 * @return 入学年份。
	 */
	public function getYearByGrade($grade)
	{
		$gradeCodeRs = Cola::$config->get('_gradeCode');
		if(!array_key_exists($grade, $gradeCodeRs)){
			return false;
		}else{
			$num = $gradeCodeRs[$grade]['code'];

			$nowMonth=date('n'); //月
			$nowYear=date('Y');  //年

			switch ($gradeCodeRs[$grade]['xd']){
				case 'xx':
						$addNum = 0;
					break;
				case 'cz':
						$addNum = 6;
					break;
				case 'gz':
						$addNum = 9;
					break;
			
			}
			
			$monthNum = ($nowMonth >= 9) ? 1 : 0;			
			$year = $nowYear - $num + $addNum + $monthNum;	
			return $year;
		}
	}
}