<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-file-o"></i> Submitted Forms</h2>
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
            <div class="input-group">
                <input type="text" id="formSubmissionsSearch" placeholder="Search" class="form-control pull-right">
                <span id="searchFormSubmission" class="input-group-addon search" id="basic-addon1"><i class="fa fa-search"></i></span>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?php echo $table; ?>
        <?php echo $links; ?>
    </div>
</div>

