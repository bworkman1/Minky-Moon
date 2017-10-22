<div class="top_nav">
    <div class="nav_menu">
        <nav class=" hidden-print">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo base_url('assets/themes/admin/images/img.jpg'); ?>" alt=""><?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name'); ?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li>
                            <a href="<?php echo base_url('users/my-account'); ?>">
                                <span>My Account</span>
                            </a>
                        </li>
                        <li><a data-toggle="modal" data-target="#needHelp">Help</a></li>
                        <li><a href="<?php echo base_url('login/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                    </ul>
                </li>

                <li role="presentation" class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bullhorn"></i>
                        <?php
                            $submittedForms = array();
                            if(count($submittedForms)>0) {
                                echo '<span class="badge bg-green">'.count($submittedForms).'</span>';
                            }
                        ?>
                    </a>
                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                        <?php
                        if(isset($submittedForms) && !empty($submittedForms)) {
                            foreach($submittedForms as $key => $submissions) {
                                echo '<li>';
                                    echo '<a href="'.base_url('forms/view-submitted-form/'.$key).'">';
                                        echo '<span>';
                                            echo '<span>';
                                                echo '<div>New Form Submitted!</div>';
                                                echo $submissions['name'];
                                            echo '</span>';
                                            echo '<span class="date">'.date('m-d-Y', strtotime($submissions['added'])).'</span>';
                                        echo '</span>';
                                    echo '</a>';
                                echo '</li>';
                            }
                        } else {
                            echo '<li>';
                            echo '<span>';
                            echo '<span>';
                            echo '<div>Nothing New to Report</div>';
                            echo '</span>';
                            echo '</span>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>



<div class="modal fade" id="needHelp" tabindex="-1" role="dialog" aria-labelledby="needHelp">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-question-circle"></i> Need Help</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="well well-sm">
                            <h4><i class="fa fa-phone"></i> Give us a call</h4>
                            <p>(740) 892-4700</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="well well-sm">
                            <h4><i class="fa fa-envelope"></i>  Email</h4>
                            <p>info@emf-websolutions.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>