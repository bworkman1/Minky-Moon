<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *
 * PUBLIC VIEW FOR CALENDAR, PARTIAL PAYMENTS, AND FORMS
 *
 */
class View extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_template('lapp-website');
    }

    public function index()
    {
        $this->load->css('assets/themes/admin/build/css/style.css');

        $this->load->model('Calendar_model');
        $data['calendar'] = $this->Calendar_model->getCalendar(base_url('view/index/'));
        $this->load->view('admin/view-calendar', $data);
    }

}