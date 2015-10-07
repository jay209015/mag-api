<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/10/15
 * Time: 1:32 AM
 */
namespace File\Model;

class Ordered
{
    public $ordered_id;
    public $file_id;
    public $file_code;
    public $job_order_id;
    public $job_item_id;
    public $order_date;

    public function exchangeArray($data)
    {
        $this->ordered_id   = isset($data["ordered_id"]) ? $data["ordered_id"] : null;
        $this->file_id      = isset($data["file_id"]) ? $data["file_id"] : null;
        $this->file_code    = isset($data["file_code"]) ? $data["file_code"] : null;
        $this->job_order_id = isset($data["job_order_id"]) ? $data["job_order_id"] : null;
        $this->job_item_id  = isset($data["job_item_id"]) ? $data["job_item_id"] : null;
        $this->order_date   = isset($data["order_date"]) ? $data["order_date"] : null;
    }

    /**
     * @param string $fileId
     * @param string $fileCode
     * @param array $fileOrdered
     * @param OrderedTable $orderedTable
     * @param InputFilter $inputFilter
     * @return void
     */
    public function saveOrdered($fileId, $fileCode, $fileOrdered, $orderedTable, $inputFilter)
    {
        // make sure that fileOrdered is an array
        if (!is_array($fileOrdered)) {
            $fileOrdered = array($fileOrdered);
        }

        foreach ($fileOrdered as $ordered) {
            $inputFilter->setData($ordered);
            if ($inputFilter->isValid()) {
                $ordered['file_id'] = $fileId;
                $ordered['file_code'] = $fileCode;
                $this->exchangeArray($ordered);
                $orderedTable->saveOrdered($this);
            }
        }
    }
}