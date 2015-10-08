<?php
/**
 * User: jayrivers
 * Date: 10/7/15
 * Time: 11:15 AM
 */

namespace Mag\Model;


class ConfigItem
{
    /**
     * Class Properties
     */
    public $id;
    public $key;
    public $value;

    /**
     * ExchangeArray method for File Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"])) ? $data["id"] : NULL;
        $this->key = (isset($data["key"])) ? $data["key"] : 0;
        $this->value = (isset($data["value"])) ? $data["value"] : NULL;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $this->value,
        ];
    }

}