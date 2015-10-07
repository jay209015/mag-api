<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/10/15
 * Time: 1:32 AM
 */
namespace File\Model;

use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

class OrderedTable extends AbstractTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function saveOrdered(Ordered $ordered)
    {
        if (!$this->checkOrderedIfExist($ordered)) {
            $data = array(
                "file_id"      => $ordered->file_id,
                "file_code"    => $ordered->file_code,
                "job_order_id" => $ordered->job_order_id,
                "job_item_id"  => $ordered->job_item_id,
                "order_date"   => $ordered->order_date,
            );

            return $this->insert($data);
        }

        return false;

    }

    public function checkOrderedIfExist(Ordered $ordered)
    {
        $result = $this->tableGateway->select(array(
            "file_id"      => $ordered->file_id,
            "job_order_id" => $ordered->job_order_id,
            "job_item_id"  => $ordered->job_item_id,
            "order_date"   => $ordered->order_date
        ));

        return $result->current();
    }

    /**
     * Get ordered
     * @param $file_id
     * @return ResultSet or NULL
     */
    public function getOrdered($file_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array(
            "file_id" => $file_id,
        ));

        $resultSet = $this->tableGateway->selectWith($select);

        return ($resultSet->count() > 0) ? $this->toArray($resultSet) : array();
    }

} 