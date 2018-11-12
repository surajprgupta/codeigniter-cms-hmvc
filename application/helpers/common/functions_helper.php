<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function get_admin_primary_menu() {
    $CI = & get_instance();
    $top_menu = $CI->config->item('admin_top_menu');

    $menu = '<ul class="nav navbar-nav">';
    foreach ($top_menu as $key => $value) {
        if (is_array($value)) {
            $menu .= '<li class="dropdown">'
                . '<a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $key . '<span class="caret"></span></a>'
                . '<ul class="dropdown-menu" role="menu">';
            foreach ($value as $sub_key => $sub_val) {
                if (is_array($sub_val)) {
                    $menu .= '<li class="dropdown has-sub-sub-menu-li">'
                        . '<a href="#" class="dropdown-toggle has-sub-sub-menu" data-toggle="dropdown">' . $sub_key . '<span class="caret"></span></a>'
                        . '<ul class="dropdown-menu sub-sub-menu" role="menu">';
                    foreach ($sub_val as $sub_sub_key => $sub_sub_val) {
                        $menu .= '<li><a href="' . base_url($sub_sub_val) . '">' . $sub_sub_key . '</a></li>';
                    }
                    $menu .= '</ul>';
                } else {
                    $menu .= '<li><a href="' . base_url($sub_val) . '">' . $sub_key . '</a></li>';
                }
            }
            $menu .= '</ul>';
        } else {
            $menu .= '<li><a href="' . base_url($value) . '">' . $key . '</a></li>';
        }
    }
    $menu .= '</ul>';
    return $menu;
    ?>
    <?php

}

function password_decrypt($password, $hash)
{
    return crypt($password, $hash);
}

function password_encrypt($password) {
    $cost = 10;
    $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
    $salt = sprintf("$2a$%02d$", $cost) . $salt;
    $hash = crypt($password, $salt);
    return $hash;
}