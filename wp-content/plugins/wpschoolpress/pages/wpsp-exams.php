<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();
	if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role	=	$current_user->roles[0];
		$current_user_Id	=	intval($current_user->ID);
		$subject_table		=	$wpdb->prefix."wpsp_subject";			
		if($current_user_role=='administrator' || $current_user_role=='teacher')
		{
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();	

			$filename	=	'';
			$header ='Exams';
			if( isset($_GET['tab'] ) && sanitize_text_field($_GET['tab']) == 'addexam' ) {
				$header	=	$label	=	__( 'Add New Exam', 'WPSchoolPress');
				$filename	=	WPSP_PLUGIN_PATH .'includes/wpsp-examForm.php';				
			}elseif(( isset($_GET['id']) && intval($_GET['id'])))  {
				$header	=	$label	=	__( 'Update Exam', 'WPSchoolPress');
				$filename	=	WPSP_PLUGIN_PATH .'includes/wpsp-examForm.php';				
			}
			$extable	=	$wpdb->prefix."wpsp_exam";
			$ctable		=	$wpdb->prefix."wpsp_class";
			$wpsp_exams =	$wpdb->get_results( "select * from $extable");
			$class_ID	=	0;
			if( $current_user_role=='teacher' ) {
				$cuserId	=	intval($current_user->ID);
				$class_ID	=	$wpdb->get_Var( "select cid from $ctable where teacher_id=$cuserId" );
				$msg		=	'Please Ask Principal To Assign Class';
				if( !empty( intval($class_ID )) ) {
					$wpsp_exams =	$wpdb->get_results( "select * from $extable where classid=$class_ID");
				}
			}
		?>
		<section class="content-header">
			<h1><?php echo $header; ?></h1>
			<ol class="breadcrumb">
				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="<?php wpsp_admin_url();?>sch-exams">Exams</a></li>
				<?php if( !empty($label) ) { ?><li><?php echo $label;?></li> <?php } ?>
			</ol>
		</section>
		<?php 
		if( !empty( $filename) ) {
			include_once ( $filename );
		} else {?>
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					 
				<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbsp; Students Exams List </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">
					<div class="subject-head">
							<?php if( $current_user_role=='teacher' && empty( $class_ID ) ) {
									echo '<div class="alert alert-danger col-lg-2">'.$msg.'</div>';
							} else {?>
							<div class="pull-right">
								<!-- <button class="btn btn-primary pull-right" id="AddExamButton"><i class="fa fa-plus"></i> Add Exam</button> -->
								<a class="btn btn-success pull-right" href="<?php wpsp_admin_url();?>sch-exams&tab=addexam"><i class="fa fa-plus"></i>&nbsp;  Add Exam</a>
							</div>
					</div>		
							<div class="col-md-12 table-responsive">
							<table id="exam_class_table" class="table table-bordered table-striped" style="margin-top:10px">
								<thead>
									<tr>
										<th class="nosort">#</th>
										<th>Exam Name</th>
										<th>Class Name</th>
										<th>Subject Name</th>										
										<th>Start Date</th>
										<th>End Date</th>
										<th class="nosort">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php									
									$sno=1;
									foreach( $wpsp_exams as $wpsp_exam ) {
										$classname = $sublist	=	'';
										$classid	=	isset($wpsp_exam->classid)  ? intval($wpsp_exam->classid) : '';
										if( !empty( intval($classid )) ) {
											$classname = $wpdb->get_var( "SELECT c_name FROM $ctable WHERE cid = '$classid'" );
										}
										$sublist	=	'-';	
										if( !empty($wpsp_exam->subject_id) ) {
											$subject_list	=	array();											
											$slist	=	str_replace( 'All,', '',$wpsp_exam->subject_id);											
											if( !empty( $slist ) ) {											
												$subjectlist	=	$wpdb->get_results("SELECT sub_name FROM $subject_table WHERE id IN($slist)", ARRAY_A );
												foreach( $subjectlist as $list ) {												
													$subject_list[]	= $list['sub_name'];
												}
												$sublist	=	implode(", ",$subject_list);												
											}
										}
									?>
										<tr id="<?php echo intval($wpsp_exam->eid);?>">
											<td><?php echo intval($sno); ?>
											<td><?php echo  $wpsp_exam->e_name;?></td>
											<td><?php echo $classname; ?></td>
											<td><?php echo  $sublist; ?></td>											
											<td><?php echo  wpsp_ViewDate($wpsp_exam->e_s_date); ?></td>
											<td><?php echo  wpsp_ViewDate($wpsp_exam->e_e_date);?></td>
											<td>
												<!-- <a href="javascript:;" eid="<?php echo $wpsp_exam->eid;?>" class="EditExamLink" title="Edit"><i class="fa fa-pencil btn btn-warning"></i></a> -->
												<a href="<?php wpsp_admin_url();?>sch-exams&id=<?php echo intval($wpsp_exam->eid).'&edit=true';?>"><i class="fa fa-pencil btn btn-warning edit-btn "></i></a>
												<a href="javascript:;" eid="<?php echo intval($wpsp_exam->eid);?>" class="ExamDeleteBt" title="Delete"><i class="fa fa-trash btn btn-danger"></i></a>	
											</td>
										</tr>
									<?php	
										$sno++;
									}
									?>
								</tbody>
							</table>
						</div>
							<?php  } ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php } ?>
		
		
		<!--Info Modal-->
		<div class="modal fade" id="InfoModal" tabindex="-1" role="dialog" aria-labelledby="InfoModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="col-md-12">
						<div class="box box-success">
							<div class="box-header">
								<p class="box-title" id="InfoModalTitle"></p>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							</div><!-- /.box-header -->
							<div id="InfoModalBody" class="box-body PTZero">
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
		else if( $current_user_role=='parent' || $current_user_role='student')
		{
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			?>
			<section class="content-header">
				<h1>Exams</h1>
				<ol class="breadcrumb">
					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="<?php wpsp_admin_url();?>sch-exams">Exams</a></li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						
						<div class="box box-info">
							  <div class="box-header ui-sortable-handle" style="cursor: move;">
                                    <i class="fa fa-graph"></i>
                                    <h3 class="box-title"><i class="fa fa-calendar" aria-hidden="true"></i> Time Table </h3>
                                    <!-- tools box -->
                                  
                                    <!-- /. tools -->
                                </div>
							<div class="box-body">
								<div class="col-md-12 table-responsive">
								<table id="exam_class_table" class="table table-bordered table-striped table-responsive" style="margin-top:10px">
									<thead>
									<tr>
										<th class="nosort">#</th>
										<th>Exam Name</th>
										<th>Class Name</th>
										<th>Subject Name</th>
										<th>Start Date</th>
										<th>End Date</th>
									</tr>
									</thead>
									<tbody>
									<?php
									$extable	=	$wpdb->prefix."wpsp_exam";
									$studtable	=	$wpdb->prefix."wpsp_student";
									$classtable	=	$wpdb->prefix."wpsp_class";
									if( $current_user_role=='parent' ) {
										$wpsp_exams =$wpdb->get_results( "SELECT DISTINCT e.*,c.c_name FROM $studtable st, $extable e, $classtable c where st.parent_wp_usr_id=$current_user_Id AND st.class_id=e.classid AND c.cid=e.classid");
									} else {
										$wpsp_exams =$wpdb->get_results( "SELECT DISTINCT e.*,c.c_name FROM $studtable st, $extable e, $classtable c where st.wp_usr_id=$current_user_Id AND st.class_id=e.classid AND c.cid=e.classid");
									}
									$sno=1;
									foreach ($wpsp_exams as $wpsp_exam)
									{
										$sublist	=	'';
										if( !empty($wpsp_exam->subject_id) ) {
											$subject_list	=	array();
											$subjectlist	=	$wpdb->get_results("SELECT sub_name FROM $subject_table WHERE id IN($wpsp_exam->subject_id)", ARRAY_A );
											foreach( $subjectlist as $list ) {												
												$subject_list[]	= $list['sub_name'];
											}
											$sublist	=	implode(", ",$subject_list);
										}
										?>
										<tr id="<?php echo $wpsp_exam->eid;?>" class="pointer">											
											<td><?php echo $sno;?></td>											
											<td><?php echo  $wpsp_exam->e_name;?></td>
											<td><?php echo  $wpsp_exam->c_name;?> </td>											
											<td style="width: 580px;"><?php echo  $sublist; ?> </td>											
											<td><?php echo  wpsp_ViewDate($wpsp_exam->e_s_date); ?></td>
											<td><?php echo  wpsp_ViewDate($wpsp_exam->e_e_date);?></td>
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
		