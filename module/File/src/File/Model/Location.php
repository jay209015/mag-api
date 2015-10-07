<?php
namespace File\Model;

/**
 * Class Location
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class Location
{
    /**
     * Class Properties
     */
    public $location_id;
    public $file_id;
    public $file_code;
    public $archive_id;
    public $server;
    public $path;
    public $old_path;
    public $date_added;
    public $migrated;

    /**
     * ExchangeArray method for Location Class.
     * @param $data
     * @return NULL
     */
    public function exchangeArray($data)
    {
        $this->location_id = (isset($data["location_id"])) ? $data["location_id"] : NULL;
        $this->file_id = (isset($data["file_id"])) ? $data["file_id"] : NULL;
        $this->file_code = (isset($data["file_code"])) ? $data["file_code"] : NULL;
        $this->archive_id = (isset($data["archive_id"])) ? $data["archive_id"] : NULL;
        $this->server = (isset($data["server"])) ? $data["server"] : NULL;
        $this->path = (isset($data["path"])) ? $data["path"] : NULL;
        $this->old_path = (isset($data["old_path"])) ? $data["old_path"] : NULL;
        $this->date_added = date('Y-m-d H:i:s');
        $this->migrated = (isset($data["migrated"])) ? $data["migrated"] : NULL;
    }

    /**
     * @param array $fileLocations
     * @param LocationTable $locationTable
     * @param InputFilter $inputFilter
     * @return void
     */
    public function saveLocations($fileLocations, $locationTable, $inputFilter)
    {
        // make sure that fileLocations is an array
        if (!is_array($fileLocations)) {
            $fileLocations = array($fileLocations);
        }

        foreach ($fileLocations as $location) {
            $inputFilter->setData($location);
            if ($inputFilter->isValid()) {
                $this->exchangeArray($location);
                $locationTable->saveLocation($this);
            }
        }
    }

    public function toArray(){
        return array(
            'location_id' => $this->location_id,
            'file_id' => $this->file_id,
            'file_code' => $this->file_code,
            'archive_id' => $this->archive_id ,
            'server' => $this->server,
            'path' => $this->path,
            'old_path' => $this->old_path,
            'date_added' => $this->date_added,
            'migrated' => $this->migrated
        );
    }

}