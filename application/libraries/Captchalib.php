<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Captchalib
{
    public $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('session');
        $this->CI->load->helper('captcha');
        $this->CI->load->model('captcha_model');
    }

    public function generate_captcha()
    {
        $captcha_config = array(
            'img_path'      => 'backend/captcha_images/',
            'img_url'       => base_url().'backend/captcha_images/',
            'font_path'     => FCPATH.'system/fonts/texb.ttf',
            'img_width'     => '150',
            'img_height'    => 50,
            'word_length'   => 6,
            'font_size'     => 15,
            'expiration'    => 300,
            'colors'        => array(
               'background'     => array(51, 189, 255),
               'border'         => array(220, 220, 220),
               'text'           => array(0, 0, 0),
               'grid'           => array(7, 91, 175)
            )
        );
        
        $captcha = create_captcha($captcha_config);
        // Unset previous captcha and set new captcha word
        $this->CI->session->unset_userdata('captchaCode');
        $this->CI->session->set_userdata('captchaCode', $captcha['word']);
        return $captcha;
    }

    public function is_captcha($login_page = null) {
        // Get the captcha status
        $captcha = $this->CI->captcha_model->getStatus($login_page);
    
        // Check if $captcha is an array and contains the "status" key
        if (is_array($captcha) && isset($captcha["status"])) {
            // Check if login_page is not null and captcha status is 1
            if ($login_page !== null && $captcha["status"] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            // Handle cases where $captcha is not an array or does not contain "status"
            return false;
        }
    }
    

}
