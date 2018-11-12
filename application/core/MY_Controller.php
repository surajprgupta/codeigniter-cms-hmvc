<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class My_Controller extends MX_Controller
{

    /**
     * $ajaxRequest : this is the variable which contains the requested page is via ajax call or not. by default it is false and will be set as false and will be set as true in constructor after validating the request type.
     *
     */
    public $ajaxRequest = false;
    public $template = NULL;

    public function __construct()
    {
        parent::__construct();

        /**
         * validating the request type is ajax or not and setting up the $ajaxRequest variable as true/false.
         *
         */
        $requestType = $this->input->server('HTTP_X_REQUESTED_WITH');
        $this->ajaxRequest = strtolower($requestType) == 'xmlhttprequest';

        /**
         * set the default template as blank when the request type is ajax
         */
        if ($this->ajaxRequest === true) {
            $this->load->setTemplate('blank');
        }

        $module = strtolower($this->router->fetch_module());

        $this->_load_default_config($module);

        switch ($module) {
            case 'admin':
                $this->load->setTemplate('admin');
                break;
        }
    }

    private function _load_default_config($module)
    {
        $default_confgi_arr = array($module . '/assets/default', $module . '/menu/default', $module . '/report/default');
        foreach ($default_confgi_arr as $index => $item) {
            if (file_exists(APPPATH . 'config/' . $item))
                $this->config->load($item);
        }
    }

    public function _table_listing($data)
    {
        $data['message'] = $this->session->flashdata('data_message');

        $dataArray = array(
            'table' => $this->reportlibrary->makeTable($data['listing_headers'], $data),
            'message' => $data['message'],
            'template_title' => $data['template_title']
        );

        $dataArray['local_js'] = array(
            'jquery.dataTables',
            'dataTables.bootstrap',
            'dataTables.fnFilterOnReturn',
            'dataTables.fnSortUSDate',
            'dataTables-main.min'
        );

        $dataArray['local_css'] = array(
            'dataTables.bootstrap.css'
        );

        return $dataArray;
    }

    public function _remap($method, $params = array())
    {
        $module = $this->router->fetch_module();
        $class = $this->router->fetch_class();

        $this->_check_user_auth($module, $class, $method, $params);

        /*$this->load->library('migration');

        if ($this->migration->current() === FALSE) {
            show_error($this->migration->error_string());
        }*/

        if (method_exists($this, $method)) {
            call_user_func_array(array($this, $method), $params);
        } else {
            show_404();
        }
    }

    public function _check_user_auth($module, $class, $method, $params)
    {
        $sess_user_auth = $this->session->userdata('user_auth');
        $user_authd = false;
        $redirect_login = false;

        if (!empty($sess_user_auth['connected']) && (isset($sess_user_auth['id']) && $sess_user_auth['id'] != NULL)) {
            $user_authd = true;
        }

        if ($module == 'auth') {
            if (!in_array($method, array('login', 'forgot_password'))) {

                if (!$user_authd) {
                    $redirect_login = true;
                }
            }
        } else {

            if (!$user_authd) {
                $redirect_login = true;
            }
        }

        if ($redirect_login == true) {
            redirect(base_url('login'));
        } else {
            return false;
        }

    }


}
