<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-user"></i> Edit User</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a href="<?php echo base_url('users/'); ?>"><i class="fa fa-users"></i> All Users</a>
                        </li>
                    </ul>
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

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">User Access <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" name="access" required>
                                    <option value="">Select One</option>
                                    <?php
                                        if(!empty($groups)) {
                                            $usersGroup = $this->ion_auth->get_users_groups($user->id)->result();
                                            $userGroups = array();
                                            foreach($usersGroup as $group) {
                                                $userGroups[] = $group->id;
                                            }
                                            foreach ($groups as $group) {
                                                if(in_array($group->id, $userGroups)) {
                                                    echo '<option selected value="' . $group->id . '">' . $group->description . '</option>';
                                                } else {
                                                    echo '<option value="' . $group->id . '">' . $group->description . '</option>';
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                                <div class="helper-error text-danger"></div>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button id="submitButton" type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save User</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
