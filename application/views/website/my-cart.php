<div class="container">
    <h1>My Cart</h1>
    <?php
        $success = $this->session->flashdata('success');
        if($success) {
            echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> '.$success.'</div>';
        }
    ?>
    <div style="background:#fff;padding:10px">
        <script>setTimeout(function() {shop.init();}, 1000);</script>
        <table class="table">
            <tr>
                <th>QTY</th>
                <th>Item Description</th>
                <th style="text-align:right;width: 100px;">Item Price</th>
                <th style="text-align:right;width: 100px;">Sub-Total</th>
            </tr>

            <?php $i = 1; ?>

            <?php foreach ($this->cart->contents() as $items): ?>

                <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>

                <tr>
                    <td>
                        <?php
                            echo form_input(
                                array(
                                    'name'      => $i.'[qty]',
                                    'value'     => $items['qty'],
                                    'type'      => 'number',
                                    'class'     => 'form-control qtyInput',
                                    'maxlength' => '3',
                                    'size'      => '5',
                                    'style'     => 'width: 80px;',
                                    'min'       => 0,
                                    'data-id'   => $items['rowid'],
                                    'data-qty'  => $items['qty'],
                                )
                            );
                        ?>

                        <button class="btn btn-danger btn-sm removeItemFromCart" data-id="<?php echo $items['rowid']; ?>" style="margin-top:5px;"><i class="fa fa-times"></i> Remove</button>
                    </td>
                    <td>
                        <?php echo '<b>'.$items['name'].'</b>'; ?>

                        <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
                            <p>

                                <?php
                                    $personalization = '';
                                    $features = '';
                                    foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value){
                                        if(is_array($option_value)) {
                                            $personalization .= '<ul>';
                                            foreach($option_value as $v) {
                                                if(isset($v->value) && $v->value) {
                                                    $personalization .= '<li><b>' . $v->name . '</b> ' . $v->value . '</li>';
                                                }
                                            }
                                            $personalization .= '</ul>';

                                        } else {
                                            if(isset($option_value->name)) {
                                                $features .= '<strong>'.ucwords(str_replace('_' , ' ', $option_name)).':</strong> ';
                                                $features .= ucwords($option_value->name).' - ';
                                            } elseif($option_value) {
                                                $personalization .= '<strong>'.ucwords(str_replace('_' , ' ', $option_name)).':</strong> ';
                                                $personalization .= $option_value.' - ';
                                            }
                                        }
                                    }

                                echo rtrim($features, ' - ').'<br>';
                                echo rtrim($personalization, ' - ');
                                ?>
                            </p>
                        <?php endif; ?>

                    </td>
                    <td style="text-align:right;">$<?php echo $this->cart->format_number($items['price']); ?></td>
                    <td style="text-align:right;width: 100px;">$<?php echo $this->cart->format_number($items['subtotal']); ?></td>
                </tr>

                <?php $i++; ?>

            <?php endforeach; ?>

            <tr>
                <td colspan="2"> </td>
                <td class="right"><strong>Total</strong></td>
                <td class="right">$<?php echo $this->cart->format_number($this->cart->total()); ?></td>
            </tr>

        </table>
        <button class="updateCart btn btn-success pull-left"><i class="fa fa-cart-plus" aria-hidden="true"></i> Update your cart</button>
        &nbsp; <a href="<?php echo base_url('shop'); ?>" class="btn btn-primary"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Continue Shopping</a>
        <a href="<?php echo base_url('shop/checkout'); ?>" class="checkOut btn btn-info pull-right"><i class="fa fa-credit-card" aria-hidden="true"></i> Check Out</a>
        <div class="clearfix"></div>
    </div>
</div>