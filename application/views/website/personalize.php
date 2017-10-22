
<div id="productType" data-type="<?php echo $this->uri->segment(3); ?>" data-productid="<?php echo $product->id; ?>" class="container">
    <h2 style="border-bottom:1px solid #ccc">Customize Your <?php echo rtrim($type, 's'); ?></h2>

    <?php
        if(!empty($settings)) {
            $price = 0;
            foreach($settings as $setting) {
                if(isset($setting->price) && $setting->price > 0) {
                    (float)$price = $price + $setting->price;
                } else if(is_array($setting) && !empty($setting)) {
                    foreach($setting as $val) {
                        if(isset($val->price) && $val->price > 0) {
                            (float)$price = $price + $val->price;
                        }
                    }
                }
            }
        }
    ?>

    <script>setTimeout(function(){ <?php echo strtolower(str_replace(' ', '', $type)); ?>.init();}, 1500)</script>
    <div class="row">
        <div class="col-md-4">
            <div id="previewSection">
                <h3 class="text-center">Preview</h3>
                <div id="itemPreview" class="blog-image">

                </div>
                <?php
                    $sizingTitle = '';
                    $productSettings = $this->session->userdata($this->uri->segment(3));
                    if(!empty($productSettings) && isset($productSettings['size']->name)) {
                        $sizingTitle = $productSettings['size']->name .' '.$productSettings['size']->size;
                    }
                ?>
                <div id="itemSize" class="text-center"><?php echo $sizingTitle; ?></div>
            </div>
            <?php
            $class = 'hide';
            $price = 0;
            if(isset($price) && $price>0) {
                $class = '';
                $price = '$'.number_format($price, 2);
            }
            ?>
            <div id="currentPrice" class="<?php echo $class; ?>"><?php echo $price; ?></div>
            <br>
            <button class="btn btn-success btn-block btn-lg addToCart hidden-sm hidden-xs" data-type="<?php echo ucwords($this->uri->segment(3)); ?>"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
        </div>
        <div class="col-md-8">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php
                $i = 0;
                $sizingTitle = '';
                if(!empty($pricingOptions)) {
                    foreach($pricingOptions as $key => $data) {

                        $settingView = '';
                        $hide = 'hide';

                        if(!empty($settings)) {
                            if(isset($settings[rtrim($key, 's')]) && !empty($settings[rtrim($key, 's')])) {
                                $settingView = '<i class="fa fa-check-circle"></i> '.$settings[rtrim($key, 's')]->name;
                                $hide = '';
                            } elseif(isset($settings[strtolower(str_replace(' ', '_', $key))]) && !empty($settings[strtolower(str_replace(' ', '_', $key))]) ) {
                                $settingView = '<i class="fa fa-check-circle"></i> '.$settings[strtolower(str_replace(' ', '_', $key))]->name;
                                $hide = '';
                            }

                            if(isset($settings['line']) && $key == 'personalization') {
                                $count = 0;
                                foreach($settings['line'] as $row) {
                                    if(!empty($row->value)) {
                                        $count++;
                                    }
                                }
                                if($count>0) {
                                    $settingView = '<i class="fa fa-check-circle"></i> ' . $count . ' Lines';
                                    $hide = '';
                                } else {
                                    $settingView = '<i class="fa fa-check-circle"></i> None';
                                    $hide = '';
                                }
                            }
                        }

                        $title = rtrim(ucwords($key), 's');

                        echo '<div id="'.str_replace(' ', '', $key).'" class="panel panel-default">';
                            echo '<div class="panel-heading" role="tab" id="heading'.$i.'">';
                                echo '<h4 class="panel-title">';
                                    echo '<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" aria-expanded="true" aria-controls="collapseOne">';
                                        $caretState = $i == 0 ? 'down' : 'right';
                                        echo '<span class="fa fa-caret-'.$caretState.'"></span> ';
                                        echo 'Select '.$title;
                                        echo '<span class="pull-right priceSelected '.$hide.'">'.$settingView.'</span>';
                                    echo '</a>';
                                echo '</h4>';
                            echo '</div>';

                            $pageData = array(
                                'data' => $pricingOptions[$key],
                                'type' => $title,
                                'settings' => $settings
                            );
                            $panelClass = $i == 0 ? 'in' : '';
                            echo '<div id="collapse'.$i.'" class="panel-collapse collapse '.$panelClass.'" role="tabpanel" aria-labelledby="heading'.$i.'">';
                                echo '<div class="panel-body">';
                                    switch($title) {
                                        case 'Size':
                                            if(!empty($settings) && isset($settings['size'])) {
                                                $sizingTitle = '<b>'.$settings['size']->name.' '.$settings['size']->size.'</b>';
                                            }
                                            $this->load->view('website/option-templates/blankets/sizes', $pageData);
                                            break;

                                        case 'Trim':
                                            if(!empty($userData) && isset($userData['trim'])) {
                                                $sizingTitle = '<b>'.$this->session->userdata('minky')['size']->name.' '.$this->session->userdata('minky')['trim']->trim.'</b>';
                                            }
                                            $this->load->view('website/option-templates/blankets/trim', array('trim' => $pricingOptions[$key], 'settings' => $settings));
                                            $this->load->view('website/option-templates/fabric', array('fabric' => $fabrics['trim'], 'type' => 'trim', 'settings' => $settings['trim_fabric']));
                                            break;
                                        case 'Personalization':
                                            $pageData['font_colors'] = $font_colors;
                                            $this->load->view('website/option-templates/blankets/personalization', $pageData);
                                            break;
                                        case 'Front Fabric':
                                            $data['pricing'] = $data;
                                            $data['font_colors'] = $font_colors;
                                            $this->load->view('website/option-templates/fabric',
                                                array(
                                                    'fabric'    => $fabrics['front_fabric'],
                                                    'type'      => 'front',
                                                    'settings'  => $settings['front_fabric']
                                                )
                                            );
                                            break;
                                        case 'Back Fabric':
                                            $data['pricing'] = $data;
                                            $data['font_colors'] = $font_colors;
                                            $this->load->view('website/option-templates/fabric',
                                                array(
                                                    'fabric'    => $fabrics['back_fabric'],
                                                    'type'      => 'back',
                                                    'settings'  => $settings['back_fabric']
                                                )
                                            );
                                            break;
                                        default:
                                            break;
                                    }

                                echo '</div>';
                            echo '</div>';
                        echo '</div>';

                        $i++;
                    }
                }
            ?>
                <br>
                <button class="btn btn-success btn-block btn-lg addToCart hidden-lg hidden-md" data-type="<?php echo ucwords($this->uri->segment(3)); ?>"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
            </div>
        </div>



    </div>

    </div>
</div>