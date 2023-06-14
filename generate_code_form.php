<!--Contains the form that gets the entity input fields to generate the code-->

<form id="" class="form-horizontal" method="POST" action="<?= site_url('MVCCodeGenerator/getFields/'); ?>">
                                                                        <div class="box-body">
                                                                            <div class="form-group">
                                                                                <label class="control-label col-sm-3" for="component_name">Component Name:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="component_name" class="form-control" id="component_name" placeholder="component name" required="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-sm-3" for="table_name">Table Name:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="table_name" class="form-control" id="table_name" placeholder="table name" required="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-sm-3" for="record_id">Record ID:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="record_id" class="form-control" id="record_id" placeholder="record_id" required="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-sm-3" for="record_id_segment">Record ID Segment:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="number" name="record_id_segment" class="form-control" id="record_id_segment" placeholder="record_id_segment" required="">
                                                                                </div>
                                                                            </div>
																			<div class="form-group">
                                                                                <label class="control-label col-sm-3" for="fields">Fields:</label>
                                                                                <div class="col-sm-9">
                                                                                    <textarea name="fields" class="form-control" rows="3" id="fields" placeholder="fields"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-sm-3" for="model_filename">Model Filename:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="model_filename" class="form-control" id="model_filename" placeholder="model_filename" required="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-sm-3" for="view_filepath">View FilePath:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="view_filepath" class="form-control" id="view_filepath" placeholder="view_filepath" required="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-sm-3" for="loggedin_variable">LoggedIn Variable:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="loggedin_variable" class="form-control" id="loggedin_variable" placeholder="loggedin_variable" required="">
                                                                                </div>
                                                                            </div>                                                                            
                                                                        </div><!-- /.box-body -->
                                                                        <div class="box-footer">
                                                                            <button type="submit" class="btn btn-success">Create Files()</button>
                                                                            <img id="" class="hidden" src="<?php echo base_url(); ?>assets/img/142.gif" />
                                                                            <p id=""></p>
                                                                        </div>
                                                                    </form>
