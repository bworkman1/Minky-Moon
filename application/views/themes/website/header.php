
<div id="topBar">
    <div class="container">
        <div class="row">
            <div class="col-xs-7">
                <ul class="topMenuLinks">
                    <li><a href="">FAQ</a> / </li>
                    <li><a href="">HELP</a> /</li>
                    <?php
                        if($this->ion_auth->logged_in()) {
                            echo '<li><a href="">ACCOUNT</a> /</li> ';
                            echo '<li><a href="'.base_url('shop/logout').'"> LOGOUT</a> </li>';
                        } else {
                            echo '<li><a href="">LOGIN</a> </li>';
                        }
                    ?>

                </ul>
            </div>
            <div class="col-xs-5">
                <ul class="topMenuLinks pull-right social-icons">
                    <li><a href="#" target="_blank"><i class="fa fa-facebook-official"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fa fa-envelope-o"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="middleBar">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo base_url('assets/themes/minky-moon/img/minkymoonlogo.jpg'); ?>" class="img-responsive">
            </div>
            <div class="col-md-6">
                <div class="row hidden-sm hidden-xs">
                    <div class="col-md-8 text-center">
                        <div id="madeInAmerica"><i class="fa fa-globe" style="color:#C4EEED !important;"></i> Made in America</div>
                    </div>
                    <div class="col-md-4">
                        <img src="<?php echo base_url('assets/themes/minky-moon/img/free_shipping.png'); ?>" id="freeShipping" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="topMenu">
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="navbar-header hidden-lg hidden-md">
                <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand">Menu</a>
            </div>
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Gallery</a></li>
                    <li><a href="#">Gift Certificates</a></li>
                    <li><a href="#">Project Linus</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="active">
                        <a href="<?php echo base_url('shop'); ?>"><i class="fa fa-shopping-cart"></i> Shop Now
                            <?php
                                $badge = $this->cart->total_items() > 0 ? '<span class="badge itemsInCart">'.$this->cart->total_items().'</span>' : '';
                                echo $badge;
                            ?>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

