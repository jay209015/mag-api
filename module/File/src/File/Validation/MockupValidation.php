<?php
namespace File\Validation;

use File\Model\Mockup;
use Zend\View\Model\JsonModel;

/**
 * Class MockupValidation
 * @package File\Validation
 * @author emman@uprinting.com
 */
class MockupValidation
{
    protected $errors = array();
    /**
     * validate method for MockupValidation Class.
     * @param $mockup
     * @return new JsonModel
     */
    public function validate(Mockup $mockup)
    {
        $this->shouldHavePage($mockup);
        $this->shouldHaveWidth($mockup);
        $this->shoudlHavePath($mockup);
                        
        return new JsonModel(
            array(
                    'valid' => $this->errors == array(),
                    'errors' => implode(' ', $this->errors)
                )
        );
    }

    protected function shouldHavePage($mockup)
    {
        if ($mockup->page == null) {
            $this->errors[] = 'Missing page.';
        }
    }

    protected function shouldHaveWidth($mockup)
    {
        if ($mockup->width == null) {
            $this->errors[] = 'Missing width.';
        }
    }

    protected function shoudlHavePath($mockup)
    {
        if ($mockup->path == null) {
            $this->errors[] = 'Missing path.';
        }
    }

}