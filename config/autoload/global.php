<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
    ),
    'file_filters' => array(
        'customer_files' => array(
            'SITE',
            'PORTAL',
            'CUSTOMQUOTE',
            'MAILINGLIST',
            'SITE_UPLOAD',
            'PORTAL_UPLOAD',
            'CUSTOMQUOTE_UPLOAD',
            'MAILINGLIST_UPLOAD'
        )
    )
);
