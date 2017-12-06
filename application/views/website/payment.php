<script>setTimeout(function() {shop.init();}, 1000);</script>
<div class="container">
    <?php
        $success = $this->session->flashdata('success');
        if($success) {
            echo '<div class="alert alert-success" style="margin-top:15px;"><i class="fa fa-check-circle"></i> '.$success.'</div>';
        }
    ?>
    <form id="paymentForm">
        <div class="whitePanel">
            <div class="cartPanel">
                <h3><i class="fa fa-shopping-cart"></i> Shopping Cart</h3>
                <hr>
                <?php
                    $couponCodes = $this->session->userdata('coupon_codes');

                    echo '<ul class="list-unstyled">';
                        foreach ($this->cart->contents() as $items) {
                            echo '<li style="border-bottom: 1px solid #ccc; margin-bottom: 4px;">';
                                echo '<div class="pull-left"><b>' . $items['name'] . '</b> (x'.$items['qty'].') </div>';
                                echo '<div class="pull-right">$'.$this->cart->format_number($items['subtotal']).'</div>';
                                echo '<div class="clearfix"></div>';
                            echo '</li>';
                        }

                        echo '<li style="border-bottom: 1px solid #ccc; margin-bottom: 4px;">';
                            echo '<div class="pull-left"><b>Shipping</b></div>';
                            echo '<div class="pull-right">$0.00</div>';
                            echo '<div class="clearfix"></div>';
                        echo '</li>';

                        $totalView = 'Total';
                        if(!empty($couponCodes)) {
                            $totalView = 'Before Coupons';
                        }

                        echo '<li class="text-right">';
                            echo '<b>'.$totalView.': ';
                            echo '$'.$this->cart->format_number($this->cart->total()).'</b>';
                        echo '</li>';


                        $cartTotal = $this->cart->format_number($this->cart->total());
                        if(!empty($couponCodes)) {

                            echo '<li><br></li>';
                            foreach($couponCodes as $codes) {


                                if($codes->discount_type == 'percent') {
                                    $amount = (float)$this->Shop_model->calculateDiscountAmountByPercent($codes, $cartTotal);
                                } elseif($codes->discount_type == 'amount') {
                                    $amount = (float)$this->Shop_model->calculateDiscountAmountByAmount($codes, $cartTotal);
                                }
                                $type = $codes->discount_type == 'percent' ? $codes->discount_amount.'%' : '$'.number_format($amount, 2);
                                $cartTotal = $cartTotal - $amount;

                                echo '<li style="border-top: 1px solid #ccc; margin-bottom: 4px;">';
                                    echo '<div class="pull-left"><b>Coupon</b> '.$codes->code.' Saved you <b>'.$type.'</b></div>';
                                    echo '<div class="pull-right">- $'.number_format($amount, 2).'</div>';
                                    echo '<div class="clearfix"></div>';
                                echo '</li>';
                            }
                            echo '<li class="text-right"><b>Total: $'.number_format($cartTotal, 2).'</b></li>';
                        }
                        echo '<li><br></li>';
                    echo '</ul>';

                    $disabledPaymentInfo = number_format($cartTotal, 2) > 0 ? '' : 'readonly disabled';
                    $submitbuttonTxt = number_format($cartTotal, 2) > 0 ? 'Submit Payment' : 'Submit Order';
                ?>

                <a href="<?php echo base_url('shop'); ?>" class="btn btn-danger" style="margin-bottom: 15px;">Continue Shopping</a>
                <button id="giftCard" data-toggle="modal" data-target="#giftcard" class="btn btn-primary btn-md pull-right">Add Coupon Code/Gift Card</button>
                <div class="clearfix"></div>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="shippingAddress">
                        <?php
                            $name = '';
                            $address = '';
                            $address2 = '';
                            $city = '';
                            $state = '';
                            $zip = '';
                            if(count((array)$saved_shipping)) {
                                $name       = $this->session->userdata('first_name').' '.$this->session->userdata('last_name');
                                $address    = $this->encrypt->decode($saved_shipping->address);
                                $address2   = $this->encrypt->decode($saved_shipping->additional_address);
                                $city       = $this->encrypt->decode($saved_shipping->city);
                                $state      = $this->encrypt->decode($saved_shipping->state);
                                $zip        = $this->encrypt->decode($saved_shipping->zip);
                            }
                        ?>
                        <h3><i class="fa fa-truck"></i> Shipping Address</h3>
                        <hr>
                        <div class="well well-sm">
                            <p><b>Email:</b> <?php echo $this->session->userdata('email'); ?><br><small>If you need to change your email address click the "Account" link at the top of the page. You will receive all emails to this address.</small></p>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><span class="text-danger">*</span> Full Name</label>
                            <div class="controls">
                                <input id="full-name" name="shipping_full_name" type="text" required maxlength="30" placeholder="full name" class="form-control" value="<?php echo $name; ?>">
                                <p class="help-block"></p>
                            </div>
                        </div>

                        <!-- address-line1 input-->
                        <div class="control-group">
                            <label class="control-label"><span class="text-danger">*</span> Address Line 1</label>
                            <div class="controls">
                                <input id="address-line1" name="shipping_address_line1" required maxlength="30" type="text" placeholder="address line 1" class="form-control" value="<?php echo $address; ?>">
                                <p class="help-block">Street address, P.O. box, company name, c/o</p>
                            </div>
                        </div>

                        <!-- address-line2 input-->
                        <div class="control-group">
                            <label class="control-label">Address Line 2</label>
                            <div class="controls">
                                <input id="address-line2" name="shipping_address_line2" required maxlength="30" type="text" placeholder="address line 2" class="form-control" value="<?php echo $address2; ?>">
                                <p class="help-block">Apartment, suite , unit, building, floor, etc.</p>
                            </div>
                        </div>

                        <!-- city input-->
                        <div class="control-group">
                            <label class="control-label"><span class="text-danger">*</span> City</label>
                            <div class="controls">
                                <input id="city" name="shipping_city" type="text" required maxlength="30" placeholder="city" class="form-control" value="<?php echo $city; ?>">
                                <p class="help-block"></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <!-- state input-->
                                <div class="control-group">
                                    <label class="control-label"><span class="text-danger">*</span> State</label>
                                    <div class="controls">
                                        <select id="shipping-state" name="shipping_state" required class="form-control">
                                            <option value="">State</option>
                                            <?php
                                                foreach(allStates() as $key => $val) {
                                                    if($key == $state) {
                                                        echo '<option selected value="' . $key . '">' . $val . '</option>';
                                                    } else {
                                                        echo '<option value="' . $key . '">' . $val . '</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="control-group">
                                    <label class="control-label"><span class="text-danger">*</span> Zip</label>
                                    <div class="controls">
                                        <input id="shipping-zip" name="shipping_zip" type="text" required maxlength="5" placeholder="zip" class="form-control" value="<?php echo $zip; ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="shippingAddress">
                        <h3><i class="fa fa-home"></i> Billing Address</h3>
                        <hr>
                        <label><input type="checkbox" id="sameAsShipping"> Copy from Shipping?</label><br><br>
                        <div class="control-group">
                            <label class="control-label"><span class="text-danger">*</span> Full Name</label>
                            <div class="controls">
                                <input id="billing-full-name" name="billing_full_name" type="text" required maxlength="30" placeholder="full name" class="form-control">
                                <p class="help-block"></p>
                            </div>
                        </div>

                        <!-- address-line1 input-->
                        <div class="control-group">
                            <label class="control-label"><span class="text-danger">*</span> Address Line 1</label>
                            <div class="controls">
                                <input id="billing-address-line1" name="billing_address_line1" required maxlength="30" type="text" placeholder="address line 1" class="form-control">
                                <p class="help-block">Street address, P.O. box, company name, c/o</p>
                            </div>
                        </div>

                        <!-- address-line2 input-->
                        <div class="control-group">
                            <label class="control-label">Address Line 2</label>
                            <div class="controls">
                                <input id="billing-address-line2" name="billing_address_line2" required maxlength="30" type="text" placeholder="address line 2" class="form-control">
                                <p class="help-block">Apartment, suite , unit, building, floor, etc.</p>
                            </div>
                        </div>

                        <!-- city input-->
                        <div class="control-group">
                            <label class="control-label"><span class="text-danger">*</span> City</label>
                            <div class="controls">
                                <input id="billing-city" name="billing_city" type="text" required maxlength="30" placeholder="city" class="form-control">
                                <p class="help-block"></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <!-- state input-->
                                <div class="control-group">
                                    <label class="control-label"><span class="text-danger">*</span> State</label>
                                    <div class="controls">
                                        <select id="billing-state" name="billing_state" required class="form-control">
                                            <option value="">State</option>
                                            <?php
                                            foreach(allStates() as $key => $val) {
                                                echo '<option value="'.$key.'">'.$val.'</option>';
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <!-- postal-code input-->
                                <div class="control-group">
                                    <label class="control-label"><span class="text-danger">*</span> Zip</label>
                                    <div class="controls">
                                        <input id="billing-zip" name="billing_zip" required maxlength="5" type="text" placeholder="zip" class="form-control">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="paymentSection">
                        <h3><i class="fa fa-credit-card"></i> Payment Details</h3>
                        <hr>
                        <p><i class="fa fa-lock pull-left fa-4x text-primary" style="font-size:5em;"></i> <small>Your payment and personal details are secure. We do not share your data with third partys and no payment details are saved on our server. For further details check out our <a href="http://theminkymoon.com/privacy-policy/">privacy policy</a>.</small></p>
                        <div class='row'>
                            <div class='col-xs-12 form-group'>
                                <label class='control-label'><span class="text-danger">*</span> Name on Card</label>
                                <input class='form-control' name="card_name" required maxlength="30" type='text' <?php echo $disabledPaymentInfo; ?>>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-xs-12 form-group'>
                                <label class='control-label'><span class="text-danger">*</span> Card Number</label>
                                <input autocomplete='off' name="card_number" required maxlength="19" class='form-control credit-card card-number' type='text' <?php echo $disabledPaymentInfo; ?>>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-xs-4 form-group'>
                                <label class='control-label'><span class="text-danger">*</span> CVC</label>
                                <input autocomplete='off' name="cvc" class='form-control card-cvc' placeholder='ex. 311' size='4' type='text' <?php echo $disabledPaymentInfo; ?>>
                            </div>
                            <div class='col-xs-4 form-group'>
                                <label class='control-label'><span class="text-danger">*</span> Expiration</label>
                                <select class='form-control card-expiry-month' name="month" <?php echo $disabledPaymentInfo; ?>>
                                    <option value="">Month</option>
                                    <?php
                                        for($i=1;$i<13;$i++) {
                                            echo '<option value="'.str_pad($i, 2, 0, STR_PAD_LEFT).'">'.str_pad($i, 2, 0, STR_PAD_LEFT).'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class='col-xs-4 form-group'>
                                <label class='control-label'> </label>
                                <select name="year" class="form-control" <?php echo $disabledPaymentInfo; ?>>
                                    <option value="">Year</option>
                                    <?php
                                        $year = (int)date('Y');
                                        for($i=0;$i<11;$i++) {
                                            $option = $i+$year;
                                            echo '<option value="'.$option.'">'.$option.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <p class="text-right"><b>Payment Total: <?php echo '$'.$this->cart->format_number($cartTotal); ?></b></p>
                        <hr>

                        <button id="submitPayment" class="btn btn-success btn-md pull-right"><?php echo $submitbuttonTxt; ?></button>
                        <div class="clearfix"></div>
                        <br>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>


<div id="giftcard" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Coupon Code or Gift Certificate</h4>
            </div>
            <div class="modal-body">
                <label>Enter Code</label>
                <input type="text" class="form-control" maxlength="40" id="discountCode">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button id="applyDiscount" type="button" class="btn btn-success">Apply</button>
            </div>
        </div>

    </div>
</div>