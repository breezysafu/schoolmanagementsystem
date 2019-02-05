<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();

if( is_user_logged_in() ) {

		global $current_user, $wpdb;

		$current_user_role=$current_user->roles[0];		

		wpsp_topbar();

		wpsp_sidebar();

		wpsp_body_start();

	?>

		<section class="content-header">

			<h1><?php _e( 'Change Password', 'WPSchoolPress' ); ?></h1>

			<ol class="breadcrumb">

				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i><?php _e( 'Dashboard', 'WPSchoolPress'); ?></a></li>

				<li><a href="<?php wpsp_admin_url();?>sch-changepassword"><?php _e( 'Change Password', 'WPSchoolPress'); ?> </a></li>

			</ol>

		</section>

		<section class="content">

			<div class="row">

				<div class="col-md-12">

					<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-key" aria-hidden="true"></i>&nbsp; Change Password</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">	

							<div class="col-md-12 col-sm-12 col-lg-12 float-right change-password" style="padding:0;">

								<form class="form-horizontal group-border-dashed" action="" id="changepassword">

                                        <div id="message_response"></div>

                                        <div class="form-group">

                                            <label class="col-sm-3 control-label"><?php _e( 'Current Password', 'WPSchoolPress' ); ?></label>

                                            <div class="col-sm-5">

                                                <input class="form-control" name="oldpw" id="oldpw" type="password" required>

                                            </div>

                                        </div>

                                        <div class="form-group">

                                            <label class="col-sm-3 control-label"><?php _e( 'New Password', 'WPSchoolPress' ); ?></label>

                                            <div class="col-sm-5">

                                                <input class="form-control" name="newpw" id="newpw" type="password" required>

                                            </div>

										</div>

										<div class="form-group">

                                            <label class="col-sm-3 control-label"><?php _e( 'Confirm  New Password', 'WPSchoolPress' ); ?></label>

                                            <div class="col-sm-5">

                                                <input class="form-control" name="newrpw" id="newrpw" type="password" required>

                                            </div>

                                        </div>

										<div class="form-group">

                                            <div class="col-sm-offset-4 col-sm-8">

                                                <input class="btn btn-primary" name="Change" id="Change" value="Change" type="submit">                                              

                                            </div>

                                        </div>

                                    </form>

							</div>							

						</div>

					</div>

				</div>

			</div>

		</section>		

		<?php

			wpsp_body_end();

			wpsp_footer();

} else {

		//Include Login Section

	include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-login.php');

}

?>