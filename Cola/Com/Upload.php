<?php

/**
 *
 */
class Cola_Com_Upload
{

    /**
     * Upload error message
     *
     * @var array
     */
    protected $_message = array(
        1 => 'upload_file_exceeds_limit',
        2 => 'upload_file_exceeds_form_limit',
        3 => 'upload_file_partial',
        4 => 'upload_no_file_selected',
        6 => 'upload_no_temp_directory',
        7 => 'upload_unable_to_write_file',
        8 => 'upload_stopped_by_extension'
    );

    /**
     * Upload config
     *
     * @var array
     */
    protected $_config = array(
        'savePath' => '/tmp',
        'maxSize' => 0,
        'maxWidth' => 0,
        'maxHeight' => 0,
        'allowedExts' => '*',
        'allowedTypes' => '*',
        'override' => false,
        'mogilefs' => array(),
    );

    /**
     * The num of successfully uploader files
     *
     * @var int
     */
    protected $_num = 0;

    /**
     * Formated $_FILES
     *
     * @var array
     */
    protected $_files = array();

    /**
     * Error
     *
     * @var array
     */
    protected $_error;

    /**
     * mogilefs
     *
     * @var object
     */
    protected $_mogilefs = null;

    /**
     * Constructor
     *
     * Construct && formate $_FILES
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->_config = $config + $this->_config;

        $this->_config['savePath'] = rtrim($this->_config['savePath'], DIRECTORY_SEPARATOR);

        $this->_format();
    }

    /**
     * Config
     *
     * Set or get configration
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function config($name = null, $value = null)
    {
        if (null == $name) {
            return $this->_config;
        }

        if (null == $value) {
            return isset($this->_config[$name]) ? $this->_config[$name] : null;
        }

        $this->_config[$name] = $value;

        return $this;
    }

    /**
     * Format $_FILES
     *
     */
    protected function _format()
    {
        foreach ($_FILES as $field => $file) {

            if (empty($file['name'])) continue;

            if (is_array($file['name'])) {
                $cnt = count($file['name']);

                for ($i = 0; $i < $cnt; $i++) {
                    if (empty($file['name'][$i])) continue;
                    $this->_files[] = array(
                        'field' => $field,
                        'name' => $file['name'][$i],
                        'type' => $file['type'][$i],
                        'tmp_name' => $file['tmp_name'][$i],
                        'error' => $file['error'][$i],
                        'size' => $file['size'][$i],
                        'ext' => $this->getExt($file['name'][$i], true)
                    );
                }
            } else {
                $this->_files[] = $file + array('field' => $field, 'ext' => $this->getExt($file['name'], true));
            }
        }
    }

    /**
     * init mogilefsd
     * @return object
     */
    protected function _mogilefsInit()
    {

        if (NULL == $this->_mogilefs) {
            extract($this->config('mogilefs'));
            $this->_mogilefs = new Cola_Com_Mogilefs($domain, $class, $trackers);
        }

        return $this->_mogilefs;
    }

    /**
     * Save uploaded files
     *
     * @param array $file
     * @param string $name
     * @return boolean
     */
    public function save($file = null, $name = null)
    {
        if (!is_null($file)) {
            return $this->_move($file, $name);
        }

        $return = true;

        foreach ($this->_files as $file) {
            $return = $return && $this->_move($file);
        }

        return $return;
    }
    /**
     * Instance MogileFs
     * @return Cola_Com_Mogilefs
     */
    public function getMogilefsInstance()
    {

        if (NULL === $this->_mogilefs) {
            extract($this->config('mogilefs'));
            $this->_mogilefs = new Cola_Com_Mogilefs($domain, $class, $trackers);
        }

        return $this->_mogilefs;
    }
    /**
     * Move file
     *
     * @param array $file
     * @param string $name
     * @return boolean
     */
    protected function _move($file, $name = null)
    {
        if (!$this->check($file)) {
            return false;
        }

        if (null === $name) $name = $file['name'];
        $fileFullName = $this->_config['savePath'] . DIRECTORY_SEPARATOR . $name;

        if (file_exists($fileFullName) && !$this->_config['override']) {
            $msg = 'file_already_exits:' . $fileFullName;
            $this->_error[] = $msg;
            return false;
        }

        $dir = dirname($fileFullName);
        is_dir($dir) || Cola_Com_Fs::mkdir($dir);

        if (is_writable($dir) && move_uploaded_file($file['tmp_name'], $fileFullName)) {
            $this->_num++;
            return true;
        }

        $this->_error[] = 'move_uploaded_file_failed:' . $dir . 'may not be writeable.';
        return false;
    }

