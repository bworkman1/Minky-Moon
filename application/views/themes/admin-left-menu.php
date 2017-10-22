<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title == '' ? 'LAPP Admin Area' : $title; ?></title>
        <link rel="shortcut icon" href="<?php echo base_url('assets/themes/admin/images/favicon.ico'); ?>" />
        <?php
            foreach($css as $file){
                echo "\n\t\t";
                ?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
            } echo "\n\t";
        ?>
        <?php
            if(!empty($meta))
                foreach($meta as $name=>$content){
                echo "\n\t\t";
                ?><meta name="<?php echo $name; ?>" content="<?php echo is_array($content) ? implode(", ", $content) : $content; ?>" /><?php
            }
        ?>
    </head>
    <body class="nav-md">
        <div id="base_url" data-base="<?php echo base_url(); ?>"></div>
        <div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
        <div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col menu_fixed">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="<?php echo base_url($this->session->userdata('user_home')); ?>" class="site_title"><i class="fa fa-book"></i> <span><?php echo BUSINESS_NAME; ?></span></a>
                        </div>

                        <div class="clearfix"></div>

                        <!-- menu profile quick info -->
                        <div class="profile clearfix">
                            <div class="profile_pic">
                                <img src="<?php echo base_url('assets/themes/admin/images/img.jpg'); ?>" alt="..." class="img-circle profile_img">
                            </div>
                            <div class="profile_info">
                                <span>Welcome,</span>
                                <h2><?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name'); ?></h2>
                            </div>
                        </div>
                        <!-- /menu profile quick info -->

                        <br />

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <?php $this->load->view('themes/menus/side-nav.php'); ?>
                        </div>
                        <!-- /sidebar menu -->

                        <!-- /menu footer buttons -->
                        <div class="sidebar-footer hidden-small">
                            <a href="<?php echo base_url('admin-settings'); ?>" data-toggle="tooltip" data-placement="top" title="Settings">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" onclick="toggleFullScreen();" data-placement="top" title="FullScreen">
                                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo base_url('login/logout'); ?>">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a>
                            <a href="<?php echo base_url('calendar'); ?>" data-toggle="tooltip" data-placement="top" title="Calendar">
                                <span class="fa fa-calendar" aria-hidden="true"></span>
                            </a>
                            </a>
                        </div>
                        <!-- /menu footer buttons -->
                    </div>
                </div>

                <!-- top navigation -->
                <?php $this->load->view('themes/menus/top-nav'); ?>
                <!-- /top navigation -->
                <div class="right_col" role="main">
                    <?php echo $output;?>
                </div>
                <!-- /page content -->

                <!-- footer content -->
                <footer class="hidden-print">
                    <div class="pull-right">
                        &copy;<?php echo date('Y'); ?> All Rights Reserved. <?php echo BUSINESS_NAME; ?>
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->
            </div>
        </div>

        <?php
            foreach($js as $file){
                echo "\n\t\t";
                ?><script src="<?php echo $file; ?>"></script><?php
            } echo "\n\t";
        ?>
        <link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">
    </body>
</html>

