<?php

/**
 * 文件工具类，封装了基础的文件下载、上传功能
 * @author liujie <ljyf5593@gmail.com>
 */
class Models_Attachment extends Cola_Model
{

    public static $image_mimetypes = array(
        'bmp' => 'image/bmp',
        'gif' => 'image/gif',
        'ief' => 'image/ief',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'jpe' => 'image/jpeg',
        'png' => 'image/png',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'djvu' => 'image/vnd.djvu',
        'djv' => 'image/vnd.djvu',
        'wbmp' => 'image/vnd.wap.wbmp',
        'ras' => 'image/x-cmu-raster',
        'pnm' => 'image/x-portable-anymap',
        'pbm' => 'image/x-portable-bitmap',
        'pgm' => 'image/x-portable-graymap',
        'ppm' => 'image/x-portable-pixmap',
        'rgb' => 'image/x-rgb',
        'xbm' => 'image/x-xbitmap',
        'xpm' => 'image/x-xpixmap',
        'xwd' => 'image/x-xwindowdump',
    );

    /**
     * 文件后缀对应的文件类型
     * @var array
     */
    public static $file_mimetypes = array(
        'ez' => 'application/andrew-inset',
        'hqx' => 'application/mac-binhex40',
        'cpt' => 'application/mac-compactpro',
        'doc' => 'application/msword',
        'bin' => 'application/octet-stream',
        'dms' => 'application/octet-stream',
        'lha' => 'application/octet-stream',
        'lzh' => 'application/octet-stream',
        'exe' => 'application/octet-stream',
        'class' => 'application/octet-stream',
        'so' => 'application/octet-stream',
        'dll' => 'application/octet-stream',
        'oda' => 'application/oda',
        'pdf' => 'application/pdf',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        'smi' => 'application/smil',
        'smil' => 'application/smil',
        'mif' => 'application/vnd.mif',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'wbxml' => 'application/vnd.wap.wbxml',
        'wmlc' => 'application/vnd.wap.wmlc',
        'wmlsc' => 'application/vnd.wap.wmlscriptc',
        'bcpio' => 'application/x-bcpio',
        'vcd' => 'application/x-cdlink',
        'pgn' => 'application/x-chess-pgn',
        'cpio' => 'application/x-cpio',
        'csh' => 'application/x-csh',
        'dcr' => 'application/x-director',
        'dir' => 'application/x-director',
        'dxr' => 'application/x-director',
        'dvi' => 'application/x-dvi',
        'spl' => 'application/x-futuresplash',
        'gtar' => 'application/x-gtar',
        'hdf' => 'application/x-hdf',
        'js' => 'application/x-javascript',
        'skp' => 'application/x-koan',
        'skd' => 'application/x-koan',
        'skt' => 'application/x-koan',
        'skm' => 'application/x-koan',
        'latex' => 'application/x-latex',
        'nc' => 'application/x-netcdf',
        'cdf' => 'application/x-netcdf',
        'sh' => 'application/x-sh',
        'shar' => 'application/x-shar',
        'swf' => 'application/x-shockwave-flash',
        'sit' => 'application/x-stuffit',
        'sv4cpio' => 'application/x-sv4cpio',
        'sv4crc' => 'application/x-sv4crc',
        'tar' => 'application/x-tar',
        'tcl' => 'application/x-tcl',
        'tex' => 'application/x-tex',
        'texinfo' => 'application/x-texinfo',
        'texi' => 'application/x-texinfo',
        't' => 'application/x-troff',
        'tr' => 'application/x-troff',
        'roff' => 'application/x-troff',
        'man' => 'application/x-troff-man',
        'me' => 'application/x-troff-me',
        'ms' => 'application/x-troff-ms',
        'ustar' => 'application/x-ustar',
        'src' => 'application/x-wais-source',
        'xhtml' => 'application/xhtml+xml',
        'xht' => 'application/xhtml+xml',
        'zip' => 'application/zip',
        'au' => 'audio/basic',
        'snd' => 'audio/basic',
        'mid' => 'audio/midi',
        'midi' => 'audio/midi',
        'kar' => 'audio/midi',
        'mpga' => 'audio/mpeg',
        'mp2' => 'audio/mpeg',
        'mp3' => 'audio/mpeg',
        'aif' => 'audio/x-aiff',
        'aiff' => 'audio/x-aiff',
        'aifc' => 'audio/x-aiff',
        'm3u' => 'audio/x-mpegurl',
        'ram' => 'audio/x-pn-realaudio',
        'rm' => 'audio/x-pn-realaudio',
        'rpm' => 'audio/x-pn-realaudio-plugin',
        'ra' => 'audio/x-realaudio',
        'wav' => 'audio/x-wav',
        'pdb' => 'chemical/x-pdb',
        'xyz' => 'chemical/x-xyz',
        'igs' => 'model/iges',
        'iges' => 'model/iges',
        'msh' => 'model/mesh',
        'mesh' => 'model/mesh',
        'silo' => 'model/mesh',
        'wrl' => 'model/vrml',
        'vrml' => 'model/vrml',
        'css' => 'text/css',
        'html' => 'text/html',
        'htm' => 'text/html',
        'asc' => 'text/plain',
        'txt' => 'text/plain',
        'rtx' => 'text/richtext',
        'rtf' => 'text/rtf',
        'sgml' => 'text/sgml',
        'sgm' => 'text/sgml',
        'tsv' => 'text/tab-separated-values',
        'wml' => 'text/vnd.wap.wml',
        'wmls' => 'text/vnd.wap.wmlscript',
        'etx' => 'text/x-setext',
        'xsl' => 'text/xml',
        'xml' => 'text/xml',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpe' => 'video/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'mxu' => 'video/vnd.mpegurl',
        'avi' => 'video/x-msvideo',
        'movie' => 'video/x-sgi-movie',
        'mp4' => 'video/mp4',
        'flv' => 'application/x-shockwave-flash',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
        'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        'ice' => 'x-conference/x-cooltalk',
    );
    private $_config = null;
    private $_mogilefs = null;
    protected $_table = 'sys_attachment';

