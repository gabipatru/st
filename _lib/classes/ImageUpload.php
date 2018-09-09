<?php

/*
 * Upload and resize an image
 * Options
 * - setStretch - default false
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
            $this->setFileExtension('jpg');
            return $this->ResizeJPG();
        }
        if ($mimeType == 'image/png') {
            $this->setFileExtension('png');
            return $this->ResizePNG();
        }
        if ($mimeType == 'image/gif') {
            return $this->ResizeGIF();
        }
    }
    
    private function ResizeJPG()
    {
        $sourceFile = $this->getSourceFile();
        $extension = ($this->getFileExtension() ? $this->getFullFileExtension() : '.jpg');
        $targetFile = $this->getUploadPath().'/'.$this->getFileName() . $extension;
        
        list( $newWidth, $newHeight, $width, $height ) = $this->CalculateResize();
        
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
        $sourceFile = $this->getSourceFile();
        $extension = ($this->getFileExtension() ? $this->getFullFileExtension() : '.png');
        $targetFile = $this->getUploadPath().'/'.$this->getFileName() . $extension;
        
        list( $newWidth, $newHeight, $width, $height ) = $this->CalculateResize();
        
        $src = imagecreatefrompng( $sourceFile );
        $dst = imagecreatetruecolor($newWidth, $newHeight);
        if ( imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) ) {
            $r = imagepng($dst, $targetFile);
            return $r;
        }
        
        return false;
    }
    
    private function ResizeGIF()
    {
        
    }
    
    /*
     * Calculate the resize dimensions of the new image
     */
    private function CalculateResize()
    {
        $sourceFile = $this->getSourceFile();
        list( $newWidth, $newHeight ) = $this->getResizeTo();
        
        list( $width, $height ) = getimagesize($sourceFile);
        
        if (! $this->getStretch()) {
            // keep the image width:height ratio
            list( $newWidth, $newHeight ) = $this->keepAspectRatio( $newWidth, $newHeight, $width, $height );
        }
        
        return [$newWidth, $newHeight, $width, $height];
    }
}