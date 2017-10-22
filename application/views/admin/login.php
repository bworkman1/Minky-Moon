<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <?php echo form_open("login/ajax-validate-login", array('id'=>'loginForm', 'data-baseurl'=>base_url()));?>
                    <h1>Login</h1>
                <?php
                    if($this->session->flashdata('message') != '') {
                        echo $this->session->flashdata('message');
                    }
                    echo '<div id="loginFeedback"></div>';
                ?>
                    <div>
                        <input id="username" type="text" name="identity" class="form-control" placeholder="Username" required="" />
                    </div>
                    <div>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="" />
                    </div>
                    <div class="checkbox hide">
                        <label><input name="remember" id="remember" type="checkbox" value="1"> Remember Me</label>
                    </div>
                    <div>
                        <button id="loginButton" class="btn btn-default">Log in</button>
                        <a class="reset_pass hide" href="#">Lost your password?</a>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <br />

                        <div>
                            <p>&copy;<?php echo date('Y'); ?> All Rights Reserved. <?php echo BUSINESS_NAME; ?></p>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </section>
        </div>

    </div>
</div>