    /**
     * 获取用户的附件列表
     * @param  string  $user_id 用户ID
     * @param  integer $start   分页开始记录
     * @param  integer $limit   每页显示数量
     * @return array
     */
    public function getUserFileList($user_id, $start = 0, $limit = 10, array $extend_conditions = array())
    {
        $where_sql = "`user_id`='{$user_id}'";
        if ($extend_conditions) {
            foreach ($extend_conditions as $key => $value) {
                $where_sql .= " AND `{$key}`='{$value}'";
            }
        }
        return array(
            'total' => $this->count($where_sql),
            'rows' => $this->find(array(
                'where' => $where_sql,
                'start' => $start,
                'limit' => $limit,
            )),
        );
    }

    /**
     * 文件下载
     */
    public function download($file, $filename)
    {
        if ($this->_mogilefs->exists($file)) {
            $fileInfo = $this->_mogilefs->fileinfo($file);
            $pathinfo = pathinfo($file);
            $mimetypes = self::$file_mimetypes + self::$image_mimetypes;
            //根据文件后缀获取文件类型
            header("Content-type:{$mimetypes[strtolower($pathinfo['extension'])]}");
            header("Accept-Ranges:bytes");
            header("Accept-Length:{$fileInfo['length']}");
            header("Content-Disposition:attachment;filename={$filename}");

            //注意这里一定要使用echo输出，否则下载的文件为空
            echo $this->_mogilefs->get($file);
        } else {
            die('文件不存在');
        }
    }

    /**
     * 删除附件,重载父类的删除方法，在删除数据库数据时同时删除该附件
     * @return bool
     */
    public function delete($id, $col = null)
    {
        try {
            $file_info = $this->load($id, $col);
            if ($file_info) {
                $this->_mogilefs->delete($file_info['file_path']);
            }
            return parent::delete($id, $col);
        } catch (Exception $e) {
            // 记录错误以后，仍返回成功
//	        $this->_logError($e);
            return true;
        }
    }

    /**
     * 根据文件路径删除文件
     * @param  string $file_path 文件路径
     * @return bool            是否删除成功
     */
    public function deleteByPath($file_path)
    {
        $file_info = $this->find(array(
            'where' => "`file_path`='{$file_path}'",
            'limit' => 1,
        ));
        if ($file_info) {
            $file_info = current($file_info);
            return $this->delete($file_info['id']);
        }
    }

    /**
     * 删除文件
     * @param  string $file_path 文件路径
     * @return bool
     */
    public function delFile($file_path)
    {
        return $this->_mogilefs->delete($file_path);
    }

