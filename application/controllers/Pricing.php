<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pricing extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
            exit;
        }

        $this->load->model('Pricing_model');
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
        $this->load->js('assets/themes/admin/vendors/jquery.hotkeys/jquery.hotkeys.js');
        $this->load->js('assets/themes/admin/vendors/google-code-prettify/src/prettify.js');
        $this->load->js('assets/themes/admin/vendors/jquery.tagsinput/src/jquery.tagsinput.js');
        $this->load->js('assets/themes/admin/vendors/switchery/dist/switchery.min.js');
        $this->load->js('assets/themes/admin/vendors/select2/dist/js/select2.full.min.js');
        $this->load->js('assets/themes/admin/vendors/parsleyjs/dist/parsley.min.js');
        $this->load->js('assets/themes/admin/vendors/autosize/dist/autosize.min.js');
        $this->load->js('assets/themes/admin/vendors/starrr/dist/starrr.js');
        $this->load->js('assets/themes/admin/vendors/mask/jquery.mask.min.js');
        $this->load->js('assets/themes/admin/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js');
        $this->load->js('assets/themes/lapp/js/notify.min.js');
        $this->load->js('assets/themes/admin/build/js/custom.js');
        $this->load->js('assets/themes/lapp/js/app.js');

        $this->output->set_template('admin-left-menu');
    }

    public function index()
    {
        $this->init_page();

        $data['pricing'] = $this->Pricing_model->getPricingOptions();
        $this->load->view('pricing/pricing', $data);
    }

    public function getPricingOption()
    {
        $category = $this->input->post('category');
        $name = $this->input->post('name');

        echo json_encode($this->Pricing_model->getPricingOption($name, $category));
    }

    public function savePricingOption()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|alpha_numeric_spaces|max_length[50]|min_length[3]');
        $this->form_validation->set_rules('price', 'Price', 'required|decimal');
        $this->form_validation->set_rules('category', 'Category', 'required|alpha_numeric_spaces|max_length[50]|min_length[3]');
        $this->form_validation->set_rules('size', 'Size', 'alpha_numeric_spaces|max_length[50]');
        $this->form_validation->set_rules('sequence', 'Sequence', 'required|numeric');
        $this->form_validation->set_rules('id', 'ID', 'numeric');
        if ($this->form_validation->run() == FALSE) {
            $feedback = array(
                'success' => false,
                'error'   => validation_errors_array(),
                'msg'     => 'There are errors in the form, please fix them before trying again'
            );
            echo json_encode($feedback);
            exit;
        } else {
            echo json_encode($this->Pricing_model->savePricingOption($this->input->post()));
            exit;
        }
    }

    public function deletePricingOption()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|alpha_numeric_spaces|max_length[50]|min_length[3]');
        $this->form_validation->set_rules('category', 'Category', 'required|alpha_numeric_spaces|max_length[50]|min_length[3]');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('success' => false));
        } else {
            echo json_encode(array('success' => $this->Pricing_model->deletePricingOption($this->input->post())));
            exit;
        }
    }

}