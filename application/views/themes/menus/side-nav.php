<div class="menu_section">
    <h3>General</h3>
    <ul class="nav side-menu">
        <?php if($this->ion_auth->is_admin()) { ?>
            <li><a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?php echo base_url('users/add-user'); ?>">Add New User</a></li>
                    <li><a href="<?php echo base_url('users'); ?>">View All Users</a></li>
                </ul>
            </li>
        <?php } ?>

        <li><a><i class="fa fa-files-o"></i> Forms <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="<?php echo base_url('forms/add-form'); ?>">Add New Form</a></li>
                <li><a href="<?php echo base_url('forms/all-forms'); ?>">View All Forms</a></li>
            </ul>
        </li>

        <?php
            $group = 'View Submitted Forms';
            if ($this->ion_auth->in_group($group) || $this->ion_auth->is_admin()) {
                echo '<li><a href="'.base_url('forms/form-submissions').'"><i class="fa fa-file-o"></i> Submitted Forms</a></li>';
            }
        ?>


        <li><a><i class="fa fa-money"></i> Payments <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="<?php echo base_url('payments'); ?>">All Payments</a></li>
                <li><a href="<?php echo base_url('payments/submit-payment'); ?>">Submit Payment</a></li>
            </ul>
        </li>
        <li>
            <a href="<?php echo base_url('calendar'); ?>"><i class="fa fa-calendar"></i> Calendar</a>
        </li>
    </ul>
</div>

