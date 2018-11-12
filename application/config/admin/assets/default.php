<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


$config['css_default'] = array(
    'bootstrap.min.css' => array('name' => 'assets/vendors/bootstrap/dist/css/bootstrap.min.css'),
    'font-awesome.min.css' => array('name' => 'assets/vendors/font-awesome/css/font-awesome.min.css'),
    'custom.min.css' => array('name' => 'assets/css/custom.min.css'),
//    'font-awesome.min.css' => array(
//        'name' => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
//        'cdn' => true
//    ),
);

$config['js_default'] = array(
    'jquery.min.js' => array('name' => 'assets/vendors/jquery/dist/jquery.min.js'),
    'bootstrap.min.js' => array('name' => 'assets/vendors/bootstrap/dist/js/bootstrap.min.js'),
    'fastclick.js' => array('name' => 'assets/vendors/fastclick/lib/fastclick.js'),
    'nprogress.js' => array('name' => 'assets/vendors/nprogress/nprogress.js'),
    'custom.min.js' => array('name' => 'assets/js/custom.min.js'),
);

$config['css_arr'] = array(
);

$config['js_arr'] = array(
);
