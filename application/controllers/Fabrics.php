<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fabrics extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
            exit;
        }

        $this->load->model('Fabrics_model');
        $this->load->model('Products_model');
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
        $this->load->js('assets/themes/minky-moon/js/notify.min.js');
        $this->load->js('assets/themes/admin/build/js/custom.js');
        $this->load->js('assets/themes/minky-moon/js/app.js');

        $this->output->set_template('admin-left-menu');
    }

    public function index()
    {
        $this->init_page();

        $data['products'] = $this->Products_model->getProducts();
        $data['fabric'] = $this->Fabrics_model->getFabricData();
        $data['categories'] = $this->Fabrics_model->getCategories();

        $this->load->view('fabrics/fabrics', $data);
    }

    public function editFabric()
    {
        $id =  $this->input->post('id');

        $data['fabric'] = $this->Fabrics_model->getSingleFabric($id);
        $data['products'] = $this->Fabrics_model->getFabricProducts($data['fabric']->id);
        $data['categories'] = $this->Fabrics_model->getFabricCategories($data['fabric']->id);

        echo json_encode($data);
    }

    public function save_fabric()
    {
        $data = array('post' => $_POST, 'files' => $_FILES);
        $return = $this->Fabrics_model->saveFabricData($data);
        echo json_encode($return);
    }

    public function getPlacements()
    {
        $category = $this->input->post('category');
        echo json_encode($this->Fabrics_model->getPlacements($category));
    }

    public function products()
    {
        $this->init_page();

        $product = $this->uri->segment(3) != '' ? $this->uri->segment(3) : '1';

        $data = $this->Fabrics_model->getFabricsWithProduct($product);
        $data['products'] = $this->Products_model->getProducts();
        $data['categories'] = $this->Fabrics_model->getCategories();

        $this->load->view('fabrics/fabrics-by-product', $data);
    }

    public function add_fabric_category()
    {
        $category = ucwords($this->input->post('category'));
        if($category) {
            $this->db->insert('fabric_categories', array('category' => $category));
            echo json_encode(array('success' => true, 'id' => $this->db->insert_id(), 'category' => $category));
        } else {
            echo json_encode(array('success' => false));
        }
    }

}