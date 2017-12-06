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
            $this->form_validation->set_rules('card_name',      'Card holder name',                         'required|alpha_numeric_spaces|min_length[5]|max_length[30]|trim');
            $this->form_validation->set_rules('card_number',    'Card number',                              'required|alpha_dash|min_length[19]|max_length[19]|trim');
            $this->form_validation->set_rules('cvc',            'CVC on the back of the card is required',  'required|numeric|min_length[3]|max_length[4]|trim');
            $this->form_validation->set_rules('month',          'Expiry Month',                             'required|numeric|min_length[2]|max_length[2]|trim');
            $this->form_validation->set_rules('year',           'Expiry Year',                              'required|numeric|min_length[4]|max_length[4]|trim');
        }

        if($this->form_validation->run() == FALSE) {
            $this->feedback['data'] = $this->form_validation->error_array();
        } else {

            $paymentSuccess = $this->sendPaymentPaypal();
            if(is_array($paymentSuccess)) {

                $this->saveOrderDetails($paymentSuccess['transaction_id']);

                $this->load->model('Payment_model');

                $this->Payment_model->postValues              = $_POST;
                $this->Payment_model->postValues['user_id']   = $this->session->userdata['user_id'];
                $this->Payment_model->postValues['amount']    = $this->cart->format_number($this->cart->total());

                $this->Payment_model->logPaymentDetails($paymentSuccess['transaction_id']);

                $this->sendPaymentEmail($paymentSuccess['transaction_id']);

                $this->session->unset_userdata('minkies');

                $this->cart->destroy();

                // SEND USER TO THERE ACCOUNT ONCE THEY COMPLETE THE PAYMENT
                $this->feedback['redirect'] = base_url('account/orders');
                $this->feedback['success'] = true;
            }
        }

        return $this->feedback;
    }

    private function sendPaymentPaypal()
    {
        $this->load->helper('url');
        $this->config->load('paypal');

        $config = array(
            'Sandbox'       => $this->config->item('Sandbox'),
            'APIUsername'   => $this->config->item('APIUsername'),
            'APIPassword'   => $this->config->item('APIPassword'),
            'APISignature'  => $this->config->item('APISignature'),
            'APISubject'    => '',
            'APIVersion'    => $this->config->item('APIVersion')
        );
        $this->load->library('paypal/Paypal_pro', $config);

        $DPFields = array(
            'paymentaction' => 'Sale', 					// How you want to obtain payment.
            'ipaddress' => $_SERVER['REMOTE_ADDR'], 	// Required.  IP address of the payer's browser.
            'returnfmfdetails' => '1' 					// Flag to determine whether you want the results returned by FMF.  1 or 0.  Default is 0.
        );

        $CCDetails = array(
            'creditcardtype'    => $this->getCreditCardType(str_replace('-' , '', $_POST['card_number'])), // Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.
            'acct'              => str_replace('-' , '', $_POST['card_number']), 	// Required.  Credit card number.  No spaces or punctuation.
            'expdate'           => $_POST['month'].$_POST['year'], 	// Required.  Credit card expiration date.  Format is MMYYYY
            'cvv2'              => $_POST['cvc'], 				// Requirements determined by your PayPal account settings.  Security digits for credit card.
            'startdate'         => '', 							// Month and year that Maestro or Solo card was issued.  MMYYYY
            'issuenumber'       => ''							// Issue number of Maestro or Solo card.  Two numeric digits max.
        );

        $PayerInfo = array(
            'email'         => $this->session->userdata('email'), // Email address of payer.
            'payerid'       => '', 							// Unique PayPal customer ID for payer.
            'payerstatus'   => '', 						// Status of payer.  Values are verified or unverified
            'business'      => 'Testers, LLC' 			// Payer's business name.
        );

        $name = explode(' ', $_POST['billing_full_name']);
        $firstName = '';
        $lastName = '';
        if(!empty($name)) {
            $lastName = end($name);
            $firstName = $name[0];
        }

        $PayerName = array(
            'salutation'    => '', 						            // Payer's salutation.  20 char max.
            'firstname'     => $firstName, 							// Payer's first name.  25 char max.
            'middlename'    => '', 						            // Payer's middle name.  25 char max.
            'lastname'      => $lastName, 							// Payer's last name.  25 char max.
            'suffix'        => ''								        // Payer's suffix.  12 char max.
        );

        $BillingAddress = array(
            'street'        => $_POST['billing_address_line1'], 	// Required.  First street address.
            'street2'       => $_POST['billing_address_line2'], 	// Second street address.
            'city'          => $_POST['billing_city'], 				// Required.  Name of City.
            'state'         => $_POST['billing_state'], 			// Required. Name of State or Province.
            'countrycode'   => 'US', 					            // Required.  Country code.
            'zip'           => $_POST['billing_zip'], 			    // Required.  Postal code of payer.
            'phonenum'      => '' 						            // Phone Number of payer.  20 char max.
        );

        $ShippingAddress = array(
            'shiptoname'    => $_POST['shipping_full_name'], 			    // Required if shipping is included.  Person's name associated with this address.  32 char max.
            'shiptostreet'  => $_POST['shipping_address_line1'], 	        // Required if shipping is included.  First street address.  100 char max.
            'shiptostreet2' => $_POST['shipping_address_line2'], 		    // Second street address.  100 char max.
            'shiptocity'    => $_POST['shipping_city'], 					// Required if shipping is included.  Name of city.  40 char max.
            'shiptostate'   => $_POST['shipping_state'], 					// Required if shipping is included.  Name of state or province.  40 char max.
            'shiptozip'     => $_POST['shipping_zip'], 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
            'shiptocountry' => 'US', 					                    // Required if shipping is included.  Country code of shipping address.  2 char max.
            'shiptophonenum' => ''					                        // Phone number for shipping address.  20 char max.
        );


        /*
         * TODO: Figure out the price of each item with the discounts included
         */
        $OrderItems = array();
        foreach ($this->cart->contents() as $items) {
            $Item	 = array(
                'l_name'    => $items['name'], 						                // Item Name.  127 char max.
                'l_desc'    => $items['name'], 						                // Item description.  127 char max.
                'l_amt'     => $this->cart->format_number($items['subtotal']), 	    // Cost of individual item.
                'l_number'  => '', 						                            // Item Number.  127 char max.
                'l_qty'     => $items['qty'], 							            // Item quantity.  Must be any positive integer.
                'l_taxamt'  => 0, 						                            // Item's sales tax amount.
            );

            array_push($OrderItems, $Item);
        }


        // FIGURE OUT THE CART TOTAL WITH OR WITHOUT COUPON CODES
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

        $PaymentDetails = array(
            'amt'           => $cartTotal, 							    // Required.  Total amount of order, including shipping, handling, and tax.
            'currencycode'  => 'USD', 					                // Required.  Three-letter currency code.  Default is USD.
            'itemamt'       => $cartTotal, 						        // Required if you include itemized cart details. (L_AMTn, etc.)  Subtotal of items not including S&H, or tax.
            'shippingamt'   => '0.00', 					                // Total shipping costs for the order.  If you specify shippingamt, you must also specify itemamt.
            'shipdiscamt'   => '', 					                    // Shipping discount for the order, specified as a negative number.
            'handlingamt'   => '', 					                    // Total handling costs for the order.  If you specify handlingamt, you must also specify itemamt.
            'taxamt'        => '', 						                // Required if you specify itemized cart tax details. Sum of tax for all items on the order.  Total sales tax.
            'desc'          => 'Web Order', 							// Description of the order the customer is purchasing.  127 char max.
            'custom'        => '', 						                // Free-form field for your own use.  256 char max.
            'invnum'        => '', 						                // Your own invoice or tracking number
            'notifyurl'     => base_url('shop/paypal-notification'),// URL for receiving Instant Payment Notifications.  This overrides what your profile is set to use.
        );

        $Secure3D = array(
            'authstatus3d' => '',
            'mpivendor3ds' => '',
            'cavv' => '',
            'eci3ds' => '',
            'xid' => ''
        );

        $PayPalRequestData = array(
            'DPFields'          => $DPFields,
            'CCDetails'         => $CCDetails,
            'PayerInfo'         => $PayerInfo,
            'PayerName'         => $PayerName,
            'BillingAddress'    => $BillingAddress,
            'ShippingAddress'   => $ShippingAddress,
            'PaymentDetails'    => $PaymentDetails,
            'OrderItems'        => $OrderItems,
            'Secure3D'          => $Secure3D
        );

        $PayPalResult = $this->paypal_pro->DoDirectPayment($PayPalRequestData);

        if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK'])) {
            $errorString = '';
            if(!empty($PayPalResult['ERRORS']) && is_array($PayPalResult['ERRORS'])) {
                foreach($PayPalResult['ERRORS'] as $error) {
                    $errorString .= $error['L_LONGMESSAGE'].'<br><hr>';
                }
            }

            $this->feedback['msg'] = rtrim($errorString, '<hr>');
            // TODO: FIX THIS ONCE THE PAYPAL STUFF STARTS WORKING
            $response = array(
                'transaction_id' => rand(10000, 1000000000).rand(10000, 1000000000),
            );

            //TODO: MOVE THIS INTO THE NEXT ELSE ONCE PAYPAL STARTS WORKING

            $userData = array(
                'first_name' => $firstName,
                'last_name' => $lastName
            );
            $this->ion_auth->update($this->session->userdata('user_id'), $userData);

            return $response; // FALSE TODO: ADDED TO GET AROUND ERROR - REMOVE
        } else {
            // TODO: WORDING SO YEAH, TODO
            $this->feedback['msg'] = 'Your payment was successful';
            $this->session->set_flashdata('success', $this->feedback['msg']);

            // $PayPalResult - PAYMENT DETAILS WILL BE IN THIS VAR - FIND THE TRANSACTION ID
            //log_message('error', print_r($PayPalResult, true));

            $response = array(
                'transaction_id' => rand(100000, 1000000000).rand(100000, 1000000000),
            );

            return $response;
        }
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

    private function getCreditCardType($cardNumber, $format = 'string')
    {
        if (empty($cardNumber)) {
            return false;
        }

        $matchingPatterns = [
            'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'mastercard' => '/^5[1-5][0-9]{14}$/',
            'amex' => '/^3[47][0-9]{13}$/',
            'diners' => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
            'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'jcb' => '/^(?:2131|1800|35\d{3})\d{11}$/',
            'any' => '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/'
        ];

        $ctr = 1;
        foreach ($matchingPatterns as $key=>$pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return $format == 'string' ? $key : $ctr;
            }
            $ctr++;
        }
    }

    private function saveOrderDetails($transaction_id)
    {
        foreach($this->cart->contents() as $item) {
            $orderObj = array();
            foreach($item['options'] as $key => $option) {
                $doInsert = true;
                if(!empty($option)) {
                    if(is_array($option)) {
                        foreach($option as $o) {
                            if(isset($o->id) && $o->id) {
                                $orderObj = array(
                                    'product_id' => $o->id,
                                    'product_type' => $key,
                                    'name' => $o->name,
                                    'price' => $o->price,
                                    'qty' => $item['qty'],
                                    'item_total' => $item['subtotal'],
                                    'value' => isset($o->value) ? $o->value : '',
                                    'transaction_id' => $transaction_id,
                                    'cart_item_id' => $item['rowid'],
                                    'user_id' => $this->session->userdata('user_id'),
                                    'item_type' => $item['item_type'],
                                );

                                if (!empty($orderObj)) {
                                    $this->db->insert('web_orders', $orderObj);
                                }
                            }
                        }
                        $doInsert = false;
                    } else {
                        if(is_string($option)) {
                            $orderObj = array(
                                'product_id'    => 0,
                                'product_type'  => $key,
                                'name'          => $key,
                                'price'         => isset($option->price) ? $option->price : 0.00,
                                'qty'           => $item['qty'],
                                'item_total'    => $item['subtotal'],
                                'value'         => $option,
                                'transaction_id' => $transaction_id,
                                'cart_item_id'  => $item['rowid'],
                                'user_id'       => $this->session->userdata('user_id'),
                                'item_type'     => $item['item_type'],
                            );
                        } else {
                            $orderObj = array(
                                'product_id'    => $option->id,
                                'product_type'  => $key,
                                'name'          => $option->name,
                                'price'         => isset($option->price) ? $option->price : 0.00,
                                'qty'           => $item['qty'],
                                'item_total'    => $item['subtotal'],
                                'value'         => isset($item['value']) ? $item['value'] : '',
                                'transaction_id' => $transaction_id,
                                'cart_item_id'  => $item['rowid'],
                                'user_id'       => $this->session->userdata('user_id'),
                                'item_type'     => $item['item_type'],
                            );
                        }
                    }
                }

                if(!empty($orderObj) && $doInsert) {
                    $this->db->insert('web_orders', $orderObj);
                }
            }

        }
    }

    private function sendPaymentEmail($transaction_id)
    {
        $this->load->library('email');
        if(strpos(base_url(), 'localhost') === false) {
            $this->email
                ->from(FROM_EMAIL_ADDRESS, FROM_EMAIL_NAME)
                ->to($this->session->userdata('email'))
                ->subject('Thanks for your order!')
                ->message($this->load->view('email-templates/order-success', array('transaction_id' => $transaction_id), true))
                ->set_mailtype('html');

            $this->email->send();
        }
    }

}