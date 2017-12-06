<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('cart');
        $this->load->model('Account_model');
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

    public function orders()
    {
        if (!$this->ion_auth->in_group('customer')) {
            $this->session->set_flashdata('error', 'You must log in first');
            redirect('account/login');
        }

        $this->init_template();

        $this->load->library('encrypt');
        $data['orders'] = $this->Account_model->getCustomerOrders($this->session->userdata('user_id'));

        $this->load->view('website/my-orders', $data);
    }

    public function login()
    {
        if ($this->ion_auth->in_group('customer')) {
            redirect('account/orders');
        }

        $this->init_template();
        $this->load->view('website/login');
    }

}