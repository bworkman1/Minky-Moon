<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forms extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function init_page()
    {
        $this->output->enable_profiler(false);

        $this->load->css('assets/themes/admin/vendors/bootstrap/dist/css/bootstrap.min.css');
        $this->load->css('assets/themes/admin/vendors/font-awesome/css/font-awesome.min.css');
        $this->load->css('assets/themes/admin/vendors/nprogress/nprogress.css');
        $this->load->css('assets/themes/admin/vendors/iCheck/skins/flat/green.css');
        $this->load->css('assets/themes/admin/vendors/google-code-prettify/bin/prettify.min.css');
        $this->load->css('assets/themes/admin/vendors/select2/dist/css/select2.min.css');
        $this->load->css('assets/themes/admin/vendors/switchery/dist/switchery.min.css');
        $this->load->css('assets/themes/admin/vendors/starrr/dist/starrr.css');
        $this->load->css('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.css');
        $this->load->css('assets/themes/admin/build/css/custom.min.css');
        $this->load->css('assets/themes/admin/build/css/style.css');

        $this->load->js('assets/themes/admin/vendors/jquery/dist/jquery.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap/dist/js/bootstrap.min.js');
        $this->load->js('assets/themes/admin/vendors/fastclick/lib/fastclick.js');
        $this->load->js('assets/themes/admin/vendors/nprogress/nprogress.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js');
        $this->load->js('assets/themes/admin/vendors/iCheck/icheck.min.js');
        $this->load->js('assets/themes/admin/vendors/moment/min/moment.min.js');
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
        $this->load->js('assets/themes/lapp/js/app.js');

        $this->output->set_template('admin-left-menu');

    }

    public function index()
    {
        $this->load->view('forms/all-forms');
    }

    public function add_form()
    {
        $this->init_page();

        $this->load->css('assets/themes/admin/css/alertify/alertify.core.css');
        $this->load->css('assets/themes/admin/css/alertify/alertify.default.css');

        $this->load->js('assets/themes/admin/js/alertify/alertify.min.js');

        $this->load->model('Form_model');
        $data['validation_options'] = $this->Form_model->getValidationRules();
        $data['inputs'] = $this->Form_model->getUnsavedInputs();

        $this->load->view('forms/add-form', $data);
    }

    public function format_validation_rules()
    {
        $this->load->model('Form_model');
        $return = $this->Form_model->checkForValidValidation($_POST);
        echo json_encode($return);
    }

    public function add_input()
    {
        $this->load->model('Form_model');
        $results = $this->Form_model->addNewFormInput($_POST);

        echo json_encode($results);
    }

    public function delete_input()
    {
        $this->load->model('Form_model');
        $results = $this->Form_model->deleteInput($_POST);

        echo json_encode($results);
    }

    public function get_form_input()
    {
        $this->load->model('Form_model');
        echo json_encode($this->Form_model->getSingleFormInput($_POST));
    }

}