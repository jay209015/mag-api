<?php
/**
 * User: jayrivers
 * Date: 10/7/15
 * Time: 11:15 AM
 */

namespace Mag\Model;


class User
{

    /**
     * Class Properties
     */
    public $id;
    public $first_name;
    public $last_name;
    public $company_name;
    public $address_1;
    public $address_2;
    public $city;
    public $state;
    public $zip;
    public $phone;
    public $email;
    public $paddword;

    /**
     * ExchangeArray method for File Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"])) ? $data["id"] : NULL;
        $this->first_name = (isset($data["first_name"])) ? $data["first_name"] : 0;
        $this->last_name = (isset($data["last_name"])) ? $data["last_name"] : NULL;
        $this->company_name = (isset($data["company_name"])) ? $data["company_name"] : NULL;
        $this->address_1 = (isset($data["address_1"])) ? $data["address_1"] : NULL;
        $this->address_2 = (isset($data["address_2"])) ? $data["address_2"] : NULL;
        $this->city = (isset($data["city"])) ? $data["city"] : NULL;
        $this->state = (isset($data["state"])) ? $data["state"] : NULL;
        $this->zip = (isset($data["zip"])) ? $data["zip"] : NULL;
        $this->phone = (isset($data["phone"])) ? $data["phone"] : NULL;
        $this->email = (isset($data["email"])) ? $data["email"] : NULL;
        $this->password = (isset($data["password"])) ? $data["password"] : NULL;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company_name' => $this->company_name,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => 'PROTECTED',
        ];
    }

}