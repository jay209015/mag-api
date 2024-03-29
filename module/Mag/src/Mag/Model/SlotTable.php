<?php
/**
 * Slot: jayrivers
 * Date: 10/7/15
 * Time: 11:17 AM
 */

namespace Mag\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\AbstractTableGateway;


class SlotTable extends AbstractTableGateway
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

    public function save(Slot $Slot){
        $data = $Slot->toArray();

        $id = (int) $Slot->id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->fetch($Slot->id)) {
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

    public function calculateAvailable(Magazine $Magazine, OrderTable $OrderTable)
    {
        $total_slots = $Magazine->pages * 6;
        $serviceManager = $this->getServiceLocator();
    }
}