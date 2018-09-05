<?php

/*
 * Upload files
 * To use you need to set the following
 *  - setUploadPath
 *  - setAllowedTypes (mime types)
 *  - setFieldName
 *  - setFileNam
 */
class FileUpload extends SetterGetter 
{
    const DEFAULT_MAX_UPLOAD_SIZE = 8388608; // 8 MB
    
    function __construct() {
        $this->resetAllowedTypes();
        $this->setMaxFileSize(self::DEFAULT_MAX_UPLOAD_SIZE);
        $this->setMimeGetMode('php');
    }
    
    /*
     * Add an allowed mime type
     */
    public function addAllowedType($sType) {
        $this->aAllowedTypes[] = $sType;
    }
    
    /*
     * Reset all the allowed mime types
     */
    public function resetAllowedTypes() {
        $this->aAllowedTypes = array();
    }
    
    /*
     * Returns all the allowed mime types
     */
    public function getAllowedTypes() {
        return $this->aAllowedTypes;
    }
    
    /*
     * Check if an error occurred during the file upload
     */
    public function checkUploadError() {
        if ($_FILES[$this->getFieldName()]['error'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /*
     * get the file extension based on the mime tyoe
     */
    public function getExtension($sPath = false) {
        if (!$sPath) {
            $sPath = $_FILES[$this->getFieldName()]['name'];
        }
        if (!$sPath) {
            return false;
        }
        return pathinfo($sPath, PATHINFO_EXTENSION);
    }
    
    /*
     * Special function that checks the mp3 file
     */
    public function getMP3BitRateSampleRate($filename) {
        if (!file_exists($filename)) {
            return false;
        }
        
        $bitRates = array(
                array(0,0,0,0,0),
                array(32,32,32,32,8),
                array(64,48,40,48,16),
                array(96,56,48,56,24),
                array(128,64,56,64,32),
                array(160,80,64,80,40),
                array(192,96,80,96,48),
                array(224,112,96,112,56),
                array(256,128,112,128,64),
                array(288,160,128,144,80),
                array(320,192,160,160,96),
                array(352,224,192,176,112),
                array(384,256,224,192,128),
                array(416,320,256,224,144),
                array(448,384,320,256,160),
                array(-1,-1,-1,-1,-1),
        );
        $sampleRates = array(
                array(11025,12000,8000), //mpeg 2.5
                array(0,0,0),
                array(22050,24000,16000), //mpeg 2
                array(44100,48000,32000), //mpeg 1
        );
        $bToRead = 1024 * 12;
        
        $fileData = array('bitRate' => 0, 'sampleRate' => 0);
        $fp = fopen($filename, 'r');
        if (!$fp) {
            return false;
        }
        //seek to 8kb before the end of the file
        fseek($fp, -1 * $bToRead, SEEK_END);
        $data = fread($fp, $bToRead);
        
        $bytes = unpack('C*', $data);
        $frames = array();
        $lastFrameVerify = null;
        
        for ($o = 1; $o < count($bytes) - 4; $o++) {
        
            //http://mpgedit.org/mpgedit/mpeg_format/MP3Format.html
            //header is AAAAAAAA AAABBCCD EEEEFFGH IIJJKLMM
            if (($bytes[$o] & 255) == 255 && ($bytes[$o+1] & 224) == 224) {
                $frame = array();
                $frame['version'] = ($bytes[$o+1] & 24) >> 3; //get BB (0 -> 3)
                $frame['layer'] = abs((($bytes[$o+1] & 6) >> 1) - 4); //get CC (1 -> 3), then invert
                $srIndex = ($bytes[$o+2] & 12) >> 2; //get FF (0 -> 3)
                $brRow = ($bytes[$o+2] & 240) >> 4; //get EEEE (0 -> 15)
                $frame['padding'] = ($bytes[$o+2] & 2) >> 1; //get G
                if ($frame['version'] != 1 && $frame['layer'] > 0 && $srIndex < 3 && $brRow != 15 && $brRow != 0 &&
                        (!$lastFrameVerify || $lastFrameVerify === $bytes[$o+1])) {
                    //valid frame header
        
                    //calculate how much to skip to get to the next header
                    $frame['sampleRate'] = $sampleRates[$frame['version']][$srIndex];
                    if ($frame['version'] & 1 == 1) {
                        $frame['bitRate'] = $bitRates[$brRow][$frame['layer']-1]; //v1 and l1,l2,l3
                    } else {
                        //v2 and l1 or l2/l3 (3 is the offset in the arrays)
                        $frame['bitRate'] = $bitRates[$brRow][($frame['layer'] & 2 >> 1)+3]; 
                    }
        
                    if ($frame['layer'] == 1) {
                        $frame['frameLength'] = (12*$frame['bitRate']*1000/ $frame['sampleRate']+ $frame['padding'])* 4;
                    } else {
                        $frame['frameLength'] = 144* $frame['bitRate']* 1000 / $frame['sampleRate']+ $frame['padding'];
                    }
        
                    $frames[] = $frame;
                    $lastFrameVerify = $bytes[$o+1];
                    $o += floor($frame['frameLength'] - 1);
                } else {
                    $frames = array();
                    $lastFrameVerify = null;
                }
            }
            if (count($frames) < 3) { //verify at least 3 frames to make sure its an mp3
                continue;
            }
        
            $header = array_pop($frames);
            $fileData['sampleRate'] = $header['sampleRate'];
            $fileData['bitRate'] = $header['bitRate'];
        
            break;
        }
        
        return $fileData;
    }
    
    /*
     * Check if the mime type is in the allowed mime type list
     */
    public function checkMimeType($sMimeType) {
        $aAllowed = $this->getAllowedTypes();
        if (in_array($sMimeType, $aAllowed)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /*
     * Gets the mime type of the uploaded file
     */
    public function getMimeType($sCheckType = 'php') {
        $sFile = $this->getFieldName();
        if (!$sFile) {
            return false;
        }
        switch ($sCheckType) {
            case 'php':
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $sMime = finfo_file($finfo, $_FILES[$sFile]['tmp_name']);
                finfo_close($finfo);
                return $sMime;
            case 'mp3':
                $mp3Data = $this->getMP3BitRateSampleRate($_FILES[$sFile]['tmp_name']);
                if ($mp3Data['bitRate'] >= 8 && $mp3Data['bitRate'] <= 512 && 
                        $mp3Data['sampleRate'] >= 8000 && $mp3Data['sampleRate'] <= 48000) {
                    return 'audio/mpeg';
                }
                return false;
        }
    }
    
    /*
     * Make the Upload
     */
    public function Upload() {
        // sanity checks
        if (!$this->getUploadPath()) {
            trigger_error('No upload path selected, line '.__LINE__, E_USER_WARNING);
        }
        if (!is_dir($this->getUploadPath())) {
            trigger_error('Destination folder does not exist. Please create it.'.__LINE__, E_USER_WARNING);
        }
        if (!$this->getFieldName()) {
            trigger_error('No file specified for uplaod, line '.__LINE__, E_USER_WARNING);
        }
        if (!$this->getFileName()) {
            trigger_error('No destination name specified, line'.__LINE__, E_USER_WARNING);
        }
        if (!$this->checkUploadError()) {
            return false;
        }
        
        // check if mime type matches allowed mime types
        $mimeMode = $this->getMimeType($this->getMimeGetMode());
        if (!$this->checkMimeType($mimeMode)) {
            return false;
        }
        
        // check the file size
        if (filesize($_FILES[$this->getFieldName()]['tmp_name']) > $this->getMaxFileSize()) {
            return false;
        }
        
        if (!is_uploaded_file($_FILES[$this->getFieldName()]['tmp_name'])) {
            return false;
        }
        
        // set the file size for later use
        $this->setFileSize(filesize($_FILES[$this->getFieldName()]['tmp_name']));
        
        // get file extension
        $sExt = $this->getExtension();
        $this->setFileExtension($sExt);
        
        $r = move_uploaded_file(
            $_FILES[$this->getFieldName()]['tmp_name'], 
            $this->getUploadPath().'/'.$this->getFileName().'.'.$sExt
        );
        if(! $r) {
            return false;
        }
        
        return true;
    }
}
