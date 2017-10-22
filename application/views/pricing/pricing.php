<div class="row">
    <?php
        echo '<div class="col-md-12">';
            echo '<div class="x_panel">';
                echo '<div class="x_title">';
                    echo '<h2><i class="fa fa-money"></i> Pricing Options</h2>';
                    echo '<ul class="nav navbar-right panel_toolbox">';
                        echo '<li>';
                            echo '<a href="#" class="openEditPanel" data-modaltitle="All New Item"><i class="fa fa-plus"></i> Add New</a>';
                        echo '</li>';
                    echo '</ul>';
                echo '<div class="clearfix"></div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';

        if(isset($pricing) && !empty($pricing)) {
            foreach($pricing as $key => $category) {
                if(is_array($category)) {
                    echo '<div class="col-md-6">';
                        echo '<div class="x_panel">';
                            echo '<div class="x_title">';
                                echo '<h2>'.ucwords($key).' Pricing</h2>';
                                echo '<ul class="nav navbar-right panel_toolbox">';
                                    echo '<li>';
                                        echo '<a href="#" class="openEditPanel" data-category="'.$key.'" data-modaltitle="New Item"><i class="fa fa-plus"></i> Add New</a>';
                                    echo '</li>';
                                echo '</ul>';
                                echo '<div class="clearfix"></div>';
                            echo '</div>';
                            echo '<div class="x_content">';
                                echo '<ul class="list-group">';
                                if(!empty($category)) {
                                    foreach ($category as $item) {
                                        echo '<li class="list-group-item">';
                                            echo $item->name;
                                            if($item->size) {
                                                echo ' <small class="text-muted"> ' . $item->size . '</small>';
                                            }

                                            echo '<button class="btn btn-danger btn-xs pull-right deleteItem" data-category="'.$item->category.'" data-name="'.$item->name.'" data-tile="Delete"><i class="fa fa-times-circle"></i></button>';
                                            echo '<button id="editItem" class="btn btn-success btn-xs pull-right openEditPanel" data-modaltitle="Edit Item" data-category="'.$item->category.'" data-name="'.$item->name.'" data-tile="Edit"><i class="fa fa-pencil-square"></i></button>';
                                            if($item->price > 0) {
                                                echo ' <span class="badge badge-primary" style="margin-right:10px;">$'.number_format($item->price, 2).'</span> &nbsp;';
                                            }
                                        echo '</li>';
                                    }
                                }
                                echo '</ul>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            }
        }
    ?>
</div>


<div class="modal fade" id="optionModal" tabindex="-1" role="dialog" aria-labelledby="optionModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeModalButtons" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="pricingOptionForm">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><span class="text-danger">*</span>  Name</label>
                                <input id="name" type="text" class="form-control" required name="name" maxlength="50">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Price</label>
                                <input id="price" type="text" class="form-control money" required name="price" maxlength="6">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label><span class="text-danger">*</span> Category</label>
                                <select id="category" class="form-control categorySelect" required name="category">
                                    <?php
                                        if(isset($pricing) && !empty($pricing)) {
                                            echo '<option value="">Select One...</option>';
                                            foreach($pricing as $key => $category) {
                                                echo '<option value="'.$key.'">'.ucwords($key).'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><span class="text-danger">*</span> Category</label>
                                <input id="" class="form-control categoryInput" required name="" maxlength="50">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Size</label>
                                <input id="size" type="text" class="form-control" required name="size" maxlength="50" placeholder="12x12">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Sequence</label>
                                <select id="sequence" class="form-control" required name="sequence">
                                    <?php
                                        echo '<option value="">Select One...</option>';
                                        for($i=0;$i<10;$i++) {
                                            echo '<option value="'.($i+1).'">'.ordinal(($i+1)).'</option>';
                                        }
                                        echo '<option value="'.($i+1).'">'.ordinal(($i+1)).'</option>';
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="id" maxlength="10">
                </form>
                <p class="text-danger"><span>*</span> Indicates a required field</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default closeModalButtons" data-dismiss="modal">Close</button>
                <button type="button" id="savePricingOptions" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
