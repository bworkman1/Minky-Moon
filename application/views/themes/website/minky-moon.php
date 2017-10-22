<!DOCTYPE html>
    <html lang="en">
    <head>
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title == '' ? BUSINESS_NAME : $title; ?></title>

        <link rel="icon" type="image/png" href="http://theminkymoon.com/design-a-minky/img/favicon.png">
        <link rel="icon" href="http://theminkymoon.com/design-a-minky/img/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="http://theminkymoon.com/design-a-minky/img/favicon.ico" type="image/x-icon" />

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
        <div id="loading"></div>
        <div id="wrapper">
            <div id="base_url" data-base="<?php echo base_url(); ?>"></div>
            <div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
            <div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>

            <?php $this->load->view('themes/website/header'); ?>

            <div id="shop">
                <?php echo $output;?>
            </div>
        </div>
        <footer class="hidden-print text-center footer">
            &copy;<?php echo date('Y'); ?> All Rights Reserved. <?php echo BUSINESS_NAME; ?>
        </footer>

        <?php
            foreach($js as $file){
                echo "\n\t\t";
                ?><script src="<?php echo $file; ?>"></script><?php
            } echo "\n\t";
        ?>
        <link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">
    </body>

</html>

