<?php

class Account_model extends CI_Model
{
    private $feedback;
    /**
     * ERROR CODES START WITH 600 - 625 FOR THIS MODAL
     */
    function __construct()
    {
        parent::__construct();

        $this->feedback = array(
            'success' => false,
            'msg' => 'Invalid username or password',
            'data' => array(),
            'post' => array(),
        );
    }

    public function process()
    {
        $hasErrors = false;

        $data = $this->input->post('data');
        if(!empty($data) && $data['type']) {
            if(!isset($data['data']['email']) || !isset($data['data']['password'])) {
                $hasErrors = true;
            }

            if(!$this->isValidEmail($data['data']['email'])) {
                $hasErrors = true;
            }

            if($data['type'] == 'login' && $hasErrors == false) {
                $this->checkLoginDetails($data['data']['email'], $data['data']['password']);
            } elseif($data['type'] == 'createAccount' && $hasErrors == false) {
                if($this->checkPassword($data['data']['password'], $data['data']['password2'])) {
                    $this->createUserAccount($data['data']);
                }
            }
        }

        return $this->feedback;
    }


    public function isValidEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->feedback['msg'] = 'Invalid email address';
            return false;
        }

        return true;
    }


    /*
     * PASSWORD MUST BE 8 CHARS LONG AND AT LEAST ONE LETTER AND ONE NUMBER
     */
    public function checkPassword($pwd, $confirm = false) {
        if (strlen($pwd) < 8) {
            $this->feedback['msg'] = "Password must be at least 8 characters long and include one letter and one number";
            return false;
        }

        if (!preg_match("#[0-9]+#", $pwd)) {
            $this->feedback['msg'] = "Password must include at least one number!";
            return false;
        }

        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $this->feedback['msg'] = "Password must include at least one letter!";
            return false;
        }

        if($confirm !== false) {
            if($pwd != $confirm) {
                $this->feedback['msg'] = "Passwords don't match";
                return false;
            }
        }

        return true;
    }

    private function checkLoginDetails($email, $password)
    {
        $remember = false;
        if ($this->ion_auth->login($email, $password, $remember)) {
            foreach($this->ion_auth->user()->row() as $key => $val) {
                if($key != 'password' || $key != 'salt') {
                    $this->session->set_userdata($key, $val);
                }
            }

            $this->feedback['success'] = true;
            $this->feedback['msg'] = strip_tags($this->ion_auth->messages());
            $this->session->set_flashdata('success', $this->feedback['msg']);
        } else {
            $this->feedback['msg'] = strip_tags($this->ion_auth->errors());
        }
    }

    private function createUserAccount($data)
    {
        $username = $data['email'];
        $password = $data['password'];
        $email = $data['email'];

        $group = array('13'); // Sets user to customer group.

        if (!$this->ion_auth->username_check($username) && !$this->ion_auth->email_check($email)) {
            if($this->ion_auth->register($username, $password, $email, array(), $group)) {

                $this->checkLoginDetails($email, $password);

                $this->feedback['success'] = true;
                $this->feedback['msg'] = 'Account created successfully';

                $this->session->set_flashdata = $this->feedback['msg'];
            } else {
                $this->feedback['msg'] = 'Failed to create an account, try refreshing the page and trying again. If the problem persists please contact us and give us the following error code. ERROR CODE #605';
            }
        } else {
            $this->feedback['msg'] = 'Email already registered, please login';
        }
    }

}