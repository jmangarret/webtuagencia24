<?php
/**
 * @file com_rotator/admin/uploader.php
 * @ingroup _comp_adm
 * Archivo encargado de administrar la imagen cuando se sube al servidor mediante
 * el componente (Por medio de Ajax))
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @brief Handle file uploads via XMLHttpRequest (Please to see http://valums.com/ajax-upload/)
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    /**
     * @brief Return the file's name (Please to see http://valums.com/ajax-upload/)
     * @return string
     */
    function getName() {
        return $_GET['qqfile'];
    }
    /**
     * @brief Return the file's size (Please to see http://valums.com/ajax-upload/)
     * @return integer
     */
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * @brief Handle file uploads via regular form post (uses the $_FILES array)
 * (Please to see http://valums.com/ajax-upload/)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    /**
     * @brief Return the file's name (Please to see http://valums.com/ajax-upload/)
     * @return string
     */
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    /**
     * @brief Return the file's size (Please to see http://valums.com/ajax-upload/)
     * @return integer
     */
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

/**
 * @brief Class to manage the uploads
 * (Please to see http://valums.com/ajax-upload/)
 * @ingroup _comp_adm
 */
class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;
    private $uploadDirectory;

    /**
     * @brief Contructor (Please to see http://valums.com/ajax-upload/)
     * @param array $allowedExtensions Extensions allowed
     * @param integer $sizeLimit Size maximum
     */
    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    /**
     * @brief Check the server configuration (Please to see http://valums.com/ajax-upload/)
     */
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }
    }
    
    /**
     * @brief Change the string for a integer that represents the
     * size of the file (Please to see http://valums.com/ajax-upload/)
     * @param string $str String to change
     * @return integer
     */
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * @brief Save the file on the server (Please to see http://valums.com/ajax-upload/)
     *
     * Returns array('success'=>true) or array('error'=>'error message')
     * @param string $uploadDirectory It's the directory where it saves the file
     * @param bool $replaceOldFile Mean if it replaces the file
     * @return array
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){

        $this->uploadDirectory = $uploadDirectory;

        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>true);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
    }    

    /**
     * @brief Return the file's name (Please to see http://valums.com/ajax-upload/)
     * @return string
     */
    function getName()
    {
        return $this->uploadDirectory.$this->file->getName();
    }
}
