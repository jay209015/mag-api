<?php
namespace File\Service;

use File\Model\File;
use File\Model\OrderedTable;

class OrderedService
{
    public function getOrdered(File $file, OrderedTable $OrderedTable)
    {
        $result = array();
        $resultSet = $OrderedTable->getOrdered($file);
        if ($resultSet) {
            while ($resultSet->valid()) {
                array_push($result, $resultSet->current());
                $resultSet->next();
            }
        }

        return (sizeof($result) > 0) ? $result : null;
    }
}