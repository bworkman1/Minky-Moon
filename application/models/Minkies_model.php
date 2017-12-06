<?php

class Minkies_model extends CI_Model {

    /**
     * ERROR CODES START WITH 900 - 925 FOR THIS MODAL
     * CURRENT CODES BEING USED: #900,
     */
    private $type = 'Minky', $size, $frontFabric, $backFabric, $trimFabric, $appliques, $font, $fontColor, $trim, $price, $feedback, $product_type, $line;

    function __construct()
    {
        parent::__construct();

        $this->feedback = array(
            'success' => false,
            'msg' => 'Invalid selection, try again',
            'data' => array(),
            'post' => array(),
        );
    }

    function process($data)
    {
        $this->product_type = $data['product_type'];
        $option = $data['type'];
        switch($option) {
            case 'size':
                $this->setSize($data['pricing_id']);
                $this->setTrimPrice();
                break;
            case 'trim':
                $this->setTrim($data['pricing_id']);
                break;
            case 'line':
                $data['value'] = trim($data['value']);
                $this->setLine($data);
                break;
            case 'fabric':
                $this->setFabric($data);
                break;
            case 'font-color':
                $this->setFontColor($data['value']);
                break;
            case 'font':
                $this->setFont($data['value']);
                break;
            case 'add':
                $this->addToCart();
                break;
            default:
                $this->feedback['msg'] = 'The product type you selected wasn\'t found';
                break;
        }
        $this->feedback['post'] = $data;
        $this->feedback['product'] = $this->setUserData();

        return $this->feedback;
    }

    public function setUserData()
    {
        $data[$this->product_type] = array(
            'size'              => $this->getSize(),
            'trim'              => $this->getTrim(),
            'trim_fabric'       => $this->getTrimFabric(),
            'line'              => $this->getLines(),
            'font'              => $this->getFont(),
            'font-color'        => $this->getFontColor(),
            'front_fabric'      => $this->getFrontFabric(),
            'back_fabric'       => $this->getBackFabric(),
            'appliques'         => $this->getAppliques(),
        );
        $this->session->set_userdata($data);
        return $this->session->userdata($this->product_type);
    }

    public function addToCart()
    {
        if($this->validItem()) {
            $this->load->helper('string');
            $randomness = random_string('numeric',25);

            $this->calculatePrice();

            if($this->price>0) {
                $data = array(
                    'id'        => $randomness,
                    'qty'       => 1,
                    'price'     => $this->price,
                    'name'      => $this->type . ' - ' . $this->getSize()->name,
                    'item_type' => $this->type,
                    'options'   => array(
                        'size'          => $this->getSize(),
                        'front'         => $this->getFrontFabric(),
                        'font'          => $this->getFont(),
                        'font-color'    => $this->getFontColor(),
                        'back'          => $this->getBackFabric(),
                        'accolades'     => $this->getAppliques(),
                        'trim'          => $this->getTrim(),
                        'trim_fabric'   => $this->getTrimFabric(),
                        'lines'         => $this->getLines(),
                    ),
                );

                if ($this->cart->insert($data)) {
                    $this->feedback['success'] = true;
                    $this->feedback['msg'] = 'Item successfully added';
                    $this->session->set_flashdata('success', $this->feedback['msg']);

                    // TODO: Is this the right way to do this?
                    $this->session->unset_userdata('minkies');

                } elseif ($this->feedback['msg'] == '') {
                    $this->feedback['msg'] = 'Item failed to add, try refreshing the page and trying again';
                }
            } else {
                $this->feedback['msg'] = 'Failed to generate the price, please try refreshing the page and trying again. (Code: #900)';
            }
        }
    }

    /*
     * MAKE SURE THAT USER HAS SELECTED ALL THE REQUIRED ITEMS AND IF A TRIM HAS BEEN SELECTED TO MAKE SURE THE
     * FABRIC HAS ALSO BEEN SELECTED. If a line is set, make sure that the color and font type has been set.
     */
    public function validItem()
    {
        $msg = '';
        $open = '';
        $settings = $this->session->userdata($this->product_type);

        /* CHECK FOR A FABRIC SIZE SELECTION */
        if(!isset($settings['size']) || $settings['size']->name == '') {
                $msg = 'You must select a size before adding to the cart';
                $open = 'sizes';
        }

        /* CHECK TRIM TYPE AND FABRIC */
        if(isset($settings['trim'])) {
            if($settings['trim']->name != 'None') {
                if ($settings['trim_fabric'] == '') {
                    $msg = 'You must select a fabric for your trim';
                    $open = 'trim';
                }
            }
        }

        /* CHECK FOR A FRONT FABRIC */
        if(!isset($settings['front_fabric']) || $settings['front_fabric']->name == '') {
            $msg = 'You must select a front fabric';
            $open = 'FrontFabric';
        }

        /* CHECK FOR A BACK FABRIC */
        if(!isset($settings['back_fabric']) || $settings['back_fabric']->name == '') {
            $msg = 'You must select a back fabric';
            $open = 'BackFabric';
        }

        /* CHECK FOR FONT COLOR AND FONT IF A LINE OF TEXT HAS BEEN ADDED */
        if(isset($settings['line'])) {
            if(isset($settings['line'][0]->value)) {
                if(!isset($settings['font']) || $settings['font'] == '' || !isset($settings['font-color']) || $settings['font-color'] == '') {
                    $msg = 'You must select a font color since you added personalization';
                    $open = 'personalization';
                }
            }
        }

        if($msg) {
            $this->feedback['msg'] = $msg;
            $this->feedback['data'] = array('panel' => $open);

            return false;
        } else {
            return true;
        }
    }

