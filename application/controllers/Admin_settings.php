<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->in_group('admin')) {
            redirect('request-error/access-not-allowed');
            exit;
        }
    }

    private function init()
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
        $this->load->js('assets/themes/admin/build/js/custom.min.js');
        $this->load->js('assets/themes/lapp/js/admin-settings.js');

        $this->output->set_template('admin-left-menu');
    }

    public function index()
    {
        $this->init();
        $this->load->model('Admin_model');
        $this->load->library('encrypt');

        $data['settings'] = $this->Admin_model->getAllAdminSettings();
        $data['groups'] = $this->ion_auth->groups()->result();

        $this->load->view('admin/admin-settings', $data);
    }

    public function saveAuthorizeSettings()
    {
        $this->form_validation->set_rules('type', 'Request Type', 'required|trim|max_length[6]');

        if(isset($_POST['type']) && $_POST['type'] != 'remove') {
            $this->form_validation->set_rules('api_key', 'Authorize API Key', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('auth_key', 'Authorize Key', 'required|trim|max_length[255]');
        }

        if ($this->form_validation->run() == true) {

            $this->load->model('Admin_model');
            echo json_encode($this->Admin_model->saveAuthorizeSettings($_POST));

        } else {

            $this->form_validation->set_error_delimiters('<span>', '. </span>');

            $returns['success'] = false;
            $returns['msg'] = validation_errors();

            echo json_encode($returns);
        }
    }

    public function saveSecuritySettings()
    {
        $this->form_validation->set_rules('failed', 'Failed login attempts', 'required|trim|integer|max_length[2]|greater_than[2]');
        $this->form_validation->set_rules('time', 'Lockout Time', 'required|trim|max_length[2]|greater_than[2]');
        $this->form_validation->set_rules('emails', 'Emails', 'trim|max_length[255]|valid_emails');

        if ($this->form_validation->run() == true) {
            $this->load->model('Admin_model');
            echo json_encode($this->Admin_model->saveSecuritySettings($_POST));
        } else {

            $this->form_validation->set_error_delimiters('<span>', '. </span>');
            $returns['success'] = false;
            $returns['msg'] = validation_errors();

            echo json_encode($returns);
        }
    }

    public function saveUserGroup()
    {
        $return = array(
            'success' => false,
            'msg'     => 'Something went wrong, try again'
        );

        $this->form_validation->set_rules('name', 'Page name', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('desc', 'Short Description', 'required|trim|max_length[255]');

        if ($this->form_validation->run() == true) {
            $group = $this->ion_auth->create_group($_POST['name'], $_POST['desc']);
            if($group) {
                $return['success'] = true;
                $return['msg'] = 'Page added successfully';
                $return['data'] = array('id' => $group);
            } else {
                $return['msg'] =  $this->ion_auth->errors();
            }
        } else {

            $this->form_validation->set_error_delimiters('<span>', '. </span>');
            $return['msg'] = validation_errors();
        }

        echo json_encode($return);
    }

    public function deleteUserGroup()
    {
        $return = array(
            'success' => false,
            'msg'     => 'Something went wrong, try again'
        );

        $this->form_validation->set_rules('id', 'Page Id', 'required|integer|greater_than[0]|trim|max_length[8]');

        if ($this->form_validation->run() == true) {
            $group = $this->ion_auth->delete_group($_POST['id']);
            if($group) {
                $return['success'] = true;
                $return['msg'] = 'Page deleted successfully';
                $return['data'] = array('id' => $group);
            } else {
                $return['msg'] =  $this->ion_auth->errors();
            }
        } else {

            $this->form_validation->set_error_delimiters('<span>', '. </span>');
            $return['msg'] = validation_errors();
        }

        echo json_encode($return);
    }



}