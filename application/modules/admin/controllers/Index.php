<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: surajgupta
 * Date: 23/2/16
 * Time: 1:21 PM
 */
class Index extends My_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function Index()
    {
        $this->load->view('dashboard', array());
    }
}