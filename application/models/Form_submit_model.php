<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_submit_model extends CI_Model
{
    private $submittedFormId = 999999;
    private $liveFormSettings;
    private $liveFormInputs;
    private $postValues;
    private $paymentTransaction = false;
    private $clientId;
    private $adminSettings;
    private $transaction_id = 0;
    private $approval_code = 0;
    private $submission_id = 0;
    private $isTestMode = false;

    public $feedback = array(
        'msg' => 'Form failed to submit, try again',
        'success' => false,
        'errors' => array(),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Form_model');
        $this->load->library('encrypt');
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

            $this->load->model('Admin_model');
            $this->adminSettings = $this->Admin_model->getAllAdminSettings();

            if(!empty($this->liveFormSettings) && !empty($this->liveFormInputs)) {
                if($this->preventMultipleSubmissions() == false) {
                    if ($this->validateInputData()) {

                        $this->setClientId();

                        $submittedSuccess = false;

                        if ($this->paymentTransaction) {
                            if ($this->processPayment()) {
                                $submittedSuccess = true;
                                $this->saveFormData();
                            }
                        } else {
                            if ($this->saveFormData()) {
                                $submittedSuccess = true;
                            }
                        }

                        if ($submittedSuccess) {
                            $this->sendAdminsEmail();

                            $this->feedback['success'] = true;
                            $this->feedback['msg'] = 'You have successfully submitted the form.';
                            $this->session->set_flashdata('success', $this->feedback['msg']);
                            $this->feedback['data'] = array(
                                'redirect' => base_url('forms/view-submitted-form/' . $this->submission_id),
                                'submission_page' => base_url('view/submitted-form/'.url_title($this->liveFormSettings['name'])),
                            );
                        }
                    }
                } else {
                    $this->feedback['msg'] = 'You have already submitted this form recently. If you need to submit this form again please wait for a while and try again.';
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
                    if(strpos($input['custom_class'], 'input_state') == -1) {
                        if (in_array($input['input_type'], $needsList)) {
                            foreach ($input['options'] as $option) {
                                $listValues[] = $option['value'];
                            }
                        }
                    }
                    if(!empty($listValues)) {
                        $input['input_validation'] .= $input['input_validation'].'|in_list['.implode(',', $listValues).']';
                        $input['input_validation'] = ltrim($input['input_validation'], '|');
                    }

                    if($input["input_type"] == 'checkbox') {
                        $_POST[$input['input_name']] = $input['input_name'].'[]';
                    }
                }

                $this->form_validation->set_rules('form['.$input["input_name"].']', $input['input_label'], $input['input_validation']);
            }

            if($this->paymentTransaction) {
                $_POST['form']['cardNumber'] = str_replace('-', '', $_POST['form']['cardNumber']);

                $this->form_validation->set_rules('form[billing_address]', 'Billing Address', 'required|max_length[100]');
                $this->form_validation->set_rules('form[billing_city]', 'Billing City', 'required|max_length[60]');
                $this->form_validation->set_rules('form[billing_state]', 'Billing State', 'required|alpha|max_length[2]');
                $this->form_validation->set_rules('form[billing_zip]', 'Billing Zip', 'required|integer|min_length[5]|max_length[5]');
                $this->form_validation->set_rules('form[billing_name]', 'Name On Card', 'required|max_length[100]');

                $this->form_validation->set_rules('form[cardNumber]', 'Credit Card Number', 'required|integer|min_length[16]|max_length[16]');
                $this->form_validation->set_rules('form[cardExpiry]', 'Expiration Date', 'required|min_length[5]|max_length[5]');
                $this->form_validation->set_rules('form[cardCVC]', 'CVC Number', 'required|integer|min_length[2]|max_length[5]');

                if($this->liveFormSettings['min_cost'] > 0) {
                    $this->form_validation->set_rules('form[amount]', 'Payment Amount', 'required|decimal|min_length[3]|max_length[8]|less_than_equal_to[' . $this->liveFormSettings['cost'] . ']|greater_than_equal_to[' . $this->liveFormSettings['min_cost'] . ']');
                }
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
            $paymentKeys = array('cardNumber', 'cardExpiry', 'cardCVC', 'amount', 'billing_name', 'billing_address', 'billing_city', 'billing_state', 'billing_zip');
            $liveKeys = array_merge($paymentKeys, $liveKeys);
        }

        $submittedFields = array_diff($submittedKeys, $liveKeys);

        if(empty($submittedFields)) {
            return true;
        } else {
            $this->feedback['msg'] = 'There were an incorrect amount of form inputs submitted, please refresh your screen and trying again.';
            return false;
        }
    }

    private function processPayment()
    {
        $config = array(
            'api_login_id' 			=> $this->adminSettings['api_key']->value,
            'api_transaction_key' 	=> $this->adminSettings['auth_key']->value,
        );

        if($this->adminSettings['authorize_test_mode']->value == 'y') {
            $config['api_url'] = 'https://test.authorize.net/gateway/transact.dll';
        } else {
            $config['api_url'] = 'https://secure.authorize.net/gateway/transact.dll';
        }

        $this->load->library('authorize_net', $config);
        $billingNames = explode(' ', $this->postValues['billing_name']);
        $formName = $this->liveFormSettings['name'];

        $auth_net = array(
            'x_card_num'			=> str_replace('-', '', $this->postValues['cardNumber']), // Visa
            'x_exp_date'			=> $this->postValues['cardExpiry'],
            'x_card_code'			=> $this->postValues['cardCVC'],
            'x_description'			=> 'LAPP Form ('.$formName.')', //todo: lapp payment description ??? What should we set this to
            'x_amount'				=> $this->postValues['amount'],
            'x_first_name'			=> $billingNames[0],
            'x_last_name'			=> isset($billingNames[1]) ? $billingNames[1] : '',
            'x_address'				=> $this->postValues['billing_address'],
            'x_city'				=> $this->postValues['billing_city'],
            'x_state'				=> $this->postValues['billing_state'],
            'x_zip'					=> $this->postValues['billing_state'],
            'x_country'				=> 'US',
            'x_email'				=> isset($this->postValues['email']) ? $this->postValues['email'] : '',
            'x_customer_ip'			=> $this->input->ip_address(),
        );

        $this->authorize_net->setData($auth_net);

        if($this->authorize_net->authorizeAndCapture()) {
            $this->transaction_id = $this->authorize_net->getTransactionId();
            $this->approval_code = $this->authorize_net->getApprovalCode();
            return true;
        } else {
            $this->feedback['msg'] = $this->authorize_net->getError();

            if($this->feedback['msg'] == 'The card code is invalid.') {
                $this->feedback['errors'] = array(
                    'cardCVC' => 'The card code is invalid.'
                );
            } elseif($this->feedback['msg'] == 'The credit card has expired.') {
                $this->feedback['errors'] = array(
                    'cardExpiry' => $this->feedback['msg']
                );
            } else {
                $this->feedback['errors'] = array(
                    'cardNumber' => $this->feedback['msg']
                );
            }

            return false;
        }
    }

    private function logPaymentDetails()
    {
        $paymentData = array(
            'amount'            => $this->postValues['amount'],
            'form_id'           => $this->liveFormSettings['id'],
            'customer_id'       => $this->clientId,
            'form_cost'         => $this->liveFormSettings['cost'],
            'billing_name'      => $this->encrypt->encode($this->postValues['billing_name']),
            'billing_address'   => $this->encrypt->encode($this->postValues['billing_address']),
            'billing_city'      => $this->encrypt->encode($this->postValues['billing_city']),
            'billing_state'     => $this->encrypt->encode($this->postValues['billing_state']),
            'billing_zip'       => $this->encrypt->encode($this->postValues['billing_zip']),
            'transaction_id'    => $this->transaction_id,
            'approval_code'     => $this->approval_code,
            'submission_id'     => $this->submission_id,
        );
        $this->db->insert('payments', $paymentData);
    }

    private function getFormSubmissionId()
    {
        $query = $this->db->query('SELECT submission_id FROM form_data ORDER BY submission_id DESC LIMIT 1');
        $row = $query->row();
        if($row) {
            return ($row->submission_id + 1);
        } else {
            return 1;
        }
        $this->db->from('form_data');
        $this->db->order_by('submission_id', 'desc');
        $this->db->get('form_data');
    }

    private function saveFormData()
    {
        $this->submission_id = $this->getFormSubmissionId();
        $added = date('Y-m-d H:i:s');
        foreach($this->liveFormInputs as $input) {
            if($input['input_type'] == 'checkbox') {
                if(!empty($this->postValues[$input['input_name']]) && is_array($this->postValues[$input['input_name']])) {
                    foreach($this->postValues[$input['input_name']] as $val) {

                        if($input['encrypt_data']) {
                            $val = $this->encrypt->encode($val);
                        }

                        $formInput = array(
                            'customer_id'   => $this->clientId,
                            'value'         => $val,
                            'name'          => $input['input_name'],
                            'added'         => $added,
                            'form_id'       => $this->submittedFormId,
                            'submission_id' => $this->submission_id,
                            'transaction_id'=> $this->transaction_id,
                        );
                        $this->db->insert('form_data', $formInput);
                    }
                }
            } else {
                if($input['encrypt_data']) {
                    $this->postValues[$input['input_name']] = $this->encrypt->encode($this->postValues[$input['input_name']]);
                }

                $formInput = array(
                    'customer_id'   => $this->clientId,
                    'value'         => $this->postValues[$input['input_name']],
                    'name'          => $input['input_name'],
                    'added'         => $added,
                    'form_id'       => $this->submittedFormId,
                    'submission_id' => $this->submission_id,
                    'transaction_id'=> $this->transaction_id,
                );
                $this->db->insert('form_data', $formInput);
            }
        }

        if($this->transaction_id > 0) {
            $this->logPaymentDetails();
        }

        return true;
    }

    private function setClientId()
    {
        // Look for last name input
        $findNames = array('name', 'full_name', 'last', 'last_name');
        $lastNameValue = '';
        $key = '';
        foreach($findNames as $val) {
            if(isset($this->postValues[$val])) {
                $key = $val;
                $lastNameValue = $this->postValues[$val];
            }
        }

        if($key == 'name'|| $key == 'full_name') {
            $nameArray = explode(' ', $lastNameValue);
            $lastNameValue = end($nameArray);
        }

        if($lastNameValue) {
            $lastName2 = substr($lastNameValue, 0, 2);

            // look for ssn input
            $social = '';
            $findSSN = array('ssn', 'social', 'social_security', 'social_security_number');
            foreach ($findSSN as $val) {
                if (isset($this->postValues[$val])) {
                    $key = $val;
                    $social = $this->postValues[$val];
                }
            }

            if($social) {
                $socialLast4 = substr($social, -4);

                $this->clientId = strtoupper($lastName2).$socialLast4;

            } else {
                $this->feedback['msg'] = 'No social security input found';
            }
        } else {
            $this->feedback['msg'] = 'Last name not found, invalid form settings';
        }

    }

    private function sendAdminsEmail()
    {
        if(!$this->isTestMode && !empty($this->adminSettings['emails']->value)) {
            $this->load->library('email');
            $this->email->set_mailtype("html");

            $data['usefulFields']   = $this->determineUsefulFields($this->postValues);
            $data['form_inputs']    = $this->liveFormInputs;
            $data['form_settings']  = $this->liveFormSettings;
            $data['payment']        = $this->paymentTransaction;
            $data['submission_id']  = $this->submission_id;

            $this->email->from(FROM_EMAIL_ADDRESS, FROM_EMAIL_NAME);
            $this->email->to($this->adminSettings['emails']->value);

            $this->email->subject('New Form Submission Received - ' . $this->liveFormSettings['name']);
            $this->email->message($this->load->view('email-templates/new-form-submission-admin', $data, TRUE));
            $this->email->send();

            if ($data['usefulFields']['email']['value'] != '' && !empty($this->liveFormSettings['submission'])) {
                $this->sendUserEmail($data);
            }
        }
    }

    private function sendUserEmail($data)
    {
        $this->email->set_mailtype("html");
        $this->email->to($data['usefulFields']['email']['value']);
        $this->email->from(FROM_EMAIL_ADDRESS, FROM_EMAIL_NAME);

        $this->email->subject('We Received Your Form - ' . $this->liveFormSettings['name']);
        $this->email->message($this->load->view('email-templates/form-successful-email', $data, TRUE));

        $this->email->send();
    }

    private function preventMultipleSubmissions()
    {
        if (!$this->ion_auth->logged_in()) {
            $submittedForms = $this->session->userdata('submitted_forms');
            if (is_array($submittedForms)) {
                if (in_array($this->submittedFormId, $submittedForms)) {
                    return true;
                } else {
                    $submittedForms[] = $this->submittedFormId;
                    $this->session->set_userdata('submitted_forms', $submittedForms);
                    return false;
                }
            } else {
                $this->session->set_userdata('submitted_forms', array($this->submittedFormId));
                return false;
            }
            return false;
        } else {
            return false;
        }
    }

    public function determineUsefulFields($formInputs)
    {
        $basePercentThreshold = 50;
        $definedFields = array(
            'first_name' => array(
                'value' => '',
                'percent' => 0,
            ),
            'address'   => array(
                'value' => '',
                'percent' => 0,
            ),
            'city'      => array(
                'value' => '',
                'percent' => 0,
            ),
            'state'     => array(
                'value' => '',
                'percent' => 0,
            ),
            'zip'       => array(
                'value' => '',
                'percent' => 0,
            ),
            'last_name'      => array(
                'value' => '',
                'percent' => 0,
            ),
            'ssn'      => array(
                'value' => '',
                'percent' => 0,
            ),
            'social'      => array(
                'value' => '',
                'percent' => 0,
            ),
            'social_security_number' => array(
                'value' => '',
                'percent' => 0,
            ),
            'client_id' => array(
                'value' => '',
                'percent' => 100,
            ),
            'amount_left' => array(
                'value' => '',
                'percent' => 100,
            ),
            'amount_paid' => array(
                'value' => '',
                'percent' => 100,
            ),
            'form_name' => array(
                'value' => '',
                'percent' => 100,
            ),
            'email' => array(
                'value' => '',
                'percent' => 90,
            )
        );
        if(!empty($formInputs)) {
            $definedFieldsKeys = array_keys($definedFields);
            foreach($formInputs as $key => $input) {
                foreach($definedFieldsKeys as $field) {
                    similar_text(strtoupper($field), strtoupper($key), $percent);
                    if($percent > $basePercentThreshold) {
                        if($definedFields[$field]['percent'] < $percent) {
                            $definedFields[$key]['value'] = $input;
                            $definedFields[$key]['percent'] = $percent;
                        }
                    }
                }
            }
        }

        if($definedFields['ssn']['value'] != '' || $definedFields['social']['value'] != '' || $definedFields['social_security_number']['value']) {
            if($definedFields['last_name']['value'] != '') {
                if($definedFields['social_security_number']['value']) {
                    $ssn = $definedFields['social_security_number']['value'];
                } else {
                    $ssn = $definedFields['ssn']['value'] != '' ? $definedFields['ssn']['value'] : $definedFields['social']['value'];
                }
                $last_name = $definedFields['last_name']['value'];
                $last4 = substr($ssn, -4);
                $firstTwo = substr($last_name, 0, 2);
                $definedFields['client_id']['value'] = strtoupper($firstTwo.$last4);
            }
        }

        return $definedFields;
    }

}