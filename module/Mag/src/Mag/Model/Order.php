<?php
/**
 * Order: jayrivers
 * Date: 10/7/15
 * Time: 11:15 AM
 */

namespace Mag\Model;


class Order
{

    /**
     * Class Properties
     */
    public $id;
    public $magazine_id;
    public $user_id;
    public $date;
    public $subtotal;
    public $tax;
    public $total;

    /**
     * ExchangeArray method for File Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"])) ? $data["id"] : NULL;
        $this->magazine_id = (isset($data["magazine_id"])) ? $data["magazine_id"] : 0;
        $this->user_id = (isset($data["user_id"])) ? $data["user_id"] : 0;
        $this->date = (isset($data["date"])) ? $data["date"] : NULL;
        $this->subtotal = (isset($data["subtotal"])) ? $data["subtotal"] : NULL;
        $this->tax = (isset($data["tax"])) ? $data["tax"] : NULL;
        $this->total = (isset($data["total"])) ? $data["total"] : NULL;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'magazine_id' => $this->magazine_id,
            'user_id' => $this->user_id,
            'date' => $this->date,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
        ];
    }

}