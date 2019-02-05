<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');

wpsp_header();

	if( is_user_logged_in() ) {

		global $current_user, $wpdb;

		$current_user_role=$current_user->roles[0];

		if( $current_user_role=='administrator' || $current_user_role=='teacher' ) {

			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			$class_id		=	$subject_id	=	$exam_id=0;
			$proversion		=	wpsp_check_pro_version();
			$proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
			$protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';
			$prodisable		=	!$proversion['status'] ? 'disabled="disabled"'	: '';			

			if(	isset(  $_POST['MarkAction']  ) ){
				$class_id	=	intval($_POST['ClassID']);
				$subject_id	=	intval($_POST['SubjectID']);
				$exam_id	=	intval($_POST['ExamID']);
			}

			$ctname		=	$wpdb->prefix.'wpsp_class';
			$classQuery	=	"select `cid`,`c_name` from `$ctname`";
			$msg		=	'Please Add Class Before Adding Marks';

			if( $current_user_role=='teacher' ) {
				$cuserId	=	intval($current_user->ID);
				$classQuery	=	"select cid,c_name from $ctname where teacher_id=$cuserId";
				$msg		=	'Please Ask Principal To Assign Class';
			}

			$clt	=	$wpdb->get_results( $classQuery );

		?>

			<section class="content-header">
				<h1> <?php _e( 'Marks', 'WPSchoolPress' ); ?> </h1>
				<ol class="breadcrumb">
					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i><?php  _e( 'Dashboard', 'WPSchoolPress' ); ?></a></li>
					<li><a href="<?php wpsp_admin_url();?>sch-marks"><?php _e( 'Marks', 'WPSchoolPress' ); ?> </a></li>
				</ol>
			</section>

			<section class='content'>
				<div class="row">
				<div class="col-md-12">

					<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbsp; Students Marks</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">	

							<?php if( empty( $clt ) ) {

								echo '<div class="alert alert-danger col-lg-2">'.$msg.'</div>';

							} else { ?>

							<div class="col-md-12">

								<form class="form-horizontal" id="MarkForm" action="" style="border-radius: 0px;" method="post" enctype="multipart/form-data">

									<div class="form-group col-md-4">

										<label class="col-md-2 control-label"><?php _e( 'Class', 'WPSchoolPress' ); ?></label>

										<div class="col-md-10">   

											<select name="ClassID"  id="ClassID" class="form-control" required>

												<option value=""><?php _e( 'Select Class', 'WPSchoolPress' ); ?> </option>

												<?php

													foreach( $clt as $cnm ) { ?>

														<option value="<?php echo intval($cnm->cid);?>" <?php if($cnm->cid==$class_id) echo "selected";?>><?php echo $cnm->c_name;?></option>

													<?php }	?>

											</select>

										</div>

									</div>

									<div class="form-group col-md-4">

										<label class="col-lg-2 col-md-2 control-label"><?php _e( 'Exam', 'WPSchoolPress'); ?></label>

										<div class="col-lg-10 col-md-10">

											<select name="ExamID" class="form-control" id="ExamID" required>

												<?php

												if( $exam_id > 0 ) {

													$examtable	=	$wpdb->prefix.'wpsp_exam';

													$examlist	=	$wpdb->get_results("select eid,e_name from $examtable where classid=$class_id");													

													foreach( $examlist as $exam ) { ?>

														<option value="<?php echo intval($exam->eid);?>" <?php if($exam->eid==$exam_id) echo "selected";?>><?php echo $exam->e_name;?></option>

													<?php }

												} else { ?>

													<option value=""><?php _e( 'Select Exam', 'WPSchoolPress' ); ?> </option>

												<?php } ?>

											</select>

										</div>

									</div>

									<div class="form-group col-md-4">

										<label class="col-md-2 control-label"><?php _e( 'Subject', 'WPSchoolPress'); ?> </label>

										<div class="col-md-10">

										<?php

											$examtable	=	$wpdb->prefix.'wpsp_exam';	
											if( $exam_id!= '' ) {	
												$subjectID		=	$wpdb->get_var("select subject_id from $examtable where eid=$exam_id");
												$subjectlist	=	explode( ",", $subjectID );	
											}

										?>

											<select name="SubjectID"  id="SubjectID" class="form-control" required>

											<?php if( $subject_id>0 ) {												

												$sub_tbl	=	$wpdb->prefix."wpsp_subject";

												$subInfo	=	$wpdb->get_results("select sub_name,id from $sub_tbl where class_id=$class_id");

												foreach( $subInfo as $sub_list ) {

													if( in_array( $sub_list->id, $subjectlist ) ) {

														echo "<option value='".$sub_list->id."'". selected( $subject_id, $sub_list->id, false ).">".$sub_list->sub_name."</option>";

													}

												}

											} else { ?>

												<option value=""><?php _e( 'Select Subject', 'WPSchoolPress' ); ?></option>

											<?php } ?>

											</select>

										</div>

									</div>									

									<div class="form-group col-md-4 <?php echo $proclass;?>" title="<?php echo $protitle;?>" <?php echo $prodisable; ?>>

										<label class="col-lg-2 col-md-2 control-label"><?php _e( 'Attach CSV', 'WPSchoolPress'); ?></label>

										<div class="col-lg-10 col-md-10">

											<input type="file" name="MarkCSV" class="<?php echo $proclass;?>" title="<?php echo $protitle;?>" <?php echo $prodisable; ?>>

										</div>

									</div>

								

									<div class="form-group">

										<div class="col-sm-offset-4 col-sm-8">

											<button type="submit" class="btn btn-primary MarkAction update-btn" name="MarkAction"  value="Add Marks"><?php _e( 'Add/Update', 'WPSchoolPress'); ?> </button>

											<button name="MarkAction " class="btn btn-secondary update-btn MarkAction <?php echo $proclass;?>" title="<?php echo $protitle;?>" <?php echo $prodisable; ?> value="ImportCSV"><?php _e( 'Upload CSV', 'WPSchoolPress'); ?></button>

											<button name="MarkAction" class="btn btn-success update-btn" id="viewmarks" value="View Marks"><?php _e( 'View Marks', 'WPSchoolPress'); ?> </button>

										</div>

									</div>									

								</form>

							</div>	

							<?php

							if(isset($_POST['MarkAction']) && sanitize_text_field($_POST['MarkAction'])=='Add Marks'){							

								$mark_entered	=	'';
								//Get Extra Fields
								$extra_tbl		=	$wpdb->prefix."wpsp_mark_fields";

								$extra_fields	=	$wpdb->get_results("select * from $extra_tbl where subject_id='$subject_id'");

								if( wpsp_IsMarkEntered( $class_id,$subject_id,$exam_id ) ) {

									$wpsp_marks		=	wpsp_GetMarks($class_id,$subject_id,$exam_id);

									$mark_entered	=	1;

									$wpsp_exmarks	=	wpsp_GetExMarks($subject_id,$exam_id);

									

									//Create extra mark array i.e $extra_marks[wp_usr_id][field_id]=mark;

									foreach($wpsp_exmarks as $exmark){

										$extra_marks[$exmark->student_id][$exmark->field_id]=$exmark->mark;

									}

								}

							?>

							<div id="mark_entry" class="col-md-12 col-lg-12 col-sm-12">

								<?php if( $mark_entered == 1 ) {
										
									?>

									<h3><?php _e( 'Marks Already Entered update here!', 'WPSchoolPress'); ?></h3><br/>

								<?php } else {	?>

									<h1><?php _e( 'Enter Marks', 'WPSchoolPress'); ?></h1>

								<?php }	?>

								<div class="col-md-12 table-responsive">

									<form class="form-horizontal group-border-dashed" id="AddMarkForm" action="" style="border-radius: 0px;" method="post">

										<input class="form-control" type="hidden" value="<?php echo intval($subject_id);?>" name="SubjectID">  

										<input class="form-control" type="hidden" value="<?php echo intval($class_id);?>" name="ClassID">  

										<input class="form-control" type="hidden" value="<?php echo intval($exam_id);?>" name="ExamID">  

										<table class="table no-border">

											<thead>

												<tr>

													<!-- <th>#</th> -->

													<th><?php _e( 'RollNo.', 'WPSchoolPress'); ?></th>

													<th><?php _e( 'Name', 'WPSchoolPress' ); ?></th>

													<th><?php _e( 'Mark', 'WPSchoolPress' );?></th>

													<?php if(!empty($extra_fields)){

															foreach($extra_fields as $extf){

															?>

																<th><?php echo $extf->field_text;?></th>

															<?php } } ?>

												</tr>

											</thead>

											<tbody>

											<?php
											if($mark_entered==1)

											{

												$stable		=	$wpdb->prefix."wpsp_student";

												$sno		=	1;

												$getslist	=	$wpdb->get_results("select * from $stable WHERE class_id=$class_id order by CAST('s_rollno' as SIGNED)");

												
												//echo "<pre>";print_r($getslist);echo "</pre>"; 
												if (empty($getslist)) {
													echo "<tr><td>".__( 'No Students to retrive', 'WPSchoolPress')."</td></tr>";
													
												}else {
												foreach ($getslist as $student ) {													

													$usid		=	intval($student->wp_usr_id);

													$stroll		=	$student->s_rollno;

													$stfullname	=	$student->s_fname.' '.$student->s_mname.' '.$student->s_lname;

													$marktable	=	$wpdb->prefix."wpsp_mark";													

													$getmark	=	$wpdb->get_row("select * from $marktable WHERE class_id=$class_id AND student_id=$usid AND subject_id=$subject_id AND exam_id=$exam_id ");													

													//$markID		=	$wpdb->get_results("select mid from $mtable WHERE subject_id=$subject_id and class_id=$class_id and exam_id=$exam_id and student_id=$usid order by mid ASC");													

													$getmarkid		=	isset( $getmark->mid ) ? $getmark->mid : '';

													if( empty($getmark) ) {

															$mark_data	=	array( 'subject_id'=>$subject_id,'class_id'=>$class_id,'student_id'=>$usid,'exam_id'=>$exam_id );

															$m_ins		=	$wpdb->insert($marktable,$mark_data);

															if( $wpdb->insert_id )

																$getmarkid = $wpdb->insert_id;																

													}

													?>

													<tr>

													<!-- 	<td class="number"><?php // echo $sno;?></td>
 -->
														<td class="number"><?php echo $stroll;?></td>

														<td class="number"><?php echo $stfullname;?></td>

														<td class="sch_mark">														

															<input type="text" class="numbers" id="v_marks" value="<?php echo $getmark->mark;  ?>" name="marks[<?php echo $getmarkid;?>][]">

														</td>

														<?php if(!empty($extra_fields)){
															
															foreach($extra_fields as $extf){

															?>

																<td><input type="text" class="numbers"id="v_exmark" min="0" name="exmarks[<?php echo $usid;?>][<?php echo $extf->field_id;?>]" value="<?php echo $extra_marks[$usid][$extf->field_id];?>"></td>

														<?php } } ?>

													</tr>

												<?php

													$sno++;	

												}

												echo "<input type='hidden' name='update' value='true'>";
											}

											}else{

												global $wpdb;

												$stable		=	$wpdb->prefix."wpsp_student";

												$getslist	=	$wpdb->get_results("select * from $stable WHERE class_id=$class_id order by CAST('rollno' as SIGNED)");

												$sno		=	1;
													if (empty($getslist)) {
													echo "<tr><td>".__( 'No Students to retrive', 'WPSchoolPress')."</td></tr>";
													
												}else {
												foreach( $getslist as $slist ) {

												?>

													<tr>

														<!-- <td class="number"><?php //echo $sno; ?></td> -->

														<td class="number"><?php echo $slist->s_rollno;?></td>

														<td class="number"><?php echo $slist->s_fname.' '.$slist->s_mname.' '.$slist->s_lname;?></td>

														<td class="sch_mark">

															<input type="text" class="numbers" min="0" value="" class="markbox" name="marks[<?php echo $slist->wp_usr_id;?>][]">

														</td>

														<?php if(!empty($extra_fields)){

															foreach($extra_fields as $extf){

															?>

																<td><input type="text" class="numbers" id="v_exmark1" min="0" name="exmarks[<?php echo $slist->wp_usr_id;?>][<?php echo $extf->field_id;?>]"></td>
  
															<?php } } ?> 

													</tr>			

												<?php

													$sno++;

												}	
											}
											}

											?>

											<?php 
if (empty($getslist)) {
													//echo "<tr><td>".__( 'No Students to retrive', 'WPSchoolPress')."</td></tr>";
													
												}else {
											if(empty($getslist) && $mark_entered=='0'){

													//echo "<tr><td>".__( 'No Students to retrive', 'WPSchoolPress')."</td></tr>";

												}else { ?>

												<tr>

													<td></td>

													<td></td>

													<td><input  type="submit" class="btn btn-primary" id="AddMark_Submit" name="AddMark_Submit"  value="Save Marks"></td>

												</tr>

												<?php } } ?>

											</tbody>

										</table>

									</form>

								</div>

							</div>

							<?php

								} else if(isset($_POST['MarkAction']) && sanitize_text_field($_POST['MarkAction'])=='View Marks'){

									include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-viewMark.php');									

								}else{

									do_action( 'wpsp_marks_actions' );

								}

							?>

						<?php } ?>	

						</div>

					</div>

				</div>

			</div>

		</section>	

		<?php

			wpsp_body_end();

			wpsp_footer();

		}else if( $current_user_role=='parent' ) {

			wpsp_topbar();

			wpsp_sidebar();

			wpsp_body_start();

				global $wpdb;

				$parent_id		=	intval($current_user->ID);

				$student_table	=	$wpdb->prefix."wpsp_student";

				$class_table	=	$wpdb->prefix."wpsp_class";

				$students		=	$wpdb->get_results("select st.wp_usr_id, st.class_id, CONCAT_WS(' ', st.s_fname, st.s_mname, st.s_lname ) AS full_name,cl.c_name from $student_table st LEFT JOIN $class_table cl ON cl.cid=st.class_id where st.parent_wp_usr_id='$parent_id'");

				$child			=	array();

				foreach($students as $childinfo){

					$child[]=array(	'student_id'	=>	$childinfo->wp_usr_id,

									'name'			=>	$childinfo->full_name,

									'class_id'		=>	$childinfo->class_id,

									'class_name'	=>	$childinfo->c_name	);

				}

				?>

				<section class="content-header">

					<h1> <?php _e( 'Marks', 'WPSchoolPress' ); ?> </h1>

					<ol class="breadcrumb">

						<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> <?php _e( 'Dashboard', 'WPSchoolPress'); ?></a></li>

						<li><a href="<?php wpsp_admin_url();?>sch-marks"><?php _e( 'Marks', 'WPSchoolPress' ); ?> </a></li>

					</ol>

				</section>

				<section class="content">

					<div class="row">

						<div class="col-md-12">

							<div class="box box-info">
									<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbsp; Students Marks</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>

								<div class="box-body">

									<div class="tabbable-line">

										<ul class="nav nav-tabs ">

											<?php $i=0; foreach($child as $ch) { ?>

												<li class="<?php echo ($i==0)?'active':''?>"><a href="#<?php echo 'student'.$i;?>"  data-toggle="tab"><?php echo $ch['name'];?></a></li>

												<?php $i++; } ?>

										</ul>

										<div class="tab-content">

											<?php

											$i=0;

											foreach( $child as $ch ) {

												$ch_class=$ch['class_id'];

											?>

											<div class="tab-pane <?php echo ($i==0)?'active':''?>" id="<?php echo 'student'.$i;?>">
												<div class="col-md-12 table-responsive">

												<?php

												$student_id	=	$ch['student_id'];

												wpsp_MarkReport( $student_id );

												?></div>

											</div>

											<?php

											$i++;

											}

											?>

										</div>

									</div>

								</div>

							</div>		

						</div>	

					</div>	

				</section>

			<?php

			wpsp_body_end();

			wpsp_footer();

		}else if( $current_user_role=='student' ) {

			wpsp_topbar();

			wpsp_sidebar();

			wpsp_body_start();

			$student_id=intval($current_user->ID);

			?>

			<section class="content-header">

				<h1><?php _e( 'Marks', 'WPSchoolPress' ); ?></h1>

				<ol class="breadcrumb">

					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> <?php _e( 'Dashboard', 'WPSchoolPress'); ?></a></li>

					<li><a href="<?php wpsp_admin_url();?>sch-marks"><?php _e( 'Marks', 'WPSchoolPress' ); ?> </a></li>

				</ol>

			</section>

			<section class="content">
				<div class="col-md-12">

					<div class="box box-info">
							<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbsp; Your Marks</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>

						<div class="box-body">
							<div class="gap-top-bottom">

			<?php	wpsp_MarkReport($student_id); ?>
			</div>
		</div>
	</div>
</div> 

			</section>

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