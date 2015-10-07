<?php
namespace File\Model;

use Zend\Db\TableGateway\TableGateway;
/**
 * Class ThumbnailTable
 * @package File\Model
 * @author Oscar Baccay <oscar.b@uprinting.com>
 */
class ThumbnailTable extends AbstractTable
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
     * Get thumbnails
     * @param $file_id
     * @return ResultSet or NULL
     */
    public function getThumbnails($file_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array(
            "file_id" => $file_id,
        ));

        $resultSet = $this->tableGateway->selectWith($select);

        return ($resultSet->count() > 0) ? $this->toArray($resultSet) : array();
    }

    public function saveThumbnail(Thumbnail $thumbnail)
    {
        $data = array(
            "file_id"   => $thumbnail->file_id,
            "file_code" => $thumbnail->file_code,
            "page"      => $thumbnail->page,
            "width"     => $thumbnail->width,
            "path"      => $thumbnail->path,
        );

        return $this->insert($data);

    }    
}
