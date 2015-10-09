<?php
/**
 * User: jayrivers
 * Date: 10/7/15
 * Time: 11:15 AM
 */

namespace Mag\Model;


class Magazine
{
    /**
     * Class Properties
     */
    public $id;
    public $issue_name;
    public $pages;
    public $deadline;
    public $created_date;

    /**
     * ExchangeArray method for File Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"])) ? $data["id"] : NULL;
        $this->issue_name = (isset($data["issue_name"])) ? $data["issue_name"] : 0;
        $this->pages = (isset($data["pages"])) ? $data["pages"] : NULL;
        $this->deadline = (isset($data["deadline"])) ? $data["deadline"] : NULL;
        $this->created_date = (isset($data["created_date"])) ? $data["created_date"] : NULL;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'issue_name' => $this->issue_name,
            'page' => $this->pages,
            'deadline' => $this->deadline,
            'created_date' => $this->created_date,
        ];
    }

}