<?php
namespace File\Model;

use Zend\Db\TableGateway\TableGateway;
/**
 * Class PageTable
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class PageTable extends AbstractTable
{
    /**
     * @var \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway;

    /**
     * Class constructor
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Get pages
     * @param $file_id
     * @return ResultSet or NULL
     */
    public function getPages($file_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array(
            "file_id" => $file_id,
        ));

        $resultSet = $this->tableGateway->selectWith($select);

        return ($resultSet->count() > 0) ? $this->toArray($resultSet) : array();
    }
}
