<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-file-o"></i> Submitted Forms</h2>

        <div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
        <div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>
        <div id="base_url" data-base="<?php echo base_url(); ?>"></div>

        <?php if($forms) { ?>
        <form id="viewByFormSubmitted" class="form-inline pull-right" style="margin-left: 15px;" action="<?php echo base_url('forms/form-submissions'); ?>" method="post">
            <select id="sortByFormNames" class="form-control" name="form_names">
                <?php
                    echo '<option value="all">Show All Forms</option>';
                    foreach($forms as $form) {
                        if($this->session->userdata('search_form_submission_name') == $form['id']) {
                            echo '<option selected value="' . $form['id'] . '">' . $form['name'] . '</option>';
                        } else {
                            echo '<option value="' . $form['id'] . '">' . $form['name'] . '</option>';
                        }
                    }
                ?>
            </select>
        </form>
        <?php } ?>
        <form id="perPageForm" class="form-inline pull-right" action="<?php echo base_url('forms/form-submissions'); ?>" method="post">
            <select id="formSubmissionsPerPage" name="limit" class="form-control">
                <?php
                    $options = array(10, 20, 25, 50);
                    $selectedNumber = $this->session->userdata('submission_limit') != '' ? $this->session->userdata('submission_limit') : '20';
                    foreach($options as $option) {
                        $selected = $option == $selectedNumber ? 'selected' : '';
                        echo '<option '.$selected.' value="'.$option.'">'.$option.' Per Page</option>';
                    }
                ?>
            </select>
        </form>
        <div class=" pull-right" style="max-width: 200px; margin-right: 15px">
            <form id="formSearchForm" class="form-inline pull-right" action="<?php echo base_url('forms/form-submissions'); ?>" method="post">
                <div class="input-group">
                    <input type="text" id="formSubmissionsSearch" name="search" placeholder="Search" class="form-control pull-right">
                    <span id="searchFormSubmission" class="input-group-addon search" id="basic-addon1"><i class="fa fa-search"></i></span>
                </div>
            </form>
        </div>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?php echo $table; ?>

        <?php
            if(isset($_POST['search'])) {
                echo '<a href="'.base_url('forms/form-submissions').'" class="btn btn-primary">Back All Results</a>';
            } else {
                echo $links;
            }
        ?>
    </div>
</div>

