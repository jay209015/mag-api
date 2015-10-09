<?php
/**
 * User: jayrivers
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
        $data = array(
            'key' => $Magazine->key,
            'value'  => $Magazine->value,
        );

        $id = (int) $Magazine->id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->fetch($Magazine->key)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Magazine key does not exist');
            }
        }
    }

    public function fetch($key)
    {
        $rowset = $this->tableGateway->select(array('key' => $key));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $key");
        }
        return $row;
    }
}