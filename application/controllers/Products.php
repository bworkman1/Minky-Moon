<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
            exit;
        }

        $this->load->model('Products_model');
        $this->load->model('Pricing_model');
        $this->load->model('Fabrics_model');
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

        $data['products'] = $this->Products_model->getProducts();
        $data['pricing'] = $this->Pricing_model->getPricingOptions();
        $data['fabrics'] = $this->Fabrics_model->getFabricData();

        $this->load->view('products/products', $data);
    }

    public function deleteProduct()
    {
        $id = $this->input->post('id');
        echo json_encode($this->Products_model->deleteProduct($id));
    }

    public function getProductById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->Products_model->getProductById($id));
    }

    public function deleteProductImage()
    {
        $id = $this->input->post('id');
        echo json_encode($this->Products_model->deleteProductImage($id));
    }

    public function saveProduct()
    {
        $this->form_validation->set_rules('id', 'ID', 'numeric');
        if($this->input->post('id') == '') {
            $this->form_validation->set_rules('name', 'Name', 'required|alpha_numeric_spaces|max_length[50]|min_length[3]|is_unique[products.name]', array('is_unique' => 'Product already exists'));
        }
        $this->form_validation->set_rules('pricing_options[]', 'Pricing', 'required');
        $this->form_validation->set_rules('fabrics[]', 'Fabrics', 'required');

        if ($this->form_validation->run() == FALSE) {

            $data = array();
            $errors = validation_errors_array();
            foreach($errors as $key => $val) {
                $key = str_replace('[', '', $key);
                $key = str_replace(']', '', $key);
                $data[$key] = $val;
            }

            $feedback = array(
                'success' => false,
                'error'   => $data,
                'msg'     => 'There are errors in the form, please fix them before trying again'
            );
            echo json_encode($feedback);
            exit;
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'fabrics' => $this->input->post('fabrics'),
                'pricing_options' => $this->input->post('pricing_options'),
                'image' => 'image',
            );

            echo json_encode($this->Products_model->saveProduct($data));
            exit;
        }
    }

    public function edit()
    {
        $this->init_page();

        $productId = (int)$this->uri->segment(3);
        $data['options'] = $this->Products_model->getProductData($productId);

        $this->load->view('products/edit', $data);
    }

}