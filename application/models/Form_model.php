<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_model extends CI_Model
{
    private $formId = 999999;

    public function __construct()
    {
        parent::__construct();
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
form_inputs.input_inline, form_inputs.input_columns, form_inputs.encrypt_data, form_input_options.name, form_input_options.value')->from('form_inputs');
        $this->db->where('form_inputs.form_id', $this->formId);
        $this->db->join('form_input_options', 'form_input_options.form_id = form_inputs.form_id AND form_input_options.input_id = form_inputs.id', 'left');
        $this->db->order_by('form_inputs.sequence', 'asc');

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
                        'encrypt_data' => $val->encrypt_data,
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

        $this->formId = $inputs['form_id'] > 0 ? $inputs['form_id'] : $this->formId;
        $validInput = $this->validateRequiredFields($inputs);
        if($validInput) {
            $data = array(
                'form_id'           => $this->formId,
                'input_name'        => $inputs['name'],
                'input_label'       => $inputs['label'],
                'input_validation'  => $inputs['validations'],
                'custom_class'      => $inputs['classes'],
                'input_type'        => $inputs['type'],
                'input_columns'     => $inputs['columns'],
                'input_inline'      => $inputs['inline']=='yes'?true:false,
                'sequence'          => $inputs['sequence'] != '' ? $inputs['sequence'] : $this->getInputLastSequence(),
                'encrypt_data'      => $inputs['encrypted'],
            );

            $dbType = 'insert';
            $inputId = (isset($inputs['input_id']) && $inputs['input_id'] > 0) ? $inputs['input_id'] : '';
            if(empty($inputId)) {
                $this->db->insert('form_inputs', $data);
                $inputId = $this->db->insert_id();
            } else {
                $this->db->where('id', $inputId);
                $this->db->update('form_inputs', $data);

                $this->db->delete('form_input_options', array('input_id' => $inputId));

                $dbType = 'update';
            }

            if($inputId > 0) {
                if (isset($inputs['extras']) && !empty($inputs['extras']) && $inputId > 0) {
                    foreach ($inputs['extras'] as $key => $val) {
                        $data = array(
                            'name' => $val['label'],
                            'value' => $val['values'],
                            'form_id' => $this->formId,
                            'input_id' => $inputId,
                        );
                        $this->db->insert('form_input_options', $data);
                    }
                }

                $this->reorderFormInputs();

                $pageData['inputs'] = $this->getUnsavedInputs();
                $page = $this->load->view('forms/form-inputs', $pageData, true);
                $returnData['success'] = true;
                $returnData['msg'] = 'Form input saved successfully';

                $returnData['data'] = array('input_id' => $inputId, 'db_type' => $dbType, 'form_id' => $this->formId, 'page' => $page);

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

    public function saveFormValues($data)
    {
        $returns = array(
            'success' => false,
            'msg' => 'Failed to save form',
            'errors' => array(),
        );

        $this->formId = (isset($inputs['form_id']) && $inputs['form_id'] > 0) ? $inputs['form_id'] : $this->formId;
        if($this->formId != 999999) {
            // UPDATE FORM
        } else {
            // Insert form
            $data = array(
                'name'      => $data['name'],
                'cost'      => $data['cost'],
                'min_cost'  => $data['min'],
                'header'    => $data['header'],
                'footer'    => $data['footer'],
                'footer'    => $data['footer'],
                'active'    => $data['active'],
            );
            $this->db->insert('forms', $data);
            $insertId = $this->db->insert_id();

            if($insertId > 0) {
                $this->db->set('form_id', $insertId);
                $this->db->where('form_id', 999999);
                $this->db->update('form_inputs');

                $this->db->set('form_id', $insertId);
                $this->db->where('form_id', 999999);
                $this->db->update('form_input_options');

                $this->session->set_flashdata('success', 'Form saved successfully');
                $returns['msg'] = 'Form saved successfully';
                $returns['success'] = TRUE;
                $returns['data'] = array(
                    'id' => $insertId,
                );
            } else {
                $returns['msg'] = 'Failed to save form, try again';
            }
        }

        return $returns;
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

    public function getSingleFormInput($inputs)
    {
        $this->formId = (isset($inputs['form_id']) && $inputs['form_id'] > 0) ? $inputs['form_id'] : $this->formId;
        $input_id = (isset($inputs['id']) && $inputs['id'] > 0) ? $inputs['id'] : '';

        $returnData = array(
            'success' => false,
            'msg' => 'Something went wrong deleting the input, try again',
            'data' => array(),
        );

        if($input_id > 0) {
            $this->db->select('form_inputs.id, form_inputs.form_id, form_inputs.input_name, form_inputs.input_type, form_inputs.sequence, form_inputs.custom_class, form_inputs.input_label, form_inputs.input_validation,
form_inputs.input_inline, form_inputs.input_columns, form_inputs.encrypt_data, form_input_options.name, form_input_options.value')->from('form_inputs');
            $this->db->where('form_inputs.form_id', $this->formId);
            $this->db->where('form_inputs.id', $input_id);
            $this->db->join('form_input_options', 'form_input_options.form_id = form_inputs.form_id AND form_input_options.input_id = form_inputs.id', 'left');
            $this->db->order_by('form_inputs.sequence', 'desc');

            $data = $this->db->get()->result();
            if ($data) {
                $returnData['data'] = $this->sortInputJoinQuery($data);
                $returnData['msg'] = 'You are now editing the form input';
                $returnData['success'] = true;
            } else {
                $returnData['msg'] = 'Input element not found, try refreshing the page and starting again';
            }
        } else {
            $returnData['msg'] = 'Empty element id, try refreshing the page and starting again';
        }
        return $returnData;

    }

    private function getInputLastSequence()
    {
        $this->db->select('sequence')->from('form_inputs');
        $this->db->where('form_id', $this->formId);
        $this->db->order_by('sequence', 'desc');
        $this->db->limit('1');
        $data = $this->db->get()->result();
        if($data) {
            return $data[0]->sequence+1;
        } else {
            return 1;
        }

    }

    public function getFormById($id)
    {
        $this->db->select('forms.id, forms.name AS form_name, forms.category, forms.header, forms.footer, forms.added, forms.updated, forms.cost, forms.min_cost, forms.active');
        $this->db->select('form_inputs.id AS input_id, form_inputs.input_name, form_inputs.input_type, form_inputs.sequence, form_inputs.custom_class, form_inputs.added, form_inputs.input_label, form_inputs.input_validation, form_inputs.input_inline, form_inputs.input_columns, form_inputs.encrypt_data');
        $this->db->select('form_input_options.id AS options_id, form_input_options.name, form_input_options.value, form_input_options.form_id as options_form_id, form_input_options.input_id AS options_form_id');
        $this->db->from('forms');
        $this->db->join('form_inputs', 'form_inputs.form_id = forms.id', 'left');
        $this->db->join('form_input_options', 'form_input_options.form_id = form_inputs.form_id AND form_input_options.input_id = form_inputs.id', 'left');
        $this->db->where('forms.id', $id);
        $this->db->order_by('form_inputs.sequence', 'asc');
        $data = $this->db->get()->result();

        if($data) {
            $data = $this->sortAllFormData($data, $id);
        }

        return $data;
    }

    private function sortAllFormData($data, $form_id)
    {
        $form = array(
            'form_settings' => array(),
            'form_inputs' => array(),
        );
        if(!empty($data)) {
            $form['form_settings'] = array(
                'id' => $form_id,
                'name' => $data[0]->form_name,
                'category' => $data[0]->category,
                'header' => $data[0]->header,
                'footer' => $data[0]->footer,
                'added' => $data[0]->added,
                'updated' => $data[0]->updated,
                'cost' => $data[0]->cost,
                'min_cost' => $data[0]->min_cost,
                'active' => $data[0]->active,
                'active' => $data[0]->active,
            );

            $inputs = array();
            foreach ($data as $key => $val) {
                if (!array_key_exists($val->input_name, $inputs)) {

                    $inputs[$val->input_name] = array(
                        'input_name' => $val->input_name,
                        'input_type' => $val->input_type,
                        'sequence' => $val->sequence,
                        'custom_class' => $val->custom_class,
                        'added' => $val->added,
                        'input_label' => $val->input_label,
                        'input_validation' => $val->input_validation,
                        'input_inline' => $val->input_inline,
                        'input_columns' => $val->input_columns,
                        'input_id' => $val->input_id,
                        'encrypt_data' => $val->encrypt_data,
                    );

                    if ($val->name != '' && $val->value != '') {
                        $inputs[$val->input_name]['options'][] = array(
                            'name' => $val->name,
                            'value' => $val->value
                        );
                    }

                } else {
                    if ($val->name != '' && $val->value != '') {
                        $inputs[$val->input_name]['options'][] = array(
                            'name' => $val->name,
                            'value' => $val->value
                        );
                    }
                }

                $form['form_inputs'] = $inputs;
            }
        }

        return $form;
    }

    public function getForms()
    {
        $formData = array(
            'form_name' => '',
            'form_fields' => '',
            'submissions' => '',
            'created' => '',
            'updated' => '',
            'cost' => '',
            'active' => '',
        );
        $forms = array();
        $query = $this->db->get('forms');
        foreach ($query->result() as $row) {
            $formData = array(
                'id' => $row->id,
                'form_name' => $row->name,
                'form_fields' => $this->getInputFieldCount($row->id),
                'submissions' => $this->getSubmissionCount($row->id),
                'created' => date('m/d/Y', strtotime($row->added)),
                'updated' => date('m/d/Y', strtotime($row->updated)),
                'cost' => number_format($row->cost, 2),
                'active' => $row->active > 0 ? 'Active' : 'Inactive',
            );

            $forms[] = $formData;
        }
        return $forms;
    }

    private function getInputFieldCount($form_id)
    {
        $this->db->from('form_inputs');
        $this->db->where('form_id', $form_id);
        return $this->db->count_all_results();
    }

    private function getSubmissionCount($form_id)
    {
        // TODO: Once I figure out where/how the form will be saved pull the count from there
//        $this->db->from('form_data');
//        $this->db->where('form_id', $orm_id);
//        $this->db->group_by('form_id');
//        return $this->db->count_all_results();
        return 0;
    }

    public function toggleFormAvailability($post)
    {
        $returns = array(
            'success' => false,
            'msg' => 'Failed to update form',
            'data' => array(),
        );
        if((int)$post['id'] && $post['status']) {
            $status = $post['status'] == 'active' ? 1 : 0;
            $this->db->set('active', $status);
            $this->db->where('id', $post['id']);
            $this->db->update('forms');

            $returns['msg'] = $post['status'] == 'active' ? 'This form has been activated' : 'This form has been deactivated';
            $returns['success'] = true;
            $returns['data'] = array('status' => $status);
        } else {
            $returns['msg'] = 'Id and/or status was not set, try again';
        }

        return $returns;
    }

    private function reorderFormInputs()
    {
        $this->db->select('id');
        $this->db->from('form_inputs');
        $this->db->where('form_id', $this->formId);
        $this->db->order_by('sequence', 'asc');
        $result = $this->db->get()->result();
        if($result) {
            foreach($result as $i => $row) {
                $this->db->where('id', $row->id);
                $this->db->update('form_inputs', array('sequence' => ($i+1)));
            }
        }
    }

}

