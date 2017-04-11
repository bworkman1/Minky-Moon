<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model
{
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

}