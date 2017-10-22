<div class="row">
    <?php
        echo "<div id='trimPricing'>";
            foreach($trim as $type) {
                echo '<div class="col-md-4">';

                    $class = '';
                    if(isset($settings['trim']) && !empty($settings['trim'])) {
                        if($settings['trim']->name == $type->name) {
                            $class = 'selectedOption';
                        }
                    }
                    echo '<div class="well well-sm pricingItem trimSelection '.$class.'" data-id="'.$type->id.'" data-name="'.$type->name.'">';
                        echo '<img src="'.base_url('assets/'.$type->image).'" class="img-responsive img-center">';
                        echo '<h4 class="text-center">'.$type->name.'</h4>';
                    echo '</div>';
                echo '</div>';
            }
        echo '</div>';
    ?>
</div>