<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop_model extends CI_Model
{
    private $feedback;

    public function __construct()
    {
        parent::__construct();

        $this->feedback = array(
            'success' => false,
            'msg' => 'Invalid selection',
            'data' => array(),
        );
    }

    public function getProductsOptionsByType($type)
    {
        $type = ucwords(str_replace('-', ' ', filter_var($type, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH )));

        $productDetails = $this->db->get_where('products', array('name' => $type))->row();
        if(!empty($productDetails)) {
            $fabrics = $this->Fabrics_model->getFabricsByProductId($productDetails->id);
            $fabrics = $this->sortFabricSides($fabrics, $productDetails->id);

            $pricingOptions = $this->getOptions(explode('|', $productDetails->pricing_options), 'pricing', 'category_sequence');

            $query = $this->db->get('text_colors');
            $query2 = $this->db->get('fonts');

            $data = array(
                'product'        => $productDetails,
                'fabrics'        => $fabrics,
                'pricingOptions' => $pricingOptions,
                'type'           => $type,
                'font_colors'    => $query->result(),
                'fonts'    => $query2->result(),
            );

            return $data;
        } else {
            return false;
        }
    }

    private function sortFabricSides($data, $productId)
    {
        $returnData = array('front_fabric' => array(), 'back_fabric' => array(), 'trim' => array());
        if($data) {
            $addedIds = array();
            foreach($data as $key => $row) {
                if($key == 'Uncategorized') continue;

                foreach($row as $r) {
                    if($r->product_id == $productId && !in_array($r->id, $addedIds)) {

                        if(strtolower($r->category) == 'trim') {
                            $returnData['trim']['Trim Fabric'][] = $r;
                        } elseif($r->side == 3) {
                            $returnData['front_fabric'][$key][] = $r;
                            $returnData['back_fabric'][$key][] = $r;

                            $addedIds[] = $r->id;
                        } elseif($r->side == 2) {
                            $returnData['back_fabric'][$key][] = $r;

                            $addedIds[] = $r->id;
                        } elseif($r->side == 1) {
                            $returnData['front_fabric'][$key][] = $r;

                            $addedIds[] = $r->id;
                        }
                    }
                }
            }
        }
        return $returnData;
    }

    private function getOptions($data, $table, $orderBy = 'sequence')
    {
        $options = array();
        if(!empty($data)) {

            $in = '';
            foreach($data as $option) {
                $in .= '"'.$option.'", ';
            }
            $in = rtrim($in, ', ');
            $query = $this->db->query('SELECT * FROM '.$table.' WHERE category IN ('.$in.') ORDER BY '.$orderBy);
            if($query->num_rows()>0) {
                foreach($query->result() as $row) {
                    $options[$row->category][] = $row;
                }
            }
        }
        return $options;
    }

    public function process($data)
    {
        if($data['type'] == 'update') {
            if($this->cart->update($data['products'])) {
                $this->feedback['success'] = true;
                $this->feedback['msg'] = 'Your cart was successfully updated';

                $this->session->set_flashdata('success', $this->feedback['msg']);
            }
        } elseif($data['type'] == 'remove') {
            if($this->cart->update(array('rowid' => $data['cart_id'], 'qty' => 0))) {
                $this->feedback['success'] = true;
                $this->feedback['msg'] = 'Item successfully removed';

                $this->session->set_flashdata('success', $this->feedback['msg']);
            }
        }

        return $this->feedback;
    }

    public function apply_discount_code($code)
    {
        $this->feedback['msg'] = 'Invalid code, try again';

        $code = strtoupper(clean($code));
        if($this->cart->total_items() > 0 ) {
            if (strlen($code) > 2 && strlen($code) < 30) {
                $results = $this->db->get_where('gift_codes', array('code' => $code, 'active' => 'y'))->row();
                if ($results) {
                    if($this->checkCurrentCodeUse($results)) {
                        $this->feedback['success'] = true;
                        $this->session->set_flashdata('success', 'Coupon code added successfully');
                    }
                }
            }
        } else {
            $this->feedback['msg'] = 'You must have items in your cart first to apply discount/gift certificates to the cart';
        }

        return $this->feedback;
    }

    public function calculateDiscountAmountByPercent($codes, $cartTotal)
    {
        $price = 0;
        if($codes->discount_amount > 0) {

            if($cartTotal > 0) {
                $percent_off = '.'.$codes->discount_amount;
                $price = $cartTotal * $percent_off;
            }
        }
        return number_format($price, 2);
    }

    public function calculateDiscountAmountByAmount($codes, $cartTotal)
    {
        if($codes->discount_amount > 0 && $codes->amount_left > 0) {
            if ($codes->amount_left >= $cartTotal) {
                return number_format($cartTotal, 2);
            } elseif ($codes->amount_left < $cartTotal) {
                return number_format($codes->amount_left, 2);
            }
        }
    }

    private function checkCurrentCodeUse($codeData)
    {
        $couponCodes = $this->session->userdata('coupon_codes');
        if(!empty($couponCodes)) {
            $codesInUse = array();
            foreach($couponCodes as $codes) {
                $codesInUse[] = $codes->code;
            }

            if(!in_array($codeData->code, $codesInUse)) {
                $couponCodes[] = $codeData;
            } else {
                $this->feedback['msg'] = 'Coupon code is already being used';
                return false;
            }

        } else {
            $couponCodes = array($codeData);
        }

        $this->session->set_userdata('coupon_codes', $couponCodes);

        return true;
    }

    public function processPayment()
    {
        $this->feedback['msg'] = 'There were some errors processing your order';

        $this->form_validation->set_rules('shipping_full_name',     'Shipping full name',       'required|alpha_numeric_spaces|min_length[5]|max_length[30]|trim');
        $this->form_validation->set_rules('shipping_address_line1', 'Shipping address line 1',  'required|alpha_numeric_spaces|min_length[5]|max_length[30]|trim');
        $this->form_validation->set_rules('shipping_address_line2', 'Shipping address line 2',  'alpha_numeric_spaces|max_length[30]|trim');
        $this->form_validation->set_rules('shipping_city',          'Shipping City',            'required|alpha_numeric_spaces|min_length[5]|max_length[30]|trim');
        $this->form_validation->set_rules('shipping_state',         'Shipping City',            'required|alpha_numeric_spaces|min_length[2]|max_length[2]|trim');
        $this->form_validation->set_rules('shipping_zip',           'Shipping Zip',             'required|alpha_numeric_spaces|min_length[5]|max_length[5]|trim');

        $this->form_validation->set_rules('billing_full_name',     'Billing full name',       'required|alpha_numeric_spaces|min_length[5]|max_length[30]|trim');
        $this->form_validation->set_rules('billing_address_line1', 'Billing address line 1',  'required|alpha_numeric_spaces|min_length[5]|max_length[30]|trim');
        $this->form_validation->set_rules('billing_address_line2', 'Billing address line 2',  'alpha_numeric_spaces|max_length[30]|trim');
        $this->form_validation->set_rules('billing_city',          'Billing City',            'required|alpha_numeric_spaces|min_length[5]|max_length[30]|trim');
        $this->form_validation->set_rules('billing_state',         'Billing City',            'required|alpha_numeric_spaces|min_length[2]|max_length[2]|trim');
        $this->form_validation->set_rules('billing_zip',           'Billing Zip',             'required|alpha_numeric_spaces|min_length[5]|max_length[5]|trim');

        if($this->getCartTotalWithCoupons() > 0) {
            $this->form_validation->set_rules('card_name', 'Card holder name', 'required|alpha_numeric_spaces|min_length[5]|max_length[30]|trim');
            $this->form_validation->set_rules('card_number', 'Card number', 'required|alpha_dash|min_length[19]|max_length[19]|trim');
            $this->form_validation->set_rules('cvc', 'CVC on the back of the card is required', 'required|numeric|min_length[3]|max_length[4]|trim');
            $this->form_validation->set_rules('month', 'Expiry Month', 'required|numeric|min_length[2]|max_length[2]|trim');
            $this->form_validation->set_rules('year', 'Expiry Year', 'required|numeric|min_length[4]|max_length[4]|trim');
        }

        if($this->form_validation->run() == FALSE) {
            $this->feedback['data'] = $this->form_validation->error_array();
        } else {
            /*
             * SEND PAYMENT INFO TO BE PROCESSED (GET BACK TRANSACTION ID AND SAVE IT WITH THE ORDER)
             * SAVE CART DATA AND CUSTOMER INFO TO DATABASE (SAVE EACH ITEM IN SEPARATE LINE ITEM AND GROUP THEM BY AN ORDER ID AND TIE THEM TO THE CUSTOMER ID)
             * SEND EMAIL TO CUSTOMER AND ADMINS WITH THE DETAILS (EMAIL MODEL TO HANDLE ALL EMAIL BY TYPE WITH DATA PASSED INTO IT)
             */
        }


        return $this->feedback;
    }

    private function getCartTotalWithCoupons()
    {
        $couponCodes = $this->session->userdata('coupon_codes');

        $cartTotal = $this->cart->format_number($this->cart->total());
        if(!empty($couponCodes)) {
            foreach($couponCodes as $codes) {
                if($codes->discount_type == 'percent') {
                    $amount = (float)$this->Shop_model->calculateDiscountAmountByPercent($codes, $cartTotal);
                } elseif($codes->discount_type == 'amount') {
                    $amount = (float)$this->Shop_model->calculateDiscountAmountByAmount($codes, $cartTotal);
                }

                $cartTotal = $cartTotal - $amount;
            }
        }

        return number_format($cartTotal, 2);
    }

}