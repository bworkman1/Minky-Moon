<?php
$us_state_abbrevs_names = array(
    'AL'=>'ALABAMA',
    'AK'=>'ALASKA',
    'AZ'=>'ARIZONA',
    'AR'=>'ARKANSAS',
    'CA'=>'CALIFORNIA',
    'CO'=>'COLORADO',
    'CT'=>'CONNECTICUT',
    'DE'=>'DELAWARE',
    'DC'=>'DISTRICT OF COLUMBIA',
    'FL'=>'FLORIDA',
    'GA'=>'GEORGIA',
    'GU'=>'GUAM GU',
    'HI'=>'HAWAII',
    'ID'=>'IDAHO',
    'IL'=>'ILLINOIS',
    'IN'=>'INDIANA',
    'IA'=>'IOWA',
    'KS'=>'KANSAS',
    'KY'=>'KENTUCKY',
    'LA'=>'LOUISIANA',
    'ME'=>'MAINE',
    'MH'=>'MARSHALL ISLANDS',
    'MD'=>'MARYLAND',
    'MA'=>'MASSACHUSETTS',
    'MI'=>'MICHIGAN',
    'MN'=>'MINNESOTA',
    'MS'=>'MISSISSIPPI',
    'MO'=>'MISSOURI',
    'MT'=>'MONTANA',
    'NE'=>'NEBRASKA',
    'NV'=>'NEVADA',
    'NH'=>'NEW HAMPSHIRE',
    'NJ'=>'NEW JERSEY',
    'NM'=>'NEW MEXICO',
    'NY'=>'NEW YORK',
    'NC'=>'NORTH CAROLINA',
    'ND'=>'NORTH DAKOTA',
    'MP'=>'NORTHERN MARIANA ISLANDS',
    'OH'=>'OHIO',
    'OK'=>'OKLAHOMA',
    'OR'=>'OREGON',
    'PW'=>'PALAU',
    'PA'=>'PENNSYLVANIA',
    'PR'=>'PUERTO RICO',
    'RI'=>'RHODE ISLAND',
    'SC'=>'SOUTH CAROLINA',
    'SD'=>'SOUTH DAKOTA',
    'TN'=>'TENNESSEE',
    'TX'=>'TEXAS',
    'UT'=>'UTAH',
    'VT'=>'VERMONT',
    'VI'=>'VIRGIN ISLANDS',
    'VA'=>'VIRGINIA',
    'WA'=>'WASHINGTON',
    'WV'=>'WEST VIRGINIA',
    'WI'=>'WISCONSIN',
    'WY'=>'WYOMING',
);

    $name       = '';
    $address    = '';
    $city       = '';
    $state      = '';
    $zip        = '';
    $client_id  = '';
    $amount_left  = 0;
    $amount_paid  = 0;
    $form_name = '';

    if(isset($lookup) && !empty($lookup)) {
        $name         = $lookup['first_name']['value'].' '.$lookup['last_name']['value'];
        $address      = $lookup['address']['value'];
        $city         = $lookup['city']['value'];
        $form_state   = $lookup['state']['value'];
        $zip          = $lookup['zip']['value'];
        $client_id    = $lookup['client_id']['value'];
        $amount_left  = $lookup['amount_left']['value'];
        $amount_paid  = $lookup['amount_paid']['value'];
        $form_name    = $lookup['form_name']['value'];
    }

