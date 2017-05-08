<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->in_group('admin')) {
            redirect('request-error/access-not-allowed');
            exit;
        }
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
        $this->load->js('assets/themes/lapp/js/notify.min.js');
        $this->load->js('assets/themes/admin/build/js/custom.js');
        $this->load->js('assets/themes/lapp/js/app.js');

        $this->output->set_template('admin-left-menu');
    }

    public function index()
    {
        $this->init_page();
        $this->load->css('assets/themes/admin/css/alertify/alertify.core.css');
        $this->load->css('assets/themes/admin/css/alertify/alertify.default.css');

        $this->load->js('assets/themes/admin/js/alertify/alertify.min.js');

        $data['users'] = $this->ion_auth->users()->result();
        $this->load->view('admin/all-users', $data);
    }

    public function add_user()
    {
        $this->init_page();

        $data['groups'] = $this->ion_auth->groups()->result();
        $this->load->view('admin/add-user', $data);
    }

    public function edit_user()
    {
        $id = (int)$this->uri->segment(3);
        if($id>0) {
            $this->init_page();
            $data['user'] = $this->ion_auth->user($id)->row();
            $data['groups'] = $this->ion_auth->groups()->result();
            $userGroups = $this->ion_auth->get_users_groups($id)->result();
            if($userGroups) {
                $data['user_groups'] = array();
                foreach($userGroups as $group) {
                    $data['user_groups'][] = $group->id;
                }
            }
            $this->load->view('admin/edit-user', $data);
        } else {
            $this->session->set_flashdata('error', 'Invalid User Id');
            redirect('users');
            exit;
        }
    }

    public function save_edit_user()
    {
        $id = (int)$this->uri->segment(3);
        if($id>0) {
            $user = $this->ion_auth->user($id)->row();
            $userGroup = $this->ion_auth->get_users_groups($user->id)->result();

            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $data = array(
                'first_name'    => $this->input->post('first_name'),
                'last_name'     => $this->input->post('last_name'),
                'email'         => $this->input->post('email'),
                'username'      => $this->input->post('username'),
                'group'         => $this->input->post('access'),
            );

            $this->form_validation->set_rules('first_name', 'First Name', 'required|alpha|max_length[30]|trim');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|alpha|max_length[30]|trim');

            if($user->email != $email) {
                $this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[users.email]|trim');
            }
            if($user->username != $username) {
                $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|is_unique[users.username]|trim');
            }
            $this->form_validation->set_rules('access[]', 'Access Level', 'required|integer|trim');

            $this->form_validation->set_message('is_unique', '{field} is already taken');
            $this->form_validation->set_message('regex_match', 'Password doesn\'t match the required strength');

            $returns = array(
                'success' => false,
                'msg' => 'Invalid username or password',
            );

            if ($this->form_validation->run() == true) {
                if($this->ion_auth->update($id, $data) !== false) {
                    if($userGroup) {
                        foreach($userGroup as $group) {
                            $this->ion_auth->remove_from_group($group->id, $user->id);
                        }
                        $this->ion_auth->add_to_group($this->input->post('access'), $user->id);
                    }

                    $returns['msg'] = strip_tags($this->ion_auth->messages());
                    $this->session->set_flashdata('success', strip_tags($this->ion_auth->messages()));
                    $returns['redirect'] = base_url('users');
                    $returns['success'] = true;
                } else {
                    $returns['msg'] = $this->ion_auth->errors();
                }
            } else {
                $this->form_validation->set_error_delimiters('', '');
                $returns['msg'] = 'Failed to add user, see errors below';
                $returns['errors'] = validation_errors_array();

                if(isset($returns['errors']['password1'])) {
                    //unset($returns['errors']['password1']);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid User Id');
            redirect('users');
            exit;
        }
        echo json_encode($returns);
    }

    public function delete_user()
    {
        $id = (int)$this->uri->segment(3);
        $returns = array(
            'success' => false,
            'msg' => 'Failed to delete user',
        );

        if($id>0) {
            if($this->ion_auth->delete_user($id)) {
                $returns['success'] = true;
                $returns['msg'] = strip_tags($this->ion_auth->messages());
            } else {
                $returns['msg'] = $this->ion_auth->errors();
            }
        } else {
            $returns['msg'] = 'Invalid user id';
        }

        echo json_encode($returns);
    }

    public function ajax_submit_user()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required|alpha|max_length[30]|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|alpha|max_length[30]|trim');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[users.email]|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|is_unique[users.username]|trim');
        $this->form_validation->set_rules('access[]', 'Access Level', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|regex_match[/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*(?=\S*[\W])/]|matches[password1]|trim|min_length[8]|max_length[20]');
        $this->form_validation->set_rules('password1', 'Confirm Password', 'required|regex_match[/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*(?=\S*[\W])/]|trim');

        $this->form_validation->set_message('matches', 'Passwords don\'t match');
        $this->form_validation->set_message('is_unique', '{field} is already taken');
        $this->form_validation->set_message('regex_match', 'Password doesn\'t match the required strength');

        $returns = array(
            'success' => false,
            'msg' => 'Invalid username or password',
        );

        if ($this->form_validation->run() == true) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $email = $this->input->post('email');
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
            );
            $group = array($this->input->post('access'));
            if($this->ion_auth->register($username, $password, $email, $additional_data, $group) !== false) {
                $returns['msg'] = strip_tags($this->ion_auth->messages());
                $this->session->set_flashdata('success', strip_tags($this->ion_auth->messages()));
                $returns['redirect'] = base_url('users');
                $returns['success'] = true;
            } else {
                $returns['msg'] = $this->ion_auth->errors();
            }
        } else {
            $this->form_validation->set_error_delimiters('', '');
            $returns['msg'] = 'Failed to add user, see errors below';
            $returns['errors'] = validation_errors_array();

            if(isset($returns['errors']['password1'])) {
                //unset($returns['errors']['password1']);
            }
        }

        echo json_encode($returns);
    }

    public function reset_login_attempts()
    {
        $id = (int)$this->uri->segment(3);
        if($id>0) {
            $user = $this->ion_auth->user($id)->row();
            if($user) {
                $this->ion_auth->clear_login_attempts($user->username);

                $results = array(
                    'success' => true,
                    'msg' => 'Login attempts for ' . $user->first_name . ' ' . $user->last_name . ' have been reset'
                );
            } else {
                $results = array(
                    'success' => false,
                    'msg' => 'User not found, try again'
                );
            }
        } else {
            $results = array(
                'success' => false,
                'msg' => 'Invalid user id, try again'
            );
        }
        echo json_encode($results);
    }

    public function reset_password()
    {
        $id = (int)$this->uri->segment(3);
        $results = array(
            'success' => false,
            'msg' => 'Failed to reset user password',
        );
        if($id>0) {
            $user = $this->ion_auth->user($id)->row();
            $this->form_validation->set_rules('password', 'Password', 'required|regex_match[/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*(?=\S*[\W])/]|trim|min_length[8]|max_length[20]');
            $this->form_validation->set_message('regex_match', 'Password doesn\'t match the required strength');

            if ($this->form_validation->run() == true) {

                $password = $this->input->post('password');
                if ($user) {
                    if ($this->ion_auth->update($id, array('password' => $password))) {
                        $results = array(
                            'success' => true,
                            'msg' => 'Password for ' . $user->first_name . ' ' . $user->last_name . ' have been reset'
                        );
                    } else {
                        $results = array(
                            'success' => false,
                            'msg' => $this->ion_auth->errors()
                        );
                    }
                } else {
                    $results = array(
                        'success' => false,
                        'msg' => 'User not found, try again'
                    );
                }
            } else {
                $this->form_validation->set_error_delimiters('<div>', '</div>');
                $results['msg'] = validation_errors();
            }
        } else {
            $results['msg'] = 'Invalid user id';
        }
        echo json_encode($results);
    }

}