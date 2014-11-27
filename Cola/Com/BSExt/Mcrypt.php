<?php
/**********************************************
 * 3des加密解密字符串类;加密串失效时间默认为600000毫秒。10分钟
 *
 * soft456@gmail.com 2009年8月17日 23:28:32
 * mod 20120804
 **********************************************/
class Cola_Com_BSExt_Mcrypt {
	private $key = null;
	private $timeOut = null;
	private $digestFunc = "sha1";

	//默认超时时间为：10分钟，即600秒。
	function __construct($key="aH1_~2@?",$timeOut=600){
		$this -> key = $key;
		$this -> timeOut = $timeOut;
	}
	
	function SetKey($sKey){
		$this -> key = $sKey;
	}

	function SetDigFunc($funcName){
		switch ($funcName) {
			case 'sha1':
				$this -> digestFunc = 'sha1';
			break;
			case 'md5':
				$this -> digestFunc = 'md5';
			break;
			default :
				$this -> digestFunc = 'sha1';
		}
	}

	//加密字符串，入口参数为一唯数组；成功返回加密后的字符串
	public function Encode($aPar){
		
		if(!is_array($aPar)) die("传入参数错误！");
		
		$oIV = pack('H16',"0102030405060708");

		//取得时间戳
		$sTimeStamp = $this -> getMicroTime();
		$sParameterValue = null;
		
		//$aPar = array_map(array('Cola_Com_BSExt_Mcrypt','getUTFString'), $aPar);				
		$sParameterValue = base64_encode(json_encode($aPar));
		
		$digFuncName = $this -> digestFunc;				
		
		$digest = base64_encode($digFuncName($sTimeStamp."$".$sParameterValue));

		$sParameterValue = $this -> paddingPKCS7($sTimeStamp."$".$sParameterValue."$".$digest);

		$sParameterValue = mcrypt_encrypt(MCRYPT_3DES,$this->key,$sParameterValue,MCRYPT_MODE_CBC,$oIV);
		$sParameterValue = rawurlencode(base64_encode($sParameterValue));
		return $sParameterValue;
	}

	//解码字符串，成功返回数组，不含时间。否则返回false
	public function Decode($str){
		if('' == $str) die('Param Error!');
		
		$str = base64_decode(rawurldecode($str));		
		$oIV = pack('H16',"0102030405060708");

		//解密字符串
		$str = mcrypt_decrypt(MCRYPT_3DES,$this->key,$str,MCRYPT_MODE_CBC,$oIV);			

		$aStrRs = explode("$", $str);	
		
		if (count($aStrRs) < 2 ) die('Decode Error');
		
		$urlTime = $aStrRs[0];
		
		//验证HAS字串是否相等		
		$iCnt = count($aStrRs);
	
		$sParameterValue = $aStrRs[1];
		
		$digFuncName = $this -> digestFunc;		
		$sDigest = strtoupper($digFuncName($urlTime.'$'.$sParameterValue));	

		$urlDig = iconv('utf-8','gb2312',$aStrRs[$iCnt - 1]);	
		$sUrlDigest = strtoupper(base64_decode($urlDig));
				
		//转换成数组
		$aStrRs = (array)json_decode(base64_decode($sParameterValue));	
		
		//统一用utf-8，不用转换
		//$aStrRs = array_map(array('Cola_Com_BSExt_Mcrypt','getGb2312Str'), $aStrRs);		
			
		if($sDigest == $sUrlDigest){
			//检测加密串的有效期
			$sTimeStamp = $this -> getMicroTime();
			if(($sTimeStamp - $urlTime) > $this -> timeOut){
				//超时
				return false;
				exit;
			}else{
				return $aStrRs;
			}
		}else{
			return false;
			exit;
		}
	}

	private function isTimeOut($str){
		$str = base64_decode(rawurldecode($str));
		$oIV = pack('H16',"0102030405060708");

		//解密字符串获取加密串的时间
		$str = mcrypt_decrypt(MCRYPT_3DES,$this->key,$str,MCRYPT_MODE_CBC,$oIV);
		$aStrRs = explode("$", $str);
		$oldTime = $aStrRs[0];
		unset($aStrRs);

		$sTimeStamp = $this -> getMicroTime();
		if(($sTimeStamp - $oldTime) > $this -> timeOut){
			//超时
			return true;
		}else{
			return false;
		}
	}

	//将当前时间更新到加密串中，避免失效.成功返回新的加密串
	public function refreshTimeOut($str){
		if('' == $str){
			return false;
		}else{
			$aTempRs = $this -> Decode($str);
			array_shift($aTempRs);
			return $this -> Encode($aTempRs);
		}
	}

	//取得当前时间到指定时间之间的毫秒数（默认为2000年1月1日0点0分0秒）
	private function getMicroTime(){
		list($usec, $sec) = explode(" ",microtime());
		//$time_start =($sec*1000+$usec*1000);
		//$to = mktime(0,0,0,1,1,2000);
		//$sUnixTime = ceil($time_start-$to*1000);
		//$sUnixTime = ceil($time_start-946684800000);
		return "$sec";
	}

	//将要加密的子串的填充模式转换，补齐位数
	public function paddingPKCS7($data) {
		$block_size = mcrypt_get_block_size('tripledes', 'cbc');
		$padding_char = $block_size - (strlen($data) % $block_size);
		$data .= str_repeat(chr($padding_char),$padding_char);
		return $data;
	}

	public function getUTFString($string){
		$encoding = mb_detect_encoding($string, array('ASCII','GB2312','GBK','BIG5'));
		return mb_convert_encoding($string, 'utf-8', $encoding);
	}

	public function getGb2312Str($string){
		$encoding = mb_detect_encoding($string, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
		return mb_convert_encoding($string, 'GB2312', $encoding);
	}
}