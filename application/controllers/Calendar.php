<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            redirect('login');
            exit;
        }
    }

    public function init_page()
    {
        $this->output->enable_profiler(PROFILER);

        $this->load->css('assets/themes/admin/vendors/bootstrap/dist/css/bootstrap.min.css');
        $this->load->css('assets/themes/admin/vendors/font-awesome/css/font-awesome.min.css');
        $this->load->css('assets/themes/admin/vendors/nprogress/nprogress.css');
        $this->load->css('assets/themes/admin/vendors/iCheck/skins/flat/green.css');
        $this->load->css('assets/themes/admin/vendors/google-code-prettify/bin/prettify.min.css');
        $this->load->css('assets/themes/admin/vendors/select2/dist/css/select2.min.css');
        $this->load->css('assets/themes/admin/vendors/switchery/dist/switchery.min.css');
        $this->load->css('assets/themes/admin/vendors/starrr/dist/starrr.css');
        $this->load->css('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.css');
        $this->load->css('assets/themes/admin/css/alertify/alertify.core.css');
        $this->load->css('assets/themes/admin/css/alertify/alertify.default.css');
        $this->load->css('assets/themes/admin/build/css/custom.min.css');
        $this->load->css('assets/themes/admin/build/css/style.css');

        $this->load->js('assets/themes/admin/vendors/jquery/dist/jquery.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap/dist/js/bootstrap.min.js');
        $this->load->js('assets/themes/admin/vendors/fastclick/lib/fastclick.js');
        $this->load->js('assets/themes/admin/vendors/nprogress/nprogress.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js');
        $this->load->js('assets/themes/admin/vendors/iCheck/icheck.min.js');
        $this->load->js('assets/themes/admin/vendors/moment/min/moment.min.js');
        $this->load->js('assets/themes/admin/js/alertify/alertify.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js');
        $this->load->js('assets/themes/admin/vendors/jquery.hotkeys/jquery.hotkeys.js');
        $this->load->js('assets/themes/admin/vendors/google-code-prettify/src/prettify.js');
        $this->load->js('assets/themes/admin/vendors/jquery.tagsinput/src/jquery.tagsinput.js');
        $this->load->js('assets/themes/admin/vendors/switchery/dist/switchery.min.js');
        $this->load->js('assets/themes/admin/vendors/select2/dist/js/select2.full.min.js');
        $this->load->js('assets/themes/admin/vendors/parsleyjs/dist/parsley.min.js');
        $this->load->js('assets/themes/admin/vendors/autosize/dist/autosize.min.js');
        $this->load->js('assets/themes/admin/vendors/starrr/dist/starrr.js');
        $this->load->js('assets/themes/admin/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js');
        $this->load->js('assets/themes/admin/build/js/custom.js');
        $this->load->js('assets/themes/admin/vendors/mask/jquery.mask.min.js');
        $adminSettings = $this->session->userdata('settings');
        $this->load->js('https://maps.googleapis.com/maps/api/js?key=' . $adminSettings['google_api_key'] . '&libraries=places');
        $this->load->js('assets/themes/lapp/js/app.js');
        $this->load->js('assets/themes/lapp/js/calendar.js');


        $this->load->model('Payment_model');
        $this->output->set_template('admin-left-menu');

    }

    public function index()
    {
        $this->init_page();
        $this->load->model('Calendar_model');
        $this->load->model('Form_model');

        $this->output->set_meta('pagename','calendar');
        
        $data['calendar'] = $this->Calendar_model->getCalendar(base_url('Calendar/index/'));
        $data['forms'] = $this->Form_model->getAllFormNames();

        $this->load->view('admin/view-calendar', $data);
    }

    public function add_event()
    {
        $input = array(
            'name'      => $this->input->post('name'),
            'all_day'   => $this->input->post('all_day'),
            'start'     => $this->input->post('start'),
            'desc'      => $this->input->post('desc'),
            'link_to_form'      => $this->input->post('link_to_form'),
        );
        $this->load->model('Calendar_model');

        echo json_encode($this->Calendar_model->addEvent($input));
    }

    public function view_event()
    {
        $id = (int)$this->input->post('event');
        $this->load->model('Calendar_model');
        echo json_encode($this->Calendar_model->getEvent($id));
    }

    public function delete_event()
    {
        $id = (int)$this->input->post('event');
        $this->load->model('Calendar_model');

        echo json_encode($this->Calendar_model->deleteEvent($id));
    }

}
