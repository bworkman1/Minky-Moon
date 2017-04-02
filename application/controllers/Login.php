<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->ion_auth->logged_in()) {
            redirect('dashboard');
            exit;
        }

        $this->output->enable_profiler(TRUE);

        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->load->css('assets/themes/admin/vendors/bootstrap/dist/css/bootstrap.min.css');
        $this->load->css('assets/themes/admin/vendors/font-awesome/css/font-awesome.min.css');

        $this->load->css('assets/themes/admin/vendors/nprogress/nprogress.css');
        $this->load->css('assets/themes/admin/vendors/animate.css/animate.min.css');

        $this->load->css('assets/themes/admin/build/css/custom.min.css');

        $this->load->js('assets/themes/lapp/js/jquery-3.1.1.min.js');
        $this->load->js('assets/themes/lapp/js/bootstrap.min.js');

        $this->load->js('assets/themes/lapp/js/login.js');

        $this->output->set_template('bootstrap_blank_theme');

        $this->load->view('admin/login');
    }

    public function logout()
    {
        $this->ion_auth->logout();
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('login');
    }

    public function ajax_validate_login()
    {
        $this->form_validation->set_rules('identity', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        $returns = array(
            'success' => false,
            'msg' => 'Invalid username or password',
        );

        if ($this->form_validation->run() == true) {
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {

                foreach($this->ion_auth->user()->row() as $key => $val) {
                    if($key != 'password' || $key != 'salt') {
                        $this->session->set_userdata($key, $val);
                    }
                }

                $this->load->model('Admin_model');
                $this->session->set_userdata('settings', $this->Admin_model->getAdminSettingsByArray('google_api_key'));

                $returns['success'] = true;
                $returns['msg'] = strip_tags($this->ion_auth->messages());
            } else {
                $returns['success'] = false;
                $returns['msg'] = strip_tags($this->ion_auth->errors());
            }
        }

        echo json_encode($returns);
    }
}
