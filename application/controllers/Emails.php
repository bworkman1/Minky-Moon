<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emails extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->library('cart');
    }

    public function test() {
        $file = $this->input->get('email');
        $this->load->view('email-templates/'.$file, array('transaction_id' => '2939faf923r2fj2f29f2'));
    }
}