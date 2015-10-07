<?php
namespace File\Model;

use Zend\Db\TableGateway\TableGateway;
/**
 * Class MockupTable
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class MockupTable extends AbstractTable
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
     * Get mockups
     * @param $file_id
     * @return ResultSet or NULL
     */
    public function getMockups($file_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array(
            "file_id" => $file_id,
        ));

        $resultSet = $this->tableGateway->selectWith($select);

        return ($resultSet->count() > 0) ? $this->toArray($resultSet) : array();
    }

    /**
     * @param Mockup $mockup
     * @return mixed
     */
    public function saveMockup(Mockup $mockup)
    {
        if (!$this->getMockup($mockup)) {
            $data = array(
                "file_id"   => $mockup->file_id,
                "file_code" => $mockup->file_code,
                "page"      => $mockup->page,
                "width"     => $mockup->width,
                "path"      => $mockup->path,            
            );

            return $this->insert($data);
        }
        return false;
    }

    /**
     * Get location
     * @param Location $location
     * @return null
     */
    public function getMockup(Mockup $mockup)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array(
            "file_id" => $mockup->file_id,
            "page" => $mockup->page,
        ));

        $resultSet = $this->tableGateway->selectWith($select);

        return ($resultSet->count() > 0) ? $resultSet->current() : null;
    }

}