<?php
namespace File\Service;

use Zend\View\Model\JsonModel;

class PurgeService
{
    /**
     * @var \File\Model\FileTable
     */
    protected $FileTable;
    /**
     * @var \File\Model\LocationTable
     */
    protected $LocationTable;
    /**
     * @var \File\Model\PageTable
     */
    protected $PageTable;
    /**
     * @var \File\Model\ThumbnailTable
     */
    protected $ThumbnailTable;
    /**
     * @var \File\Model\OrderedTable
     */
    protected $OrderedTable;
    /**
     * @var \File\Model\MockupTable
     */
    protected $MockupTable;

    /**
     * @param $FileTable \File\Model\FileTable
     * @param $LocationTable \File\Model\LocationTable
     * @param $PageTable \File\Model\PageTable
     * @param $ThumbnailTable \File\Model\ThumbnailTable
     * @param $OrderedTable \File\Model\OrderedTable
     * @param $MockupTable \File\Model\MockupTable
     */
    function __construct($FileTable, $LocationTable, $PageTable, $ThumbnailTable, $OrderedTable, $MockupTable)
    {
        $this->FileTable        = $FileTable;
        $this->LocationTable    = $LocationTable;
        $this->PageTable        = $PageTable;
        $this->ThumbnailTable   = $ThumbnailTable;
        $this->OrderedTable     = $OrderedTable;
        $this->MockupTable      = $MockupTable;
    }


    /**
     * For creation of file document
     * @param string $fileId
     * @return array
     */
    public function getFileDocument($fileId)
    {
        $data = array();
        $file = $this->FileTable->getFile($fileId);

        // convert upload date to PST
        if (isset($file->created)) {
            $file->created = $this->convertDateTime($file->created);
        }

        if ($file) {
            $file->type = $file->content_type;

            $file->location = $this->LocationTable->getLocations($file->file_id);
            $file->pages = $this->PageTable->getPages($file->file_id);

            $data['files'] = array($file);
            $data['ordered'] = $this->OrderedTable->getOrdered($file->file_id);
            $data['thumbnail'] = $this->ThumbnailTable->getThumbnails($file->file_id);
            if (!$data['thumbnail'] && $file->parent_file_id) {
                $data['thumbnail'] = $this->ThumbnailTable->getThumbnails($file->parent_file_id);
            }
            $data['mockup'] = $this->MockupTable->getMockups($file->file_id);
            if (!$data['mockup'] && $file->parent_file_id) {
                $data['mockup'] = $this->MockupTable->getMockups($file->parent_file_id);
            }

            // remove unnecessary fields from the response
            unset($file->content_type);
            unset($file->file_id);
            unset($file->parent_file_id);
            unset($file->actual_filename);
            unset($file->deleted);
            unset($file->file_code);
            unset($file->from_migration);
        }

        return $data;

    }

	public function getFiles($params)
	{
		$links = array("pageCount" => 0);
		$embedded = array();
		$totalFiles = $this->FileTable->getFiles($params, true);

		// ensure that limit is set
		$params['limit'] = !isset($params['limit']) ? 0 : $params['limit'];

		// ensure that page is set
		$params['page'] = !isset($params['page']) ? 1 : $params['page'];

        $links['pageCount'] = 1;
        if ($params['limit'] > 0) {
            // get page total
            $links['pageCount'] = ceil($totalFiles/$params['limit']);
        }

		// do not process if page parameter is greater than the total number of pages
		if ($params['page'] > $links['pageCount']) {
			return array();
		}

		// offset
		$params['offset'] = ($params['page'] > 0) ? ($params['page'] - 1) : 0;

		// get files
		$files = $this->FileTable->getFiles($params);
		if ($files) {
			foreach($files as $file) {
				$embedded[] = $this->getFileDocument($file->file_id);
			}
		}

		return array("_links" => $links, "_embedded" => $embedded);;
	}

    protected function convertDateTime($dateTime, $fromTimezone='UTC', $toTimezone='America/Los_Angeles')
    {
        $time_object = new \DateTime($dateTime, new \DateTimeZone($fromTimezone));
        $time_object->setTimezone(new \DateTimeZone($toTimezone));
        return $time_object->format('Y-m-d H:i:s');
    }

}