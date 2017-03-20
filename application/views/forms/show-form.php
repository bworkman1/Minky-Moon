<div class="container">
    <div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
    <div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>
    <div id="base_url" data-base="<?php echo base_url(); ?>"></div>
    <?php
        if(!empty($form)) {
            echo '<div class="row">';
                echo '<div class="col-md-8">';
                    echo '<div class="x_panel">';
                        echo '<div class="x_title">';
                            echo '<h2><i class="fa fa-file-o"></i> '.ucwords($form['form_settings']['name']).'</h2>';
                            echo '<ul class="nav navbar-right panel_toolbox">';
                                echo '<li>';
                                    echo '<a href="http://localhost/lapp/forms/all-forms"><i class="fa fa-file"></i> All Forms</a>';
                                echo ' </li>';
                            echo '</ul>';
                            echo '<div class="clearfix"></div>';
                        echo '</div>';

                        echo '<div class="x_content">';
                            echo '<p>'.$form['form_settings']['header'].'</p>';
                            echo '<hr>';
                            echo '<div class="row">';
                                foreach($form['form_inputs'] as $val) {
                                    switch($val['input_type']) {
                                        case 'text':
                                            echo '<div class="'.$val['input_columns'].'" data-sequence="'.$val['sequence'].'" data-validation="'.$val['input_validation'].'">';
                                            echo '<div class="form-group">';
                                            $required = '';
                                            if (strpos($val['input_validation'], 'required') !== false) {
                                                $required = '<span class="text-danger">*</span> ';
                                            }
                                            echo '<label>'.$required.$val['input_label'].'</label>';
                                            echo '<input class="'.$val['custom_class'].' form-control" name="'.$val['input_name'].'"/>';
                                            echo '</div>';
                                            echo '</div>';
                                            break;

                                        case 'select':
                                            echo '<div class="'.$val['input_columns'].'" data-sequence="'.$val['sequence'].'" data-validation="'.$val['input_validation'].'">';
                                            echo '<div class="form-group">';
                                            $required = '';
                                            if (strpos($val['input_validation'], 'required') !== false) {
                                                $required = '<span class="text-danger">*</span> ';
                                            }
                                            echo '<label>'.$required.$val['input_label'].'</label>';
                                            echo '<select class="'.$val['custom_class'].' form-control" name="'.$val['input_name'].'">';
                                            echo '<option value="">Select One</option>';
                                            foreach($val['options'] as $option) {
                                                echo '<option  value="'.$option['value'].'">'.ucwords($option['name']).'</option>';
                                            }
                                            echo '</select>';
                                            echo '</div>';
                                            echo '</div>';
                                            break;

                                        case 'textarea':
                                            echo '<div class="'.$val['input_columns'].'" data-sequence="'.$val['sequence'].'" data-validation="'.$val['input_validation'].'">';
                                            echo '<div class="form-group">';
                                            echo '<label>'.$val['input_label'].'</label>';
                                            echo '<textarea class="'.$val['custom_class'].' form-control" name="'.$val['input_name'].'"></textarea>';
                                            echo '</div>';
                                            echo '</div>';
                                            break;

                                        case 'checkbox':
                                            echo '<div class="'.$val['input_columns'].'" data-sequence="'.$val['sequence'].'" data-validation="'.$val['input_validation'].'">';
                                            $required = '';
                                            if (strpos($val['input_validation'], 'required') !== false) {
                                                $required = '<span class="text-danger">*</span> ';
                                            }
                                            echo '<label>'.$required.$val['input_label'].'</label><br>';
                                            foreach($val['options'] as $option) {
                                                $inline = 'checkbox';
                                                if($val['input_inline']) {
                                                    $inline = 'checkbox-inline';
                                                }
                                                echo '<div class="'.$inline.'">';
                                                echo '<label><input type="checkbox" class="' . $val['custom_class'] . '" name="' . $option['name'] . '[]" value="'.$option['value'].'"> ' . ucwords($option['name']) . '</label>';
                                                echo '</div>';
                                            }
                                            echo '</div>';

                                            break;
                                        default:
                                            break;
                                    }
                                }
                            echo '</div>';
                            echo '<hr>';
                            echo '<p>'.$form['form_settings']['footer'].'</p>';

                            echo '<hr>';
                            if($form['form_settings']['cost'] > 0) {
                                echo '<button type="button" id="addPaymentDetails" class="btn btn-info btn-lg pull-right" data-toggle="modal" data-target="#paymentModal">Add Payment Details</button>';
                            } else {
                                echo '<button type="button" id="submitNonPaymentForm" class="btn btn-info btn-lg pull-right">Submit</button>';
                            }
                            echo '<div class="clearfix"></div>';

                        echo '</div>';
                    echo '</div>';

                echo '</div>';

                echo '<div class="col-md-4">';

                    echo '<div class="x_panel">';

                        echo '<div class="x_title">';
                            echo '<h2><i class="fa fa-gears"></i> Form Settings</h2>';
                            echo '<ul class="nav navbar-right panel_toolbox">';
                                echo '<li>';
                                    echo '<a href="http://localhost/lapp/forms/edit-form/'.$form['form_settings']['id'].'"><i class="fa fa-file"></i> Edit Form</a>';
                                echo ' </li>';
                            echo '</ul>';
                            echo '<div class="clearfix"></div>';
                        echo '</div>';

                        echo '<div class="x_content">';

                            echo '<ul class="list-group">';
                                echo '<li class="list-group-item">';
                                    echo '<b>Name:</b> <span class="pull-right">'.ucwords($form['form_settings']['name']).'</span>';
                                echo '</li>';
                                echo '<li class="list-group-item">';
                                    echo '<b>Added:</b> <span class="pull-right">'.date('m/d/Y h:i A', strtotime($form['form_settings']['added'])).'</span>';
                                echo '</li>';
                                echo '<li class="list-group-item">';
                                    echo '<b>Last Updated:</b> <span class="pull-right">'.date('m/d/Y h:i A', strtotime($form['form_settings']['updated'])).'</span>';
                                echo '</li>';
                                echo '<li class="list-group-item">';
                                    echo '<b>Cost:</b> <span class="pull-right">$'.number_format($form['form_settings']['cost'], 2).'</span>';
                                echo '</li>';
                                echo '<li class="list-group-item">';
                                    echo '<b>Min Amount:</b> <span class="pull-right">$'.number_format($form['form_settings']['min_cost'], 2).'</span>';
                                echo '</li>';
                                echo '<li class="list-group-item">';
                                    $active = $form['form_settings']['active'] > 0 ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span></span>';
                                    echo '<b>Active:</b> <span id="formStatusView" class="pull-right">'.$active.'</span>';
                                echo '</li>';
                            echo '</ul>';

                            if($form['form_settings']['active'] == 0) {
                                echo '<button id="toggleForm" data-id="'.$form['form_settings']['id'].'" data-status="active" class="btn btn-primary pull-right">Activate Form</button>';
                            } else {
                                echo '<button id="toggleForm" data-id="'.$form['form_settings']['id'].'" data-status="inactive" class="btn btn-primary pull-right">Deactivate Form</button>';
                            }

                        echo '</div>';

                    echo '</div>'; // closes x_panel
                echo '</div>'; // closes col-md-4

            echo '</div>';
        }
    ?>