    /**
     * 文件上传
     * @param array $file 待上传的文件（来自$_FILES数组）
     * @param string $user_id 上传的用户
     * @param string $dir 文件夹
     * @param array $fileinfo 指定要生成的文件信息，需要保护
     * @return array
     */
    public function upload(array $file, $user_id, $dir = '', $upload_path = NULL)
    {
        $uploader = $this->getUploader();

        if ($upload_path === NULL) {
            $upload_path = $this->_makeUploadPath($file['name'], $dir);
        }
        if ($dir) {
            $upload_path = $dir . '/' . $upload_path;
        }
        $upload_path = trim($upload_path, '/');

        // 如果上传成功,则返回附件的名称
        if ($uploader->saveToMogilefs($file, $upload_path)) {

            $file_info = array(
                'file_path' => $upload_path,
                'file_name' => $file['name'],
                'user_id' => $user_id,
                'create_time' => $_SERVER['REQUEST_TIME'],
            );

            $insert_id = $this->insert($file_info);
            if ($insert_id) {
                $file_info['id'] = $insert_id;
                return $file_info;
            }

            // 如果上传失败则显示错误信息，同时终止执行不在将数据写入数据库
        } else {
            $file_info['error'] = implode(';', $uploader->error());
            return $file_info;
        }
    }

    /**
     * 上传附件(上传到社区的文件域)
     * @param string $appName 应用名称，如：school,class,blog...
     * @param int $appId 应用ID,如学校id,班级id...
     * @return mixed FALSE Or file info array
     */
    public function uploadToDodo($appName, $appId)
    {
        $uploader = new Cola_Com_Upload(Cola::getConfig('_atsDodoUpload'));
        //没有上传文件直接返回FALSE
        if (empty($_FILES)) {
            return FALSE;
        }
        $result = array();
        foreach ($uploader->files() as $file) {
            $sMicrotime = str_replace('.', '', microtime(TRUE));
            $fileName = "{$appName}_{$appId}_{$sMicrotime}{$file['ext']}";
            if ($uploader->getMogilefsInstance()->setFile($fileName, $file['tmp_name'])) {
                array_push($result, array('name' => $file['name'], 'path' => HTTP_MFS_ATS . $fileName));
            }
        }
        return empty($result) ? FALSE : $result;
    }

    /**
     * 流文件上传，用于flash预览上传
     * @param  [type] $filename [description]
     * @param  [type] $file     [description]
     * @return [type]           [description]
     */
    public function streamUpload($filename, $file, $user_id)
    {
        $upload_path = $this->_makeUploadPath($filename, NULL);
        $result = $this->_mogilefs->set($upload_path, $file);
        if ($result) {
            $file_info = array(
                'file_path' => $upload_path,
                'file_name' => $filename,
                'user_id' => $user_id,
                'create_time' => time(),
            );

            $insert_id = $this->insert($file_info);
            if ($insert_id) {
                $file_info['id'] = $insert_id;
                return $file_info;
            }
        }
    }

    /**
     * 重载父类的插入方法，解析文件后缀判断是否是图片
     * @param  [type] $data  [description]
     * @param  [type] $table [description]
     * @return [type]        [description]
     */
    public function insert($data, $table = null)
    {
        if (isset($data['file_path'])) {
            $pathinfo = pathinfo($data['file_path']);
            $data['file_ext'] = $pathinfo['extension'];
            $data['is_image'] = intval(isset(self::$image_mimetypes[$pathinfo['extension']]));
        }
        return parent::insert($data, $table);
    }

    /**
     * 获取文件访问地址 getBaseUrl().$path
     */
    public function getBaseUrl()
    {
        return $this->_config['requestUrl'];
    }

    public function getImageSize($filename)
    {
        $uploader = $this->getUploader();
        return $uploader->getImageSize($filename);
    }

    private function getUploader()
    {
        static $uploader = NULL;
        if (!$uploader) {
            $uploader = new Cola_Com_Upload($this->_config);
        }
        return $uploader;
    }

    /**
     * 生成文件名称
     * @param $file
     * @param $dir
     * @return array
     */
    private function _makeUploadPath($filepath, $dir)
    {
        $fileinfo = pathinfo($filepath);
        $filename = uniqid();
        return $filename . '.' . $fileinfo['extension'];
    }

    /**
     * 获取配置信息
     */
    protected function __construct()
    {
        $this->_config = Cola::getConfig('_atsUpload');
        $this->_mogilefs = new Cola_Com_Mogilefs($this->_config['mogilefs']['domain'], $this->_config['mogilefs']['class'], $this->_config['mogilefs']['trackers']);
    }

}
