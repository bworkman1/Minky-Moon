<div class="col-md-12">
    <div id="currentUrl" class="x_panel" data-url="<?php echo current_url(); ?>">
        <div class="x_title">
            <h2><i class="fa fa-shopping-bag"></i> Fabrics</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <select id="fabricByProduct" class="form-control">
                        <?php
                        echo '<option value="">View Fabrics By Product</option>';
                        foreach($products as $product) {
                            echo '<option value="'.$product->id.'">'.$product->name.'</option>';
                        }
                        ?>
                    </select>
                </li>
                <li>
                    <a href="#" class="openFabricPanel"><i class="fa fa-plus"></i> Add New</a>
                    </li>
                </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

                <?php
                    $success = $this->session->flashdata('success');
                    if($success) {
                        echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> '.$success.'</div>';
                    }

                    if($fabric) {
                        foreach($fabric as $key => $category) {

                                echo '<div class="panel panel-default">';
                                    echo '<div class="panel-heading">';
                                        echo '<h3 class="panel-title">'.$key.'</h3>';
                                    echo '</div>';
                                    echo '<div class="panel-body">';
                                        echo '<ul class="list-unstyled">';
                                        foreach ($category as $fabrics) {
                                            $featured = '';
                                            if($fabrics->Featured == 'Yes') {
                                                $featured = 'greenBorder';
                                            }
                                            echo '<li class="fabricImage '.$featured.'" data-toggle="tooltip" data-title="Edit '.$fabrics->name.'" data-id="'.$fabrics->id.'" data-name="'.$fabrics->name.'" data-category="'.$key.'">';
                                                echo '<img src="'.base_url('assets/'.$fabrics->thumb).'">';
                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                    echo '</div>';
                                echo '</div>';

                        }
                    } else {
                        echo '<div class="alert alert-info"><i class="fa fa-question-circle"></i> No fabrics have been loaded</div>';
                    }
                ?>

        </div>
    </div>
</div>


<div class="modal fade" id="editFabricModal" tabindex="-1" role="dialog" aria-labelledby="optionModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeModalButtons" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Fabric</h4>
            </div>
            <div class="modal-body">
                <form id="editFabricForm">
                    <div class="row">
                        <div class="col-lg-9 col-md-8">
                            <div class="row">

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" id="name" class="form-control" name="name" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Active</label>
                                        <select id="active" class="form-control" name="active" required>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <span class="pull-right addCategory text-success" data-type="Category"><i class="fa fa-plus"></i> New</span>
                                        <select id="category" class="form-control" name="category" required multiple="multiple">
                                            <?php

                                            if($categories) {
                                                foreach($categories as $category) {
                                                    echo '<option value="'.$category->id    .'">'.$category->category.'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

<!--                                <div class="col-md-8">-->
<!--                                    <div class="form-group hide">-->
<!--                                        <label>Placement</label>-->
<!--                                        <span class="addCategory pull-right text-success" data-type="Placement"><i class="fa fa-plus"></i> New</span>-->
<!--                                        <select class="form-control" id="placement" name="placement">-->
<!--                                            <option value="">Select Category First</option>-->
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                </div>-->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Featured</label>
                                        <select id="featured" class="form-control" name="featured" required>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label>Products This Fabric Belongs To</label><br>
                                    <?php
                                        foreach($products as $product) {
                                            echo '<label class="checkbox-inline">';
                                                echo '<input type="checkbox" class="fabricProducts" name="products[]" value="'.$product->id.'"> '.$product->name;
                                            echo '</label>';
                                        }
                                    ?>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-md-12">
                                    <label>Sides</label><br>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="fabricSide" name="side[]" value="1"> Front
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="fabricSide" name="side[]" value="2"> Back
                                    </label>
                                </div>
                            </div>
                            <br>
                            <p class="text-muted"><span class="text-danger">*</span> To add a new image delete the current one first</p>
                            <input id="id" type="hidden" value="" required>
                        </div>
                        <div class="col-lg-3 col-md-4">

                            <div id="fabricUpload">
                                <label>Upload Image</label>
                                <input type="file" name="fabric_image" class="form-control" id="fabric_image">
                            </div>

                            <div id="fabricPreview">
                                <img id="previewImg" src="" class="img-responsive img-thumbnail">

                                <br>
                                <br>
                                <div class="text-center">
                                    <button class="btn btn-danger btn-block btn-lg" id="deleteFabricImage"><i class="fa fa-times-circle"></i> Delete Image</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success pull-left"><i class="fa fa-link"></i> Add Suggestion</button>
                <button type="button" class="btn btn-default closeModalButtons" data-dismiss="modal">Close</button>
                <button type="button" id="saveFabricData" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>