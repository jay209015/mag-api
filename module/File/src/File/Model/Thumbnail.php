<?php
namespace File\Model;

/**
 * Class Thumbnail
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class Thumbnail
{
    /**
     * Class Properties
     */
    public $thumbnail_id;
    public $file_id;
    public $file_code;
    public $page;
    public $width;
    public $path;


    public function exchangeArray($data)
    {
        $this->thumbnail_id = isset($data["thumbnail_id"]) ? $data["thumbnail_id"] : null;
        $this->file_id      = isset($data["file_id"]) ? $data["file_id"] : null;
        $this->file_code    = isset($data["file_code"]) ? $data["file_code"] : null;
        $this->page         = isset($data["page"]) ? $data["page"] : null;
        $this->width        = isset($data["width"]) ? $data["width"] : null;
        $this->path         = isset($data["path"]) ? $data["path"] : null;
    }

    /**
     * @param string $fileId
     * @param string $fileCode
     * @param array $fileThumbnails
     * @param ThumbnailTable $thumbnailTable
     * @param InputFilter $inputFilter
     * @return void
     */
    public function saveThumbnails($fileId, $fileCode, $fileThumbnails, $thumbnailTable, $inputFilter)
    {
        // make sure that fileThumbnails is an array
        if (!is_array($fileThumbnails)) {
            $fileThumbnails = array($fileThumbnails);
        }

        foreach ($fileThumbnails as $thumbnail) {
            $inputFilter->setData($thumbnail);
            if ($inputFilter->isValid()) {
                $thumbnail['file_id'] = $fileId;
                $thumbnail['file_code'] = $fileCode;
                $this->exchangeArray($thumbnail);
                $thumbnailTable->saveThumbnail($this);
            }
        }
    }

}