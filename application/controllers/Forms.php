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
        $this->output->enable_profiler(true);

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
        $this->load->js('assets/themes/lapp/js/app.js');

        if (!$this->ion_auth->logged_in()) {
            redirect('login');
            exit;
        }

        $this->output->set_template('admin-left-menu');

    }

    public function index()
    {
        $this->load->view('forms/all-forms');
    }

    public function add_form()
    {
        $this->init_page();

        $this->load->model('Form_model');
        $data['validation_options'] = $this->Form_model->getValidationRules();
        $data['inputs'] = $this->Form_model->getSavedInputs();

        $this->load->view('forms/add-form', $data);
    }

    public function save_form()
    {
        $returns = array(
            'success' => false,
            'msg' => 'Invalid form values',
            'errors' => array(),
        );

        $this->load->model('Form_model');

        $newName = true;

        $form_id = (int)$_POST['form_id'];
        if(!empty($form_id)) {
            $savedFrom = $this->Form_model->getFormById($form_id);
            if($savedFrom['form_settings']['name'] == $_POST['name']) {
                $newName = false;
            }
        }

        if($newName) {
            $this->form_validation->set_rules(
                'name', 'form name',
                'required|min_length[2]|max_length[255]|is_unique[forms.name]',
                array(
                    'required' => 'You have not provided a %s.',
                    'is_unique' => 'This %s already exists.'
                )
            );
        }

        $this->form_validation->set_rules(
            'cost', 'form cost',
            'max_length[10]|decimal|greater_than_equal_to['.$_POST["min"].']',
            array(
                'required'      => 'You have not provided a %s.',
                'decimal'     => '%s must be formatted as a currency value (50.00).',
                'greater_than_equal_to'     => 'Cost must be greater then or equal to min payment.'
            )
        );

        $this->form_validation->set_rules(
            'min', 'min cost',
            'max_length[10]|decimal',
            array(
                'required'      => 'You have not provided a %s.',
                'decimal'     => '%s must be formatted as a currency value (24.99).'
            )
        );

        $this->form_validation->set_rules('header', 'header', 'max_length[1000]');
        $this->form_validation->set_rules('footer', 'footer', 'max_length[1000]');
        $this->form_validation->set_rules('footer', 'footer', 'max_length[1000]');
        $this->form_validation->set_rules('active', 'active', 'max_length[5]');


        if ($this->form_validation->run() == FALSE) {
            $returns['errors'] = validation_errors_array();
        } else {
            $returns = $this->Form_model->saveFormValues($_POST);
        }

        echo json_encode($returns);
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

    public function view_form()
    {
        $formId = (int)$this->uri->segment(3);
        if(!$formId) {
            show_404();
            exit;
        }

        $this->init_page();

        $this->load->model('Form_model');

        $data['form'] = $this->Form_model->getFormById($formId);
        if(empty($data['form'])) {
            show_404();
        }

        $this->load->view('forms/show-form', $data);
    }

    public function all_forms()
    {
        $this->init_page();

        $this->load->model('Form_model');
        $data['forms'] = $this->Form_model->getForms();
        $this->load->view('forms/all-forms', $data);
    }

    public function toggle_form()
    {
        $this->load->model('Form_model');
        echo json_encode($this->Form_model->toggleFormAvailability($_POST));
    }

    public function edit_form()
    {
        $formId = (int)$this->uri->segment(3);
        if(!$formId) {
            show_404();
            exit;
        }

        $this->init_page();

        $this->load->model('Form_model');

        $data['form'] = $this->Form_model->getFormById($formId);
        $data['validation_options'] = $this->Form_model->getValidationRules();

        $this->Form_model->formId = $formId;
        $data['inputs'] = $this->Form_model->getSavedInputs();

        $this->load->view('forms/edit-form', $data);
    }

    public function submit_form_manually()
    {
        $formId = (int)$this->uri->segment(3);
        if(!$formId) {
            show_404();
            exit;
        }

        $this->init_page();
        $this->load->model('Form_model');

        $data['form'] = $this->Form_model->getFormById($formId);

        $this->load->view('forms/enter-form-manually', $data);
    }

    public function save_user_form()
    {
        $this->load->model('Form_submit_model');
        $data = isset($_POST['form']) ? $_POST['form'] : array();

        $this->Form_submit_model->submitForm($data);

        echo json_encode($this->Form_submit_model->feedback);
    }

}