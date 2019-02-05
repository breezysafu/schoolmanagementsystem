<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();

	if( is_user_logged_in() ) {

		global $current_user, $wpdb;

		$current_user_role=$current_user->roles[0];

		if($current_user_role=='administrator' || $current_user_role=='teacher')

		{

			wpsp_topbar();

			wpsp_sidebar();

			wpsp_body_start();

		?>

		<section class="content-header">

			<h1>Transport</h1>

			<ol class="breadcrumb">

				<li><a href="<?php echo site_url('wp-admin/admin.php?page=sch-dashboard'); ?> "><i class="fa fa-dashboard"></i> Dashboard</a></li>

				<li><a href="<?php echo site_url('wp-admin/admin.php?page=sch-transport'); ?>">Transport</a></li>

			</ol>

		</section>

		<section class="content">

			<div class="row">

				<div class="col-md-12">

					<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-bus" aria-hidden="true"></i>&nbsp; Transportation Details  </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">						

							

							<div class="subject-head">

								<button class="btn btn-success pull-right" id="AddNew"><i class="fa fa-plus"></i>&nbsp; Add New </button>

							</div>
							<div class="col-md-12 table-responsive">
							<table id="transport_table" class="table table-bordered table-striped table-responsive" style="margin-top:10px">

								<thead>

									<tr>

										<th class="nosort">#</th>

										<th>Vehicle Number</th>

										<th>Driver Name</th>

										<th>Driver Phone</th>

										<th>Route Fees</th>

										<th>Vehicle Route </th>

										<th class="nosort">Action</th>

									</tr>

								</thead>

								<tbody>

									<?php

									$trans_table=$wpdb->prefix."wpsp_transport";

									$wpsp_trans =$wpdb->get_results("select * from $trans_table");

									$sno=1;

									foreach ($wpsp_trans as $wpsp_tran)

									{

										

									?>

										<tr>

											<td><?php echo $sno;?><td><?php echo  $wpsp_tran->bus_no;?> </td>

											<td><?php echo  $wpsp_tran->driver_name; ?></td>

											<td><?php echo  $wpsp_tran->phone_no;?></td>

											<td><?php echo  $wpsp_tran->route_fees;?></td>

											<td><?php echo  $wpsp_tran->bus_route;?></td>

											<td>

												<a href="javascript:;" data-id="<?php echo intval($wpsp_tran->id);?>" class="ViewTrans" title="View"><i class="fa fa-eye btn btn-success"></i></a>

												<a href="javascript:;" data-id="<?php echo intval($wpsp_tran->id);?>" class="EditTrans" title="Edit"><i class="fa fa-pencil btn btn-warning"></i></a>

												<a href="javascript:;" data-id="<?php echo intval($wpsp_tran->id);?>" class="DeleteTrans" title="Delete"><i class="fa fa-trash btn btn-danger"></i></a>

											</td>

										</tr>

									<?php	

										$sno++;

									}

									?>

								</tbody>

							</table>
						</div>
						</div>

					</div>

				</div>

			</div>

		</section>

		<!--Info Modal-->

		<div class="modal fade" id="TransModal" tabindex="-1" role="dialog" aria-labelledby="TransModal" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">

					<div class="col-md-12">

						<div class="box box-success">

							<div class="box-header">

								

								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="modalTitle"> Add Transportation</h4>
							</div><!-- /.box-header -->

							<div id="TransModalBody" class="box-body PTZero">



							</div>

						</div>

					</div>					

				</div>

			</div>

		</div><!-- /.modal -->



		<?php

			wpsp_body_end();

			wpsp_footer();

		}

		else if($current_user_role=='parent' || $current_user_role=='student' )

		{

			wpsp_topbar();

			wpsp_sidebar();

			wpsp_body_start();

			?>

			<section class="content-header">

				<h1>Transport</h1>

				<ol class="breadcrumb">

					<li><a href="<?php echo site_url('wp-admin/admin.php?page=ch-dashboard'); ?> "><i class="fa fa-dashboard"></i> Dashboard</a></li>

					<li><a href="<?php echo site_url('wp-admin/admin.php?page=sch-transport'); ?>">Transport</a></li>

				</ol>

			</section>

			<section class="content">

				<div class="row">

					<div class="col-md-12">

						<div class="box box-info">
								<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-bus" aria-hidden="true"></i>&nbsp; Transportation Details  </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>

							<div class="box-body ">
								<div class="col-md-12  table-responsive">

								<table id="transport_table" class="table table-bordered table-striped table-responsive" style="margin-top:10px">

									<thead>

									<tr>

										<th class="nosort">#</th>

										<th>Vehicle Number</th>

										<th>Driver Name</th>

										<th>Driver Phone</th>

										<th>Route Fees</th>

										<th>Vehicle Route </th>

										<th class="nosort">Action</th>

									</tr>

									</thead>

									<tbody>

									<?php

									$trans_table=$wpdb->prefix."wpsp_transport";

									$wpsp_trans =$wpdb->get_results("select * from $trans_table");

									$sno=1;

									foreach ($wpsp_trans as $wpsp_tran)

									{



										?>

										<tr>

											<td><?php echo $sno;?><td><?php echo  $wpsp_tran->bus_no;?> </td>

											<td><?php echo  $wpsp_tran->driver_name; ?></td>

											<td><?php echo  $wpsp_tran->phone_no;?></td>

											<td><?php echo  $wpsp_tran->route_fees;?></td>

											<td><?php echo  $wpsp_tran->bus_route;?></td>

											<td>

												<a href="javascript:;" data-id="<?php echo intval($wpsp_tran->id);?>" class="ViewTrans" title="View"><i class="fa fa-eye btn btn-primary"></i></a>

											</td>

										</tr>

										<?php

										$sno++;

									}

									?>

									</tbody>

								</table>
							</div>

							</div>

						</div>

					</div>

				</div>

			</section>

			<!--Info Modal-->

			<div class="modal fade" id="TransModal" tabindex="-1" role="dialog" aria-labelledby="TransModal" aria-hidden="true">

				<div class="modal-dialog">

					<div class="modal-content">

						<div class="col-md-12">

							<div class="box box-success">

								<div class="box-header">

									<p class="box-title" id="TransModalTitle"></p>

									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

								</div><!-- /.box-header -->

								<div id="TransModalBody" class="box-body PTZero">



								</div>

							</div>

						</div>

					</div>

				</div>

			</div><!-- /.modal -->

			<?php

			wpsp_body_end();

			wpsp_footer();

		}



	}

	else{

		//Include Login Section

		include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-login.php');

	}



		?>

		