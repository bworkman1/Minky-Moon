<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validator_model extends CI_Model
{
    private $formObjectTypes = array(
        'number'        => 'isInteger',
        'alpha'         => 'isAlpha',
        'alpha_space'   => 'isAlphaWSpace',
        'alpha_numeric' => 'isAlphaNumeric',
        'float'         => 'isFloat',
    );
    private $unknownField = false;
    private $invalidField = false;

    public function getValidationRules()
    {
        $query = $this->db->get('mytable');
        return $query->result();
    }

    public function validateFormById($formId, $postData)
    {
        $formData = $this->db->get('SELECT ALL FORM INPUTS');
        if($formData && $postData) {
            foreach($postData as $data) {

                if(array_key_exists($formData->input_type, $this->formObjectTypes)) {
                    if($this->{$this->formObjectTypes[$formData->input_type]} === false) {
                        $this->invalidField = true;
                    }
                } else {
                    $this->unknownField = true;
                }
            }
        }
    }

    public function isInteger($val)
    {
        if (!is_scalar($val) || is_bool($val)) {
            return false;
        }
        if (is_float($val + 0) && ($val + 0) > PHP_INT_MAX) {
            return false;
        }
        return is_float($val) ? false : preg_match('~^((:?+|-)?[0-9]+)$~', $val);
    }

    public function isFloat($val)
    {
        if (!is_scalar($val)) {
            return false;
        }
        return is_float($val + 0);
    }

}