?>
<form id="payment-form">
    <div id="base_url" class="row" data-base="<?php echo base_url(); ?>">
        <div class="col-md-4">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                    <h3 class="panel-title">Client Details</h3>
                </div>
                <div class="panel-body">
                    <p>If this payment is a payment for a form that the user has already filled out and made a partial payment on you can go to their submitted form and tie the payment to that particular form.</p>
                    <div class="form-group">
                        <label for="name"><span class="text-danger">*</span> Client Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" required/>
                    </div>

                    <div class="form-group">
                        <label for="billingAddress"><span class="text-danger">*</span> Address</label>
                        <input type="text" class="form-control address addressLookup" name="address" id="address" value="<?php echo $address; ?>" placeholder="Address" required/>
                    </div>

                    <div class="row">

                        <div class="col-md-7">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> City</label>
                                <input type="text" class="form-control city" name="city" id="city" placeholder="City" value="<?php echo $city; ?>" required/>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> State</label>
                                <select name="state" id="state" class="state form-control">
                                    <option value="">Select State</option>
                                    <?php
                                        foreach($us_state_abbrevs_names as $key => $state) {
                                            if($form_state == $key || ($key == 'OH' && $state == '')) {
                                                echo '<option value="'.$key.'" selected>'.ucwords(strtolower($state)).'</option>';
                                            } else {
                                                echo '<option value="'.$key.'">'.ucwords(strtolower($state)).'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-5 col-sm-6 col-xs-8">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Zip</label>
                                <input type="text" class="form-control zip" value="<?php echo $zip; ?>" maxlength="5" name="zip" id="zip" placeholder="Zip" required/>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-5 col-sm-6 col-xs-8">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Client Number</label>
                                <input type="text" class="form-control zip" maxlength="6" value="<?php echo $client_id; ?>" name="client_number" id="client_number" placeholder="AA0000" required/>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- You can make it whatever width you want. I'm making it full width
             on <= small devices and 4/12 page width on >= medium devices -->
        <div class="col-md-4">

            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                    <h3 class="panel-title" >Billing Address</h3>
                </div>
                <div class="panel-body">
                    <label style="margin-top: 10px;"><input type="checkbox" id="copyHomeAddress"> Copy Client Details</label>
                    <br>

                    <div class="form-group">
                        <label for="billingName"><span class="text-danger">*</span> Cardholder Name</label>
                        <input type="text" class="form-control" name="billing_name" id="billing_name" placeholder="Name" required/>
                    </div>

                    <div class="form-group">
                        <label for="billingAddress"><span class="text-danger">*</span> Billing Address</label>
                        <input type="text" onfocus="initMap()" class="form-control address addressLookup" name="billing_address" id="billing_address" placeholder="Address" required/>
                    </div>

                    <div class="row">

                        <div class="col-md-7">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Billing City</label>
                                <input type="text" class="form-control city" name="billing_city" id="billing_city" placeholder="City" required/>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Billing State</label>
                                <select name="billing_state" id="billing_state" class="state form-control">
                                    <option value="">Select State</option>
                                    <?php
                                    foreach($us_state_abbrevs_names as $val => $state) {
                                        if($val == 'OH') {
                                            echo '<option value="'.$val.'" selected>'.ucwords(strtolower($state)).'</option>';
                                        } else {
                                            echo '<option value="'.$val.'">'.ucwords(strtolower($state)).'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-5 col-sm-6 col-xs-8">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Billing Zip</label>
                                <input type="text" class="form-control zip" maxlength="5" name="billing_zip" id="billing_zip" placeholder="Zip" required/>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- CREDIT CARD FORM STARTS HERE -->
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                    <div class="row display-tr" >
                        <h3 class="panel-title display-td" >Payment Details</h3>
                        <div class="display-td" >
                            <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="cardNumber"><span class="text-danger">*</span> Card Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control credit-card" name="cardNumber" id="cardNumber" placeholder="Valid Card Number" autocomplete="cc-number" required autofocus />
                                    <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-7 col-md-7">
                            <div class="form-group">
                                <label for="cardExpiry"><span class="hidden-xs"><span class="text-danger">*</span> Expiration</span><span class="visible-xs-inline">Exp</span> Date</label>
                                <input type="text" class="form-control cc-expires" name="cardExpiry" id="cardExpiry" placeholder="MM / YY"
                                       autocomplete="cc-exp"
                                       maxlength="5"
                                       required
                                />
                            </div>
                        </div>
                        <div class="col-xs-5 col-md-5 pull-right">
                            <div class="form-group">
                                <label for="cardCVC"><span class="text-danger">*</span> CV Code <small>(On the back)</small></label>
                                <input type="text" class="form-control" name="cardCVC" id="cardCVC" placeholder="CVC" autocomplete="cc-csc" maxlength="5" required />
                            </div>
                        </div>

                        <div class="col-xs-5 col-md-5">
                            <div class="form-group">
                                <label for="amount"><span class="text-danger">*</span> Payment Amount</label>
                                <input type="text" class="form-control money" name="amount" id="amount" placeholder="0.00" maxlength="6" value="<?php echo $amount_left; ?>" required />
                            </div>
                        </div>

                        <?php
                            if($this->uri->segment(3) != '') {
                                echo '<div class="col-xs-7 col-md-7">';
                                echo '<div class="well well-sm text-warning" style="margin-top: 20px;">';
                                echo '<i class="fa fa-exclamation-triangle"></i> $' . $amount_paid . ' of $' . number_format(($amount_left + $amount_paid), 2).' Paid';
                                echo '</div>';
                                echo '</div>';
                            }
                        ?>

                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="amount"><span class="text-danger">*</span> Payment For</label>
                                <input type="text" class="form-control" name="for" id="for" maxlength="100" value="<?php echo $form_name; ?>" required />
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display:none;">
                        <div class="col-xs-12">
                            <p class="payment-errors"></p>
                        </div>
                    </div>
                    <hr>
                    <input type="hidden" class="form-control" name="submission_id" id="submission_id" value="<?php echo $this->uri->segment(3); ?>" />
                    <?php
                        if($this->uri->segment(3) != '') {
                            echo '<a href="'.base_url('forms/view-submitted-form/'.$this->uri->segment(3)).'" class="btn btn-primary">View Form Submission</a>';
                        }
                    ?>
                    <button id="submitPayment" class="btn btn-info pull-right">Submit Payment</button>

                </div>
            </div>

        </div>

    </div>
</form>