<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!isset($config)) {
    $config = array();
}


$config = array_merge($config, array(
    'housekeeping_listing_headers' => array(
        'Name' => array(
            'jsonField' => 'name',
            'width' => '25%'
        ),
        'Mobile' => array(
            'jsonField' => 'mobile',
            'width' => '20%'
        ),
        'Created' => array(
            'jsonField' => 'created',
            'width' => '15%'
        ),
        'Modified' => array(
            'jsonField' => 'modified',
            'width' => '15%'
        ),
        'action' => array(
            'isSortable' => FALSE,
            'systemDefaults' => TRUE,
            'isLink' => TRUE,
            'width' => '5%',
            'align' => 'center',
            'type' => array(
                'EDIT_ICON',
                'DELETE_ICON'
            ),
            'linkParams' => array(
                'EDIT_ICON' => 'id',
                'DELETE_ICON' => 'id'
            ),
            'linkTarget' => array(
                'EDIT_ICON' => 'admin/housekeeping/add-housekeeper/',
                'DELETE_ICON' => 'admin/housekeeping/delete/housekeeper/'
            ),
        )
    )
));