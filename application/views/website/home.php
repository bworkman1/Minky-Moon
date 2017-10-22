<div class="container">
    <script>setTimeout(function(){ shop.init();}, 1500)</script>
    <h2 class="text-center">Select a Product</h2>
    <?php
        $error = $this->session->flashdata('error');
        $success = $this->session->flashdata('success');
        if($error) {
            echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> '.$error.'</div>';
        }
        if($success) {
            echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> '.$success.'</div>';
        }
    ?>
    <ul class="list-group">
    <?php
        if(!empty($products)) {
            foreach($products as $product) {
                echo '<li class="list-group-item">';
                    echo '<a href="' . base_url('shop/personalize/' . url_title($product->name, '-', TRUE)) . '" class="btn btn-success btn-lg pull-right customize-btn"><i class="fa fa-gear"></i> Customize</a>';
                echo '<img src="' . base_url('assets/' . $product->image) . '" class="img-responsive pull-left product-image-selection" alt="Minky Moon Product ' . $product->name . '">';
                echo ' <h3>' . $product->name . '</h3>';
                echo ' <p>' . $product->description . '</p>';
                    echo '<div class="clearfix"></div>';
                echo '</li>';
            }
        }
    ?>
    </ul>
</div>