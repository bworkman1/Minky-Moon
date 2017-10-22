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

            $this->db->where('name', 'authorize_test_mode');
            $this->db->update('admin_settings', array('value' => $data['test_mode']));

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

    public function saveSecuritySettings($post)
    {
        $return = array(
            'success' => false,
            'msg'     => 'Something went wrong, try again',
            'data'    => array(),
        );
        $validPostNames = array('time', 'failed');
        if($post) {
            $rowData = array(
                'name' => '',
                'value' => '',
                'group_title' => 'Security Settings',
            );
            foreach($post as $key => $val) {
                if(in_array($key, $validPostNames)) {
                    $settingExists = $this->db->select('value')->where('name', $key)->from('admin_settings')->count_all_results();

                    $rowData['name'] = $key;
                    $rowData['value'] = $val;
                    if ($settingExists) {
                        $this->db->where('name', $key);
                        $this->db->update('admin_settings', $rowData);
                    } else {
                        $this->db->insert('admin_settings', $rowData);
                    }
                }
            }

            $return['success'] = true;
            $return ['msg'] = 'Security settings saved successfully';
        }
        return $return;
    }

    public function saveEmailSettings($post)
    {
        $return = array(
            'success' => false,
            'msg'     => 'Something went wrong, try again',
            'data'    => array(),
        );

        $validPostNames = array('submission', 'emails');
        if($post) {
            $rowData = array(
                'name' => '',
                'value' => '',
                'group_title' => 'Email Settings',
            );
            foreach($post as $key => $val) {
                if(in_array($key, $validPostNames)) {
                    $settingExists = $this->db->select('value')->where('name', $key)->from('admin_settings')->count_all_results();
                    $rowData['name'] = $key;
                    $rowData['value'] = $val;
                    if ($settingExists) {
                        $this->db->where('name', $key);
                        $this->db->update('admin_settings', $rowData);
                    } else {
                        $this->db->insert('admin_settings', $rowData);
                    }
                }
            }

            $return['success'] = true;
            $return ['msg'] = 'Email settings saved successfully';
        }
        return $return;
    }

    public function getAdminSettingsByArray($settingNames)
    {
        $data = array();
        if(!empty($settingNames)) {
            $this->db->select('name, value');
            $this->db->from('admin_settings');
            $this->db->where_in('name', $settingNames);
            $result = $this->db->get()->result();
            if($result) {
                foreach($result as $row) {
                    $data[$row->name] = $row->value;
                }
            }
        }
        return $data;
    }

    public function determineHomeScreenUser()
    {
        $userGroups = $this->ion_auth->get_users_groups($this->session->userdata('id'))->result();

        $uriTranslator = array(
            'View Submitted Forms'      => 'forms/form-submissions',
            'View Submitted Payments'   => 'payments/all',
            'Submit Forms Manually'     => 'forms/all-forms',
            'View Forms'                => 'forms/all-forms',
            'Edit Forms'                => 'forms/all-forms',
            'Submit Payments'           => 'payments/submit-payment',
            'Add New Forms'             => 'forms/add-form',
            'Add Users'                 => 'users',
            'Edit Users'                => 'users',
        );

        $isRedirect = $this->session->userdata('not_logged_in');

        if($this->ion_auth->is_admin()) {
            if($isRedirect) {
                $this->session->unset_userdata('not_logged_in');
                return $isRedirect;
            } else {
                return 'forms/form-submissions';
            }
        } else {
            if($userGroups) {
                $highestPosition = count($uriTranslator);
                $urls = array_values($uriTranslator);

                $forceRedirect = false;

                foreach($userGroups as $group) {
                    $index = array_search($group->name, array_keys($uriTranslator));
                    if($index < $highestPosition) {
                        $highestPosition = $index;
                    }

                    if($isRedirect && $group->name == 'View Submitted Forms') {
                        $forceRedirect = true;
                    }
                }

                $this->session->set_userdata('user_home', $urls[$highestPosition]);
                if($forceRedirect) {
                    $this->session->unset_userdata('not_logged_in');
                    return $isRedirect;
                } else {
                    return $urls[$highestPosition];
                }
            }
        }

    }

}