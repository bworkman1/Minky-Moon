<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model {

    private $feedback = array(
        'success' => false,
        'msg' => '',
        'error' => '',
        'data' => '',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function getProducts()
    {
        $query = $this->db->get('products');
        return $query->result();
    }

    public function deleteProduct($id)
    {
        $product = $this->getProductById($id);

        if(isset($product['data']->image) && $product['data']->image == '') {
            $this->load->model('Upload_model');
            $this->Upload_model->deleteImage($product['data']->image);
        }

        $this->db->delete('products', array('id' => $id));
        
        $this->session->set_flashdata('success', 'Product deleted successfully');

        return array('success' => true);
    }

    public function getProductById($id)
    {
        $query = $this->db->get_where('products', array('id' => $id));
        $row = $query->row();
        if($row) {
            $row->pricing_options = explode('|', $row->pricing_options);
            $row->fabrics = explode('|', $row->fabrics);
        }
        $this->feedback['data'] = $row;
        $this->feedback['success'] = true;

        return $this->feedback;
    }

    public function saveProduct($data)
    {
        $this->load->model('Upload_model');
        $currentProduct = $this->getProductById($data['id']);

        if($data['id'] == '' || $_FILES['image']['name'] != '' || $currentProduct['data']->image == '') {
            $image = $this->Upload_model->uploadImage($data['image']);
        } else {
            $image['image'] = $currentProduct['data']->image;
            $image['success'] = true;
        }

        if($image['success']) {
            $data['image'] = $image['image'];
            $data['pricing_options'] = implode('|', $data['pricing_options']);
            $data['fabrics'] = implode('|', $data['fabrics']);

            if($data['id'] > 0) {
                $this->db->replace('products', $data);
            } else {
                $this->db->insert('products', $data);
            }
            $this->session->set_flashdata('success', 'Product successfully saved');
            $this->feedback['success'] = true;
        } else {
            $this->feedback = $image;
        }
        return $this->feedback;
    }

    public function deleteProductImage($id)
    {
        $this->feedback['msg'] = 'Product image not found';
        if($id) {
            $product = $this->getProductById($id);
            if (!empty($product['data']->image)) {
                $this->load->model('Upload_model');

                $this->Upload_model->deleteImage($product['data']->image);
                $this->db->where('id', $id);
                $this->db->update('products', array('image' => ''));
                $this->feedback['msg'] = 'Product image deleted successfully';
                $this->feedback['success'] = true;
            }
        }
        return $this->feedback;
    }

    public function getProductData($id)
    {

    }

}