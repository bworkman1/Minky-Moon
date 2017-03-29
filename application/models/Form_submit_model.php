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

                $this->form_validation->set_rules($input['input_name'], $input['input_label'], $input['input_validation']);
            }

            if($this->paymentTransaction) {

                $_POST['cardNumber'] = str_replace('-', '', $_POST['cardNumber']);

                $this->form_validation->set_rules('cardNumber', 'Credit Card Number', 'required|integer|min_length[16]|max_length[16]');
                $this->form_validation->set_rules('cardExpiry', 'Expiration Date', 'required|min_length[5]|max_length[5]');
                $this->form_validation->set_rules('cardCVC', 'CVC Number', 'required|integer|min_length[2]|max_length[5]');
                $this->form_validation->set_rules('amount', 'Payment Amount', 'required|decimal|min_length[3]|max_length[8]|greater_than_equal_to['.$this->liveFormSettings['min_cost'].']');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->feedback['errors'] = validation_errors_array();
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

            log_message('error', 'There was invalid form data submitted... Seem suspicious?');
            log_message('error', print_r($submittedFields, true));

            return false;
        }
    }

    private function processPayment()
    {

    }

    private function saveFormData()
    {

    }

}