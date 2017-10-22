<div id="fabricOption">
    <?php
    if($fabric) {
        foreach($fabric as $key => $fab) {
            echo '<h3 class="fabricCategoryHeader">'.$key.'</h3>';
            if(!empty($fab)) {
                echo '<ul class="list-unstyled">';
                foreach($fab as $f) {
                    $selected = isset($settings->id) && $settings->id == $f->id ? 'selectedOption' : '';
                    echo '<li class="pull-left fabricSelection '.$selected.'" data-productid="'.$f->product_id.'" data-id="'.$f->id.'" data-type="'.$key.'" data-section="'.$type.'">';
                        echo '<img src="'.base_url('assets/'.$f->thumb).'" class="img-responsive">';
                    echo '</li>';
                }
                echo '</ul>';
                echo '<div class="clearfix"></div>';
            }
        }
    } else {
        echo '<div class="alert alert-danger"><i class="fa fa-times"></i> Error: The fabrics were not found, please refresh the page and try again</div>';
    }
    ?>
</div>
