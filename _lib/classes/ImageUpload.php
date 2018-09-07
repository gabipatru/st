<?php

/*
 * Upload and resize image
 */
class ImageUpload extends FileUpload
{
    private $resizeWidth;
    private $resizeHeight;
    
    public function __construct()
    {
        $this->addAllowedType('image/jpeg');
        $this->addAllowedType('image/png');
        $this->addAllowedType('image/gif');
        
        $this->setStretch(false);
        
        $this->setMimeGetMode('php');
    }
    
    public function ResizeTo(int $width, int $height)
    {
        $this->resizeWidth = $width;
        $this->resizeHeight = $height;
    }
    
    private function getResizeTo() :array
    {
        return [ $this->resizeWidth, $this->resizeHeight ];
    }
    
    protected function getSourceFile() :string
    {
        if ($this->getFieldName()) {
            return $_FILES[$this->getFieldName()]['tmp_name'];
        } elseif ($this->getSourceFileName()) {
            return $this->getSourceFileName();
        }
        
        return null;
    }
    
    /**
     * Modify the newHeight parameter in order to keep the image's old aspect ratio
     * 
     * @param int $newWidth
     * @param int $newHeight
     * @param int $width
     * @param int $height
     * 
     * @return array
     */
    protected function keepAspectRatio($newWidth, $newHeight, $width, $height) :array
    {
        $ratio = $width / $height;
        $newRatio = $newWidth / $newHeight;
        
        if ($newRatio != $ratio) {
            $newHeight = ceil( $height * ($newWidth / $width) );
        }
        
        return [ $newWidth, $newHeight ];
    }
    
    public function Upload() :bool
    {
        // if no resize is required the parent upload will do
        list( $newWidth, $newHeight ) = $this->getResizeTo();
        if (! $newWidth && ! $newHeight ) {
            return parent::Upload();
        }
        
        if (! $this->UploadSanityChecks()) {
            return false;
        }
        
        // check if mime type matches allowed mime types
        $mimeType = $this->getMimeType($this->getMimeGetMode());
        if (!$this->checkMimeType($mimeType)) {
            return false;
        }
        
        if ($mimeType == 'image/jpeg') {
            return $this->ResizeJPG();
        }
        if ($mimeType == 'image/png') {
            return $this->ResizePNG();
        }
        if ($mimeType == 'image/gif') {
            return $this->ResizeGIF();
        }
    }
    
    private function ResizeJPG()
    {
        list( $newWidth, $newHeight ) = $this->getResizeTo();
        
        $sourceFile = $this->getSourceFile();
        $targetFile = $this->getUploadPath().'/'.$this->getFileName().'.jpg';
        
        list( $width, $height ) = getimagesize($sourceFile);
        
        if (! $this->getStretch()) {
            // keep the image width:height ratio
            list( $newWidth, $newHeight ) = $this->keepAspectRatio( $newWidth, $newHeight, $width, $height );
        }
        
        $src = imagecreatefromjpeg($sourceFile);
        $dst = imagecreatetruecolor($newWidth, $newHeight);
        if ( imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) ) {
            $r = imagejpeg($dst, $targetFile);
            return $r;
        }
        
        return false;
    }
    
    private function ResizePNG()
    {
        
    }
    
    private function ResizeGIF()
    {
        
    }
}