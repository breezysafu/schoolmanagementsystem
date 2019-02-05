<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();

	if( is_user_logged_in() ) {

		global $current_user, $wp_roles, $wpdb;

		//get_currentuserinfo();

		foreach ( $wp_roles->role_names as $role => $name ) :

		if ( current_user_can( $role ) )

			$current_user_role =  $role;

		endforeach;		
		wpsp_topbar(); 
		wpsp_sidebar();
		wpsp_body_start();

		if($current_user_role=='administrator' || $current_user_role=='teacher')

		{ ?>

			<section class="content-header">

				<h1>Import History</h1>

				<ol class="breadcrumb">

					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>

					<li><a href="<?php wpsp_admin_url();?>sch-importhistory">Import History</a></li>

				</ol>

			</section>

			<section class="content">

				<div class="row">

					<div class="col-md-12">

						<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp; list of Import Data </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">	

								<?php 

									$importtable=$wpdb->prefix."wpsp_import_history";

									$result = $wpdb->get_results("SELECT * FROM $importtable");

									$imtype=array('-','Student','Teacher','Parent','Mark');

								?>

								<div class="col-md-12 col-lg-12 col-sm-12 table-responsive">         

								  <table class="table table-bordered table-striped" id="import">

									<thead>

									  <tr>

										<th class="nosort">#</th><th>Imported Date</th><th>Type</th><th>Number Of Rows</th><th class="nosort">Undo</th></tr>

									</thead>

									<tbody>

									 <?php 

									 $count = 0;

									 foreach($result as $value){

										 $count = $count+1;

									 ?>

										<tr>

										<td><?php echo $count; ?></td>

										<td><?php echo wpsp_ViewDate($value->time); ?></td>

										<td><?php echo $imtype[$value->type];?></td>

										<td><?php echo $value->count; ?></td>

										<td><a href="javascript:;" class="undoimport" value="<?php echo intval($value->id);?>">Click to undo</a></td>

										</tr>

									 <?php } ?>

									</tbody>

								  </table>

								</div>										

							</div>

						</div>

					</div>

				</div>	

			</section>			

	 <?php }

		wpsp_body_end();

		wpsp_footer();

	}

	else {

		include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-login.php');

	}

	?>