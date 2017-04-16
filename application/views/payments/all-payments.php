<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-users"></i> All Forms <small><?php echo count($forms).' Total'; ?></small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a href="<?php echo base_url('forms/add-form'); ?>"><i class="fa fa-forms-o"></i> Add Forms</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
        <div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>

        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table">
                    <thead>
                    <tr class="headings">
                        <th class="column-title" style="display: table-cell;">Form Name </th>
                        <th class="column-title" style="display: table-cell;">Fields </th>
                        <th class="column-title" style="display: table-cell;">Submissions </th>
                        <th class="column-title" style="display: table-cell;">Created </th>
                        <th class="column-title" style="display: table-cell;">Updated </th>
                        <th class="column-title" style="display: table-cell;">Cost </th>
                        <th class="column-title" style="display: table-cell;">Active </th>
                        <th class="column-title no-link last" style="display: table-cell;"><span class="nobr">Action</span>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    if(count($forms)>0) {
                        foreach ($forms as $form) {

                            echo '<tr class="pointer">';
                            echo '<td class="">' . $form['form_name'] . '</td>';
                            echo '<td class="">' . $form['form_fields'] . '</td>';
                            echo '<td class="">' . $form['submissions'] . '</td>';
                            echo '<td class="">' . $form['created']  . '</td>';
                            echo '<td class="">' . $form['updated'] . '</td>';
                            echo '<td class="">$' . $form['cost'] . '</td>';

                            if ($form['active']) {
                                $active = '<span class="badge bg-green">Active</span>';
                            } else {
                                $active = '<span class="badge bg-red">In-Active</span>';
                            }
                            echo '<td class="">' . $active . '</td>';
                            echo '<td>';
                            echo ' <a href="' . base_url('forms/edit-form/' . $form['id']) . '" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Edit Form"><i class="fa fa-pencil"></i> </a>';
                            echo ' <a href="' . base_url('forms/view-form/' . $form['id']) . '" class="btn btn-success btn-xs" data-toggle="tooltip" title="View Form"><i class="fa fa-eye"></i> </a>';
                            echo ' <a href="' . base_url('forms/submit-form-manually/' . $form['id']) . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="Enter New Manually Form"><i class="fa fa-file"></i> </a>';
                            echo ' <button class="btn btn-danger btn-xs deleteForm pull-right" data-url="' . base_url('forms/delete-form/' . $form['id']) . '" data-toggle="tooltip" title="Delete Form"><i class="fa fa-times"></i> </button>';
                            echo '</td>';

                            echo '</tr>';
                        }
                    } else {
                        echo '<tr class="headings">';
                        echo '<td colspan="8" class="column-title" style="display: table-cell;"><div class="alert alert-danger"><i class="fa fa-times-circle fa-2x" style="position:relative;top:4px"></i> No Forms Found </div></td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>