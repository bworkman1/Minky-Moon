<div class="row">
    <?php
        if(!empty($data)) {
            foreach($data as $key => $option) {
                if (strpos($option->name, 'Line') !== false) {
                    echo '<div class="col-md-4">';
                        echo '<div class="form-group">';

                            $value = '';
                            if(isset($settings['line']) && isset($settings['line'][($key+1)]) && isset($settings['line'][($key+1)]->value)) {
                                $value = $settings['line'][($key + 1)]->value;
                            }

                            $price = $option->price > 0 ? '<small>($'.number_format($option->price, 2).' Extra)</small>' : '';
                            echo '<label>'.$option->name.' '.$price.'</label>';
                            echo '<input id="textLine'.$key.'" data-line="'.($key+1).'" data-id="'.$option->id.'" value="'.$value.'" type="text" name="lines[]" class="form-control personalizationLines" maxlength="15" data-price="'.$option->price.'">';
                        echo '</div>';
                    echo '</div>';
                }
            }
        }
    ?>
</div>

<div id="textOptionsPersonalization" class="well well-sm">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Font Style</label>
                <select id="customFont" data-type="font" name="font" class="form-control fontSelect" data-selected="">
                    <option value="">Select One...</option>
                    <?php
                        if(!empty($fonts)) {
                            foreach($fonts as $row) {
                                $selected = isset($settings['font']) && $settings['font'] == $row->font_name ? 'selected' : '';
                                echo '<option value="'.$row->font_name.'" '.$selected.'>'.ucwords($row->font_name).'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <label>Font Color</label>
            <ul id="fontColorPersonalization" class="list-unstyled">
                <?php
                    foreach($font_colors as $color) {
                        $selected = '';
                        if(isset($settings['font-color']) && !empty($settings['font-color']) && $settings['font-color'] == $color->name) {
                            $selected = 'selectedFont';
                        }
                        echo '<li data-toggle="tooltip" data-title="'.$color->name.'" style="background-color:#'.$color->hex.';height:50px;width:50px;" class="pull-left fontColorSelection '.$selected.'" data-name="'.$color->name.'"></li>';
                    }
                ?>
            </ul>
        </div>
    </div>
</div>