</div>


<div id="paymentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Payment Details</h4>
            </div>
            <div class="modal-body">


                <div class="row">
                    <!-- You can make it whatever width you want. I'm making it full width
                         on <= small devices and 4/12 page width on >= medium devices -->
                    <div class="col-xs-12">


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
                                            <label for="cardNumber">CARD NUMBER</label>
                                            <div class="input-group">
                                                <input
                                                    type="tel"
                                                    class="form-control credit-card"
                                                    name="cardNumber"
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
                                            <label for="cardExpiry"><span class="hidden-xs">EXPIRATION</span><span class="visible-xs-inline">EXP</span> DATE</label>
                                            <input
                                                type="tel"
                                                class="form-control cc-expires"
                                                name="cardExpiry"
                                                placeholder="MM / YY"
                                                autocomplete="cc-exp"
                                                maxlength="5"
                                                required
                                                />
                                        </div>
                                    </div>
                                    <div class="col-xs-5 col-md-5 pull-right">
                                        <div class="form-group">
                                            <label for="cardCVC">CV CODE</label>
                                            <input
                                                type="tel"
                                                class="form-control"
                                                name="cardCVC"
                                                placeholder="CVC"
                                                autocomplete="cc-csc"
                                                maxlength="5"
                                                required
                                                />
                                        </div>
                                    </div>

                                    <?php if($form['form_settings']['min_cost'] > 0 && $form['form_settings']['min_cost'] < $form['form_settings']['cost']) { ?>
                                        <div class="col-xs-5 col-md-5 pull-right">
                                            <div class="form-group">
                                                <label for="amount">Payment Amount</label>
                                                <input
                                                    type="text"
                                                    class="form-control money"
                                                    name="amount"
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


            </div>
            <div class="modal-footer">
                <button class="subscribe btn btn-success" type="button">Submit Form</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

