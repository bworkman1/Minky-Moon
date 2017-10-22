<script>setTimeout(function() {shop.init(); account.init()}, 1000);</script>

<?php $columnSize = $this->cart->total_items() > 0 ? '4' : '6'; ?>
<div class="container">
        <div class="row">
            <div class="col-md-<?php echo $columnSize; ?>">
                <div class="whitePanel">
                    <form id="loginForm">
                        <h3><i class="fa fa-lock"></i> Sign In</h3>
                        <p>If you already have an account, please login using the following form to continue on with your order.</p>
                        <div class="form-group">
                            <label><span class="text-danger">*</span> Email</label>
                            <input type="email" name="email" class="form-control input-md" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label><span class="text-danger">*</span> Password</label>
                            <input type="password" name="password" class="form-control input-md" maxlength="50">
                        </div>
                        <hr>
                        <button id="loginButton" class="btn btn-success btn-lg">Login</button>
                    </form>
                    <br>
                </div>
            </div>
            <div class="col-md-<?php echo $columnSize; ?>">
                <div class="whitePanel">
                    <form id="createAccount">
                        <h3><i class="fa fa-user-plus"></i> Create Account</h3>
                        <p>An account is needed in order to complete the payment process. We use your account to notify you of updates to your order and communicate with you through our online system.</p>
                        <div class="form-group">
                            <label><span class="text-danger">*</span> Email</label>
                            <input type="email" name="email" class="form-control input-md" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label><span class="text-danger">*</span> Password</label>
                            <input type="password" name="password" class="form-control input-md" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label><span class="text-danger">*</span> Confirm Password</label>
                            <input type="password" name="password2" class="form-control input-md" maxlength="50">
                        </div>

                        <div class="checkbox">
                            <label> <input type="checkbox" name="promos" class=""> Email me about special promos</label>
                        </div>
                        <hr>
                        <button id="createAccountBtn" class="btn btn-success btn-lg">Create Account</button>
                    </form>
                    <br>
                </div>
            </div>
            <div class="col-md-<?php echo $columnSize; ?>">
                <div class="whitePanel">
                    <?php
                        if($this->cart->total_items() > 0) {
                            echo '<h3><i class="fa fa-shopping-cart"></i> My Cart</h3>';
                            echo '<ul class="list-unstyled">';
                                echo '<li style="border-bottom: 1px solid #ccc;margin-bottom: 4px;">';
                                    echo '<div class="pull-left">Shipping </div><div class="text-right">$0.00</div>';
                                    echo '<div class="clearfix"></div>';
                                echo '</li>';
                                echo '<li style="border-bottom: 1px solid #ccc;margin-bottom: 4px;">';
                                    echo '<div class="pull-left">Subtotal</div>';
                                    echo '<div class="pull-right">$'.$this->cart->format_number($this->cart->total()).'</div>';
                                    echo '<div class="clearfix"></div>';
                                echo '</li>';
                                echo '<li style="font-size:1.3em;">';
                                    echo '<div class="pull-left"><b>Total:</b></div>';
                                    echo '<div class="pull-right"><b>$'.$this->cart->format_number($this->cart->total()).'</b></div>';
                                    echo '<div class="clearfix"></div>';
                                echo '</li>';
                            echo '</ul>';
                        }
                    ?>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
