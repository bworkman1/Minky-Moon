<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Form_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
            exit;
        }
    }

    public function init_page()
    {
        $this->output->enable_profiler(PROFILER);

        $this->load->css('assets/themes/minky-moon/bootstrap.minky.moon.min.css');
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


        $this->load->model('Payment_model');
        $this->output->set_template('admin-left-menu');


        $user_groups = $this->ion_auth->get_users_groups($this->session->userdata('id'))->result();
        $userGroups = array();
        if($user_groups) {
            foreach($user_groups as $group) {
                $userGroups[] = $group->name;
            }
        }

        $needed = array('View Submitted Payments', 'Submit Payments');
        $is_allowed = (count(array_intersect($needed, $userGroups))) ? true : false;
        if(!$this->ion_auth->is_admin() && !$is_allowed) {
            redirect(base_url('request-error'));
            exit;
        }

    }

    public function index()
    {
        redirect('payments/all');
    }

    public function all()
    {
        $group = 'Edit Forms';
        if (!$this->ion_auth->in_group($group) && !$this->ion_auth->is_admin()) {
            redirect(base_url('request-error'));
            exit;
        }

        $this->init_page();
        $this->load->model('Payment_model');
        $this->load->library('encryption');

        $limit = (int)$this->input->post('limit') == '' ? $this->session->userdata('submission_limit') : $this->input->post('limit');
        $limit = $limit != '' ? $limit : 10;

        $this->session->set_userdata('submission_limit_payments', $limit);
        $start = $this->uri->segment('3') != '' ? $this->uri->segment('3') : 0;
        $search = $this->input->post('search');
        if($search) {
            $limit = 100;
        }

        $submittedPayments = $this->Payment_model->getAllPayments($search, $start, $limit);
        $data['links'] = $this->Payment_model->paginationResults($limit, 'pull-right');
        $data['table'] = $this->Payment_model->formatSubmittedFormsTable($submittedPayments, $start);
        $data['payments'] = $this->Payment_model->totalPaymentsSubmitted;

        $this->load->view('payments/all-payments', $data);
    }

    public function submit_payment()
    {
        $this->init_page();

        $data['lookup'] = '';

        if($this->uri->segment(3)) {
            $formId = $this->Form_model->convertSubmissionIdToFormId($this->uri->segment(3));
            if($formId) {
                $form = $this->Form_model->getSubmittedFormById($formId['form_id'], $this->uri->segment(3));
                if($form) {
                    $data['lookup'] = $this->Payment_model->determineClientFields($form, $this->uri->segment(3));
                }
            }
        }
        $this->load->view('payments/submit-payment', $data);
    }

    public function submitPaymentDetails()
    {
        $feedback = array(
            'success' => false,
            'msg' => 'Something went wrong, try submitting the payment again',
            'errors' => array(),
        );

        $this->form_validation->set_rules('submission_id', 'Submission Id', 'integer');
        $this->form_validation->set_rules('name', 'Client Name', 'required|max_length[40]');
        $this->form_validation->set_rules('address', 'Address', 'required|max_length[40]');
        $this->form_validation->set_rules('city', 'City', 'required|max_length[40]');
        $this->form_validation->set_rules('state', 'State', 'required|min_length[2]|max_length[2]|alpha');
        $this->form_validation->set_rules('zip', 'Zip', 'required|min_length[5]|max_length[5]|numeric');
        $this->form_validation->set_rules('client_number', 'Client Number', 'required|max_length[6]|min_length[6]');

        $this->form_validation->set_rules('billing_name', 'Billing Name', 'required|max_length[60]');
        $this->form_validation->set_rules('billing_address', 'Billing Address', 'required|max_length[60]');
        $this->form_validation->set_rules('billing_city', 'Billing City', 'required|max_length[30]');
        $this->form_validation->set_rules('billing_state', 'Billing State', 'required|max_length[2]|min_length[2]');
        $this->form_validation->set_rules('billing_zip', 'Billing Zip', 'required|min_length[5]|max_length[5]|numeric');

        $this->form_validation->set_rules('cardNumber', 'Credit Card Number', 'required|min_length[19]|max_length[19]');
        $this->form_validation->set_rules('cardExpiry', 'Expiration Date', 'required|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('cardCVC', 'CVC Number', 'required|integer|min_length[2]|max_length[5]');
        $this->form_validation->set_rules('amount', 'Payment Amount', 'required|decimal|min_length[2]|max_length[9]');
        $this->form_validation->set_rules('for', 'Payment For', 'required|min_length[2]|max_length[100]');

        if ($this->form_validation->run() == FALSE) {
            $feedback['errors']= validation_errors_array();
        } else {
            $this->load->model('Payment_model');

            $this->Payment_model->postValues = $_POST;
            if($this->Payment_model->processPayment()) {
                $this->session->set_flashdata('success', 'Payment submitted successfully');
                $feedback['success'] = true;
            } else {
                $feedback['errors'] = $this->Payment_model->feedback['errors'];
                $feedback['msg'] = $this->Payment_model->feedback['msg'];
            }
        }
        echo json_encode($feedback);
    }

}