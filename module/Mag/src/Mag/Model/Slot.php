<?php
/**
 * Slot: jayrivers
 * Date: 10/7/15
 * Time: 11:15 AM
 */

namespace Mag\Model;


class Slot
{

    /**
     * Class Properties
     */
    public $id;
    public $name;
    public $size;

    /**
     * ExchangeArray method for File Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"])) ? $data["id"] : NULL;
        $this->name = (isset($data["name"])) ? $data["name"] : 0;
        $this->size = (isset($data["size"])) ? $data["size"] : NULL;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'size' => $this->size,
        ];
    }

}