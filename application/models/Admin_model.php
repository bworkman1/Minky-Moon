<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    private $formId = 999999;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllAdminSettings()
    {
        $query = $this->db->get('admin_settings');
        return $query->result();
    }

}