    /**
     * Save uploaded files to mogilefs
     *
     * @param array $file
     * @param string $name
     * @return boolean
     */
    public function saveToMogilefs($file = null, $name = null)
    {
        if (!is_null($file)) {
            return $this->_moveToMogilefs($file, $name);
        }

        $return = true;

        foreach ($this->_files as $file) {
            $return = $return && $this->_moveToMogilefs($file);
        }

        return $return;
    }

    /**
     * upload file to mogilefs
     *
     * @param array $file
     * @param string $name
     * @return boolean
     */
    protected function _moveToMogilefs($file, $name = null)
    {
        if (!$this->check($file)) {
            return false;
        }

        if (null === $name) $name = $file['name'];

        //create mogilefs object
        $this->_mogilefsInit();

        if ($this->_mogilefs->exists($name) && !$this->_config['override']) {
            $msg = 'file_already_exits:' . $name;
            $this->_error[] = $msg;
            return false;
        }

        if ($this->_mogilefs->setFile($name, $file['tmp_name'])) {
            $this->_num++;
            return true;
        }

        $this->_error[] = 'move_uploaded_file_failed:' . $name . 'may not be writeable.';
        return false;
    }

    /**
     * Check file
     *
     * @param array $file
     * @return string
     */
    public function check($file)
    {
        if (UPLOAD_ERR_OK != $file['error']) {
            $this->_error[] = $this->_message[$file['error']] . ':' . $file['name'];
            return false;
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            $this->_error[] = 'file_upload_failed:' . $file['name'];
            return false;
        }

        if (!$this->checkType($file, $this->_config['allowedTypes'])) {
            $this->_error[] = 'file_type_not_allowed:' . $file['name'];
            return false;
        }

        if (!$this->checkExt($file, $this->_config['allowedExts'])) {
            $this->_error[] = 'file_ext_not_allowed:' . $file['name'];
            return false;
        }

        if (!$this->checkFileSize($file, $this->_config['maxSize'])) {
            $this->_error[] = 'file_size_not_allowed:' . $file['name'];
            return false;
        }

        if ($this->isImage($file) && !$this->checkImageSize($file, array($this->_config['maxWidth'], $this->_config['maxHeight']))) {
            $this->_error[] = 'image_size_not_allowed:' . $file['name'];
            return false;
        }

        return true;
    }

    /**
     * Get image size
     *
     * @param string $file
     * @return array like array(x, y),x is width, y is height
     */
    public function getImageSize($name)
    {
        if (function_exists('getimagesize')) {
            $size = getimagesize($name);
            return array($size[0], $size[1]);
        }

        return false;
    }

    /**
     * Get file extension
     *
     * @param string $fileName
     * @return string
     */
    public function getExt($name, $withdot = false)
    {
        $pathinfo = pathinfo($name);
        if (isset($pathinfo['extension'])) {
            return ($withdot ? '.' : '' ) . $pathinfo['extension'];
        }
        return '';
    }

    /**
     * Check if is image
     *
     * @param string $type
     * @param string $imageTypes
     * @return boolean
     */
    public function isImage($file)
    {
        return 'image' == substr($file['type'], 0, 5);
    }

    /**
     * Check file type
     *
     * @param string $type
     * @param string $allowedTypes
     * @return boolean
     */
    public function checkType($file, $allowedTypes)
    {
        return ('*' == $allowedTypes || false !== stripos($allowedTypes, $file['type'])) ? true : false;
    }

    /**
     * Check file ext
     *
     * @param string $ext
     * @param string $allowedExts
     * @return boolean
     */
    public function checkExt($file, $allowedExts)
    {
        return ('*' == $allowedExts || false !== stripos($allowedExts, $this->getExt($file['name']))) ? true : false;
    }

    /**
     * Check file size
     *
     * @param int $size
     * @param int $maxSize
     * @return boolean
     */
    public function checkFileSize($file, $maxSize)
    {
        return 0 === $maxSize || $file['size'] <= $maxSize;
    }

    /**
     * Check image size
     *
     * @param array $size
     * @param array $maxSize
     * @return unknown
     */
    public function checkImageSize($file, $maxSize)
    {
        $size = $this->getImageSize($file['tmp_name']);
        return (0 === $maxSize[0] || $size[0] <= $maxSize[0]) && (0 === $maxSize[1] || $size[1] <= $maxSize[1]);
    }

    /**
     * Get formated files
     *
     * @return array
     */
    public function files()
    {
        return $this->_files;
    }

    /**
     * Get the num of sucessfully uploaded files
     *
     * @return int
     */
    public function num()
    {
        return $this->_num;
    }

    /**
     * Get upload error
     *
     * @return array
     */
    public function error()
    {
        return $this->_error;
    }

    public function getImageInfo($img)
    {
        $imageInfo = getimagesize($img);
        if ($imageInfo !== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
            $imageSize = filesize($img);
            $info = array(
                "width" => $imageInfo[0],
                "height" => $imageInfo[1],
                "type" => $imageType,
                "size" => $imageSize,
                "mime" => $imageInfo['mime']
            );
            return $info;
        } else {
            return false;
        }
    }

