<?php
if(!empty($data)) {
    echo '<div class="row">';
        foreach($data as $size) {
            echo '<div class="col-md-3 col-sm-6">';

                $class = '';
                if(!empty($settings) && isset($settings['size'])) {
                    $class = $size->id == $settings['size']->id ? 'selectedOption' : '';
                }
                echo '<div class="sizeSelection text-center pricingItem '.$class.'" data-type="sizes" data-id="'.$size->id.'" data-name="'.$size->name.'" style="background-image: url('.base_url('assets/'.$size->image).');background-size: contain;background-repeat: no-repeat;">';
                    echo '<div class="basePrice">Base Price $'.$size->price.'</div>';
                    echo '<div class="pricingText">';
                        echo '<p>'.$size->size.'</p>';
                        echo '<h5>'.$size->name.'</h5>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';
}