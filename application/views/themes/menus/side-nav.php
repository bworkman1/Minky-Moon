<div class="menu_section">
    <h3>General</h3>
    <ul class="nav side-menu">
        <li>
            <a href="<?php echo base_url('dashboard'); ?>">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        </li>

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
                <li><a href="<?php echo base_url('forms/form-submissions'); ?>">Form Submissions</a></li>
            </ul>
        </li>

        <li><a><i class="fa fa-user"></i> Clients <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="#">View Clients</a></li>
                <li><a href="#">Add New Client</a></li>
            </ul>
        </li>

        <li><a><i class="fa fa-money"></i> Payments <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="<?php echo base_url('payments'); ?>">All Payments</a></li>
                <li><a href="#">Partial Payments</a></li>
                <li><a href="<?php echo base_url('payments/submit-payment'); ?>">Submit Payment</a></li>
            </ul>
        </li>
        <li><a><i class="fa fa-calendar"></i> Calendar <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="#">View Calendar</a></li>
                <li><a href="#">Add Event</a></li>
            </ul>
        </li>
    </ul>
</div>

