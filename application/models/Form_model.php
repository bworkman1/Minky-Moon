<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_model extends CI_Model
{
    public $formId = 999999;
    private $totalFormsSubmitted;
    private $searchedSubmission;

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

    public function getSavedInputs()
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
            $added = date('Y-m-d');
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
                $data['added'] = $added;
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

                $pageData['inputs'] = $this->getSavedInputs();
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

        $this->formId = (isset($data['form_id']) && $data['form_id'] > 0) ? $data['form_id'] : $this->formId;
        $ready = $this->isNeededInputsAddedBeforeSave();
        if($ready === true) {
            $added = date('Y-m-d');
            if ($this->formId != 999999) {
                // UPDATE FORM
                $data = array(
                    'name'      => $data['name'],
                    'cost'      => $data['cost'],
                    'min_cost'  => $data['min'],
                    'header'    => $data['header'],
                    'footer'    => $data['footer'],
                    'footer'    => $data['footer'],
                    'submission' => $data['submission'],
                    'updated'   => date('Y-m-D H:i:s'),
                    'active'    => $data['active'],
                    'added'     => $added,
                );

                $this->db->where('id', $this->formId);
                $this->db->update('forms', $data);

                $returns['msg'] = 'Form saved successfully';
                $returns['success'] = TRUE;
                $returns['data'] = array(
                    'id' => $this->formId,
                );

            } else {
                // Insert form
                $data = array(
                    'name' => $data['name'],
                    'cost' => $data['cost'],
                    'min_cost' => $data['min'],
                    'header' => $data['header'],
                    'footer' => $data['footer'],
                    'submission' => $data['submission'],
                    'active' => $data['active'] != '' ? 1 : 0,
                    'updated' => date('Y-m-D H:i:s'),
                    'added'  => $added,
                );
                $this->db->insert('forms', $data);
                $insertId = $this->db->insert_id();

                if ($insertId > 0) {
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
        } else {
            $returns['msg'] = $ready;
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
        $this->db->select('forms.id, forms.name AS form_name, forms.submission, forms.category, forms.header, forms.footer, forms.added, forms.updated, forms.cost, forms.min_cost, forms.active, forms.version');
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

    private function getFormSettings($form_id)
    {
        $data = array();
        $this->db->where('id', $form_id);
        $results = $this->db->get('forms')->row_array();
        if($results) {
            $data = $results;
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
                'submission' => $data[0]->submission,
                'added' => $data[0]->added,
                'updated' => $data[0]->updated,
                'cost' => $data[0]->cost,
                'min_cost' => $data[0]->min_cost,
                'active' => $data[0]->active,
                'active' => $data[0]->active,
                'version' => $data[0]->version,
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

    public function getForms($filtered = false)
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
        $this->db->where('deleted != ', true);
        if($filtered) {
            $this->db->where('active', 1);
        }
        $query = $this->db->get('forms');
        foreach ($query->result() as $row) {
            $formData = array(
                'id' => $row->id,
                'form_name' => $row->name,
                'form_fields' => $this->getInputFieldCount($row->id),
                'submissions' => $this->getSubmissionCount($row->id),
                'created' => date('m/d/Y', strtotime($row->added)),
                'updated' => date('m/d/Y', strtotime($row->updated)),
                'version' => $row->version,
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
        $this->db->from('form_data');
        $this->db->where('form_id', $form_id);
        $this->db->group_by('submission_id');

        return $this->db->count_all_results();
    }

    public function toggleFormAvailability($post)
    {
        $returns = array(
            'success' => false,
            'msg' => 'Failed to update form',
            'data' => array(),
        );
        if((int)$post['id'] && $post['status']) {
            $is_set = $this->is_clientIdFormsSet($post['id']);
            if($is_set === true) {
                $status = $post['status'] == 'active' ? 1 : 0;

                $this->db->set('active', $status);
                $this->db->where('id', $post['id']);
                $this->db->update('forms');

                $returns['msg'] = $post['status'] == 'active' ? 'This form has been activated' : 'This form has been deactivated';
                $returns['success'] = true;
                $returns['data'] = array('status' => $status);
            } else {
                $returns['msg'] = $is_set;
            }
        } else {
            $returns['msg'] = 'Id and/or status was not set, try again';
        }
        return $returns;
    }

    public function isNeededInputsAddedBeforeSave()
    {
        $formInputs = $this->getSavedInputs();

        $findNames = array('name', 'full_name', 'last', 'last_name');
        $findSSN = array('ssn', 'social', 'social_security', 'social_security_number');

        $foundSSN = false;
        $foundName = false;
        $errorMsg = true;

        if($formInputs) {
            foreach($formInputs as $input) {

                if(in_array($input['input_name'], $findNames)) {
                    $foundName = true;
                }
                if(in_array($input['input_name'], $findSSN)) {
                    $foundSSN = true;
                }
            }

            if($foundName == false) {
                $errorMsg .= 'Could\'nt find a for input for last name. Please add an input for last name or full name';
            }
            if($foundSSN == false) {
                $errorMsg = 'Could\'nt find a for input for social security number. Please add an input with the name of Social Security';
            }
        } else {
            $errorMsg = 'No form found';
        }

        return $errorMsg;

    }

    public function is_clientIdFormsSet($form_id)
    {
        $findNames = array('name', 'full_name', 'last', 'last_name');
        $findSSN = array('ssn', 'social', 'social_security', 'social_security_number');

        $foundSSN = false;
        $foundName = false;
        $errorMsg = true;

        $form = $this->getFormById($form_id);
        if($form) {
            foreach($form['form_inputs'] as $key => $input) {
                if(in_array($key, $findNames)) {
                    $foundName = true;
                }
                if(in_array($key, $findSSN)) {
                    $foundSSN = true;
                }
            }

            if($foundName == false) {
                $errorMsg .= 'Could\'nt find a for input for last name. Please add an input for last name or full name';
            }
            if($foundSSN == false) {
                $errorMsg = 'Could\'nt find a for input for social security number. Please add an input with the name of Social Security';
            }
        } else {
            $errorMsg = 'No form found';
        }

        return $errorMsg;
    }

    private function reorderFormInputs()
    {
        $this->db->select('id');
        $this->db->from('form_inputs');
        $this->db->where('form_id', $this->formId);
        $this->db->order_by('sequence', 'asc');
        $this->db->order_by('updated', 'desc');
        $result = $this->db->get()->result();
        if($result) {
            foreach($result as $i => $row) {
                $this->db->where('id', $row->id);
                $this->db->update('form_inputs', array('sequence' => ($i+1)));
            }
        }
    }

    public function getSubmittedForms($search, $start, $limit)
    {
        $formData = array();

        $this->totalFormsSubmitted = $this->getSubmittedCountTotals();

        $order_by = $this->session->userdata('order_by') ? $this->session->userdata('order_by') : 'submission_id';
        $form_direction = $this->session->userdata('form_direction') ? $this->session->userdata('form_direction') : 'DESC';

        $where = '';
        $params = array();
        if($search) {
            $this->searchedSubmission = $search;
            $search = '%'.$search.'%';
            $params = array($search, $search, $search);
            $where = ' WHERE (form_data.value LIKE ? OR form_data.customer_id LIKE ? OR form_data.transaction_id LIKE ? ';
        }

        $params[] = (int)$start;
        $params[] = (int)$limit;

        $sql = 'SELECT form_data.submission_id, form_data.customer_id, form_data.form_id, form_data.added, form_data.transaction_id, form_data.viewed
            FROM form_data
                 '.$where.' ';

        if($this->session->userdata('search_form_submission_name') > 0) {
            if($where) {
                $sql .= ' AND form_data.form_id = ' . $this->session->userdata('search_form_submission_name') . ' ';
            } else {
                $sql .= ' WHERE (form_data.form_id = ' . $this->session->userdata('search_form_submission_name') . ' ';
            }
        }

        if(strpos($sql, 'WHERE') === false) {
            $sql .= ' WHERE deleted != 1 ';
        } else {
            $sql .= ') AND deleted != 1 ';
        }

        $sql .= 'GROUP BY submission_id, added, customer_id, form_id, transaction_id, viewed  
            ORDER BY '.$order_by.' '.$form_direction.' LIMIT ?, ?';

        $query = $this->db->query($sql, $params);
        $payments = $this->sortPaymentData($query->result_array());

        $formData = array();
        foreach ($payments as $row) {
            $row['total_submitted'] = $this->totalFormsSubmitted;
            $row['submitted'] = $row['added'];

            if(!isset($formData[$row['form_id']])) {
                $formData[$row['form_id']] = $this->getFormSettings($row['form_id']);
            }

            $formSettings = $formData[$row['form_id']];
            $formSettings['name'] = substr($formSettings['name'], 0, 50);

            $row = array_merge($row, $formSettings);
            $formData[$row['submission_id']] = $row;
        }
        return $formData;
    }

    public function sortPaymentData($data)
    {
        $return = array();
        if($data) {
            foreach($data as $row) {
                if(!isset($return[$row['submission_id']])) {
                    $payment_data = $this->getTotalFormPayments($row['submission_id'], $row['form_id']);
                    $return[$row['submission_id']] = array(
                        'submission_id'     => $row['submission_id'],
                        'customer_id'       => $row['customer_id'],
                        'form_id'           => $row['form_id'],
                        'added'             => $row['added'],
                        'transaction_id'    => $row['transaction_id'] == '' ? 'NA' : $row['transaction_id'],
                        'amount'            => $payment_data[0]['amount'],
                        'viewed'            => $row['viewed'],
                        'payments_made'     => $payment_data[0]['total'],
                    );
                }
            }
        }
        return $return;
    }

    private function getTotalFormPayments($submission_id, $form_id)
    {
        $sql = 'SELECT SUM(amount) as amount, count(id) AS total
                FROM payments
                WHERE form_id = '.$form_id.' AND submission_id = '.$submission_id;

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    public function formatSubmittedFormsTable($data, $start)
    {
        if($data) {
            $this->load->library('table');
            $this->table->set_empty("");

            $order_by = $this->session->userdata('order_by');
            $form_direction = $this->session->userdata('form_direction');

            $icons = array(
                'DESC' => ' <i class="fa fa-caret-down fa-fw pull-right text-warning sortFormBy" data-direction="INSERT_DIRECTION" data-order="INSERT_ORDER"></i>',
                'ASC' => ' <i class="fa fa-caret-up fa-fw pull-right text-warning sortFormBy" data-direction="INSERT_DIRECTION" data-order="INSERT_ORDER"></i>',
                'DEFAULT' => ' <i class="fa fa-sort fa-fw pull-right text-warning sortFormBy" data-direction="INSERT_DIRECTION" data-order="INSERT_ORDER"></i>'
            );

            $form_columns = array(
                'customer_id' => 'Client Id',
                'name' => 'Form Name',
                'added' => 'Submitted',
                'transaction_id' => 'Transaction Id',
                'viewed' => 'Viewed'
            );

            $headings = array('#', 'Client Id', 'Form Name', 'Submitted', 'Transaction Id', 'Amount Paid', 'Viewed', 'Options');

            $position = false;
            if($order_by && $form_direction) {
                $position = array_search($form_columns[$order_by], $headings);
            }

            foreach($headings as $i => $val) {
                if($val != '#' && $val != 'Options' && $val != 'Amount Paid') {

                    if ($position !== false && $position == $i) {
                        if($form_direction == 'DESC') {
                            $dir = 'ASC';
                        } else {
                            $dir = 'DESC';
                        }

                        $icon = str_replace('INSERT_ORDER', array_search($headings[$i], $form_columns), $icons[$form_direction]);
                        $icon = str_replace('INSERT_DIRECTION', $dir, $icon);

                        $headings[$i] = $headings[$i] . $icon;
                    } else {
                        $icon = str_replace('INSERT_ORDER', array_search($headings[$i], $form_columns), $icons['DEFAULT']);
                        $icon = str_replace('INSERT_DIRECTION', 'DESC', $icon);
                        $headings[$i] = $headings[$i] . $icon;
                    }
                }
            }

            $this->table->set_heading($headings);

            foreach ($data as $row) {
                if(isset($row['submission_id'])) {
                    $options = '<a href="' . base_url('forms/view-submitted-form/' . $row['submission_id']) . '" data-toggle="tooltip" data-title="View Form Submission" class="btn btn-primary btn-sm"><i class="fa fa-file-o"></i> View</a>';

                    if ($this->ion_auth->is_admin()) {
                        $options .= '<button class="btn btn-danger btn-sm deleteFormSubmission pull-right" data-id="' . $row["submission_id"] . '" data-formid="' . $row["form_id"] . '" data-toggle="tooltip" data-title="Delete Form Submission" ><i class="fa fa-times"></i> Delete</button>';
                    }

                    if ($row['amount'] < $row['cost'] && $row['amount'] != '') {
                        $row['amount'] = '<span class="text-warning">$' . number_format($row['amount'], 2) . ' of $' . number_format($row['cost'], 2) . '</span>';
                        $options .= '<a href="' . base_url('payments/submit-payment/' . $row['submission_id']) . '" data-toggle="tooltip" data-title="Add another payment to this form" class="btn btn-info btn-sm"><i class="fa fa-credit-card"></i> Add Payment</a>';
                    } else {
                        if ($row['amount'] > 0) {
                            $row['amount'] = '<span class="text-success">$' . number_format($row['amount'], 2) . ' of $' . number_format($row['cost'], 2) . '</span>';
                        } else {
                            $row['amount'] = '$0.00';
                        }
                    }

                    if ($this->searchedSubmission) {
                        if (strpos($row['transaction_id'], strtoupper($this->searchedSubmission)) !== false) {
                            $row['transaction_id'] = '<span class="highlightedSearch">' . $row['transaction_id'] . '</span>';
                        }
                        if (strpos(strtoupper($row['customer_id']), strtoupper($this->searchedSubmission)) !== false) {
                            $row['customer_id'] = '<span class="highlightedSearch">' . $row['customer_id'] . '</span>';
                        }
                    }

                    $start = $start + 1;
                    $this->table->add_row(
                        array(
                            $start . ' of ' . $this->totalFormsSubmitted,
                            $row['customer_id'],
                            $row['name'],
                            date('m/d/Y h:i A', strtotime($row['submitted'])),
                            $row['transaction_id'],
                            $row['amount'],
                            $row['viewed'] = $row['viewed'] == TRUE ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times notYetViewedForm text-danger"></i>',
                            $options,
                        )
                    );
                }
            }
            $tmpl = array('table_open' => '<div class="table-responsive"><table class="table table-striped table-bordered">', 'table_close' => '</table></div>');
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

    private function getSubmittedCountTotals()
    {
        $this->db->select('added');
        $this->db->from('form_data');
        if($this->session->userdata('search_form_submission_name') > 0) {
            $this->db->where('form_id', $this->session->userdata('search_form_submission_name'));
            $this->db->where('deleted', false);
        }
        $this->db->group_by('added');
        return $this->db->count_all_results();
    }

    public function paginationResults($limit, $classes = '')
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url()."/forms/form-submissions/";
        $config['total_rows'] = $this->totalFormsSubmitted;
        $config['full_tag_open'] = '<ul class="pagination '.$classes.'">';
        $config['full_tag_close'] = '</ul>';
        $config['per_page'] = $limit;
        $config['num_links'] = 5;
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
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    public function getSubmittedFormById($form_id, $submission_id)
    {
        $data['form'] = $this->getFormById($form_id);

        $query = $this->db->get_where('form_data', array('submission_id' => $submission_id));
        $values = $query->result_array();
        $data['values'] = $this->reorderArrayKeyNames($values, 'name');

        if(!empty($values)) {
            if (empty($values[0]['viewed'])) {
                $this->db->where('submission_id', $values[0]['submission_id']);
                $this->db->update('form_data', array('viewed' => true));
            }
        }

        return $data;
    }

    public function convertSubmissionIdToFormId($submission_id)
    {
        $this->db->select('form_id, transaction_id');
        $this->db->group_by('submission_id, form_id, transaction_id');
        $query = $this->db->get_where('form_data', array('submission_id' => $submission_id));

        return $query->row_array();
    }

    public function reorderArrayKeyNames($array, $keyName)
    {
        $data = array();
        if($array) {
            foreach($array as $row) {
                $newKey = $row[$keyName];
                $data[$newKey][] = $row;
            }
        }
        return $data;
    }

    public function deleteForm($formId)
    {
        $this->db->where('id', $formId);
        $this->db->update('forms', array('deleted' => true));

        $feedback = array(
            'success' => true,
            'msg' => 'You have successfully deleted this form',
        );

        return $feedback;
    }

    public function getAllFormNames()
    {
        $results = $this->db->get('forms')->result_array();

        return $results;
    }

    public function getUnreadSubmissions()
    {
        $sql = 'SELECT *
            FROM form_data
              LEFT JOIN forms
               ON form_id = forms.id
            WHERE
                 viewed = false';
        $result = $this->db->query($sql);

        return $result->result_array();
    }

    public function getFormIdByName($name)
    {
        $this->db->select('id');
        $result = $this->db->get_where('forms', array('name' => $name, 'active' => 1, 'deleted' => 0));
        $data = $result->row_array();
        if($data) {
            return $data['id'];
        }
        return false;
    }

    public function deleteFormSubmission($id, $form_id)
    {
        $this->db->where('submission_id', $id);
        $this->db->where('form_id', $form_id);
        $this->db->update('form_data', array('deleted' => true));

        return array('success' => true, 'msg' => '');
    }

    public function checkForFormSubmissions($formId)
    {
        return $this->db->where('form_id', $formId)->count_all_results('form_data');
    }

    public function duplicateFormById($original_form_id)
    {
        $feedback = array(
            'success' => false,
            'msg' => 'Failed to duplciate form, try again',
            'new_form_id' => 0,
        );
        $form = $this->getFormById($original_form_id);
        if(!empty($form)) {
            if($form['form_settings']) {
                $settingsCopy = array();
                foreach($form['form_settings'] as $key => $setting) {
                    $dontDuplicate = array('added', 'updated', 'id');
                    if(!in_array($key, $dontDuplicate)) {
                        $settingsCopy[$key] = $setting;
                    }
                }

                $settingsCopy['added'] = date('Y-m-d');
                $settingsCopy['updated'] = date('Y-m-d H:i:s');
                $settingsCopy['version'] = $settingsCopy['version']+1;

                $this->db->insert('forms', $settingsCopy);
                $feedback['new_form_id'] = $this->db->insert_id();
            }

            if(!empty($form['form_inputs']) && $feedback['new_form_id'] > 0) {
                foreach($form['form_inputs'] as $key => $input) {
                    unset($input['added']);
                    unset($input['input_id']);

                    $options = '';
                    if(isset($input['options']) && is_array($input['options'])) {
                        $options = $input['options'];
                        unset($input['options']);
                    }

                    $input['form_id'] = $feedback['new_form_id'];

                    $this->db->insert('form_inputs', $input);
                    $input_id = $this->db->insert_id();

                    if(!empty($options)) {
                        foreach($options as $option) {
                            $option['form_id'] = $feedback['new_form_id'];
                            $option['input_id'] = $input_id;

                            $this->db->insert('form_input_options', $option);
                            $input_id = $this->db->insert_id();
                        }
                    }
                }
            }

            $this->db->where('id', $original_form_id);
            $this->db->update('forms', array('active' => 0, 'deleted' => 1));

            $this->session->set_flashdata('success', 'Form Duplicated Successfully');

            $feedback['msg'] = 'Form Duplicated Successfully';
            $feedback['success'] = true;

        } else {
            $feedback['msg'] = 'Form not found, try again';
        }

        return $feedback;
    }

}

