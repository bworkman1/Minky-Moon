<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-users"></i> All Users <small><?php echo count($users).' Total'; ?></small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a href="<?php echo base_url('users/add-user'); ?>"><i class="fa fa-user-plus"></i> Add User</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
        <div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>

        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table">
                    <thead>
                    <tr class="headings">
                        <th class="column-title" style="display: table-cell;">Name </th>
                        <th class="column-title" style="display: table-cell;">Username </th>
                        <th class="column-title" style="display: table-cell;">Email </th>
                        <th class="column-title" style="display: table-cell;">Created </th>
                        <th class="column-title" style="display: table-cell;">Last Login </th>
                        <th class="column-title" style="display: table-cell;">Active </th>
                        <th class="column-title" style="display: table-cell;">Access Level </th>
                        <th class="column-title no-link last" style="display: table-cell;"><span class="nobr">Action</span>
                        </th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php
                        $count = 0;
                        if($users) {
                            foreach ($users as $user) {
                                if ($user->id == $this->session->userdata('id')) {
                                    continue;
                                }
                                $count++;
                                $class = 'odd';
                                if ($count % 2 == 0) {
                                    $class = 'even';
                                }
                                $locked = '';
                                if ($this->ion_auth->is_max_login_attempts_exceeded($user->username)) {
                                    $locked = 'danger';
                                }
                                echo '<tr class="' . $class . ' pointer ' . $locked . '">';
                                echo '<td class="">' . $user->first_name . ' ' . $user->last_name . '</td>';
                                echo '<td class="">' . $user->username . '</td>';
                                echo '<td class="">' . $user->email . '</td>';
                                echo '<td class="">' . date('m-d-Y', $user->created_on) . '</td>';
                                if ($user->last_login) {
                                    $last_login = date('m-d-Y', $user->last_login);
                                } else {
                                    $last_login = 'Never';
                                }
                                echo '<td class="">' . $last_login . '</td>';
                                if ($user->active) {
                                    $active = '<span class="badge bg-green">Active</span>';
                                } else {
                                    $active = '<span class="badge bg-red">In-Active</span>';
                                }
                                echo '<td class="">' . $active . '</td>';

                                $user_group = $this->ion_auth->get_users_groups($user->id)->result();
                                echo '<td>' . $user_group[0]->description . '</td>';
                                echo '<td>';
                                echo ' <a href="' . base_url('users/edit-user/' . $user->id) . '" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Edit User"><i class="fa fa-pencil"></i> </a>';
                                if ($locked) {
                                    echo ' <button class="btn btn-warning btn-xs button-action pull-right" data-toggle="tooltip" data-url="' . base_url('users/reset-login-attempts/' . $user->id) . '"  title="Reset User Login Attempts"><i class="fa fa-lock"></i> </button>';
                                }
                                echo ' <button class="btn btn-default btn-xs changePassword" data-toggle="tooltip" title="Reset User Password" data-url="' . base_url('users/reset-password/'.$user->id) . '"><i class="fa fa-key"></i> </button>';
                                echo ' <button id="user_' . $user->id . '" class="btn btn-danger btn-xs deleteUser" data-url="' . base_url('users/delete-user/' . $user->id) . '" data-toggle="tooltip" title="Delete User"><i class="fa fa-times"></i> </button>';
                                echo '</td>';
                                echo '</tr>';
                            }

                            if($count==0) {
                                echo '<tr class="headings">';
                                    echo '<td colspan="8" class="column-title" style="display: table-cell;"><div class="alert alert-danger"><i class="fa fa-times-circle fa-2x" style="top: 4px;position: relative;"></i> No Other Users Found </div></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr class="headings">';
                                echo '<td colspan="8" class="column-title" style="display: table-cell;"><div class="alert alert-danger"><i class="fa fa-times-circle fa-2x" style="position:relative;top:4px"></i> No Other Users Found </div></td>';
                            echo '</tr>';
                        }
                    ?>


                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>