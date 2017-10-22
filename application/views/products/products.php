    <div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-shopping-cart"></i> Products</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a href="#" id="newProduct" data-target="#productModal" data-toggle="modal" class="openProductsPanel"><i class="fa fa-plus"></i> Add New</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <ul class="list-group">
                <?php
                    if(!empty($products)) {
                        foreach($products as $product) {
                            echo '<li class="list-group-item">';
                                echo $product->name;

                                echo '<span class="pull-right">';
                                    echo '<a href="'.base_url('products/edit/'.$product->id).'" class="btn btn-primary btn-xs" data-id="'.$product->id.'" data-toggle="tooltip" data-title="Edit"><i class="fa fa-pencil"></i></a>';
                                    echo '<button class="deleteProduct btn btn-danger btn-xs" data-id="'.$product->id.'" data-toggle="tooltip" data-title="Delete"><i class="fa fa-times-circle"></i></button>';
                                echo '</span>';
                            echo '</li>';
                        }
                    }
                ?>
            </ul>
        </div>
    </div>
</div>


<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeModalButtons" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-truck"></i> Product</h4>
            </div>
            <div class="modal-body">
                <form id="productForm">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Name</label>
                                <input type="text" id="name" name="name" class="form-control" maxlength="50" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <div id="pricing_options">
                                    <?php
                                    if($pricing) {
                                        echo '<label><span class="text-danger">*</span> Pricing Options</label>';
                                        foreach($pricing as $key => $price) {
                                            echo '<div class="checkbox">';
                                            echo '<label>';
                                            echo ' <input type="checkbox" name="pricing_options[]" value="'.$key.'"> '.ucwords($key);
                                            echo '</label>';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Image</label>
                                <input type="file" id="image" name="image" class="form-control" required>
                                <div id="imagePreviewArea" class="hide text-center">
                                    <img src="" id="imagePreview" class="img-responsive img-thumb">
                                    <br>
                                    <button id="deleteProductImage" class="btn btn-danger"><i class="fa fa-times-circle"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div id="fabrics">
                                    <?php

                                    if($fabrics) {
                                        echo '<label>Fabric Options</label>';
                                        foreach($fabrics as $key => $fabric) {
                                            echo '<div class="checkbox">';
                                            echo '<label>';
                                            echo ' <input type="checkbox" name="fabrics[]" value="'.$key.'"> '.ucwords($key);
                                            echo '</label>';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <input id="id" type="hidden" name="id" value="">
                </form>
                <p class="text-danger"><span>*</span> Indicates a required field</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default closeModalButtons" data-dismiss="modal">Close</button>
                <button type="button" id="saveProductOptions" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
