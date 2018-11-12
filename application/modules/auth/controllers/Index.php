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

    public $autoload = array(
        'libraries' => array('form_validation'),
    );

    public function login()
    {
        $this->form_validation->set_rules('uemail', 'Username', 'required|max_length[255]');
        $this->form_validation->set_rules('upass', 'Password', 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'uemail' => $this->input->post('uemail'),
                'upass' => $this->input->post('upass'),
            );

            $admin_cred_config = $this->config->item('app_config');

            if (($data['uemail'] == $admin_cred_config['admin_user']) && ($admin_cred_config['admin_pass'] == password_decrypt($data['upass'], $admin_cred_config['admin_pass']))) {

                $this->session->set_userdata('user_auth', array(
                    'connected' => true,
                    'id' => 1
                ));

                redirect(base_url());

            } else {

                $this->session->set_flashdata('flash_err', 'Error, Please provide valid login credential.');
                redirect(base_url('login'));

            }
        } else {

            $validation_errors = validation_errors();
            if (!empty($validation_errors)) {
                $this->session->set_flashdata('flash_err', $validation_errors);
            }

            $data['flash_msg'] = $this->session->flashdata('flash_err');
            $this->load->setTemplate('login');
            $this->load->view('blank', $data);
        }
    }

    public function logout()
    {

        $this->session->sess_destroy();
        redirect(base_url('login'));

    }
}