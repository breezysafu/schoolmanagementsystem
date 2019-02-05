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

			<h1>Attendance</h1>

			<ol class="breadcrumb">

				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-home"></i> Dashboard</a></li>

				<li><a href="<?php wpsp_admin_url();?>sch-attendance">Attendance</a></li>

			</ol>

		</section>

		<section class="content">

			<div class="row">

				<div class="col-md-12">

					<div class="box box-solid bg-blue-gradient">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-child" aria-hidden="true"></i> Attendance  Report</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
    

						<div class="box-footer text-black">

							<div class="col-lg-6 col-md-5 col-sm-12 col-xs-12" id="AttendanceEnterForm">

							<h3 class="box-title"> Attendance</h3>
							<div class="line_box">
								<div class="form-group">

									<label for="Class">Select Class </label>
										<select name="classid" id="AttendanceClass" class="form-control">

											<option value="">Select Class</option>

												<?php 

												if(isset($_POST['classid']) && intval($_POST['classid'])!='')

													$selid=intval($_POST['classid']);

												else 

													$selid=0;

												$ctname=$wpdb->prefix.'wpsp_class';

												$clt=$wpdb->get_results("select `cid`,`c_name` from `$ctname`");

												foreach($clt as $cnm)

												{

												?>

													<option value="<?php echo $cnm->cid;?>" <?php if($cnm->cid==$selid) echo "selected";?>><?php echo $cnm->c_name;?></option>

												<?php

												}

												?>

										</select>
									</div>


							

									<div class="form-group">

										<label for="date">Date </label>
										<input type="text" class="form-control select_date" id="AttendanceDate" value="<?php if(isset($_POST['entry_date'])) { echo $_POST['entry_date']; } else { echo date('m/d/Y'); }?>" name="entry_date">


									</div>

									<div class="row text-center">

										<button id="AttendanceEnter" name="attendance" class="btn btn-primary">Add/Update</button>

										<button id="Attendanceview" name="attendanceview" class="btn btn-success">View</button>

									</div>

									<div class="col-lg-12 col-md-12 col-sm-12  red" id="wpsp-error-msg" style="margin-top:0px;"></div>


							</div>
						</div>

						<!--	<div class="col-lg-12 col-md-12 Attendance-Overview MTTen">

								<div class="AttendanceContent">

									<?php //include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-attendanceView.php');?>

								</div>								

							</div> -->


							<div class="col-lg-6 col-md-5 col-sm-12 col-xs-12 AttendanceView">

								<?php

									$class_names		=	$c_stcount	=	$attendance	=	array();

									$class_table		=	$wpdb->prefix."wpsp_class";

									$student_table		=	$wpdb->prefix."wpsp_student";

									$attendance_table	=	$wpdb->prefix."wpsp_attendance";

									$class_info			=	$wpdb->get_results("select cid,c_name from $class_table");

									foreach($class_info as $cls){

										$class_names[$cls->cid]=$cls->c_name;

									}

									

									$classwise_count	=	$wpdb->get_results("select class_id, count(*) as count from $student_table GROUP BY class_id",ARRAY_A);

									foreach($classwise_count as $clwc){

										$c_stcount[$clwc['class_id']]	=	$clwc['count'];

									}

									$date_today	=	date('Y-m-d');

									

									$attendance_info	=	$wpdb->get_results("select class_id, absents from $attendance_table where date='$date_today'");

									foreach($attendance_info as $attend){

										$absents	=	json_decode($attend->absents);

										$present	=	$c_stcount[$attend->class_id]-count($absents);

										$percent	=	round(($present*100)/$c_stcount[$attend->class_id]);

										$attendance[$attend->class_id]	=	array('present'=>$present,'percentage'=>$percent);

									}

								?>

								<div class="col-sm-12">

									<h3 class="box-title">View Attendance Report</h3>
									<div class="line_box">

										<div class="box-body">

											<?php

												foreach($class_names as $clid=>$cln){

												$css_class='';

												if(isset($attendance[$clid])){

													if($attendance[$clid]['percentage']==100)

														$css_class="progress-bar-success";

													else if($attendance[$clid]['percentage']<100 && $attendance[$clid]['percentage']>70)

														$css_class="progress-bar-warning";

													else if($attendance[$clid]['percentage']<=70)

														$css_class="progress-bar-danger";

													else

														$css_class="progress-bar-info";



												}

											?>

													<div class="progress-group">

														<span class="progress-text"><?php echo $cln;?></span>

														<span class="progress-number"><?php if(isset($attendance[$clid])){ echo $attendance[$clid]['present']; }?>/<?php echo (isset($c_stcount[$clid]))?$c_stcount[$clid]:'0';?></span>

														<div class="progress sm">

															<div class="progress-bar <?php echo $css_class;?>" style="width: <?php echo (isset($attendance[$clid]))?$attendance[$clid]['percentage']:'0'; ?>%">

															</div>

														</div>

													</div>

											<?php } ?>

										</div></div>

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

			wpsp_body_end();

			wpsp_footer();

		}

		else if($current_user_role=='parent')

		{

			wpsp_topbar();

			wpsp_sidebar();

			wpsp_body_start();

			$parent_id=$current_user->ID;

			$student_table=$wpdb->prefix."wpsp_student";

			$class_table=$wpdb->prefix."wpsp_class";

			$att_table=$wpdb->prefix."wpsp_attendance";

			$students=$wpdb->get_results("select wp_usr_id, class_id, s_fname from $student_table where parent_wp_usr_id='$parent_id'");

			$child=array();

			foreach($students as $childinfo){

				$child[]=array('student_id'=>$childinfo->wp_usr_id,'name'=>$childinfo->s_fname,'class_id'=>$childinfo->class_id);

			}

			?>

			<section class="content-header">

				<h1>Attendance</h1>

				<ol class="breadcrumb">

					<li><a href="<?php echo site_url('sch-dashboard'); ?> "><i class="fa fa-homme"></i> Dashboard</a> </li>

					<li><a href="<?php echo site_url('sch-attendance'); ?>">Attendance</a></li>

				</ol>

			</section>

			<section class="content">

				<div class="tabbable-line">
					<div class="nav-tabs-custom">

					<ul class="nav nav-tabs ">

						<?php $i=0; foreach($child as $ch) { ?>

							<li class="<?php echo ($i==0)?'active':''?>"><a href="#<?php echo str_replace(' ', '', $ch['name'].$i );?>"  data-toggle="tab"><?php echo $ch['name'];?></a></li>

							<?php $i++; } ?>

					</ul>

					<div class="tab-content">

						<?php

						$i=0;

						foreach($child as $ch) {

							$st_id 		=	$ch['student_id'];

							$st_class	=	$ch['class_id'];

							?>

							<div class="tab-pane <?php echo ($i==0)?'active':''?>" id="<?php echo str_replace(' ', '', $ch['name'].$i );?>">

                                <?php	echo $att_info=wpsp_AttReport($st_id); ?>

							</div>

						<?php

						$i++; }

						?>

					</div>
				</div>

				</div>

			</section>

			<?php

				$startdate	=	isset( $_POST['search_from_date'])  ? sanitize_text_field($_POST['search_from_date']) : '';

				$todate		=	isset( $_POST['search_to_date'] ) ? sanitize_text_field($_POST['search_to_date']) : '';

			?>

			<div class="content" id="searchattendance">

				<div class='panel panel-info'>

					<div class='panel-heading'><h3 class='panel-title'>Search Attendance</h3></div>

					<div class='panel-body'>

						<form name="search_student_attendance" method="post">

							<div class="form-group col-lg-12 col-md-12 col-sm-12">

								<label for="search_date" class="col-md-2 control-label">Select From Date</label>

								<div class="col-md-2"><input type="text" name="search_from_date" id="search_from_date" class="select_date form-control" value="<?php echo $startdate; ?>"></div>

							</div>

							<div class="form-group col-lg-12 col-md-12 col-sm-12">	

								<label for="search_date" class="col-md-2 control-label">Select To Date</label>

								<div class="col-md-2"><input type="text" name="search_to_date" id="search_to_date" class="select_date form-control" value="<?php echo $todate; ?>"></div>

							</div>

							<div class="col-md-4 col-md-offset-2">											

								<button name="viewattendance" id="view-attendance" class="btn btn-primary" type="submit" value="viewattendance">View</button>

							</div>

						</form>

						<?php						

						if( !empty( $startdate ) && !empty( $todate ) ) {

							$date_from	=	strtotime($startdate);

							$date_to	=	strtotime($todate);

							$att_table	=	$wpdb->prefix."wpsp_attendance";

						?>

							<table class="table">

								<tr>

									<th>Date</th>

									<?php 

										foreach( $child as $ch ) {

											echo '<th>'.$ch['name'].'</th>';

										}

									?>

								</tr>

								<?php

								for ( $i=$date_from; $i<=$date_to; $i+=86400 ) {

									$cdate	=	date("Y-m-d", $i);

									echo '<tr><td>'.$cdate.'</td>';

										foreach( $child as $ch ) {

											$classID			=	$ch['class_id'];

											$get_attendance		=	$wpdb->get_row("select *from $att_table where class_id=$classID AND date='$cdate'", ARRAY_A);

											$attendance_status	=	'Not Added Yet'.$get_attendance['absents'];

											if( !empty( $get_attendance ) && isset( $get_attendance['absents'] ) && $get_attendance['absents']=='Nil' ) {

													$attendance_status	=	'Present';

											} else {

												$attendance_list	=	json_decode( $get_attendance['absents'] );

												$studentId			=	$ch['student_id'];
												if( !empty( $attendance_list  ) ) {
													foreach( $attendance_list as $key => $value ) {

														if( $value->sid == $studentId ) {

															$attendance_status = '<span class="label label-danger">Absent</span> '.$value->reason;
															break;

														} else {

															$attendance_status = 'Present';

														}
													}
												}		

											}

											echo '<td>'.$attendance_status.'</td>'; 

										}

									echo '</tr>';

								}

								?>

							</table>

						<?php }

						?>

					</div>

				</div>

			</div>

			<div class="modal fade" id="ViewModal" tabindex="-1" role="dialog" aria-labelledby="ViewModal" aria-hidden="true">

				<div class="modal-dialog">

					<div class="modal-content" id="ViewModalContent">



					</div>

				</div>

			</div>

			<?php

			wpsp_body_end();

			wpsp_footer();

		}else if($current_user_role=='student'){

			wpsp_topbar();

			wpsp_sidebar();

			wpsp_body_start();

			$st_id=$current_user->ID;

			?>

			<section class="content-header">

				<h1>Attendance</h1>

				<ol class="breadcrumb">

					<li><a href="<?php echo site_url('sch-dashboard'); ?> "><i class="fa fa-home"></i> Dashboard</a></li>

					<li><a href="<?php echo site_url('sch-attendance'); ?>">Attendance</a></li>

				</ol>

			</section>

			<section class="content">

				<?php
					echo $att_info=wpsp_AttReport($st_id);
				?>

			</section>

			<div class="modal fade" id="ViewModal" tabindex="-1" role="dialog" aria-labelledby="ViewModal" aria-hidden="true">

				<div class="modal-dialog">

					<div class="modal-content" id="ViewModalContent">



					</div>

				</div>

			</div>

			<?php

			wpsp_body_end();

			wpsp_footer();

		}



	}else{

		include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-login.php');

	}



		?>