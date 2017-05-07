<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model
{
    private $searchedSubmission;
    public $totalPaymentsSubmitted;
    public $adminSettings;
    public $postValues;
    public $feedback;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('encrypt');
    }

    public function getPaymentByTransactionId($transId)
    {
        if($transId) {
            $query = $this->db->get_where('payments', array('transaction_id' => $transId));
            return $query->row_array();
        }
        return false;
    }

    public function getAllPayments($search, $start, $limit)
    {
        $paymentData = array();

        $this->totalPaymentsSubmitted = $this->getSubmittedCountTotals();

        $sortBy = $this->session->userdata('sort_payments') == 'ASC' ? 'ASC' : 'DESC';

        $where = '';
        $params = array();
        if($search) {
            $this->searchedSubmission = $search;
            $search = '%'.$search.'%';
            $params = array($search, $search);
            $where = ' WHERE customer_id LIKE ? OR transaction_id LIKE ? ';
        }

        $params[] = (int)$start;
        $params[] = (int)$limit;
        $sql = 'SELECT * 
            FROM payments '.$where. '
            ORDER BY date '.$sortBy.' LIMIT ?, ?';

        $query = $this->db->query($sql, $params);

        return $query->result_array();
    }

    private function getSubmittedCountTotals()
    {
        $this->db->select('id');
        $this->db->from('payments');
        return $this->db->count_all_results();
    }

    public function formatSubmittedFormsTable($data, $start)
    {
        if($data) {
            $this->load->library('table');
            $this->table->set_empty("");

            $headings = array('#', 'Billing Name', 'Amount', 'Billing Address', 'Submitted', 'Transaction Id', 'Approval Code', 'Form');
            $this->table->set_heading($headings);
            foreach ($data as $row) {

                $billingName = $this->encrypt->decode($row['billing_name']).' ';
                $billingAddress = $this->encrypt->decode($row['billing_address']).'<br>';
                $billingAddress .= $this->encrypt->decode($row['billing_city']).' ';
                $billingAddress .= $this->encrypt->decode($row['billing_state']).' ';
                $billingAddress .= $this->encrypt->decode($row['billing_zip']);

                $options = '';
                if($row['form_id'] > 0) {
                    $options = ' <a href="' . base_url('forms/view-submitted-form/' . $row['form_id']) . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="View Form"><i class="fa fa-file"></i> </a>';
                }

                if($row['amount'] > 0) {
                    if($row['amount'] < $row['amount']) {
                        $row['amount'] = '$'.number_format($row['amount'], 2).' of $'.number_format($row['cost']);
                    } else {
                        $row['amount'] = '$'.number_format($row['amount'], 2);
                    }
                } else {
                    $row['amount'] = '$0.00';
                }

                if($this->searchedSubmission) {
                    if(strpos($row['transaction_id'], $this->searchedSubmission) !== false) {
                        $row['transaction_id'] = '<span class="highlightedSearch">'.$row['transaction_id'].'</span>';
                    }
                    if(strpos($row['customer_id'], $this->searchedSubmission) !== false) {
                        $row['customer_id'] = '<span class="highlightedSearch">'.$row['customer_id'].'</span>';
                    }
                }
                $start = $start+1;
                $this->table->add_row(
                    array(
                        $start,
                        $billingName,
                        $row['amount'],
                        $billingAddress,
                        date('m/d/Y h:i A', strtotime($row['date'])),
                        $row['transaction_id'],
                        $row['approval_code'],
                        $options,
                    )
                );
            }
            $tmpl = array('table_open' => '<div class="table-responsive"><table class="table table-striped table-bordered table-condensed">', 'table_close' => '</table></div>');
            $this->table->set_template($tmpl);
            $table = $this->table->generate();

        } else {
            $table = '<table class="table table-striped table-bordered">';
            $table .= '<tr>';
            $table .= '<td><div class="alert alert-info">No Results Found</div></td>';
            $table .= '</tr>';
            $table .= '</table>';
        }

        return $table;
    }

    public function paginationResults($limit, $classes = '')
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url('payments/all');
        $config['total_rows'] = $this->totalPaymentsSubmitted;
        $config['full_tag_open'] = '<ul class="pagination '.$classes.'">';
        $config['full_tag_close'] = '</ul>';
        $config['per_page'] = $limit;
        $config['num_links'] = 3;
        $config['uri_segment'] = 3;
        $config['page_query_string'] = false;
        $config['prev_link'] = '&lt; Prev';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next &gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['first_link'] = TRUE;
        $config['last_link'] = TRUE;


        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    public function processPayment()
    {
        $this->load->model('Admin_model');
        $this->adminSettings = $this->Admin_model->getAllAdminSettings();

        $this->postValues['cost'] = 0;
        if($this->postValues['submission_id'] != '') {
            $query = $this->db->get_where('form_data', array('submission_id' => $this->postValues['submission_id']));
            $formValues = $query->row();
            if($formValues) {
                $query = $this->db->get_where('forms', array('id' => $formValues->form_id));
                $formSettings = $query->row();

                $this->postValues['form_id'] = $formValues->form_id;
                $this->postValues['cost'] = $formSettings->cost;
            } else {
                $this->feedback['errors'] = '';
                $this->feedback['msg'] = 'Invalid form id, try selecting the form and trying again';
                return false;
            }
        }

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

        $auth_net = array(
            'x_card_num'			=> str_replace('-', '', $this->postValues['cardNumber']), // Visa
            'x_exp_date'			=> $this->postValues['cardExpiry'],
            'x_card_code'			=> $this->postValues['cardCVC'],
            'x_description'			=> $this->postValues['for'],
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
            $this->logPaymentDetails($this->authorize_net->getTransactionId(), $this->authorize_net->getApprovalCode());
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

    public function logPaymentDetails($transaction_id, $approval_code)
    {
        $data = array(
            'amount'            => $this->postValues['amount'],
            'form_id'           => $this->postValues['form_id'],
            'customer_id'       => $this->postValues['client_number'],
            'form_cost'         => $this->postValues['cost'],
            'billing_name'      => $this->encrypt->encode($this->postValues['billing_name']),
            'billing_address'   => $this->encrypt->encode($this->postValues['billing_address']),
            'billing_city'      => $this->encrypt->encode($this->postValues['billing_city']),
            'billing_state'     => $this->encrypt->encode($this->postValues['billing_state']),
            'billing_zip'       => $this->encrypt->encode($this->postValues['billing_zip']),
            'transaction_id'    => $transaction_id,
            'approval_code'     => $approval_code,
            'submission_id'     => $this->postValues['submission_id'],
        );

        $this->db->insert('payments', $data);
    }

    public function determineClientFields($formData, $submission_id)
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
        );

        if(isset($formData['values']) && !empty($formData['values'])) {
            foreach($formData['values'] as $key => $input) {
                $definedFieldsKeys = array_keys($definedFields);
                foreach($definedFieldsKeys as $field) {
                    similar_text($field, $key, $percent);
                    if($percent > $basePercentThreshold && $definedFields[$field]['percent'] < $percent) {
                        $definedFields[$key]['value'] = $input[0]['value'];
                        $definedFields[$key]['percent'] = $percent;
                    }
                }
            }
        }

        if($definedFields['ssn']['value'] != '' || $definedFields['ssn']['value'] != '') {
            if($definedFields['last_name']['value'] != '') {
                $ssn = $definedFields['ssn']['value'] != '' ? $definedFields['ssn']['value'] : $definedFields['ssn']['value'];
                $last_name = $definedFields['last_name']['value'];
                $last4 = substr($ssn, -4);
                $firstTwo = substr($last_name, 0, 2);
                $definedFields['client_id']['value'] = strtoupper($firstTwo.$last4);
            }
        }

        $payments = $this->getFormPayments($formData, $submission_id);

        $definedFields['amount_paid']['value'] = $payments['paid'];
        $definedFields['amount_left']['value'] = $payments['left'];
        $definedFields['form_name']['value']   = $payments['name'];


        return $definedFields;
    }

    public function getFormPayments($formData, $submission_id)
    {
        $query = $this->db->get_where('payments', array('submission_id' => $submission_id));
        $results = $query->result();
        $paid = 0;
        if(!empty($results)) {

            foreach($results as $row) {
                $paid = $paid + $row->amount;
            }

        }

        $paid = number_format($paid, 2);
        $left = number_format(($formData['form']['form_settings']['cost'] - $paid), 2);

        return array('paid' => $paid, 'left' => $left, 'name' => $formData['form']['form_settings']['name']);
    }

}