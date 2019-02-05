<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();
	if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role=$current_user->roles[0];
		wpsp_topbar(); 
		wpsp_sidebar();
		wpsp_body_start();
		if($current_user_role=='administrator' || $current_user_role=='teacher')
		{
		?>

		<section class="content-header">

			<h1>Events</h1>

			<ol class="breadcrumb">

				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>

				<li><a href="<?php wpsp_admin_url();?>sch-events">Events</a></li>

			</ol>

		</section>

		<section class="content">

			<div class="row">

				<div class="col-md-12">

						<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp; Event calendar</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">

							<div id="calendar"></div>

							<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">

								<div class="modal-dialog">

									<div class="modal-content">

										<div class="modal-header">

										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

										<h4 class="modal-title" id="modalTitle"> Add Event</h4>

										</div>

										<div id="modalBody" class="modal-body">

											<div id="response"></div>

											<form name="calevent_entry" method="post" class="form-horizontal" id="calevent_entry">

											<div class="form-group">

												<label class="col-sm-3 control-label">Start Date *</label>
												<input type="hidden"  id="wpsp_locationginal" value="<?php echo admin_url();?>"/>
													<div class="col-sm-9">     

														<input type="text" name="sdate" class="form-control sdate" id="sdate"></div>

											</div>

											<div class="form-group">

												<label class="col-sm-3 control-label">Start Time *</label>

												<div class="col-sm-9"><input type="text" name="stime" class="form-control stime" id="stime"></div>

											</div>

											

											<div class="form-group">

												<label class="col-sm-3 control-label">End Date *</label>

												<div class="col-sm-9"><input type="text" name="edate" class="form-control edate" id="edate"></div>

											</div>

											

											<div class="form-group">

												<label class="col-sm-3 control-label">End Time *</label>

												<div class="col-sm-9"><input type="text" name="etime" class="form-control etime" id="etime"></div>

											</div>

											      

											<div class="form-group">

												<label class="col-sm-3 control-label">Title *</label>

												<div class="col-sm-9"><input type="text" name="evtitle" class="form-control" id="evtitle"></div>

											</div>

											

											<div class="form-group">

												<label class="col-sm-3 control-label">Description</label>

												<div class="col-sm-9"><textarea name="evdesc" class="form-control" id="evdesc"></textarea></div>

											</div>

											

											<div class="form-group">

												<label class="col-sm-3 control-label">Type</label>

												<div class="col-sm-9">     

													<select class="form-control" id="evtype" name="evtype">

														<option value="0">External(Show to all)</option>

														<option value="1">Internal(Show to teachers only)</option>

													</select>

													<input type="hidden" name="evid" class="form-control" id="evid">

												</div>

											</div>

											

											<div class="form-group">

												<label class="col-sm-3 control-label">Color</label>

												<div class="col-sm-9">     

													<select name="evcolor" class="form-control" id="evcolor">

														<option class="bg-blue" value="">Default</option>

														<option class="bg-red" value="#f56954">Red</option>

														<option class="bg-green" value="#00a65a">Green</option>

														<option  class="bg-purple" value="#932ab6">Purple</option>

														<option class="bg-orange" value="#ff851b">Orange</option>

													</select>

												</div>

											</div>

										</form>

										</div>

										<div class="modal-footer">

											<button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>

											<button type="button" id="calevent_save" class="btn btn-primary">Save </button>

									</div>

								</div>

							  </div>

							</div>

							<div id="viewModal" class="modal fade">

								<div class="modal-dialog">

									<div class="modal-content">

										<div class="modal-header">

											<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>

											<h4 id="viewEventTitle" class="modal-title"></h4>

										</div>

										<div class="modal-body" style="min-height: 150px">

											<div class="col-md-12">

												<label>Start : </label> <span id="eventStart"> </span>

											</div>

											<div class="col-md-12">

												<label>End : </label> <span id="eventEnd"> </span>

											</div>

											<div class="col-md-12">

												<label>Description : </label> <span id="eventDesc"> </span>

											</div>



										</div>

										<div class="modal-footer">

											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

											<button class="btn btn-danger" id="deleteEvent">Delete Event</button>

											<button class="btn btn-primary" id="editEvent">Edit Event</button>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</section>

		<?php 

		}else if($current_user_role=='parent' || $current_user_role='student'){ ?>

			<section class="content">

			<div class="row">

				<div class="col-md-12">

					<div class="box box-info">
						<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp; Event calendar</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>

						<div class="box-body">

							<div id="calendar"></div>

							<div id="viewModal" class="modal fade">

								<div class="modal-dialog">

									<div class="modal-content">

										<div class="modal-header">

											<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>

											<h4 id="viewEventTitle" class="modal-title"></h4>

										</div>

										<div class="modal-body" style="min-height: 150px">

											<div class="col-md-12">

												<label>Start : </label> <span id="eventStart"> </span>

											</div>

											<div class="col-md-12">

												<label>End : </label> <span id="eventEnd"> </span>

											</div>

											<div class="col-md-12">

												<label>Description : </label> <span id="eventDesc"> </span>

											</div>



										</div>

										<div class="modal-footer">



										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</section>

	<?php

		}

		wpsp_body_end();

		wpsp_footer();

	}

	else{

		//Include Login Section

		include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-login.php');

	}



		?>

		