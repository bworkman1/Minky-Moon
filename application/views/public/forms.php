<?php
if(!empty($forms)) {
    echo '<div class="row">';
        foreach($forms as $form) {
            echo '<div class="col-md-3">';
                echo '<div class="well text-center">';
                    echo '<h4><b>'.$form['form_name'].'</b></h4>';
                    echo '<p><i class="fa fa-file-o fa-5x"></i></p>';
                    $url_title = url_title($form['form_name']);
                    echo '<a href="'.base_url('view/form/'.$url_title).'" class="btn btn-info">Go To Form</a>';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';
} else {
    echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> No forms have been added yet, check back later</div><br><br><br>';
}