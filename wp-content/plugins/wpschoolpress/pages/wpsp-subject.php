<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();
	if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role=$current_user->roles[0];
		if( $current_user_role=='administrator' || $current_user_role=='teacher')
		{
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			$class_table	=	$wpdb->prefix."wpsp_class";
			$classQuery		=	"select cid,c_name from $class_table Order By cid ASC";
			$msg			=	'Please Add Class Before Adding Subjects';
			if( $current_user_role=='teacher' ) {
				$cuserId		=	intval($current_user->ID);
				$classQuery		=	"select cid,c_name from $class_table where teacher_id=$cuserId";
				$msg			=	'Please Ask Principal To Assign Class';
			}
			$sel_class		=	$wpdb->get_results( $classQuery );
			if(( isset($_GET['classid']) && is_numeric($_GET['classid']))) {
				$label	=	__( 'Add New Class', 'WPSchoolPress');
				$filename	=	WPSP_PLUGIN_PATH .'includes/wpsp-subjectForm.php';
				include_once ( $filename );
			}elseif(( isset($_GET['id']) && is_numeric($_GET['id']))) {
				$label	=	__( 'Edit Class', 'WPSchoolPress');
				$filename	=	WPSP_PLUGIN_PATH .'includes/wpsp-editsubjectForm.php';
				include_once ( $filename );
			}else{
			 $sel_classname	=	$sel_class[0]->c_name;
			 //$sel_classid	=	$sel_class[0]->cid;
			 if( isset( $_POST['ClassID'] ) && !empty (sanitize_text_field($_POST['ClassID']) ) ) {
				 $sel_classid	= $_POST['ClassID'];
				 foreach( $sel_class as $key=>$value ) {						
					if( $value->cid	==	$sel_classid ) {
						$sel_classname	=	$value->c_name;
						break;
					}
				 }
			 }			
		?>
		<section class="content-header">
			<h1>List of Class <?php echo $sel_classname; ?> Subjects</h1>
			<ol class="breadcrumb">
				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="<?php wpsp_admin_url();?>sch-subject">Subjects</a></li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-md-12">

					<div class="box box-solid bg-blue-gradient">
						 <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-book" aria-hidden="true"></i>&nbsp;List of Subjects</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
						<div class="box-footer text-black">
							<?php if( empty( $sel_class ) ) { echo '<div class="alert alert-danger col-lg-2">'.$msg.'</div>'; } else { ?>
							<div class="subject-head">
								
								<div class="subject-inner" style="padding:0;display: inline;">
									<form action="" id="SubjectList-Form" name="SubjectList-Form" method="POST" class="class-filter">
										<label>Select Class Name *</label>
										<select name="ClassID" id="ClassID" class="form-control" style="width: auto;">


											<option value="all" <?php if($sel_classid=='all') echo "selected"; ?>><?php _e( 'All', 'WPSchoolPress' ); ?></option>

											<?php											
											foreach($sel_class as $classes) { ?>
												<option value="<?php echo intval($classes->cid);?>" <?php if($sel_classid==$classes->cid) echo "selected"; ?>><?php echo $classes->c_name;?></option>
											<?php } ?>
												
										</select>
										
									</form>

									<a class="btn btn-success pull-right" href="<?php wpsp_admin_url();?>sch-subject&tab=addsubject&classid=<?php echo intval($sel_classid);?>" id="subdisable"> <i class="fa fa-plus"></i> &nbsp; Add Subject</a>
									<span class="pull-right" style="font-size: 13px;padding: 7px 15px; color: red;"> <<< Please select a class to add subject</span>
								</div>
									
								
								 							
							</div>

							
		
							<div class="col-md-12 table-responsive">


							<table id="subject_table" class="table table-bordered table-striped " style="margin-top:10px">
								<thead>
									<tr>
										<th>#</th>
										<th>Subject Code</th>
										<th>Subject Name</th>
										<th>Faculty</th>
										<th>Book Name</th>
										<th class="nosort">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$teacher_table=	$wpdb->prefix."wpsp_teacher";
									$teacher_data = $wpdb->get_results("select wp_usr_id,CONCAT_WS(' ', first_name, last_name ) AS full_name from $teacher_table order by tid");
									$teacherlist	=	array();
									if( !empty( $teacher_data ) ) {
										foreach( $teacher_data  as $value )
											$teacherlist[$value->wp_usr_id] = $value->full_name;
									}
									
									$subtable=$wpdb->prefix."wpsp_subject";
									$class_id='';							
									if( isset($_POST['ClassID'] ) ) {
										$class_id=intval($_POST['ClassID']);
									}else if( !empty( $sel_class ) ) {
										$class_id = 'all';
									}
									$classquery	=	"where class_id='$class_id'";
									if($class_id=='NULL'){
										$classquery	=	"";
									}elseif($class_id=='all'){
										$classquery="";
									}
									$wpsp_subjects =$wpdb->get_results("select * from $subtable $classquery order by sub_code desc");
									
									$sno=1;
									foreach ($wpsp_subjects as $wpsp_subject) 
									{
										$teach_id= (int)$wpsp_subject->sub_teach_id;
										$teacher=get_userdata($teach_id);
										
									?>
										<tr id="<?php echo intval($wpsp_subject->id);?>" class="pointer">
											<td><?php echo $sno;?></td>
											<td><?php echo !empty( $wpsp_subject->sub_code ) ? $wpsp_subject->sub_code :'-';	?></td> 
											<td><?php echo  $wpsp_subject->sub_name;?></td> 
											<td><?php echo isset( $teacherlist[$teach_id] ) ? $teacherlist[$teach_id] : '';?></td>
											<td><?php echo $wpsp_subject->book_name;?></td>
											<td>
												
												<a href="<?php wpsp_admin_url();?>sch-subject&id=<?php echo intval($wpsp_subject->id).'&edit=true';?>"><i class="fa fa-pencil btn btn-warning edit-btn"></i></a>
												<a href="javascript:;" sid="<?php echo intval($wpsp_subject->id);?>" class="SubjectDeleteBt" title="Delete"><i class="fa fa-trash btn btn-danger"></i></a>	
											</td>
										</tr>
									<?php	
										$sno++;
									}
									?>
								</tbody>
							</table>
						</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php }?>
		<div class="modal fade modal-wide" id="AddSubjectModal" tabindex="-1" role="dialog" aria-labelledby="AddSubject" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="col-md-12">
						<div class="box box-success">
							<div class="box-header">
								<h3 class="box-title">New Subject Entry</h3>
								
							</div><!-- /.box-header -->
							<div class="box-body" id="SEFormContainer">
								<form name="SubjectEntryForm" action="#" id="SubjectEntryForm" method="post">
									<div class="box-body">
										<div class="col-md-12">
											<?php wp_nonce_field( 'SubjectRegister', 'subregister_nonce', '', true ) ?>
											<div class="form-group">
												<label for="Name">Class Name : </label> <span id="SClassName"></span>
												<input type="hidden" class="form-control" id="SCID" name="SCID" value=>
											</div>
											<?php for($i=1;$i<=5;$i++){?>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group col-md-3">
														<label for="Name">Subject <?php echo $i;?></label><?php if($i=='1') { ?>
														<span class="red">*</span> 
														<!-- <span class="pull-right"><a href="#" id="ShowExtraFields">Show Extra Fields</a></span> -->
														<?php } ?>
														<input type="text" class="form-control" name="SNames[]" placeholder="Subject Name">
													</div>
													<!-- <div class="SubjectExtraDetails col-md-8"> -->
														<div class="form-group col-md-3">
															<label for="Name">Subject Code</label><span class="text-gray"> (Optional)</span>
															<input type="text" class="form-control" name="SCodes[]" placeholder="Subject Code">
														</div>
														<div class="form-group col-md-3">
															<label for="Name">Subject Teacher</label><span> (Incharge)</span>
															<select name="STeacherID[]" class="form-control">
																<option value="">Select Teacher </option>
																<?php
																	$k_filter = array('role' => 'teacher');
																	$kv_teacher_list=get_users($k_filter);
																	foreach ($kv_teacher_list as $kv_single_teacher) 
																	{
																		?>
																		<option value="<?php echo intval($kv_single_teacher->ID);?>"><?php echo $kv_single_teacher->user_nicename;?></option>
																		<?php
																	}
																	?>
															</select>
														</div>
														<div class="form-group col-md-3">
															<label for="BName">Book Name</label><span class="text-gray"> (Optional)</span>
															<input type="text" class="form-control" name="BNames[]" placeholder="Book Name">
														</div>
														<?php if($i!='5') { ?>
														<hr style="border-top:1px solid #5C779E"/>
														<?php }?>
													<!-- </div> -->
												</div>
											</div>		
											<?php } ?>											
										</div>
										<div id="SEFResponse"></div>
									</div>
									<div class="box-footer">
										<span class="pull-right">
											<button type="submit" class="btn btn-primary">Submit</button>
											<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										</span>
									</div>
								</form>
							<div class="formresponse"></div>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div><!-- /.modal -->
		<!--Edit Modal-->
		<div class="modal fade" id="EditSubjectModal" tabindex="-1" role="dialog" aria-labelledby="EditSubject" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="box box-success">
						<div class="box-header">
							<h3 class="box-title">Edit Subject Details</h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div><!-- /.box-header -->
						<form action="" name="SubjectEditForm"  id="SEditForm">
							<div class="box-body">
								<div class="col-md-12">
									<div class="form-group">
										<label for="Name">Subject </label><span class="red">*</span>
										<input type="text" class="form-control" ID="EditSName" name="EditSName" placeholder="Subject Name">
										<input type="hidden" class="form-control" value="" id="SRowID" name="SRowID">
										<input type="hidden" class="form-control" value="" id="ESClassID" name="ClassID">
									</div>
									<div class="form-group">
										<label for="Name">Subject Code</label><span class="text-gray"> (Optional)</span>
										<input type="text" class="form-control" ID="EditSCode" name="EditSCode" placeholder="Subject Code">
									</div>
									<div class="form-group">
										<label for="Name">Subject Teacher</label><span> (Incharge)</span>
										<select name="EditSTeacherID" id="EditSTeacherID" class="form-control">
											<option value="">Select Teacher </option>
											<?php
												$k_filter = array('role' => 'teacher');
												$kv_teacher_list=get_users($k_filter);
												foreach ($kv_teacher_list as $kv_single_teacher) 
												{
													?>
													<option value="<?php echo intval($kv_single_teacher->ID);?>"><?php echo $kv_single_teacher->user_nicename;?></option>
													<?php
												}
												?>
										</select>
									</div>
									<div class="form-group">
										<label for="BName">Book Name</label><span class="text-gray"> (Optional)</span>
										<input type="text" class="form-control" name="EditBName" id="EditBName" placeholder="Book Name">
									</div>
								</div>
							</div>
							<div class="box-footer">
								<span class="pull-right">
									<input type="submit" id="SEditSave" class="btn btn-primary" value="Save">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								</span>
							</div>
							<div id="editformresponse"></div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!--.Edit Modal-->
		<div class="modal fade" id="InfoModal" tabindex="-1" role="dialog" aria-labelledby="InfoModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="col-md-12">
						<div class="box box-success">
							<div class="box-header">
								<h3 class="box-title" id="InfoModalTitle"></h3>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							</div><!-- /.box-header -->
							<div id="InfoModalBody" class="box-body">
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
		else if($current_user_role=='parent')
		{
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			$parent_id=intval($current_user->ID);
			$student_table=$wpdb->prefix."wpsp_student";
			$class_table=$wpdb->prefix."wpsp_class";
			$subject_table=$wpdb->prefix."wpsp_subject";
			$students=$wpdb->get_results("select st.wp_usr_id, st.class_id, st.s_fname, CONCAT_WS(' ', st.s_fname, st.s_mname, st.s_lname ) AS full_name,cl.c_name from $student_table st LEFT JOIN $class_table cl ON cl.cid=st.class_id where st.parent_wp_usr_id='$parent_id'");
			$child=array();
			foreach($students as $childinfo){
				$child[]=array('student_id'=>$childinfo->wp_usr_id,'fname'=>$childinfo->s_fname,'name'=>$childinfo->full_name,'class_id'=>$childinfo->class_id,'class_name'=>$childinfo->c_name);
			}
			?>
			<section class="content-header">
				<h1>Subjects</h1>
				<ol class="breadcrumb">
					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="<?php wpsp_admin_url();?>sch-subject">Subjects</a></li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-info">
							<div class="box-body">
								<div class="tabbable-line">
									<ul class="nav nav-tabs ">
										<?php $i=0; foreach($child as $ch) { ?>
											<li class="<?php echo ($i==0)?'active':''?>"><a href="#<?php echo str_replace(" ", "",$ch['fname'].$i);?>"  data-toggle="tab"><?php echo $ch['name'];?></a></li>
											<?php $i++; } ?>
									</ul>
									<div class="tab-content">
										<?php
										$teacher_table=	$wpdb->prefix."wpsp_teacher";
									$teacher_data = $wpdb->get_results("select wp_usr_id,CONCAT_WS(' ', first_name, last_name ) AS full_name from $teacher_table order by tid");
									$teacherlist	=	array();
									if( !empty( $teacher_data ) ) {
										foreach( $teacher_data  as $value )
											$teacherlist[$value->wp_usr_id] = $value->full_name;
									}
										$i=0;
										foreach($child as $ch) {
											$ch_class=$ch['class_id'];
											?>
											<div class="tab-pane  table-responsive <?php echo ($i==0)?'active':''?>" id="<?php echo str_replace(" ", "",$ch['fname'].$i);?>">
												<caption><label> Class Name : </label> <?php echo $ch['class_name'];?></caption>
												<table class="table table-bordered table-striped">
													<thead>
													<tr>
														<th>#</th>
														<th>Subject Code</th>
														<th>Subject Name</th>
														<th>Faculty</th>
														<th>Book Name</th>
													</tr>
													</thead>
													<tbody>
													<?php
													$cl_subjects=$wpdb->get_results("select * from $subject_table where class_id=$ch_class");
													$sno=1;
													foreach($cl_subjects as $cl_sub){
													$teach_id= (int)$cl_sub->sub_teach_id;
													$teacher=get_userdata($teach_id);
													?>
													<tr id="<?php echo intval($cl_sub->id);?>" class="pointer">
														<td><?php echo $sno;?>
														<td><?php echo !empty( $cl_sub->sub_code ) ? $cl_sub->sub_code : '-' ; ?></td>										
														<td><?php echo  $cl_sub->sub_name;?></td>										
														<td><?php echo isset( $teacherlist[$teach_id] ) ? $teacherlist[$teach_id] : '';?></td>
														<td><?php echo $cl_sub->book_name;?></td>
													</tr>
														<?php
														$sno++;
													}
													?>
													</tbody>
												</table>
											</div>
											<?php $i++; } ?>
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
		}else if($current_user_role=='student')
		{
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			$student_id=intval($current_user->ID);
			$student_table=$wpdb->prefix."wpsp_student";
			$class_table=$wpdb->prefix."wpsp_class";
			$subject_table=$wpdb->prefix."wpsp_subject";
			$cl_subjects=$wpdb->get_results("select st.class_id,su.* from $student_table st LEFT JOIN $subject_table su ON su.class_id=st.class_id where st.wp_usr_id=$student_id");
			?>
			<section class="content-header">
				<h1>Subjects</h1>
				<ol class="breadcrumb">
					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="<?php wpsp_admin_url();?>sch-subject">Subjects</a></li>
				</ol>
			</section>
			<section class="content">
				 <div class="box-header ui-sortable-handle" style="cursor: move;">
                                    <i class="fa fa-graph"></i>
                                    <h3 class="box-title"><i class="fa fa-book" aria-hidden="true"></i>  List of Subjects </h3>
                                    <!-- tools box -->
                                  
                                    <!-- /. tools -->
                                </div>
                <div class="box box-info box-body table-responsive">
				<table class="table table-bordered table-striped dataTable no-footer">
					<thead>
					<tr>
						<th>#</th>
						<th>Subject Code</th>
						<th>Subject Name</th>
						<th>Faculty</th>
						<th>Book Name</th>
					</tr>
					</thead>
					<tbody>
						<?php
						$teacher_table=	$wpdb->prefix."wpsp_teacher";
									$teacher_data = $wpdb->get_results("select wp_usr_id,CONCAT_WS(' ', first_name, last_name ) AS full_name from $teacher_table order by tid");
									$teacherlist	=	array();
									if( !empty( $teacher_data ) ) {
										foreach( $teacher_data  as $value )
											$teacherlist[$value->wp_usr_id] = $value->full_name;
									}
						$sno=1;
						foreach($cl_subjects as $cl_sub){
							$teach_id= (int)$cl_sub->sub_teach_id;
							//$teacher=get_userdata($teach_id);

							?>
							<tr id="<?php echo intval($cl_sub->id);?>" class="pointer">
								<td><?php echo $sno;?></td>
								<td><?php echo !empty( $cl_sub->sub_code ) ? $cl_sub->sub_code : '-' ; ?></td>
								<td><?php echo  $cl_sub->sub_name;?> </td>
								<td><?php echo isset( $teacherlist[$teach_id] ) ? $teacherlist[$teach_id] : '';?></td>
									
								<td><?php echo $cl_sub->book_name;?></td>
							</tr>
							<?php
							$sno++;
						}
						?>
					</tbody>
				</table>
			</div>
			</section>
			<?php
			wpsp_body_end();
			wpsp_footer();
		}
	}
	else{
		//Include Login Section
		include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-login.php');
	}
		?>
		