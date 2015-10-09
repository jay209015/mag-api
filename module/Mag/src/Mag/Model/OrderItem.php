<?php
/**
 * OrderItem: jayrivers
 * Date: 10/7/15
 * Time: 11:15 AM
 */

namespace Mag\Model;


class OrderItem
{

    /**
     * Class Properties
     */
    public $id;
    public $order_id;
    public $slot_id;
    public $dupicate;

    /**
     * ExchangeArray method for File Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"])) ? $data["id"] : NULL;
        $this->order_id = (isset($data["order_id"])) ? $data["order_id"] : 0;
        $this->slot_id = (isset($data["slot_id"])) ? $data["slot_id"] : NULL;
        $this->duplicate = (isset($data["duplicate"])) ? $data["duplicate"] : NULL;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'slot_id' => $this->slot_id,
            'duplicate' => $this->dupicate,
        ];
    }

}