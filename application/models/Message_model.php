<?php

class Message_model extends CI_Model
{

    public $senderType = 'customer';
    public $receiverId = 0;
    private $validateFailedMessage;

    function __construct()
    {
        parent::__construct();

        $this->feedback = array(
            'success' => false,
            'msg' => 'Message invalid, try again',
            'data' => array(),
            'post' => array(),
        );
    }


    public function sendMessage($data)
    {
        if($this->validateMessage(filter_var($data['data']['message'], FILTER_SANITIZE_STRING))) {
            $message = array(
                'sender_id' => $this->session->userdata('user_id'),
                'sender_type' => $this->senderType,
                'message' => filter_var($data['data']['message'], FILTER_SANITIZE_STRING),
                'order_number' => $data['data']['order_num'],
                'receiver_id' => $this->receiverId,
            );

            $this->db->insert('messages', $message);
            if ($this->db->insert_id() > 0) {
                $this->load->model('Email_model');
                $this->Email_model->sendEmailMessage($message);

                $this->feedback['msg'] = 'Message sent successfully';
                $this->feedback['success'] = true;
                $this->feedback['data']['transaction_id'] = $data['data']['order_num'];
            } else {
                $this->feedback['msg'] = 'Failed to send message, please try again';
            }
        } else {
            $this->feedback['msg'] = $this->validateFailedMessage;
        }
        return $this->feedback;
    }

    private function validateMessage($message)
    {
        if(strlen($message) > 5 && strlen($message) < 501) {
            return true;
        }
        $this->validateFailedMessage = 'Message must be between 5 and 500 characters long (currently '.strlen($message).')';
        return false;
    }

    public function getMessagesByTransactionNumber($data)
    {
        $offset = 0;
        $orderNumber = $data['order_num'];
        $this->db->order_by('ts', 'asc');
        $returnData = $this->db->get_where('messages', array('order_number' => $orderNumber))->result();
        if(!empty($returnData)) {
            $data = array();
            $users = array();
            foreach($returnData as $row) {

                if(!isset($users[$row->sender_id])) {
                    $users[$row->sender_id] = $this->ion_auth->user($row->sender_id)->row();
                }
                $data[] = array(
                    'id' => $row->id,
                    'messsage' => $row->message,
                    'date' => date('m/d/Y', strtotime($row->ts."+".$offset."hours")),
                    'time' => date('h:i a', strtotime($row->ts."+".$offset."hours")),
                    'type' => $row->sender_type,
                    'name' => $row->sender_id == $this->session->userdata('user_id') ? 'Me' : $users[$row->sender_id]->first_name.' '.$users[$row->sender_id]->last_name,
                    'transaction_id' => $row->order_number
                );
            }
            $this->updateMessagesAsRead($orderNumber);

            $this->feedback['data'] = $data;
        }
        $this->feedback['success'] = true;
        $this->feedback['msg'] = '';

        return $this->feedback;
    }

    public function updateMessagesAsRead($transactionId)
    {
        $now = date('Y-m-d H:i:s');
        if($this->ion_auth->in_group('admin')) {
            $data = array($now, 0, $transactionId);
            $sql = 'UPDATE messages SET receiver_viewed = ? WHERE receiver_id = ? AND order_number = ? AND receiver_viewed IS NULL';
        } else {
            $data = array($now, $this->session->userdata('user_id'), $transactionId);
            $sql = 'UPDATE messages SET receiver_viewed = ? WHERE receiver_id = ? AND order_number = ? AND receiver_viewed IS NULL';
        }
        $this->db->query($sql, $data);
    }
}