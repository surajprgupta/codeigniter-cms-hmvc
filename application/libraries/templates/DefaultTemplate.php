<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
    /**
     * Default template
     */
    require_once 'Template.php';

    /**
     * Default template implementation.
     * 
     * It is the default renderer of all the pages if any other renderer is not used.
     */
    class DefaultTemplate extends Template
    {

        public function __construct()
        {
            parent::__construct();

            $this->_CI = & get_instance();
        }

        public function render($view, array $data = array())
        {
            $return_val = $this->CI->load->viewPartial($view, $data);

            $data['template_content'] = $return_val;

            $css_tags = $this->collectCss(isset($data['css_local']) ? $data['css_local'] : array());
            $data['template_css'] = implode("\n", $css_tags);

            $script_tags = $this->collectJs(isset($data['js_local']) ? $data['js_local'] : array());
            $data['template_js'] = implode("\n", $script_tags);

            $data['template_title'] = 'Public Panel ';

            $this->CI->load->library('session');
            $data['template_username'] = $this->CI->session->userdata('username');
            $data['template_useremail'] = $this->CI->session->userdata('email');

            $data['top_menu'] = $this->CI->config->item('menu');
            $data['sub_menu'] = $this->CI->config->item('sub_menu');

            $data['template_header'] = $this->CI->load->viewPartial($this->viewPath . 'header', $data);
            $data['template_footer'] = $this->CI->load->viewPartial($this->viewPath . 'footer', $data);

            $return_val = $this->CI->load->viewPartial($this->viewPath . $this->masterTemplate, $data);
            return $return_val;
        }

    }
    