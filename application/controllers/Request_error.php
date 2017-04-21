<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_error extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(PROFILER);

        $this->load->css('assets/themes/lapp/css/bootstrap.min.css');
        $this->load->css('assets/themes/lapp/css/font-awesome.min.css');
        $this->load->css('assets/themes/admin/build/css/custom.min.css');

        $this->load->js('assets/themes/lapp/js/jquery-3.1.1.min.js');
        $this->load->js('assets/themes/lapp/js/bootstrap.min.js');

        $this->output->set_template('blank_blue_background');
    }

    public function index()
    {

    }

    public function access_not_allowed()
    {
        $this->load->view('errors/restricted-access-area');
    }

}