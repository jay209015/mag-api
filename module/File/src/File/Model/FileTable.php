<?php
namespace File\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
/**
 * Class FileTable
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class FileTable extends AbstractTable
{
    /**
     * @var \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway;
    protected $tableMaster;

    /**
     * Class constructor
     * @param TableGateway $tableGateway
     * @param FileTable $tableMaster | null
     */
    public function __construct(TableGateway $tableGateway, FileTable $tableMaster = null)
    {
        $this->tableGateway = $tableGateway;
        $this->tableMaster = $tableMaster;
    }

    public function getFile($file_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array("file_id" => $file_id));
        $resultSet = $this->tableGateway->selectWith($select);

        if ($resultSet->count() > 0) {
            return $resultSet->current();
        } else {
            if ($this->tableMaster) {
                return $this->tableMaster->getFile($file_id);
            }

            return array();
        }
    }

    /**
     * Transfer ownership
     * @param File $file
     */
    public function transferOwnership(File $file)
    {
        $data = array(
            "cid" => $file->cid,
        );

        $where = new \Zend\Db\Sql\Where();
        $where->equalTo("vid", $file->vid);
        $where->equalTo("cid", 0);

        return $this->tableGateway->update($data, $where);
    }

    /**
     * Transfer ownership
     * @param $file_code
     * @return ResultSet
     */
    public function getFileInfo($file_code)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array("file_code" => $file_code));
        $resultSet = $this->tableGateway->selectWith($select);

        if ($resultSet->count() > 0) {
            return $resultSet->current();
        } else {
            if ($this->tableMaster) {
                return $this->tableMaster->getFileInfo($file_code);
            }

            return array();
        }
    }

    public function getFileId($file_code)
    {
        $result = $this->getFileInfo($file_code);

        return $result ? $result->file_id : 0;        
    }

    /**
     * Get files
     * @params $params, countOnly (optional, default:false)
     * @return array("files" => array(), "total" => 0)
     */
    public function getFiles($params, $countOnly = false)
    {
        $where = array();

        // set customer id
        if (isset($params['customer_id']) && $params['customer_id'] > 0) {
            $where['cid'] = $params['customer_id'];
        } else if (isset($params['visitor_id'])) { // set visitor id
            $where['vid'] = $params['visitor_id'];
            $where['cid'] = 0;
        }

        // set filter
        if (isset($params['filter'])) {
            // $where['vid'] = $params['visitor_id'];
            array_push($where, "origin IN ({$params['filter']})");
        }

        if ($countOnly) {
            // get total records
            $select = $this->tableGateway->getSql()->select();
            $select->columns(array("total" => new Expression("COUNT(*)")));
            $select->where($where);
            $resultSet = $this->tableGateway->selectWith($select);

            if ($resultSet->count() > 0) {
                $result = $resultSet->getDataSource()->current();
                return $result['total'];
            }

            if ($this->tableMaster) {
                return $this->tableMaster->getFiles($params, $countOnly);
            }

            return 0;
        }

        // get records
        $select = $this->tableGateway->getSql()->select();

        // offset
        if (isset($params['offset'])) {
            $select->offset($params['offset']);
        }

        // limit
        if (isset($params['limit']) && $params['limit'] > 0) {
            $select->limit($params['limit']);
        }

        $select->where($where);

        // sort by uploaded date (descending)
        $select->order("created DESC");

        $resultSet = $this->tableGateway->selectWith($select);

        if ($resultSet->count() > 0) {
            return $this->toArray($resultSet);
        } else {
            if ($this->tableMaster) {
                return $this->tableMaster->getFiles($params, $countOnly);
            }

            return array();
        }
    }

    public function getFileByActualFileName($actualFileName)
    {
        $resultSet = $this->tableGateway->select(array("actual_filename" => $actualFileName));
        if ($resultSet->count() > 0) {
            return $resultSet->current();
        } else {
            if ($this->tableMaster) {
                return $this->tableMaster->getFileByActualFileName($actualFileName);
            }

            return NULL;
        }
    }

}