    public function thumbA($image, $thumbname, $Width = 200)
    {
        //�������
        $info = $this->getImageInfo($image);
        $type = $info['type'];
        $createFun = 'ImageCreateFrom' . ($type == 'jpg' ? 'jpeg' : $type);
        $srcImg = $createFun($image);
        $srcWidth = $info['width'];
        $srcHeight = $info['height'];
        $min = min($srcWidth, $srcHeight);
        $Width = ($Width > $min) ? $min : $Width;
        $type = $info['type'];
        if ($srcWidth > $srcHeight) {
            //ͼƬ����ڸ�
            $sx = abs(($srcHeight - $srcWidth) / 2);
            $sy = 0;
            $thumbw = $srcHeight;
            $thumbh = $srcHeight;
        } else {
            //ͼƬ�ߴ��ڵ��ڿ�
            //$sy = abs(($srcWidth - $srcHeight) / 2);
            $sy = 0;
            $sx = 0;
            $thumbw = $srcWidth;
            $thumbh = $srcWidth;
        }
        if (function_exists("imagecreatetruecolor")) $thumbImg = imagecreatetruecolor($Width, $Width); // ����Ŀ��ͼgd2
        else $thumbImg = imagecreate($Width, $Width); // ����Ŀ��ͼgd1
        imagecopyresized($thumbImg, $srcImg, 0, 0, $sx, $sy, $Width, $Width, $thumbw, $thumbh);
        if ('gif' == $type || 'png' == $type) {
            $background_color = imagecolorallocate($thumbImg, 0, 255, 0);  //  ָ��һ����ɫ
            imagecolortransparent($thumbImg, $background_color);  //  ����Ϊ͸��ɫ����ע�͵������������ɫ��ͼ
        }
        // ��jpegͼ�����ø���ɨ��
        if ('jpg' == $type || 'jpeg' == $type) imageinterlace($thumbImg, 1);
        // ���ͼƬ
        $imageFun = 'image' . ($type == 'jpg' ? 'jpeg' : $type);
        $imageFun($thumbImg, $thumbname);
        imagedestroy($thumbImg);
        return $thumbname;
    }

    public function thumb($image, $thumbname, $type = '', $maxWidth = 200, $maxHeight = 50, $interlace = true)
    {
        // ��ȡԭͼ��Ϣ
        $info = $this->getImageInfo($image);
        if ($info !== false) {
            $srcWidth = $info['width'];
            $srcHeight = $info['height'];
            $type = empty($type) ? $info['type'] : $type;
            $type = strtolower($type);
            if ($maxWidth == $maxHeight) {
                return $this->thumbA($image, $thumbname, $maxWidth);
            }
            $interlace = $interlace ? 1 : 0;
            unset($info);
            $scale = min($maxWidth / $srcWidth, $maxHeight / $srcHeight); // �������ű���
            if ($scale >= 1) {
                // ����ԭͼ��С��������
                $width = $srcWidth;
                $height = $srcHeight;
            } else {
                // ����ͼ�ߴ�
                $width = (int) ($srcWidth * $scale);
                $height = (int) ($srcHeight * $scale);
            }
            // ����ԭͼ
            $createFun = 'ImageCreateFrom' . ($type == 'jpg' ? 'jpeg' : $type);
            $srcImg = $createFun($image);

            //��������ͼ
            if ($type != 'gif' && function_exists('imagecreatetruecolor')) $thumbImg = imagecreatetruecolor($width, $height);
            else $thumbImg = imagecreate($width, $height);
            // ����ͼƬ
            if (function_exists("ImageCopyResampled")) imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
            else imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
            if ('gif' == $type || 'png' == $type) {
                //imagealphablending($thumbImg, false);//ȡ��Ĭ�ϵĻ�ɫģʽ
                //imagesavealpha($thumbImg,true);//�趨��������� alpha ͨ����Ϣ
                $background_color = imagecolorallocate($thumbImg, 0, 255, 0);  //  ָ��һ����ɫ
                imagecolortransparent($thumbImg, $background_color);  //  ����Ϊ͸��ɫ����ע�͵������������ɫ��ͼ
            }
            // ��jpegͼ�����ø���ɨ��
            if ('jpg' == $type || 'jpeg' == $type) imageinterlace($thumbImg, 1);
            //$gray=ImageColorAllocate($thumbImg,255,0,0);
            //ImageString($thumbImg,2,5,5,"ThinkPHP",$gray);
            // ���ͼƬ
            $imageFun = 'image' . ($type == 'jpg' ? 'jpeg' : $type);
            $imageFun($thumbImg, $thumbname);
            imagedestroy($thumbImg);
            imagedestroy($srcImg);
            return $thumbname;
        }
        return false;
    }
}