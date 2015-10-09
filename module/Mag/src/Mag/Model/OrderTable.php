<?php
/**
 * Order: jayrivers
 * Date: 10/7/15
 * Time: 11:17 AM
 */

namespace Mag\Model;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\AbstractTableGateway;

class OrderTable extends AbstractTableGateway
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

    public function save(Order $Order){
        $data = $Order->toArray();

        $id = (int) $Order->id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->fetch($Order->id)) {
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

    public function getTotalSlotsByMagazine(Magazine $Magazine)
    {

        $total_slots = $Magazine->pages * 6;

        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select()
            ->from('order')
            ->columns(array('*'))
            ->join('order_item', 'order_item.order_id = order.id')
            ->join('slot', 'order_item.slot_id = slot.id')
            ->where("order.magazine_id = '{$Magazine->id}'");

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();


        foreach($resultSet as $result){
            if(strpos($result['size'], '/') !== false) {
                $size = explode('/', $result['size']);
                $size = $size[0] / $size[1];
            }else{
                $size = $result['size'];
            }
            if($result['duplicate']){
                $size = $size * 2;
            }

            $total_slots -=  $size * 6;
        }

        return ($total_slots);
    }
}