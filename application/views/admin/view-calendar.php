<div id="baseUrl" data-base="<?php echo base_url(); ?>"></div>
<div id="errorFeedback" data-error="<?php echo $this->session->flashdata('error'); ?>"></div>
<div id="successFeedback" data-error="<?php echo $this->session->flashdata('success'); ?>"></div>

<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-calendar"></i> Calendar</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <?php
                if (strtoupper($this->uri->segment(1)) != 'VIEW') {
                    echo '<a href="'.base_url('view/calendar').'" target="_blank"><i class="fa fa-calendar-o"></i> View Live Calendar</a>';
                }
                ?>
            </li>

            <li>
                <?php
				$background = '';
				$showDelete = true;
                if (strtoupper($this->uri->segment(1)) != 'VIEW') {
                    echo '<a href="#" data-target="#add-event" data-toggle="modal"><i class="fa fa-calendar-plus-o"></i> Add Event</a>';
                } else {
					$showDelete = false;
					$background = 'style="background: #fff;"';
				}
                ?>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" <?php echo $background; ?>>
        <?php echo $calendar; ?>
    </div>
</div>

<div class="modal fade" id="add-event" tabindex="-1" role="dialog" aria-labelledby="add-event">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addEvent">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-calendar-plus-o"></i> Add Event</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name:</label>
                                <input type="text" name="name" class="form-control" maxlength="30">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" style="margin-top: 27px;">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="all_day" class="form-control" value="1"> All Day Event:</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <label>Start Date/End Date:</label>
                    <input type="text" name="start" class="form-control" readonly>

                    <div class="form-group">
                        <label>Description:</label>
                        <textarea class="form-control" style="min-height: 150px;" name="desc" maxlength="1000"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Include Link to Form</label>
                        <select class="form-control" name="link_to_form">
                            <option value="">Select Form</option>
                            <?php
                                foreach($forms as $form) {
                                    echo '<option value="'.$form['id'].'">'.$form['name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="addEventBtn" class="btn btn-primary">Add Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if($showDelete) { ?>
<div class="modal fade" id="edit-event" tabindex="-1" role="dialog" aria-labelledby="edit-event">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-calendar-check-o"></i> Edit Event</h4>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="input-group">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" maxlength="30">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="checkbox">
                                <label>All Day Event:</label>
                                <input type="text" name="name" class="form-control" maxlength="30">
                            </div>
                        </div>
                    </div>

                    <label>Description:</label>
                    <textarea class="form-control" name="desc" maxlength="255"></textarea>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<div class="modal fade" id="view-event" tabindex="-1" role="dialog" aria-labelledby="view-event">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-calendar-o"></i> Event</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <?php
                    if ($this->ion_auth->logged_in()) {
                        echo '<div class="pull-left">';
                            //echo '<button type="button" class="btn btn-info"><i class="fa fa-edit"></i> Edit</button>';
							if($showDelete) {
								echo '<button type="button" id="deleteEventBtn" class="btn btn-danger"><i class="fa fa-times-circle"></i> Delete Event</button>';
							}
                        echo '</div>';
                    }
                ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>