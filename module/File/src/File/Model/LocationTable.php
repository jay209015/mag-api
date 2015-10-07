<?php
namespace File\Model;

use File\Model\File;
use Zend\Db\TableGateway\TableGateway;
/**
 * Class LocationTable
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class LocationTable extends AbstractTable
{
    /**
     * @var \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway;
    protected $tableMaster;

    /**
     * Class constructor
     * @param TableGateway $tableGateway
     * @param LocationTable $tableMaster | null
     */
    public function __construct(TableGateway $tableGateway, LocationTable $tableMaster = null)
    {
        $this->tableGateway = $tableGateway;
        $this->tableMaster = $tableMaster;
    }

    /**
     * Get location
     * @param Location $location
     * @return null
     */
    public function getLocation(Location $location)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array(
            "file_id" => $location->file_id,
            "server" => $location->server,
        ));

        $resultSet = $this->tableGateway->selectWith($select);

        if ($resultSet->count() > 0) {
            return $resultSet->current();
        } else {
            if ($this->tableMaster) {
                return $this->tableMaster->getLocation($location);
            }

            return NULL;
        }
    }

    /**
     * Get locations
     * @param $file_id
     * @return ResultSet or NULL
     */
    public function getLocations($fileId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array(
            "file_id" => $fileId,
        ));

        $resultSet = $this->tableGateway->selectWith($select);
        if ($resultSet->count() > 0) {
            return $this->toArray($resultSet);
        } else {
            if ($this->tableMaster) {
                return $this->tableMaster->getLocations($fileId);
            }

            return array();
        }
    }

    /**
     * Get locations
     * @param $file_id
     * @return array or NULL
     */
    public function getLocationByID($fileId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array(
            "file_code" => $fileId,
        ));

        $resultSet = $this->tableGateway->selectWith($select);
        if ($resultSet->count() > 0) {
            return $resultSet->current();
        } else {
            if ($this->tableMaster) {
                return $this->tableMaster->getLocationByID($fileId);
            }

            return array();
        }
    }

    /**
    * Attached Archive ID
    * @param \File\Model\Location $Location
    */
    public function setArchiveID(Location $Location)
    {
        $data = array(
            "archive_id" => $Location->archive_id,
            "server" => 'S3'
        );

        $where = new \Zend\Db\Sql\Where();
        $where->equalTo("file_code", $Location->file_code);

        return $this->tableGateway->update($data, $where);
    }

    /**
     * @param Location $Location
     * @return mixed
     */
    public function saveLocation(Location $Location)
    {

        if (!$this->getLocation($Location)) {
            $data = array(
                "server"     => $Location->server,
                "path"       => $Location->path,
                "file_id"    => $Location->file_id,
                "file_code"  => $Location->file_code,
                "date_added" => date("Y-m-d H:i:s")
            );

            return $this->insert($data);
        }

        return false;

    }

    public function getLocationsPurge($server, $age)
    {
        $age = min(1, $age); // Backup safety. Don't want a fluke to purge current files

        $date = date("Y-m-d", strtotime("today -$age years"));
        $where = new \Zend\Db\Sql\Where();
        $where->equalTo("server", $server);
        $where->lessThanOrEqualTo("date_added", $date);
        $where->equalTo("archive_id", "");

        $select = $this->tableGateway->getSql()->select();
        $select->where($where);

        $resultSet = $this->tableGateway->selectWith($select);
        if ($resultSet->count() > 0) {
            return $this->toArray($resultSet);
        } else {
            if ($this->tableMaster) {
                return $this->tableMaster->getLocationsPurge($server, $age);
            }

            return array();
        }
    }

}