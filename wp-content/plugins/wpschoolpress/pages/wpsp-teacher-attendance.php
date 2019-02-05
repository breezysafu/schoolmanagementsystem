<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();

	if( is_user_logged_in() ) {

		global $current_user, $wpdb;

		$current_user_role=$current_user->roles[0];



		wpsp_topbar();

		wpsp_sidebar();

		wpsp_body_start();

		

		if($current_user_role=='administrator' || $current_user_role=='teacher') {			

		?>

		<section class="content-header">

			<h1><?php _e( 'Teacher Attendance', 'WPSchoolPress'); ?></h1>

			<ol class="breadcrumb">

				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i><?php _e( 'Dashboard', 'WPSchoolPress'); ?></a></li>

				<li><a href="<?php wpsp_admin_url();?>sch-teacherattendance"><?php _e( 'Teacher Attendance', 'WPSchoolPress'); ?></a></li>

			</ol>

		</section>

		<section class="content">

			<div class="row">

				<div class="col-md-12">

					<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Teacher Attendance  </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">						

							<div class="col-md-offset-3 col-md-5" id="AttendanceEnterForm">

									<div class="form-group">

										<label class="control-label"><?php _e( 'Date', 'WPSchoolPress' ); ?> </label>

										<div class="">

											<input type="text" class="form-control select_date" id="AttendanceDate" value="<?php echo isset($_POST['entry_date'])? $_POST['entry_date'] : date('m/d/Y'); ?>" name="entry_date">

										</div>

									</div>

									

										<button id="AttendanceEnter" name="attendance" class="btn btn-primary"><?php _e( 'Add', 'WPSchoolPress'); ?></button>

										<button id="AttendanceView" name="attendanceview" class="btn btn-success"><?php _e( 'View', 'WPSchoolPress'); ?></button>

								

							</div>

							<div class="col-lg-12 col-md-12 Attendance-Overview MTTen">

								<div class="AttendanceContent">

									<?php //include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-attendanceView.php');?>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</section>

		<div class="modal modal-wide" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="AddModal" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content" id="AddModalContent">



				</div>

			</div>

		</div><!-- /.modal -->

		<?php			

		} else {

			echo "No access to this page";

		}

		wpsp_body_end();

		wpsp_footer();

	} else{

		include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-login.php');

	}



		?>