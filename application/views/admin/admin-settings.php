<!--- Authorize
- Key
- API

- Pages / Groups
- One for each page

- Login
- Login limit
- Login lockout time

- Form Emails
- Emails
- Remove Sensitive info y/n-->

<div class="row" id="baseUrl" data-base="<?php echo base_url(); ?>">

    <div class="col-lg-4 col-md-6">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-money"></i> Authorize.Net</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p>The API Login ID and Transaction Key are two pieces of information unique to your account. They are used to connect your website or other integrated business application to the Authorize.Net Payment Gateway for transaction processing. They are not valid for logging into the Merchant Interface. To find your authorize.net settings visit the following link. </p>
                <a href="https://support.authorize.net/authkb/index?page=content&id=A405" class="btn btn-info" target="_blank">Find Settings</a>
                <br>
                <hr>
                <form id="authorizeSettings">
                    <div class="row">

                        <div class="col-lg-6">
                            <label>Authorize.net API Key</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" name="api_key" class="form-control" value="<?php if(isset($settings['api_key'])) { echo $settings['api_key']->value; } ?>">
                        </div>

                    </div>
                    <br>
                    <div class="row">

                        <div class="col-lg-6">
                            <label>Authorize.net Key</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" name="auth_key" class="form-control" value="<?php if(isset($settings['auth_key'])) { echo $settings['auth_key']->value; } ?>">
                        </div>

                    </div>

                    <br>
                    <div class="well well-sm" style="padding:0 9px;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="test-mode" name="test-mode" value="y" <?php echo $settings['authorize_test_mode']->value == 'y' ? 'checked' : ''; ?>> Payments Test Mode?
                            </label>
                        </div>
                    </div>
                    <hr>

                    <button id="saveAuthorize" class="btn btn-primary pull-right">Save</button>
                    <?php
                        $class = '';
                        if(isset($settings['api_key']) && !empty($settings['api_key']->value) ) {
                            $class = 'hide';
                        }
                    ?>
                    <button id="removeAuthorizeSettings" class="btn btn-danger pull-left">Delete Settings</button>
                </form>

            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-lock"></i> Security Settings</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <form id="securitySettings">
                    <p>These settings help protect against brute-force attacks. A brute-force attack is when an attacker uses a password dictionary that contains millions of words that can be used as a password. The attacker tries these passwords one by one for authentication. The settings below will limit the number of times a password can be tried before the user is locked out.</p>
                    <hr>

                    <div class="row">
                        <div class="col-lg-6">
                            <label><span class="text-danger">*</span> Allowed failed login attempts before locked out</label>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php $failed = (isset($settings['failed']) && $settings['failed']->value > 0) ? $settings['failed']->value : 8; ?>
                                <input type="number" name="failed" class="form-control" value="<?php echo $failed; ?>">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-6">
                            <label><span class="text-danger">*</span> Lockout time after too many failed attempts <small>(mins)</small></label>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php $time = (isset($settings['time']) && $settings['time']->value > 0) ? $settings['time']->value : 12; ?>
                                <input type="number" name="time" class="form-control" value="<?php echo $time; ?>">
                            </div>
                        </div>
                    </div>



                    <hr>

                    <button id="saveSecuritySettings" class="btn btn-primary pull-right">Save</button>
                </form>

            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 hide" style="float:right">

        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-unlock"></i> User Access Restrictions</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p>These settings should not be changed.</p>
                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <label>Name of the page</label>
                        <div class="form-group">
                            <input type="text" name="security_page" class="form-control" value="" maxlength="50" placeholder="Check URL if you are unsure">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Short Description</label>
                        <div class="form-group">
                            <input type="text" name="security_page_desc" class="form-control" value="" maxlength="255">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button id="addNewGroup" class="btn btn-primary pull-right">Add</button>
                    </div>
                </div>
                <hr>
                <?php
                    if(!empty($groups)) {
                        echo '<ul id="userGroupList" class="list-group" style="padding-left:0">';
                        foreach($groups as $group) {
                            echo '<li class="list-group-item" style="position:relative;">';
                                echo '<h4 class="list-group-item-heading">'.ucwords($group->name).'</h4>';
                                echo '<p class="list-group-item-text">'.$group->description.'</p>';
                                if($group->name != 'admin') {
                                    echo '<span class="deleteGroup hide" data-groupid="'.$group->id.'" style="position:absolute;top: -15px;right: -15px;">';
                                    echo '<i class="fa fa-times-circle fa-3x pull-right text-danger"></i>';
                                    echo '</span>';
                                }
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                ?>
            </div>
        </div>

    </div>


    <div class="col-lg-4 col-md-6">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-envelope"></i> Email Settings</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p>Email address below will receive an email when a client submits a form. The email won't contain any personal details but will include a link to submitted form.</p>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Emails </label>
                            <br><small>Separate emails with commas for multiple emails</small>
                            <input type="text" class="form-control" name="emails" maxlength="255" value="<?php if(isset($settings['emails'])) { echo $settings['emails']->value; } ?>">
                        </div>

                        <div class="form-group">
                            <label>Default Form Submission Text/Email</label>
                            <textarea class="form-control" name="default_submission" style="min-height: 300px;"><?php if(isset($settings['submission'])) { echo $settings['submission']->value; } ?></textarea>
                        </div>

                        <hr>
                        <button id="saveEmailSettings" class="btn btn-primary pull-right">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
