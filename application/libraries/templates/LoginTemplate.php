<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
    /**
     * Default template
     */
    require_once 'Template.php';

    class LoginTemplate extends Template
    {

        public function __construct()
        {
            parent::__construct();

            $this->_CI = & get_instance();

            $this->viewPath = "templates/login/";
        }

        public function render($view, array $data = array())
        {
            $return_val = $this->CI->load->viewPartial($view, $data);
            $data['template_content'] = $return_val;

            $css_tags = $this->collectCss(isset($data['css_local']) ? $data['css_local'] : array());
            $data['template_css'] = implode("\n", $css_tags);

            $script_tags = $this->collectJs(isset($data['js_local']) ? $data['js_local'] : array());
            $data['template_js'] = implode("\n", $script_tags);

            $return_val = $this->CI->load->viewPartial($this->viewPath . $this->masterTemplate, $data);
            return $return_val;
        }

    }
    