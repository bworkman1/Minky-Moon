<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-users"></i> All Payments <small><?php echo $payments.' Total'; ?></small></h2>

            <form id="perPageForm" class="form-inline pull-right" action="<?php echo base_url('payments/all'); ?>" method="post">
                <select id="formSubmissionsPerPage" name="limit" class="form-control">
                    <?php
                    $options = array(10, 20, 25, 50);
                    $selectedNumber = $this->session->userdata('submission_limit_payments') != '' ? $this->session->userdata('submission_limit_payments') : '20';
                    foreach($options as $option) {
                        $selected = $option == $selectedNumber ? 'selected' : '';
                        echo '<option '.$selected.' value="'.$option.'">'.$option.' Per Page</option>';
                    }
                    ?>
                </select>
            </form>
            <div class=" pull-right" style="max-width: 200px; margin-right: 15px">
                <form id="searchPage" class="form-inline pull-right" action="<?php echo base_url('payments/all'); ?>" method="post">
                    <div class="input-group">
                        <input type="text" id="formSubmissionsSearch" placeholder="Search" class="form-control pull-right" name="search">
                        <span id="searchFormSubmission" onclick="$('#searchPage').submit()" class="input-group-addon search" id="basic-addon1"><i class="fa fa-search"></i></span>
                    </div>
                </form>
            </div>

            <div class="clearfix"></div>
        </div>

        <div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
        <div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>

        <div class="x_content">
            <?php echo $table; ?>
            <?php echo $links; ?>
        </div>
    </div>
</div>