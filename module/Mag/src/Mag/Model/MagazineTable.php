<?php
/**
 * Magazine: jayrivers
 * Date: 10/7/15
 * Time: 11:17 AM
 */

namespace Mag\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\AbstractTableGateway;

class MagazineTable extends AbstractTableGateway
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet->toArray();
    }

    public function save(Magazine $Magazine){
        $data = $Magazine->toArray();

        $id = (int) $Magazine->id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->fetch($Magazine->id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                return false;
            }
        }
    }

    public function fetch($id)
    {
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }
}