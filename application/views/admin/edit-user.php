<div class="">
    <div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
    <div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <?php
                        if($this->uri->segment(3) == '') {
                            echo '<h2><i class="fa fa-user"></i> My Account</h2>';
                        } else {
                            echo '<h2><i class="fa fa-user"></i> Edit User</h2>';
                            echo '<ul class="nav navbar-right panel_toolbox">';
                                echo '<li>';
                                    echo '<a href="<?php echo base_url(\'users/\'); ?>"><i class="fa fa-users"></i> All Users</a>';
                                echo '</li>';
                            echo '</ul>';
                        }
                    ?>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br>
                    <form id="input-form" action="<?php echo base_url('users/save-edit-user/'.$this->uri->segment(3)); ?>" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                        <div class="row">
                            <div id="formFeedback" class="col-md-6 col-md-offset-3"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">First Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="alphanum" id="first-name" name="first_name" maxlength="30" value="<?php echo $user->first_name; ?>" required="true" class="form-control col-md-7 col-xs-12">
                                <div class="helper-error text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Last Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="alphanum" id="last-name" name="last_name" value="<?php echo $user->last_name; ?>" required="required" class="form-control col-md-7 col-xs-12">
                                <div class="helper-error text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user-email">Email <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" id="user-email" name="email" value="<?php echo $user->email; ?>" required="required" minlength="6" maxlength="50" class="form-control col-md-7 col-xs-12">
                                <div class="helper-error text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user-name">Username <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="user-name" name="username" value="<?php echo $user->username; ?>" required="required" minlength="6" maxlength="30" class="form-control col-md-7 col-xs-12">
                                <div class="helper-error text-danger"></div>
                            </div>
                        </div>
                        <?php if($this->ion_auth->in_group('admin')) {
                            if($user->id != $this->session->userdata('user_id')) {
                        ?>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">User Access <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                    if(!empty($groups)) {
                                        foreach ($groups as $group) {
                                            $checked = '';
                                            if(!empty($user_groups)) {
                                                if(in_array($group->id, $user_groups)) {
                                                    $checked = 'checked';
                                                }
                                            }
                                            echo ' <label data-title="'.$group->description.'" style="margin-right:10px;margin-top:10px;"> <input type="checkbox" '.$checked.' name="access[]" value="'.$group->id.'"> '.ucwords($group->name).'</label> &nbsp;';
                                        }
                                    }
                                ?>
                                <div class="helper-error text-danger"></div>
                            </div>
                        </div>
                        <?php }} ?>


                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button id="submitButton" type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save User</button>
                                <?php
                                    if($user->id == $this->session->userdata('user_id')) {
                                        echo '<button id="" class="btn btn-primary pull-right changePassword" data-url="http://superawesome.space/users/reset-password/'.$this->session->userdata('user_id').'"><i class="fa fa-key"></i> Change Password</button>';
                                    }

                                ?>
                            </div>
                        </div>

                        <?php
                            if($this->uri->segment(3) == '') {
                                echo '<input type="hidden" name="id" value="'.$this->session->userdata("user_id").'"> ';
                            }
                        ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
