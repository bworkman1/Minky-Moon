<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    private $formId = 999999;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('encrypt');
    }

    public function getAllAdminSettings()
    {
        $data = array();
        $query = $this->db->get('admin_settings');
        $results = $query->result();
        if($results) {
            foreach ($results as $row) {
                if ($row->name == 'api_key' || $row->name == 'auth_key') {
                    $row->value = $this->encrypt->decode($row->value);
                }
                $data[$row->name] = $row;
            }
        }
        return $data;
    }

    public function saveAuthorizeSettings($data)
    {
        $return = array(
            'success' => false,
            'msg'     => '',
            'data'    => array(),
        );

        if($data['type'] == 'remove') {

            $this->db->set('value', '');
            $this->db->where('name', 'api_key');
            $this->db->or_where('name', 'auth_key');
            $this->db->update('admin_settings');

            $return['success'] = true;
            $return['msg'] = 'Authorize settings removed';

        } else {

            $insertApi = array(
                'name' => 'api_key',
                'value' => $this->encrypt->encode($data['api_key']),
                'group_title' => 'Authorize Settings',
            );
            $insertKey = array(
                'name' => 'auth_key',
                'value' => $this->encrypt->encode($data['auth_key']),
                'group_title' => 'Authorize Settings',
            );

            $apiKeyPresent = $this->db->select('value')->where('name', 'api_key')->from('admin_settings')->count_all_results();
            $authKeyPresent = $this->db->select('value')->where('name', 'auth_key')->from('admin_settings')->count_all_results();

            if($apiKeyPresent == 0) {
                $this->db->insert('admin_settings', $insertApi);
            } else {
                $this->db->where('name', 'api_key');
                $this->db->update('admin_settings', $insertApi);
            }

            if($authKeyPresent == 0) {
                $this->db->insert('admin_settings', $insertKey);
            } else {
                $this->db->where('name', 'auth_key');
                $this->db->update('admin_settings', $insertKey);
            }

            $return['success'] = true;
            $return['msg'] = 'Authorize settings saved';
        }

        return $return;
    }

}