    private function throwErrorHandler($reason, $data = array())
    {
        log_message('error', 'ERROR ENCOUNTERED IN MINKY MODAL: '. print_r($reason, true));
        log_message('error', 'POST: '. print_r($_POST, true));
        log_message('error', 'CART: '. print_r($this->session->userdata('minkies'), true));
        log_message('error', '------------------------------------------------------------------');

        $this->feedback['msg'] = $reason;
    }

    public function calculatePrice()
    {
        $price = 0;
        $settings = $this->session->userdata($this->product_type);
        if(is_array($settings)) {
            foreach($settings as $setting) {
                if(is_array($setting)) { // LINE OPTIONS
                    foreach($setting as $s) {
                        if(isset($s->price) && $s->price > 0) {
                            $price = (float)number_format($price+$s->price, 2);
                        }
                    }
                } else { // SINGLE OPTION
                    if(isset($setting->price) && $setting->price > 0) {
                        $price = (float)number_format($price+$setting->price, 2);
                    }
                }
            }
        }

        if(is_float($price) && $price > 0) {
            $this->setPrice($price);
        } else {
            $this->throwErrorHandler('Failed to generate the price, please try refreshing the page and trying again. (Code: #900)');
        }
    }

    public function setLine($data)
    {
        $currentLines = $this->getLines();
        if($data['pricing_id']) {
            $results = $this->db->get_where('pricing', array('id' => $data['pricing_id']))->row();
            if(!empty($results)) {
                if($data['value'] != '') { // MAKE SURE LINE SETTINGS ARE SET
                    $results->value = $data['value'];
                    $currentLines[$data['line']] = $results;

                    $action = 'saved';
                } else {
                    $currentLines[$data['line']] = (object)array();
                    $action = 'removed';
                }
                $this->line = $currentLines;
                $this->session->userdata($this->product_type)['lines'] = $currentLines;
                $this->feedback['msg'] = 'Line ' . $data['line'] . ' ' . $action;
                $this->feedback['success'] = true;
            } else {
                $this->feedback['msg'] = 'Pricing not found for this option, try refreshing the page and trying again';
            }
        } else {
            $this->feedback['msg'] = 'Invalid selection, try refreshing the page and trying again';
        }
    }

    public function removeLine($line)
    {
        $lines = $this->getLines();
        $newLines = array();
        if(!empty($lines)) {
            foreach($lines as $key => $l) {
                if($key == $line) {
                    continue;
                } else {
                    $newLines[$key] = $l;
                }
            }
        }
        $this->line = $newLines;
    }

