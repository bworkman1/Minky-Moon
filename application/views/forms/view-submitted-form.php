<div class="container">
    <div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
    <div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>
    <div id="base_url" data-base="<?php echo base_url(); ?>"></div>
    <?php
    if(!empty($form)) {
        echo '<div class="row">';
        echo '<div class="col-md-8">';
        echo '<div class="x_panel">';
        echo '<div class="x_title hidden-print">';
        echo '<h2><i class="fa fa-file-o"></i> '.ucwords($form['form_settings']['name']).'</h2>';
        echo '<ul class="nav navbar-right panel_toolbox">';
        echo '<li>';
        echo '<a href="'. base_url('forms/form-submissions').'"><i class="fa fa-file"></i> All Submitted Forms</a>';
        echo ' </li>';
        echo '</ul>';
        echo '<div class="clearfix"></div>';
        echo '</div>';

        echo '<div class="x_content">';
        if($form['form_settings']['header'] != '') {
            echo '<p>' . $form['form_settings']['header'] . '</p>';
            echo '<hr>';
        }
        echo '<div class="row">';

        $submitted = '';
        $submission_id = '';
        foreach($form['form_inputs'] as $val) {
            if(isset($values[$val['input_name']]) && !empty($values[$val['input_name']])) {
                if(is_array($values[$val['input_name']])) {
                    echo '<div class="' . $val['input_columns'] . ' col-print-'.preg_replace("/[^0-9,.]/", "", $val['input_columns']).'">';
                        echo '<div class="underline">';
                            echo '<label>'.$val['input_label'].'</label>';
                            echo '<p>';
                                foreach($values[$val['input_name']] as $row) {
                                    if($val['encrypt_data']) {
                                        $row['value'] = $this->encrypt->decode($row['value']);
                                    }
                                    if($val['input_type'] == 'checkbox') {
                                        echo '<i class="fa fa-check"></i> ' . htmlspecialchars($row['value']) . ' ';
                                    } else {
                                        echo htmlspecialchars($row['value']);
                                    }
                                    $submitted = date('m/d/Y h:i A', strtotime($row['added']));
                                    $submission_id = $row['submission_id'];
                                }
                            echo '</p>';
                        echo '</div>';
                    echo '</div>';
                }
            }
        }
        echo '</div>';
        if($form['form_settings']['footer'] != '') {
            echo '<hr>';
            echo '<p>' . $form['form_settings']['footer'] . '</p>';
        }

        if($submitted) {
            echo '<br>';
            echo '<div class="well well-sm">';
                echo '<b><i class="fa fa-clock-o"></i> Submitted: </b>'.$submitted;
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        echo '</div>';

        echo '<div class="col-md-4">';

        echo '<div class="x_panel">';

        echo '<div class="x_title hidden-print">';
        echo '<h2><i class="fa fa-credit-card-alt"></i> Payment Details</h2>';
        echo '<ul class="nav navbar-right panel_toolbox">';
        echo '<li>';
        echo '<a href="'.base_url("payments").'"><i class="fa fa-money"></i> View All Payments</a>';
        echo ' </li>';
        echo '</ul>';
        echo '<div class="clearfix"></div>';
        echo '</div>';

        echo '<div class="x_content">';

        $totalPaid = 0;
        if($payments) {
            foreach($payments as $key => $payment) {

                echo '<div class="panel panel-default">';
                    echo '<div class="panel-heading">Payment Made</div>';
                    echo '<div class="panel-body">';
                        echo '<h3 style="margin:0 0 15px 0">'.htmlentities($this->encrypt->decode($payment->billing_name)).'</h3>';
                        echo '<p><b>Billing Address:</b> '.htmlentities($this->encrypt->decode($payment->billing_address)).', ';
                        echo htmlentities($this->encrypt->decode($payment->billing_city).' '.$this->encrypt->decode($payment->billing_state).' '.$this->encrypt->decode($payment->billing_zip)).'</p>';
                        echo '<p><b>Transaction Id:</b> '.$payment->transaction_id.'</p>';
                        echo '<p><b>Approval Code:</b> '.$payment->approval_code.'</p>';
                        echo '<p><b>Payment Date:</b> '.date('m/d/Y h:i A', strtotime($payment->date)).'</p>';
                        echo '<p><b>Payment Amount:</b> $'.number_format($payment->amount, 2).'</p>';
                    echo '</div>';
                echo '</div>';

                $totalPaid = $totalPaid+$payment->amount;
            }
        } else {
            echo '<ul class="list-group">';
            echo '<li class="list-group-item list-group-item-warning">';
            echo '<b><i class="fa fa-exclamation-triangle"></i> No Payment Found For This Submission</b>';
            echo '</li>';
            echo '</ul>';
        }

        $showMakePaymentButton = false;

        if($form['form_settings']['cost'] > 0) {
            echo '<hr>';
            echo '<div class="well well-sm">';
                echo '<h4><b>Total Paid:</b> $'.number_format($totalPaid, 2);
            echo '</div>';

            if($form['form_settings']['cost'] > $totalPaid) {
                $due = $form['form_settings']['cost'] - $totalPaid;
                $showMakePaymentButton = true;
                echo '<div class="alert alert-danger">Amount Due: $'.number_format($due, 2).'</div>';
            }
        }

        echo '</div>';

        echo '<hr>';

        if($showMakePaymentButton && $submission_id != '') {
            echo '<a href="'.base_url('payments/submit-payment/'.$submission_id).'" class="hidden-print btn btn-primary pull-left"><i class="fa fa-money"></i> Make Payment</a>';
        }

        echo '<button class="hidden-print btn btn-info pull-right" onclick="window.print();"><i class="fa fa-print"></i> Print Form</button>';

        echo '</div>'; // closes x_panel
        echo '</div>'; // closes col-md-4

        echo '</div>';
    }
    ?>
</div>