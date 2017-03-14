<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_model extends CI_Model
{

    public function __construct()
    {

    }

    public function getValidationRules()
    {
        $this->db->select('*')->from('form_input_rules');
        $this->db->order_by('parameter', 'asc');
        return $this->db->get()->result();
    }

    public function getUnsavedInputs()
    {
        $this->db->select('form_inputs.id, form_inputs.form_id, form_inputs.input_name, form_inputs.input_type, form_inputs.sequence, form_inputs.custom_class, form_inputs.input_label, form_inputs.input_validation,
form_inputs.input_inline, form_inputs.input_columns, form_input_options.name, form_input_options.value')->from('form_inputs');
        $this->db->where('form_inputs.form_id', 999999);
        $this->db->join('form_input_options', 'form_input_options.form_id = form_inputs.form_id AND form_input_options.input_id = form_inputs.id', 'left');
        $this->db->order_by('form_inputs.sequence', 'desc');

        $data = $this->db->get()->result();
        if($data) {
            $data = $this->sortInputJoinQuery($data);
        }

        return $data;
    }

    private function sortInputJoinQuery($data)
    {
        $output = array();
        if($data) {
            foreach($data as $key => $val) {
                if(!array_key_exists($val->id, $output)) {
                    $output[$val->id] = array(
                        'id'            => $val->id,
                        'form_id'       => $val->form_id,
                        'input_name'    => $val->input_name,
                        'input_type'    => $val->input_type,
                        'sequence'      => $val->sequence,
                        'custom_class'  => $val->custom_class,
                        'input_label'   => $val->input_label,
                        'input_validation' => $val->input_validation,
                        'input_inline'  => $val->input_inline,
                        'input_columns' => $val->input_columns,
                    );

                    if($val->name != '') {
                        $output[$val->id]['options'][] = array(
                            'name' => $val->name,
                            'value'=> $val->value
                        );
                    }
                } else {
                    $output[$val->id]['options'][] = array(
                        'name' => $val->name,
                        'value'=> $val->value
                    );
                }
            }
        }
        return $output;
    }

    public function checkForValidValidation($rawData)
    {
        $validationSettings = $this->getValidationRules();
        $invalidData = array();
        $inputs = 0;
        $validationString = '';
        $return = array(
            'success' => false,
            'msg' => '',
            'data' => array(),
        );

        if ($rawData) {
            foreach ($rawData as $key => $val) {
                if ($val != '') {
                    $validate = $val;
                    $paramsArray = explode('_', $key);

                    foreach ($validationSettings as $row) {
                        if ($row->type == $val && $row->parameter) {
                            if ($rawData['parameter_' . $paramsArray[1]] == '') {
                                $invalidData['parameter_' . $paramsArray[1]] = 'Required';
                            } else {
                                $validate = $validate.'['.$rawData['parameter_' . $paramsArray[1]].']';
                            }
                        }
                    }

                    if($paramsArray[0] == 'type') {
                        $validationString .= $validate . '|';
                        $inputs++;
                    }
                }
            }

            if (empty($invalidData)) {
                if($inputs > 0) {
                    $return['success'] = true;
                    $return['data'] = array('rule' => rtrim($validationString, '|'));
                    $return['msg'] = 'Form validation rules set';
                } else {
                    $return['msg'] = 'You must select a validation rule in order to set one';
                }
            } else {
                $return['errors'] = $invalidData;
                $return['msg'] = 'There were errors processing the values, see red boxes above';
            }
        } else {
            $return['msg'] = 'You must select at least one type of validation rule';
        }
        return $return;
    }

    public function addNewFormInput($inputs)
    {
        $returnData = array(
            'success' => false,
            'msg' => 'Something went wrong adding the input, try again',
            'data' => '',
        );
        $validInput = $this->validateRequiredFields($inputs);
        if($validInput) {
            $data = array(
                'form_id'       => 999999, //Todo: check for form input
                'input_name'    => $_POST['name'],
                'input_label'   => $_POST['label'],
                'input_validation' => $_POST['validations'],
                'custom_class'  => $_POST['classes'],
                'input_type'    => $_POST['type'],
                'input_columns' => $_POST['columns'],
                'input_inline'  => $_POST['inline']=='yes'?true:false,
            );
            $this->db->insert('form_inputs', $data);
            $inputId = $this->db->insert_id();

            if($inputId > 0 ) {
                if (isset($_POST['extras']) && !empty($_POST['extras']) && $inputId > 0) {
                    foreach ($_POST['extras'] as $key => $val) {
                        $data = array(
                            'name' => $val['label'],
                            'value' => $val['values'],
                            'form_id' => 999999,//Todo: check for form input
                            'input_id' => $inputId
                        );
                        $this->db->insert('form_input_options', $data);
                    }
                }

                $returnData['success'] = true;
                $returnData['msg'] = 'Form input saved successfully';
                $returnData['data'] = array('input_id' => $inputId);

            } else {
                $returnData['msg'] = 'Failed to insert the data in the database, try again';
            }
        } else {
            $returnData['msg'] = 'There seems to be a problem with the input data, try again';
        }

        return $returnData;
    }

    private function validateRequiredFields($inputs)
    {
        $validInputs = true;
        $required = array('label', 'name', 'type', 'columns');
        $extraInputsRequired = array('select', 'checkbox', 'radio');

        if(!empty($inputs)) {
            foreach ($inputs as $key => $val) {
                if (in_array($key, $required)) {
                    if ($val == '') {
                        $validInputs = false;
                    }
                }

                if ($key == 'type' && in_array($val, $extraInputsRequired)) {
                    if (!isset($inputs['extras']) && empty($inputs['extras'])) {
                        $validInputs = false;
                    }
                }
            }
        } else {
            $validInputs = false;
        }

        return $validInputs;

    }

    public function deleteInput($inputs)
    {
        $returnData = array(
            'success' => false,
            'msg' => 'Something went wrong deleting the input, try again',
        );

        if($inputs['id'] > 0) {
            $this->db->delete('form_inputs', array('id' => $inputs['id']));
            $this->db->delete('form_input_options', array('input_id' => $inputs['id']));
            $returnData['success'] = true;
            $returnData['msg'] = 'Form input deleted';
        }

        return $returnData;
    }
}

