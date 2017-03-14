<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Build_form extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(TRUE);

        $this->load->css('assets/themes/lapp/css/bootstrap.min.css');
        $this->load->css('assets/themes/lapp/css/font-awesome.min.css');

        $this->load->js('assets/themes/lapp/js/jquery-3.1.1.min.js');
        $this->load->js('assets/themes/lapp/js/bootstrap.min.js');

        $this->output->set_template('bootstrap_blank_theme');
    }

    public function index()
    {
        $this->load->view('forms/form-builder-step-1');
    }

}