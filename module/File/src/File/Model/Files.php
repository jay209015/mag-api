<?php
namespace File\Model;

/**
 * Class Files
 * File data structure exchange
 * @package File\Model
 * @author emman@uprinting.com
 *
 */
class Files
{
    const FILE_SALT = 'P0g3';

    public $cid;
    public $vid;
    public $file_code;
    public $parent_file_id;
    public $name;
    public $actual_filename;
    public $type;
    public $size;
    public $extension;
    public $website_code;
    public $origin;
    public $location;
    public $pages;

    /**
     * ExchangeArray method for Files Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($file, $data)
    {
        $this->cid              = (isset($data["cid"])) ? $data["cid"] : 0;
        $this->vid              = (isset($data["vid"])) ? $data["vid"] : 0;
        $this->file_code        = (isset($file["_id"])) ? $file["_id"] : $this->generateFileCode();
        $this->parent_file_id   = 0;
        $this->name             = (isset($file["name"])) ? $file["name"] : NULL;
        $this->actual_filename  = (isset($file["actual_filename"])) ? $file["actual_filename"] : NULL;
        $this->type             = (isset($file["type"])) ? $file["type"] : NULL;
        $this->size             = (isset($file["size"])) ? $file["size"] : NULL;
        $this->extension        = (isset($file["extension"])) ? $file["extension"] : NULL;        
        $this->website_code     = (isset($file["website_code"])) ? $file["website_code"] : NULL;
        $this->origin           = (isset($file["origin"])) ? $file["origin"] : NULL;
        $this->location         = (isset($file["location"])) ? $file["location"] : array();
        $this->pages            = (isset($file["pages"])) ? $file["pages"] : array();
    }

    public function generateFileCode()
    {
        return rand(1,9) . substr(hash('sha1', uniqid() . rand(0,9) . self::FILE_SALT), 0, -1);
    }

    public function setParentFileId($parentFileId = 0)
    {
        $this->parent_file_id = $parentFileId;
    } 

    public function getFile()
    {
        $file = array();
        $file['parent_file_id']   = $this->parent_file_id;
        $file['file_code']        = $this->file_code;
        $file['cid']              = $this->cid;
        $file['vid']              = $this->vid;
        $file['name']             = $this->name;
        $file['actual_filename']  = $this->actual_filename;
        $file['content_type']     = $this->type;
        $file['size']             = $this->size;
        $file['extension']        = $this->extension;        
        $file['website_code']     = $this->website_code;
        $file['origin']           = $this->origin;

        return $file;
    }

    public function getLocations($fileId = 0)
    {
        $locations = array();

        foreach ($this->location as $location) {
            if (isset($location['server']) && isset($location['path'])) {
                $data = array();
                $data['file_id']    = $fileId;
                $data['file_code']  = $this->file_code;
                $data['server']     = $location['server'];
                $data['path']       = $location['path'];
                $data['old_path']   = isset($location['old_path']) ? $location['old_path'] : NULL;
                $data['date_added'] = isset($location['date_added']) ? $location['date_added'] : date("Y-m-d H:i:s");
                $data['migrated']   = isset($location['migrated']) ? $location['migrated'] : 0;

                $locations[] = $data;
            }
        }

        return $locations;
    }

    public function getPages($fileId = 0)
    {
        $pages = array();

        foreach ($this->pages as $page) {
            $data = array();
            $data['file_id']    = $fileId;
            $data['file_code']  = $this->file_code;
            $data['page']       = isset($page['page']) ? $page['page'] : NULL;
            $data['width']      = isset($page['width']) ? $page['width'] : NULL;
            $data['height']     = isset($page['height']) ? $page['height'] : NULL;
            $data['colorspace'] = isset($page['colorspace']) ? $page['colorspace'] : NULL;

            $pages[] = $data;
        }

        return $pages;
    }

    public function toArray()
    {
        $file = $this->getFile();
        var_dump($file);
        $file['locations'] = $this->getLocations($file->file_id);
        $file['pages'] = $this->getPages($file->file_id);
        return $file;
    }
}