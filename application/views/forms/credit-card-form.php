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

?>
<div class="row">
    <!-- You can make it whatever width you want. I'm making it full width
         on <= small devices and 4/12 page width on >= medium devices -->
    <div class="col-xs-12">

        <div class="panel panel-default credit-card-box">
            <div class="panel-heading display-table" >
                <h3 class="panel-title" >Billing Address</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="billingName"><span class="text-danger">*</span> Cardholder Name</label>
                    <input type="text" class="form-control" name="billing_name" id="billingName" placeholder="Name" required/>
                </div>

                <div class="form-group">
                    <label for="billingAddress"><span class="text-danger">*</span> Billing Address</label>
                    <input type="text" onfocus="initMap()" class="form-control address addressLookup" name="billing_address" id="billingAddress" placeholder="Address" required/>
                </div>

                <div class="row">

                    <div class="col-md-7">
                        <div class="form-group">
                            <label><span class="text-danger">*</span> Billing City</label>
                            <input type="text" class="form-control city" name="billing_city" id="billingCity" placeholder="City" required/>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label><span class="text-danger">*</span> Billing State</label>
                            <select name="billing_state" class="state form-control">
                                <option value="">Select State</option>
                                <?php
                                    foreach($us_state_abbrevs_names as $val => $state) {
                                        echo '<option value="'.$val.'">'.$state.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>

            </div>
        </div>



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
                                <input
                                    type="tel"
                                    class="form-control credit-card"
                                    name="cardNumber"
                                    id="cardNumber"
                                    placeholder="Valid Card Number"
                                    autocomplete="cc-number"
                                    required autofocus
                                    />
                                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7 col-md-7">
                        <div class="form-group">
                            <label for="cardExpiry"><span class="hidden-xs"><span class="text-danger">*</span> Expiration</span><span class="visible-xs-inline">Exp</span> Date</label>
                            <input
                                type="tel"
                                class="form-control cc-expires"
                                name="cardExpiry"
                                id="cardExpiry"
                                placeholder="MM / YY"
                                autocomplete="cc-exp"
                                maxlength="5"
                                required
                                />
                        </div>
                    </div>
                    <div class="col-xs-5 col-md-5 pull-right">
                        <div class="form-group">
                            <label for="cardCVC">CV Code <small>(On the back)</small></label>
                            <input
                                type="tel"
                                class="form-control"
                                name="cardCVC"
                                id="cardCVC"
                                placeholder="CVC"
                                autocomplete="cc-csc"
                                maxlength="5"
                                required
                                />
                        </div>
                    </div>

                    <?php if($form['form_settings']['min_cost'] > 0 && $form['form_settings']['min_cost'] < $form['form_settings']['cost']) { ?>
                        <div class="clearfix"></div>
                        <div class="col-xs-5 col-md-5 pull-right">
                            <div class="form-group">
                                <label for="amount">Payment Amount</label>
                                <input
                                    type="text"
                                    class="form-control money"
                                    name="amount"
                                    id="amount"
                                    placeholder="0.00"
                                    maxlength="6"
                                    value="<?php echo number_format($form['form_settings']['cost'], 2); ?>"
                                    required
                                    />
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="col-xs-5 col-md-5 pull-right">
                            <p><b>Cost:</b><?php echo number_format($form['form_settings']['cost'], 2); ?></p>
                        </div>
                    <?php } ?>
                </div>

                <div class="row" style="display:none;">
                    <div class="col-xs-12">
                        <p class="payment-errors"></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>