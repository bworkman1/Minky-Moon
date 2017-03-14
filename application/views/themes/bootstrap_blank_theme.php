<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
    <body class="login">
        <?php echo $output;?>

        <?php
            foreach($js as $file){
                echo "\n\t\t";
                ?><script src="<?php echo $file; ?>"></script><?php
            } echo "\n\t";
        ?>
    </body>
</html>

