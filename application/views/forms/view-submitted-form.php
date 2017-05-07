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
        foreach($form['form_inputs'] as $val) {
            if(isset($values[$val['input_name']]) && !empty($values[$val['input_name']])) {
                if(is_array($values[$val['input_name']])) {
                    echo '<div class="' . $val['input_columns'] . '">';
                        echo '<div class="underline">';
                            echo '<label>'.$val['input_label'].'</label>';
                            echo '<p>';
                                foreach($values[$val['input_name']] as $row) {
                                    if($val['input_type'] == 'checkbox') {
                                        echo '<i class="fa fa-check"></i> ' . htmlspecialchars($row['value']) . ' ';
                                    } else {
                                        echo htmlspecialchars($row['value']);
                                    }
                                    $submitted = date('m/d/Y h:i A', strtotime($row['added']));
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

        echo '<ul class="list-group">';

        if($payment) {
            foreach($payment as $key => $val) {
                if($key == 'id' || $key == 'form_id') {
                    continue;
                }
                echo '<li class="list-group-item">';
                    $display = $val;
                    if($key == 'date') {
                        $display = date('m/d/Y h:i A', strtotime($val));
                    }

                    $encrypted = array('billing_name', 'billing_address', 'billing_city', 'billing_state', 'billing_zip');
                    if(in_array($key, $encrypted)) {
                        $display = $this->encrypt->decode($val);
                    }

                    if($key == 'form_cost' || $key == 'amount') {
                        $display = '$'.number_format($val, 2);
                    }

                    echo '<b>'.ucwords(str_replace('_', ' ', $key)).':</b> <span class="pull-right">'.htmlentities($display).'</span>';
                echo '</li>';
            }
        } else {
            echo '<li class="list-group-item list-group-item-warning">';
            echo '<b><i class="fa fa-exclamation-triangle"></i> No Payment Found For This Submission</b>';
            echo '</li>';
        }
        echo '</ul>';



        echo '</div>';

        echo '<hr>';

        echo '<button class="hidden-print btn btn-info pull-right" onclick="window.print();"><i class="fa fa-print"></i> Print Form</button>';

        echo '</div>'; // closes x_panel
        echo '</div>'; // closes col-md-4

        echo '</div>';
    }
    ?>
</div>