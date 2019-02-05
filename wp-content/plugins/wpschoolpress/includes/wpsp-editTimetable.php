<?php
if ( !defined( 'ABSPATH' ) ) exit;
	$tt_table 		=	$wpdb->prefix. "wpsp_timetable";
	$subject_table 	=	$wpdb->prefix . "wpsp_subject";
	$h_table 		=	$wpdb->prefix . "wpsp_workinghours";
	$class_id 		=	intval($_GET['timetable']);
	$sess_template 	=	sanitize_text_field($_POST['sessions_template']);
	?>

	<section class="content">

		<div class="row">

			<div class="col-md-12">

				<div class="box box-info">

					<div class="box-header"><h3 class="box-title">Drag and Drop Subjects </h3></div>

					<div class="box-body">

						<?php

							$check_tt = $wpdb->get_row("Select heading from $tt_table where class_id=$class_id and heading!=''");

							if (!empty($check_tt)) {
								$get_sessions = unserialize($check_tt->heading);

								foreach ($get_sessions as $sesio) {

									$session[] = $sesio;

								}

							} else {

								$error = 1;

								echo "<div class='alert alert-danger'>Can't fetch template from the selected class</div>";

							}

						

						if (count($session) > 0) {

							$chck_hd = $wpdb->get_row("SELECT * from $tt_table where class_id=$class_id and time_id='0' and day='0' and heading!=''");
							if (empty($chck_hd)) {
							//if(count($chck_hd) == null) {

								$ins = $wpdb->insert($tt_table, array('class_id' => $class_id,'heading' => serialize($session)));

							}

						} else {

							$error = 1;

							echo "<div class='alert alert-danger'>No Sessions Retrieved</div>";

						}

						

						$wpsp_hours_table 		=	$wpdb->prefix . "wpsp_workinghours";

						$wpsp_subjects_table	=	$wpdb->prefix . "wpsp_subject";

						$clt = $wpdb->get_results("SELECT * FROM $wpsp_subjects_table WHERE class_id=$class_id or class_id=0 order by class_id desc");

						if( count($clt) == 0 ) {

							$error = 1;

							echo "<div class='alert alert-danger'>No Subjects retrieved, Check you have subject for this class at <a href='".site_url()."/sch-subject'>Subjects</a></div>";

						}

						

						if( $error == 0 ) {

							$timetable	=	array();

							$tt_days	=	$wpdb->get_results("select * from $tt_table where class_id='$class_id' and time_id !='0' ",ARRAY_A);

							foreach( $tt_days as $ttd ) {

								$timetable[$ttd['day']][$ttd['time_id']]	=	$ttd['subject_id'];

							}

							?>

							<div class="row"> 
								<div class="col-md-12">

								<label>
								Class :  <?php echo wpsp_GetClassName( 	$class_id ); ?>
								</label></div>
						</div>

							<div class="table-responsive">

									<table align="center" class="table">

										<tbody>

										<tr>

											<?php

												foreach ($clt as $id) {

													echo '<td class="removesubject"><div class="item" id="' . $id->id . '" style="width:80px">' . $id->sub_name . '</div>	</td>';

												}

											?>

										</tr>

										</tbody>

									</table>

							</div>
					

							

							<div class="bg-yellow text-right" id="ajax_response_exist" style="background-color: #f39c12 !important;width: auto;float: right;text-align: center;"></div>

							<div class="right table-responsive" id="TimetableContainer">

								<table class="table table-bordered">

									<thead>

										<tr><th><select class="daytype"><option value="0">Days</option><option value="1">Week</option></select></th>

											<?php foreach ($session as $sess) { ?>

												<th><?php $ses_info = $wpdb->get_row("Select * from $wpsp_hours_table where id='$sess'");

													echo $ses_info->begintime . " to " . $ses_info->endtime ?></th>

											<?php } ?>

										</tr>

									</thead>

									<tbody>

										<?php

										$dayname = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

										for ($j = 1; $j <= 7; $j++) {

											?>

											<tr id="<?php echo $j; ?>">

												<td><span class="dayval">Day <?php echo $j; ?></span><span class="daynam" style="display:none"><?php echo $dayname[$j - 1]; ?></span> </td>

												<?php

												foreach ($session as $ses) {

													$hour_det	=	$wpdb->get_row("Select * from $wpsp_hours_table where id='$ses'");

													$td_class	=	$hour_det->type == "1" ? "drop" : "break";										

													$sub_id		=	$sub_name	=	'';

													if( isset($timetable[$j][$ses]) )

														$sub_id	=	$timetable[$j][$ses];

													if( $sub_id >0 ) {

														$sub_name_f =	$wpdb->get_row("SELECT sub_name from $subject_table where id=$sub_id");

														$sub_name	=	$sub_name_f->sub_name;

													}

													if( !empty( $sub_name ) ) {

														$sub_name	=	'<div class="item assigned">'. $sub_name.'</div>';

													}

													?>

													<td class="<?php echo $td_class; ?>" tid="<?php echo $ses; ?>"><?php echo $sub_name; ?> </td>

													<?php

												} ?>

											</tr>

											<?php

										}

										?>

									</tbody>

								</table>

								

								<div class="form-group">

									<div class="col-md-offset-10">

										<input type="hidden" name="class_id" id="class_id" value="<?php echo intval($class_id); ?>">

										<div class="bg-green" id="ajax_response"></div>

									</div>

								</div>					

							</div>

						<?php  } ?>

					</div>

				</div>

			</div>

		</div>

	</section>	