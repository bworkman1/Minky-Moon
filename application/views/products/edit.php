<div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-pencil"></i> Edit Product</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a href="#" id="newProduct" data-target="#productModal" data-toggle="modal" class="openProductsPanel"><i class="fa fa-plus"></i> Add New Option</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <ul class="list-group">
                <?php
                    $options = array(
                        'Sizes'             => array(),
                        'Front Fabric'      => array(),
                        'Back Fabric'       => array(),
                        'Trim'              => array(),
                        'Personalization'   => array(),
                        'Acolides'          => array(),
                    );

                ?>
            </ul>
        </div>
    </div>
</div>