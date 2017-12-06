<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Recent_updates extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('cart');
        $this->load->model('Account_model');
    }

    public function index()
    {
        $this->load->view('website/updates');
    }

}