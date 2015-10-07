<?php
namespace File\Model;

/**
 * Class Page
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class Page
{
    /**
     * Class Properties
     */
    public $page_id;
    public $file_id;
    public $file_code;
    public $page;
    public $width;
    public $height;
    public $colorspace;

    public function exchangeArray($data)
    {
        $this->page_id      = isset($data["page_id"]) ? $data["page_id"] : null;
        $this->file_id      = isset($data["file_id"]) ? $data["file_id"] : null;
        $this->file_code    = isset($data["file_code"]) ? $data["file_code"] : null;
        $this->page         = isset($data["page"]) ? $data["page"] : null;
        $this->width        = isset($data["width"]) ? $data["width"] : null;
        $this->height       = isset($data["height"]) ? $data["height"] : null;
        $this->colorspace   = isset($data["colorspace"]) ? $data["colorspace"] : null;
    }

    /**
     * @param array $filePages
     * @param PageTable $pageTable
     * @param InputFilter $inputFilter
     * @return void
     */
    public function savePages($filePages, $pageTable, $inputFilter)
    {
        // make sure that filePages is an array
        if (!is_array($filePages)) {
            $filePages = array($filePages);
        }

        foreach ($filePages as $page) {
            $inputFilter->setData($page);
            if ($inputFilter->isValid()) {
                $pageTable->insert($page);
            }
        }
    }
}