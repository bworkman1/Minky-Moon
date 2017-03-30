<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_submit_model extends CI_Model
{
    private $submittedFormId = 999999;
    private $liveFormSettings;
    private $liveFormInputs;
    private $postValues;
    private $paymentTransaction = false;

    public $feedback = array(
        'msg' => 'Form failed to submit, try again',
        'success' => false,
        'errors' => array(),
    );

    //TODO: Checkboxs and radios not working fix it!

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Form_model');
    }

    public function submitForm($post)
    {
        if(isset($post['form_id']) && (int)$post['form_id'] > 0) {

            $liveForm = $this->Form_model->getFormById($post['form_id']);

            $this->submittedFormId  = $post['form_id'];

            unset($post['form_id']);

            $this->liveFormSettings = $liveForm['form_settings'];
            $this->liveFormInputs   = $liveForm['form_inputs'];
            $this->postValues       = $post;

            if(!empty($this->liveFormSettings) && !empty($this->liveFormInputs)) {
                if($this->validateInputData()) {

                    $this->buildCustomerProfile();

                    if($this->paymentTransaction) {
                        $this->processPayment();
                    } else {
                        $this->saveFormData();
                    }
                }
            } else {
                $this->feedback['msg'] = 'The form was not found, try refreshing your page and try again';
            }
        } else {
            $this->feedback['msg'] = 'Invalid form or the form was not found, try refreshing your page and try again';
        }
    }

    private function validateInputData()
    {
        if($this->doesFormInputsMatch()) {
            foreach($this->liveFormInputs as $input) {
                $needsList = array('checkbox', 'radio', 'select');
                if(isset($input['options']) && !empty($input['options'])) {
                    $listValues = array();
                    if(in_array($input['input_type'], $needsList)) {
                        foreach($input['options'] as $option) {
                            $listValues[] = $option['value'];
                        }
                    }
                    if(!empty($listValues)) {
                        $input['input_validation'] .= $input['input_validation'].'|in_list['.implode(',', $listValues).']';
                    }
                }

                $this->form_validation->set_rules('form['.$input["input_name"].']', $input['input_label'], $input['input_validation']);
            }

            if($this->paymentTransaction) {
                $_POST['form']['cardNumber'] = str_replace('-', '', $_POST['form']['cardNumber']);

                $this->form_validation->set_rules('form[cardNumber]', 'Credit Card Number', 'required|integer|min_length[16]|max_length[16]');
                $this->form_validation->set_rules('form[cardExpiry]', 'Expiration Date', 'required|min_length[5]|max_length[5]');
                $this->form_validation->set_rules('form[cardCVC]', 'CVC Number', 'required|integer|min_length[2]|max_length[5]');
                $this->form_validation->set_rules('form[amount]', 'Payment Amount', 'required|decimal|min_length[3]|max_length[8]|greater_than_equal_to['.$this->liveFormSettings['min_cost'].']');
            }

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors_array();
                $errorsArray = [];
                foreach($errors as $key => $val) {
                    $name = str_replace('form["', '', $key);
                    $name = str_replace('form[', '', $name);
                    $name = str_replace('"]', '', $name);
                    $name = str_replace(']', '', $name);
                    $errorsArray[$name] = $val;
                }
                $this->feedback['msg'] = 'There were some errors on the form. Please fix them and try again';
                $this->feedback['errors'] = $errorsArray;
                return false;
            } else {
                return true;
            }

        }
    }

    private function doesFormInputsMatch()
    {
        $submittedKeys = array_keys($this->postValues);
        $liveKeys = array_keys($this->liveFormInputs);

        if($this->liveFormSettings['min_cost'] > 0) {
            $this->paymentTransaction = true;
            $paymentKeys = array('cardNumber', 'cardExpiry', 'cardCVC', 'amount');
            $liveKeys = array_merge($paymentKeys, $liveKeys);
        }

        $submittedFields = array_diff($submittedKeys, $liveKeys);

        if(empty($submittedFields)) {
            return true;
        } else {
            $this->feedback['msg'] = 'Invalid form data submitted';

            log_message('error', 'There was invalid form data submitted... Seems suspicious?');
            log_message('error', print_r($submittedFields, true));

            return false;
        }
    }

    private function processPayment()
    {
        $this->load->model('Admin_model');
        $adminSettings = $this->Admin_model->getAllAdminSettings();

        $config = array(
            'api_login_id' 			=> $adminSettings['api_key']->value,
            'api_transaction_key' 	=> $adminSettings['auth_key']->value,
        );

        log_message('error', print_r($config, true));



        $this->load->library('authorize_net', $config);

        $auth_net = array(
            'x_card_num'			=> '4111111111111111', // Visa
            'x_exp_date'			=> '12/18',
            'x_card_code'			=> '123',
            'x_description'			=> 'A test transaction',
            'x_amount'				=> '20',
            'x_first_name'			=> 'John',
            'x_last_name'			=> 'Doe',
            'x_address'				=> '123 Green St.',
            'x_city'				=> 'Lexington',
            'x_state'				=> 'KY',
            'x_zip'					=> '40502',
            'x_country'				=> 'US',
            'x_phone'				=> '555-123-4567',
            'x_email'				=> 'test@example.com',
            'x_customer_ip'			=> $this->input->ip_address(),
        );
        $this->authorize_net->setData($auth_net);

        if( $this->authorize_net->authorizeAndCapture() )
        {
//            echo '<h2>Success!</h2>';
//            echo '<p>Transaction ID: ' . $this->authorize_net->getTransactionId() . '</p>';
//            echo '<p>Approval Code: ' . $this->authorize_net->getApprovalCode() . '</p>';
            log_message('error', $this->authorize_net->getTransactionId());
            log_message('error', $this->authorize_net->getApprovalCode());
        }
        else
        {
//            echo '<h2>Fail!</h2>';
//            // Get error
//            echo '<p>' . $this->authorize_net->getError() . '</p>';
//            // Show debug data
//            $this->authorize_net->debug();
            log_message('error', $this->authorize_net->getError());
        }
       // log_message('error', print_r($auth_net, true));
    }

    private function saveFormData()
    {
        
    }

    private function buildCustomerProfile()
    {
        // if(preg_match('(bad|naughty)', $data)
    }

}