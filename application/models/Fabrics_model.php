<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fabrics_model extends CI_Model
{
    private $feedback = array(
        'success' => false,
        'msg' => 'Form failed to save, try again',
        'data' => array(),
    );
    private $fabric, $image, $imagePath, $thumbPath, $error;

    public function __construct()
    {
        parent::__construct();
    }

    public function getFabricData()
    {
        $query = $this->db->query('SELECT fabrics.id, fabrics.name, fabrics.thumb, fabrics.Featured, fabric_to_category.fabric_id, fabric_to_category.category_id, fabric_categories.category FROM `fabrics` LEFT JOIN fabric_to_category ON fabrics.id = fabric_to_category.fabric_id LEFT JOIN fabric_categories on fabric_categories.id = fabric_to_category.category_id');
        $data = $query->result();
        return $this->sortFabricData($data);
    }

    public function sortFabricData($data)
    {
        $fabrics = array();
        if($data) {
            foreach($data as $row) {
                if($row->category_id > 0) {
                    $fabrics[$row->category][] = $row;
                } else {
                    $fabrics['Uncategorized'][] = $row;
                }
            }
        }
        return $fabrics;
    }

    public function getSingleFabric($id)
    {
        $query = $this->db->get_where('fabrics', array('id' => $id));
        return $query->row();
    }

    public function getCategories()
    {
        return $this->db->get('fabric_categories')->result();
    }

    public function getPlacements($category)
    {
        $this->db->select('placement');
        $this->db->group_by('placement');
        $query = $this->db->get_where('fabrics', array('category' => $category));
        return $query->result();
    }

    public function getFabricCategories($id)
    {
        $query = $this->db->get_where('fabric_to_category', array('fabric_id' => $id));
        return $query->result();
    }

    public function getFabricProducts($id)
    {
        $query = $this->db->get_where('product_fabrics', array('fabric_id' => $id));
        return $query->result();
    }

    public function saveFabricData($data)
    {
        if(isset($data['post'])) {
            $this->fabric = $data['post'];
        }
        if(isset($data['files']) && isset($data['files']['fabric_image'])) {
            $this->image = $data['files']['fabric_image'];
        }

        if($this->fabric['id'] > 0) {
            $query = $this->db->get_where('fabrics', array('id' => $this->fabric['id']));
            $currentFabricData = $query->row();

            $this->imagePath = $currentFabricData->image;
            $this->thumbPath = $currentFabricData->thumb;
        }

        if($this->fabric) {
            if($this->image) { // HANDLE IMAGE
                $this->load->model('Upload_model');

                $image = $this->Upload_model->uploadImage('fabric_image', $width = 1024, $height = 768, true);
                if($image['success']) {
                    $this->imagePath = $image['image'];
                    $this->thumbPath = $image['thumb'];
                } else {
                    $this->error = true;
                    $this->feedback['msg'] = $image['msg'];
                }
            }

            $this->checkForFabricDataErrors();

            if(!$this->error) {
                $fabricData = array(
                    'id'        => $this->fabric['id'],
                    'name'      => $this->fabric['name'],
                    'active'    => $this->fabric['active'],
                    'active'    => $this->fabric['active'],
                    'image'     => $this->imagePath,
                    'thumb'     => $this->thumbPath,
                    'featured'  => $this->fabric['featured'],
                    'side'      => $this->determineSide(),
                );

                $this->db->replace('fabrics', $fabricData);

                $this->fabric['id'] = $this->db->insert_id();

                $this->saveFabricProducts();
                $this->saveFabricToCategory();

                $this->feedback['success'] = true;
                $this->feedback['msg'] = 'Fabric saved successfully';

                $this->session->set_flashdata('success', $this->feedback['msg']);
            }

        } else {
            $this->feedback['msg'] =  'No fabric data sent, try refreshing the page and trying again';
        }
        return $this->feedback;
    }

    public function getFabricsWithProduct($id)
    {
        $product = $this->db->get_where('products', array('id' => $id))->row();

        $query = $this->db->query('SELECT fabrics.id, fabrics.name, fabrics.thumb, fabrics.Featured, fabric_to_category.fabric_id, fabric_to_category.category_id, fabric_categories.category, product_fabrics.product_id FROM `fabrics` LEFT JOIN fabric_to_category ON fabrics.id = fabric_to_category.fabric_id LEFT JOIN fabric_categories on fabric_categories.id = fabric_to_category.category_id LEFT JOIN `product_fabrics` ON product_fabrics.fabric_id = fabrics.id', array());

        return array('fabric' => $this->sortFabricData($query->result()), 'product' => $product);
    }

    public function getFabricsByProductId($product_id)
    {
        $query = $this->db->query('SELECT fabrics.id, fabrics.name, fabrics.thumb, fabrics.Featured, fabric_to_category.fabric_id, fabrics.side, fabric_to_category.category_id, fabric_categories.category, product_fabrics.product_id FROM `fabrics` LEFT JOIN fabric_to_category ON fabrics.id = fabric_to_category.fabric_id LEFT JOIN fabric_categories on fabric_categories.id = fabric_to_category.category_id LEFT JOIN `product_fabrics` ON product_fabrics.fabric_id = fabrics.id WHERE product_fabrics.product_id', array($product_id));

        return $this->sortFabricData($query->result());
    }

    private function saveFabricToCategory()
    {
        if($this->fabric['id']>0) {
            $this->db->delete('fabric_to_category', array('fabric_id' => $this->fabric['id']));
            $categories = $this->explodeCommaString($this->fabric['category']);
            if($categories) {
                $insert = array();
                foreach($categories as $id) {
                    if($id>0) {
                        $insert[] = array('fabric_id' => $this->fabric['id'], 'category_id' => $id);
                    }
                }
                if(!empty($insert)) {
                    $this->db->insert_batch('fabric_to_category', $insert);
                }
            }
        }
    }

    private function saveFabricProducts()
    {
        if($this->fabric['id']>0) {
            $this->db->delete('product_fabrics', array('fabric_id' => $this->fabric['id']));
            $products = $this->explodeCommaString($this->fabric['products']);
            if($products) {
                $insert = array();
                foreach($products as $id) {
                    if($id>0) {
                        $insert[] = array('fabric_id' => $this->fabric['id'], 'product_id' => $id);
                    }
                }
                $this->db->insert_batch('product_fabrics', $insert);
            }
        }
    }

    private function checkForFabricDataErrors()
    {
        $requiredFields = array('active', 'name', 'featured', 'side');
        if($this->fabric) {
            foreach($this->fabric as $key => $fabric) {
                if(in_array($key, $requiredFields)) {
                    if($fabric == '') {
                        $this->error = true;
                        $this->feedback['data'][$key] = 'Field is required';
                    }
                }
            }

            if(!$this->imagePath) {
                $this->feedback['msg'] = 'Fabric image is required';
                $this->error = true;
            }
            if(!$this->thumbPath) {
                $this->error = true;
                $this->feedback['msg'] = 'Fabric image is required';
            }
        } else {
            $this->feedback['msg'] = 'The fabric data failed to send, please refresh the page and try again';
        }
    }

    private function determineSide()
    {
        $side = 0;
        $sides = $this->explodeCommaString($this->fabric['sides']);
        if(!empty($sides)) {
            if(count($sides) > 1) {
                $side = 3;
            } else {
                $side = $sides[0];
            }
        }
        return $side;
    }

    private function explodeCommaString($type)
    {
        $typeArray = array();
        if($type) {
            $typeArray = explode(',', $type);
        }
        return $typeArray;
    }


}