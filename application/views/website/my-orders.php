<div class="container">
    <script>setTimeout(function() {account.init();}, 1000);</script>
</div>

<div id="my-orders" class="container">
    <br>
    <div class="alert alert-info dontRemove">
        <span class="badge pull-right" style="background:#fff;color:#949494;"><?php echo count($orders); ?> Orders</span>
        <p><i class="fa fa-shopping-cart"></i> My Orders</p>
    </div>

    <?php
        $success = $this->session->flashdata('success');
        if($success) {
            echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> '.$success.'</div>';
        }
    ?>

    <?php
        $statusIcons = array(
            'Processing'        => array(
                'icon' => 'fa-hourglass-start',
                'alert_color' => 'warning',
            ),
            'Order Started'     => array(
                'icon' => '',
                'alert_color' => 'warning',
            ),
            'We have a Question'=> array(
                'icon' => 'fa-question-circle-o',
                'alert_color' => 'info'
            ),
            'Shipped'           => array(
                'icon' => 'fa-truck',
                'alert_color' => 'success',
            ),
            'Canceled'           => array(
                'icon' => 'fa-times-circle',
                'alert_color' => 'danger',
            )
        );
        if(!empty($orders)) {
            foreach($orders as $order) {
                echo '<div class="panel panel-primary">';
                    $cartItems = $this->Account_model->getCartItems($order->user_id, $order->transaction_id);

                    echo '<div class="panel-heading">';
                        echo '<div class="row">';

                            echo '<div class="col-md-3 col-xs-6">';
                                echo '<p><b>Ordered On</b></p>';
                                echo '<p>'.date('m-d-Y', strtotime($order->date)).'</p>';
                            echo '</div>';

                            echo '<div class="col-md-3 col-xs-6">';
                                echo '<p><b>Total</b></p>';
                                echo '<p>$'.number_format($order->amount, 2).'</p>';
                            echo '</div>';

                            echo '<div class="col-md-3 col-xs-6">';
                                echo '<p><b>Ship To</b></p>';

                                echo '<div class="dropdown">';
                                    echo '<a class="dropdown-toggle" type="button" id="shipping'.$order->transaction_id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                        echo $this->encrypt->decode($order->billing_name).' <i class="fa fa-caret-down"></i>';
                                    echo '</a>';
                                    echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                        echo '<p><b>'.$this->encrypt->decode($order->billing_name).'</b></p>';
                                        echo '<p>'.$this->encrypt->decode($order->billing_address).'</p>';

                                        if($order->billing_address_2) {
                                            echo '<p>' . $this->encrypt->decode($order->billing_address_2) . '</p>';
                                        }

                                        echo '<p>'.$this->encrypt->decode($order->billing_city).', ';
                                        echo $this->encrypt->decode($order->billing_state).' ';
                                        echo $this->encrypt->decode($order->billing_zip).'</p>';
                                    echo '</div>';
                                echo '</div>';

                            echo '</div>';

                            echo '<div class="col-md-3 col-xs-6">';
                                echo '<p><b>Transaction ID:</b></p>';
                                echo '<p>'.$order->transaction_id.'</p>';
                            echo '</div>';

                        echo '</div>';
                    echo '</div>';

                    echo '<div class="panel-body">';
                        echo '<div class="row">';
                            echo '<div class="col-md-9">';
                                if($cartItems) {
                                    $itemTitle = array();
                                    $qtyTitle = '0';
                                    $itemType = 'ITEM TYPE NOT FOUND';
                                    $itemPrice = array();

                                    // TODO: ADD TO THIS AS MORE PRODUCTS ARE ADDED
                                    $titleArrays = array(
                                        'Minky' /* ITEM TYPE VALUE */ => 'size' /*ITEM PRODUCT TYPE VALUE */
                                    );

                                    $items = array();
                                    $optionsSelected = array();
                                    foreach($cartItems as $k => $cartItem) {
                                        $items[$cartItem->cart_item_id][] = $cartItem;
                                    }


                                    if($items) {
                                        foreach($items as $key => $orderedItems) {
                                            $lines = array();
                                            foreach($orderedItems as $item) {
                                                $qtyTitle = $item->qty;
                                                $itemType = $item->item_type;

                                                if($item->product_type == $titleArrays[$item->item_type]) {
                                                    $itemTitle[] = $item->name;
                                                    $itemPrice[] = $item->item_total;
                                                    if($item->price > 0) {
                                                        $optionsSelected[$key][$item->product_type] = '<em>(+$'.number_format($item->price, 2).')</em> '.$item->name;
                                                    } else {
                                                        $optionsSelected[$key][$item->product_type] = $item->name;
                                                    }
                                                }

                                                if($item->product_type == 'lines' && $item->value) {
                                                    if($item->price > 0) {
                                                        $lines[] = '<em>(+$'.number_format($item->price, 2).')</em> '.$item->value;
                                                    } else {
                                                        $lines[] = $item->value;
                                                    }
                                                }

                                                if($item->product_type != $titleArrays[$item->item_type] && $item->product_type != 'lines' && $item->value == '') {
                                                    $optionsSelected[$key][$item->product_type] = $item->name;
                                                } elseif($item->value != '') {
                                                    $optionsSelected[$key][$item->product_type] = $item->value;
                                                }

                                                $optionsSelected[$key]['item_url'] = $item->item_url;

                                            }
                                            $optionsSelected[$key]['lines'] = $lines;
                                        }
                                    }
                                }

                                if($optionsSelected) {
                                    $i = 0;

                                    foreach ($optionsSelected as $options) {

                                        echo '<h4><b>' . $itemType . '</b> - ' . $itemTitle[$i] . '</h4>';
                                        echo '<ul class="list-group">';

                                        $trade = array('_', '-');
                                        $tradeFor = array(' ', ' ');
                                        $itemUrl = $options['item_url'];

                                        foreach ($options as $key => $option) {
                                            if($key != 'item_url') {
                                                if (!is_array($option)) {
                                                    echo '<li class="list-group-item"><div class="pull-left"><b>' . ucwords(str_replace($trade, $tradeFor, $key)) . ':</b></div> <div class="pull-right">' . ucwords(str_replace($trade, $tradeFor, $option)) . '</div><div class="clearfix"></div></li>';
                                                } else {
                                                    if (!empty($option)) {
                                                        $count = 0;
                                                        foreach ($option as $lines) {
                                                            $count++;
                                                            echo '<li class="list-group-item"><div class="pull-left"><b>Line' . $count . ':</b></div> <div class="pull-right">' . ucwords(str_replace($trade, $tradeFor, $lines)) . '</div><div class="clearfix"></div></li>';
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        if($qtyTitle > 1) {
                                            echo '<li class="list-group-item"><div class="pull-left"><b>Quantity:</b></div> <div class="pull-right"><b>x' . $qtyTitle . '</b></div><div class="clearfix"></div></li>';
                                        } else {
                                            echo '<li class="list-group-item"><div class="pull-left"><b>Quantity:</b></div> <div class="pull-right">' . $qtyTitle . '</div><div class="clearfix"></div></li>';
                                        }

                                        echo '</ul>';

                                        $trackingBtnColor = 'black';
                                        $disabled = 'disabled';
                                        if($itemUrl) {
                                            $trackingBtnColor = 'blue';
                                            $disabled = '';
                                        }

                                        echo '<div class="row">';
                                            echo '<div class="col-xs-6">';
                                                echo '<button class="btn '.$trackingBtnColor.'-btn '.$disabled.'" data-src="'.base_url($itemUrl).'">View Finished Item</button>';
                                            echo '</div>';
                                            echo '<div class="col-xs-6">';
                                                echo '<div class="item-price text-right"><h5><b>Item(s) Total:</b> $' . number_format($itemPrice[$i], 2) . '</h5></div>';
                                            echo '</div>';
                                        echo '</div>';

                                        echo '<hr>';
                                        $i++;
                                    }

                                    echo '<div class="alert alert-info dontRemove"><p style="font-size:1em;"><b>Order Total:</b> $' . number_format($order->amount, 2) . '</p></div>';
                                }
                            echo '</div>';

                            echo '<div class="col-md-3">';
                                echo '<br class="hidden-sm hidden-xs">';
                                echo '<br class="hidden-sm hidden-xs">';
                                echo '<ul class="list-unstyled my-order-options">';

                                    $trackingBtnColor = 'black';
                                    $disabled = 'disabled';
                                    if($order->tracking_id) {
                                        $trackingBtnColor = 'blue';
                                        $disabled = '';
                                    }

                                    echo '<li><a href="#" class="'.$disabled.' '.$trackingBtnColor.'-btn btn btn-block btn-lg"><i class="fa fa-map-marker"></i> Track Order</a></li>';
                                    echo '<li><a href="#" class="'.$disabled.' '.$trackingBtnColor.'-btn btn btn-block btn-lg"><i class="fa fa-envelope"></i> Email Tracking Number</a></li>';
                                    echo '<li><a href="#" class="btn blue-btn btn-block questionsBtn" data-order="'.$order->transaction_id.'" data-target="#questionsModel" data-toggle="modal"><i class="fa fa-question-circle-o"></i> Questions About Order</a></li>';
                                    echo '<li><a href="#" data-target="#reviewModal" data-toggle="modal" class="'.$trackingBtnColor.'-btn btn btn-block '.$disabled.'"><i class="fa fa-pencil"></i> Write a review</a></li>';
                                    echo '<li><a href="#" class="btn blue-btn btn-block messages" data-order="'.$order->transaction_id.'"><span class="badge badge-secondary">4</span> View Messages</a></li>';
                                    echo '<li><div class="alert alert-'.$statusIcons[$order->status]['alert_color'].'"><i class="fa '.$statusIcons[$order->status]['icon'].' pull-left fa-3x"></i> <b>Status:</b><br> '.$order->status.'</div></li>';
                                echo '</ul>';
                            echo '</div>';

                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle"></i> No orders found</div>';
        }

    ?>
</div>


<div id="reviewModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Leave us a review</h2>
                <p>We greatly appreciate feedback so please feel free to leave us a review.</p>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        LINK TO FACEBOOK REVIEWS
                    </div>
                    <div class="col-md-6">
                        LINK TO GOOGLE REVIEWS
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="questionsModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Have a question about your order?</h2>
                <p>Send us a message below.</p>
            </div>
            <div class="modal-body">
                <form id="questions">
                    <div class="">
                        <label>Question:</label>
                        <textarea id="messageText" class="form-control" style="min-height:150px;" maxlength="500"></textarea>
                        <p class="text-muted">Max 500 characters</p>
                        <input type="hidden" id="order-id">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="sendQuestion" class="btn btn-primary">Send</button>
            </div>
        </div>

    </div>
</div>


<div id="messagesModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">My Messages</h2>
            </div>
            <div class="modal-body">
                <form id="questions">
                    <div id="messages">
                        <div id="messageContents"></div>
                        <hr>
                        <label>Message: </label>
                        <textarea id="messageTexts" class="form-control" style="min-height:50px;"></textarea>
                        <span class="text-muted">In regards to transaction id <b><span id="chatTrasactionId">564564</span></b></span>
                        <input type="hidden" id="orderId">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="sendMessage" class="btn btn-primary" data-dismiss="modal">Send</button>
            </div>
        </div>

    </div>
</div>