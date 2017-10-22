<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pricing_model extends CI_Model
{
    private $feedback = array(
        'success' => false,
        'msg' => 'Form failed to save, try again',
        'data' => '',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function getPricingOptions()
    {
        $data = array();

        $this->db->order_by('sequence', 'asc');
        $this->db->order_by('category_sequence', 'asc');
        $query = $this->db->get('pricing');

        $allOptions = $query->result();

        if(!empty($allOptions)) {
            foreach ($allOptions as $options) {
                $data[strtolower($options->category)][] = $options;
            }
        }


        return $data;
    }

    public function getPricingOption($name, $category)
    {
        $query = $this->db->get_where('pricing', array('name' => $name, 'category' => $category) );
        $row = array();
        if($query->num_rows()>0) {
            $row = $query->row();
            $row->price = number_format($row->price, 2);
        }
        return $row;
    }

    public function getPricingCategory($category)
    {
        $query = $this->db->get_where('pricing', array('category' => $category) );
        return $query->result();
    }

    public function savePricingOption($options)
    {
        $options['category'] = strtolower($options['category']);
        if(isset($options['id']) && $options['id'] > 0) {
            //Existing Option
            $beforeSave = $this->getPricingOption($options['name'], $options['category']);
            $this->db->replace('pricing', $options);
        } else {
            //New option
            unset($options['id']);

            $query = $this->db->query('SELECT category_sequence FROM pricing ORDER BY category_sequence DESC LIMIT 1');
            $catSequence = $query->row();
            log_message('error', print_r($catSequence, true));

            $categorySequence = ($catSequence->category_sequence+1);
            $options['category_sequence'] = $categorySequence;

            $this->db->insert('pricing', $options);
            $beforeSave = $this->getPricingOption($options['name'], $options['category']);
        }

        $this->sortSequenceOrder($options, $beforeSave);

        $this->feedback['success'] = true;
        $this->feedback['msg'] = 'Pricing item successfully saved';
        $this->session->set_flashdata('success', $this->feedback['msg']);

        return $this->feedback;
    }

    private function sortSequenceOrder($options, $previousOption)
    {
        $allOptions = $this->getPricingOptions();
        // Sort out the categories
        $currentCategoryItems = $allOptions[$options['category']];

        if(count($currentCategoryItems)>1) {
            $seq = 0;
            foreach ($currentCategoryItems as $item) {
                $seq++;
                if($options['name'] != $item->name) {
                    $item->sequence = $seq;
                    if($seq == $options['sequence']) {
                        if($previousOption->sequence < $seq) {
                            $item->sequence = ($seq-1);
                        }
                    }
                    $this->db->replace('pricing', $item);
                }
            }
        }

    }

    public function deletePricingOption($option)
    {
        $this->session->set_flashdata('success', 'Item successfully deleted');
        $this->db->delete('pricing', array('name' => $option['name'], 'category' => $option['category']));
        return true;
    }


}