    public function getLines()
    {
        $settings = $this->session->userdata($this->product_type);
        if(isset($settings['line']) && empty($this->line)) {
            $this->line = $settings['line'];
        }
        return $this->line;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getFont()
    {
        $settings = $this->session->userdata($this->product_type);
        if(isset($settings['font']) && empty($this->font)) {
            $this->font = $settings['font'];
        }
        return $this->font;
    }

    public function setFont($font)
    {
        if($font) {
            $results = $this->db->get_where('fonts', array('font_name' => strtolower($font)))->result();
            if (!empty($results)) {
                $this->font = $font;
                $this->feedback['msg'] = 'Font successfully set to '.ucwords($font);
                $this->feedback['success'] = true;
            } else {
                $this->feedback['msg'] = 'Invalid font selection, try refreshing the page and trying again';
            }
        } else {
            $this->font = '';
            $this->feedback['msg'] = 'Font successfully removed';
            $this->feedback['success'] = true;
        }
    }

    public function getFontColor()
    {
        $settings = $this->session->userdata($this->product_type);
        if(isset($settings['font-color']) && empty($this->fontColor)) {
            $this->fontColor = $settings['font-color'];
        }
        return $this->fontColor;
    }

    public function setFontColor($color)
    {
        if($color) {
            $result = $this->db->get_where('text_colors', array('name' => $color))->result();
            if (!empty($result)) {
                $this->fontColor = $color;
                $this->feedback['msg'] = 'Font Color successfully set to '.ucwords($color);
                $this->feedback['success'] = true;
            } else {
                $this->feedback['msg'] = 'Invalid font color selection, try refreshing the page and trying again';
            }
        } else {
            $this->fontColor = '';
            $this->feedback['msg'] = 'Font color successfully removed';
            $this->feedback['success'] = true;
        }
    }

    public function setFabric($data)
    {
        if((int)$data['pricing_id'] > 0) {

            $this->db->select('name, image, id');
            $row = $this->db->get_where('fabrics', array('id' => $data['pricing_id']))->row();
            if (!empty($row)) {
                $this->feedback['success'] = true;
                $this->feedback['msg'] = ucwords($data['section']).' fabric set to '.$row->name;
                if ($data['section'] == 'trim') {
                    $row->price = 0;
                    $this->setTrimFabric($row);
                } elseif ($data['section'] == 'front') {
                    $row->price = 0;
                    $this->setFrontFabric($row);
                } elseif ($data['section'] == 'back') {
                    $row->price = 0;
                    $this->setBackFabric($row);
                } else {
                    $this->feedback['success'] = false;
                    $this->feedback['msg'] = 'Invalid selection, try again';
                }
            }
        }
    }

    public function getTrimFabric()
    {
        $settings = $this->session->userdata($this->product_type);
        if(isset($settings['trim_fabric']) && empty($this->trimFabric)) {
            $this->trimFabric = $settings['trim_fabric'];
        }
        if($this->trimFabric == 'reset') {
            $this->trimFabric = '';
        }
        return $this->trimFabric;

    }

    public function setTrimFabric($trimFabric)
    {
        $this->trimFabric = $trimFabric;
        return $this->trimFabric;
    }

    public function getFrontFabric()
    {
        $settings = $this->session->userdata($this->product_type);
        if(isset($settings['front_fabric']) && empty($this->frontFabric)) {
            $this->frontFabric = $settings['front_fabric'];
        }
        return $this->frontFabric;
    }

    public function setFrontFabric($frontFabric)
    {
        $this->frontFabric = $frontFabric;
        return $this->frontFabric;
    }

    public function getBackFabric()
    {
        $settings = $this->session->userdata($this->product_type);
        if(isset($settings['back_fabric']) && empty($this->backFabric)) {
            $this->backFabric = $settings['back_fabric'];
        }
        return $this->backFabric;
    }

    public function setBackFabric($backFabric)
    {
        $this->backFabric = $backFabric;
        return $this->backFabric;
    }

    public function getAppliques()
    {
        return $this->appliques;
    }

    public function setAppliques($appliques)
    {
        $this->appliques = $appliques;
    }

    public function getTrim()
    {
        $settings = $this->session->userdata($this->product_type);
        if (isset($settings['trim']) && empty($this->trim)) {
            $this->trim = $settings['trim'];
        }
        return $this->trim;
    }

    public function setTrim($trim)
    {
        $trim = $this->db->get_where('pricing', array('id' => $trim))->row();

        $size = $this->getSize();
        $sizeType = !empty($size) && isset($size->name) && $size->name != '' ? $size->name : 'Minky Moon';
        $trimPrice = $this->db->get_where('trim', array('size' => $sizeType, 'trim' => $trim->name))->row();
        if($trim->name == 'None') {
            $this->trimFabric = 'reset';
        }

        if(!empty($trimPrice)) {
            $trim->price = $trimPrice->price;
            $this->trim = $trim;
            $this->feedback['msg'] = $this->trim->name. ' Selected';
            $this->feedback['success'] = true;
        } else {
            $this->feedback['msg'] = 'Invalid size selection, try again';
        }
    }

    public function getSize()
    {
        $settings = $this->session->userdata($this->product_type);
        if(isset($settings['size']) && empty($this->size)) {
            $this->size = $settings['size'];
        }

        return $this->size;
    }

    private function setTrimPrice()
    {
        $this->size->name;
        $trim = $this->getTrim();
        if($trim) {
            $results = $this->db->get_where('pricing', array('name' => $trim->name, 'category' => 'trim'))->row();
            $this->setTrim($results->id);
        }
    }

    public function setSize($size)
    {
        $query = $this->db->get_where('pricing', array('id' => $size));
        $results = $query->result();
        if(!empty($results)) {
            $this->size = $results[0];

            $this->feedback['msg'] = $this->size->name. ' Selected';
            $this->feedback['success'] = true;
        } else {
            $this->feedback['msg'] = 'Invalid size selection, try again';
        }
    }



}
