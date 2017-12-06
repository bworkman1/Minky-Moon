<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->library('cart');
    }

    public function init_template()
    {
        $this->output->set_template('website/minky-moon');

        $this->load->css('assets/themes/minky-moon/css/bootstrap.minky.moon.min.css');
        $this->load->css('assets/themes/admin/vendors/font-awesome/css/font-awesome.min.css');
        $this->load->css('assets/themes/admin/css/alertify/alertify.core.css');
        $this->load->css('assets/themes/admin/css/alertify/alertify.default.css');
        $this->load->css('assets/themes/minky-moon/css/styles.css');

        $this->load->js('assets/themes/admin/vendors/jquery/dist/jquery.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap/dist/js/bootstrap.min.js');
        $this->load->js('assets/themes/admin/js/alertify/alertify.min.js');
        $this->load->js('assets/themes/admin/vendors/mask/jquery.mask.min.js');
        $this->load->js('assets/themes/minky-moon/js/shopcart.js');

        $this->output->enable_profiler(PROFILER);
    }

    public function index()
    {
        $this->init_template();
        $this->load->model('Products_model');
        $data['products'] = $this->Products_model->getProducts();

        $this->load->view('website/home', $data);
    }

    public function personalize()
    {
        $this->init_template();
        $this->load->model('Shop_model');
        $this->load->model('Fabrics_model');

        $type = $this->uri->segment(3);
        $data = $this->Shop_model->getProductsOptionsByType($type);
        $data['settings'] = $this->session->userdata($type);
        if(!empty($data)) {
            $this->load->view('website/personalize', $data);
        } else {
            show_404();
        }
    }

    public function ajax_call()
    {
        $data = $this->input->post('data');
        if(!isset($data['model']) || $data['model'] == '') {
            echo json_encode(array('success' => false, 'msg' => 'Invalid Option'));
            exit;
        }

        $this->load->model($data['model'].'_model', 'Product');
        echo json_encode($this->Product->process($data));
    }

    public function my_cart()
    {
        $this->init_template();
        $this->load->model('Shop_model');
        $this->load->model('Fabrics_model');

        if($this->cart->total_items()) {
            $this->load->view('website/my-cart');
        } else {
            $this->session->set_flashdata('error', 'You haven\'t added any items to your cart yet');
            redirect(base_url().'shop');
            exit;
        }
    }

    public function checkout()
    {
        $this->load->helper('site');

        $this->init_template();
        $this->load->model('Shop_model');

        if(!$this->cart->total_items()) {
            $this->session->set_flashdata('error', 'You haven\'t added any items to your cart yet');
            redirect(base_url().'shop');
            exit;
        }

        if ($this->ion_auth->logged_in()) {
            $this->load->model('Account_model');
            $this->load->library('encrypt');
            $data['saved_shipping'] = $this->Account_model->getUserShippingAddress($this->session->userdata('user_id'));
            $this->load->view('website/payment', $data);
        } else {
            $this->load->view('website/login');
        }
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect('shop');
        exit;
    }

    public function apply_discount_code()
    {
        $this->load->helper('site');
        $code = $this->input->post('code');
        $this->load->model('Shop_model');

        echo json_encode($this->Shop_model->apply_discount_code($code));
    }

    public function submit_payment()
    {
        $this->load->model('Shop_model');

        echo json_encode($this->Shop_model->processPayment());
    }

    public function paypal_notification()
    {

    }

}
