<?php
namespace File\Model;

/**
 * Class File
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class File
{
    /**
     * Class Properties
     */
    public $file_id;
    public $parent_file_id;
    public $origin;
    public $vid;
    public $cid;
    public $website_code;
    public $name;
    public $actual_filename;
    public $extension;
    public $content_type;
    public $size;
    public $created;
    public $deleted;
    public $file_code;
    public $_id;
    public $from_migration;

    /**
     * ExchangeArray method for File Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($data)
    {
        $this->file_id = (isset($data["file_id"])) ? $data["file_id"] : NULL;
        $this->parent_file_id = (isset($data["parent_file_id"])) ? $data["parent_file_id"] : 0;
        $this->origin = (isset($data["origin"])) ? $data["origin"] : NULL;
        $this->vid = (isset($data["vid"])) ? $data["vid"] : NULL;
        $this->cid = (isset($data["cid"])) ? $data["cid"] : NULL;
        $this->website_code = (isset($data["website_code"])) ? $data["website_code"] : NULL;
        $this->name = (isset($data["name"])) ? $data["name"] : NULL;
        $this->actual_filename = (isset($data["actual_filename"])) ? $data["actual_filename"] : NULL;
        $this->extension = (isset($data["extension"])) ? $data["extension"] : NULL;
        $this->content_type = (isset($data["content_type"])) ? $data["content_type"] : NULL;
        $this->size = (isset($data["size"])) ? $data["size"] : NULL;
        $this->created = (isset($data["created"])) ? $data["created"] : NULL;
        $this->deleted = (isset($data["deleted"])) ? $data["deleted"] : NULL;
        $this->file_code = (isset($data["file_code"])) ? $data["file_code"] : NULL;
        $this->_id = (isset($data["file_code"])) ? $data["file_code"] : NULL;
        $this->from_migration = (isset($data["from_migration"])) ? $data["from_migration"] : NULL;
    }

}