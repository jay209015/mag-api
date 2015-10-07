<?php
namespace File\Model;

/**
 * Class Mockup
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class Mockup
{
    /**
     * Class Properties
     */
    public $mockup_id;
    public $file_id;
    public $file_code;
    public $page;
    public $width;
    public $path;

    /**
     * ExchangeArray method for Location Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($data)
    {
        $this->mockup_id = (isset($data["mockup_id"])) ? $data["mockup_id"] : NULL;
        $this->file_id = (isset($data["file_id"])) ? $data["file_id"] : NULL;
        $this->file_code = (isset($data["file_code"])) ? $data["file_code"] : NULL;
        $this->page = (isset($data["page"])) ? $data["page"] : NULL;
        $this->width = (isset($data["width"])) ? $data["width"] : NULL;
        $this->path = (isset($data["path"])) ? $data["path"] : NULL;
    }

    /**
     * @param string $fileId
     * @param string $fileCode
     * @param array $fileMockups
     * @param MockupTable $mockupTable
     * @param InputFilter $inputFilter
     * @return void
     */
    public function saveMockups($fileId, $fileCode, $fileMockups, $mockupTable, $inputFilter)
    {
        // make sure that fileMockups is an array
        if (!is_array($fileMockups)) {
            $fileMockups = array($fileMockups);
        }
        foreach ($fileMockups as $mockup) {
            $inputFilter->setData($mockup);
            if ($inputFilter->isValid()) {
                $mockup['file_id'] = $fileId;
                $mockup['file_code'] = $fileCode;
                $this->exchangeArray($mockup);
                $mockupTable->saveMockup($this);
            }
        }
    }

}