<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
 $proversion	=	wpsp_check_pro_version();
	  $proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
	  $protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';
	  $prodisable	=	!$proversion['status'] ? 'disabled="disabled"'	: '';
	  $teacherFieldList =  array(	'empcode'			=>	__('Emp. Code', 'WPSchoolPress'),
									'first_name'		=>	__('First Name', 'WPSchoolPress'),	
									'middle_name'		=>	__('Middle Name', 'WPSchoolPress'),	
									'last_name'			=>	__('Last Name', 'WPSchoolPress'),
									'user_email'		=>	__('Teacher Email', 'WPSchoolPress'),
									'zipcode'			=>	__('Zip Code', 'WPSchoolPress'),	
									'country'			=>	__('Country', 'WPSchoolPress'),
									'gender'			=>	__('Gender', 'WPSchoolPress'),
									'address'			=>	__('Address', 'WPSchoolPress'),										
									'dob'				=>	__('Date Of Birth', 'WPSchoolPress'),
									'doj'				=>	__('Date Of Join', 'WPSchoolPress'),	
									'dol'				=>	__('Date Of Releaving', 'WPSchoolPress'),
									'phone'				=>	__('Phone Number', 'WPSchoolPress'),
									'qualification'	    =>	__('Qualification', 'WPSchoolPress'),
									'gender'			=>	__('Gender', 'WPSchoolPress'),
									'bloodgrp'			=>	__('Blood Group', 'WPSchoolPress'),
									'position'			=>	__('Position', 'WPSchoolPress'),
									'whours'			=>	__('Working Hours', 'WPSchoolPress'),
							);
$teacher_table	=	$wpdb->prefix."wpsp_teacher";
$class_table	=	$wpdb->prefix."wpsp_class";
$subjects_table =	$wpdb->prefix."wpsp_subject";
$role			=	sanitize_text_field( $current_user->roles);
$sel_classid	=	isset( $_POST['ClassID'] ) ? intval($_POST['ClassID']) : '';
//$sub_han		=	$wpdb->get_results("select sub_name,sub_teach_id from $subjects_table where sub_teach_id>0");
$sub_handling	=	$cincharge	=	$teacher	=	array();
$classquery		=	$teacherQuery	=	'';
if( !empty( $sel_classid ) && $sel_classid!='all' ){
	$classquery	=	" AND c.cid=$sel_classid ";
}

$sub_han		=	$wpdb->get_results("select sub_name,sub_teach_id,c.c_name from $subjects_table s, $class_table c where sub_teach_id>0 AND c.cid=s.class_id $classquery order by c.cid");

foreach($sub_han as $subhan) {
	$sub_handling[$subhan->sub_teach_id][]=$subhan->sub_name.' ('.$subhan->c_name.')';
	$teacher[]	=	$subhan->sub_teach_id;
}
$incharges=$wpdb->get_results("select c.c_name,c.teacher_id from $class_table c LEFT JOIN $teacher_table t ON t.wp_usr_id=c.teacher_id where c.teacher_id>0 $classquery");
foreach($incharges as $incharge){
	$cincharge[$incharge->teacher_id][]=$incharge->c_name;	
}
if( !empty( $teacher ) && !empty( $sel_classid ) && $sel_classid!='all' ) {
	$teacherQuery	=	' WHERE wp_usr_id IN ('.implode( ", " , $teacher ).") ";
}
$teachers=$wpdb->get_results("select * from $teacher_table $teacherQuery order by tid DESC");
$plugins_url=plugins_url();
?>	
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-users" aria-hidden="true"></i>&nbsp; Teacher List</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">
							<div class="subject-head">
							<div class="subject-inner" style="padding:0;">
								<form name="TeacherClass" id="TeacherClass" method="post" action="" class="class-filter">
									<label><?php _e( 'Select Class Name', 'WPSchoolPress' ); ?></label>
									<select name="ClassID" id="ClassID" class="form-control">										
										<option value="all" <?php if($sel_classid=='all') echo "selected"; ?>><?php _e( 'All', 'WPSchoolPress' ); ?></option>
										 <?php
										$class_table	=	$wpdb->prefix."wpsp_class";
										$sel_class		=	$wpdb->get_results("select cid,c_name from $class_table Order By cid ASC");
										foreach( $sel_class as $classes ) {
											print_r($classes);
										?> 
											<option value="<?php echo intval($classes->cid);?>" <?php if($sel_classid==$classes->cid) echo "selected"; ?>><?php echo $classes->c_name;?></option>
										<?php } ?>										 
									</select>
								</form>								
							</div>
							<div class="pull-right">
								<div class="button-group btn-pro" <?php echo $prodisable;?> title="<?php echo $protitle;?>">
									<div class="dropdown"> 
										<button type="button" class="btn btn-primary dropdown-toggle print" id="PrintTeacher" data-toggle="dropdown" <?php echo $prodisable;?> title="<?php echo $protitle;?>">
											<i class="fa fa-print"></i> <?php _e( 'Print', 'WPSchoolPress'); ?>
										</button>
										<button type="button" class="btn btn-primary dropdown-toggle toggle-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" <?php echo $prodisable;?> title="<?php echo $protitle;?>">
											<span class="caret"></span>
											<span class="sr-only"><?php _e( 'Toggle Dropdown', 'WPSchoolPress' );?></span>
										</button>
										<ul class="dropdown-menu">
											<li class="dropdown-header"><?php _e( 'Select Columns to Print', 'WPSchoolPress' );?> </li>
											<form id="TeacherColumnForm" name="TeacherColumnForm" method="POST">
												<?php foreach( $teacherFieldList as $key=>$value ) { ?>
													<li class="checkbox checkbox-info" >
														<input type="checkbox" name="TeacherColumn[]" value="<?php echo $key; ?>" id="<?php echo "print".$key; ?>" checked="checked">
														<label for="<?php echo $key; ?>"><?php echo $value; ?></label>
													</li>
												<?php } ?>
												<input type="hidden" name="classid" id="classid" value="<?php echo $sel_classid;?>">
											</form>
										</ul>
									</div>
									
									<div class="btn-group dropdown">
										<?php if($current_user_role=='administrator') { ?>
										<button id="ImportTeacher" class="btn btn-primary dropdown-toggle impt" <?php echo $prodisable;?> title="<?php echo $protitle;?>">
											<i class="fa fa-upload"></i> Import 
										</button>
										<?php } ?>
										<button type="button" class="btn btn-primary print" id="ExportTeachers" <?php echo $prodisable;?> title="<?php echo $protitle;?>">
											<i class="fa fa-download"></i> <?php _e( 'Export', 'WPSchoolPress'); ?>
										</button>
										<button type="button" class="btn btn-primary dropdown-toggle export-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" <?php echo $prodisable;?> title="<?php echo $protitle;?>">
											<span class="caret"></span>
											<span class="sr-only"><?php _e( 'Toggle Dropdown', 'WPSchoolPress' );?></span>
										</button>
										<ul class="dropdown-menu">
											<li class="dropdown-header"><?php _e( 'Select Columns to Export', 'WPSchoolPress' );?> </li>
											<form id="ExportColumnForm" name="ExportTeacherColumn" method="POST">
												<?php foreach( $teacherFieldList as $key=>$value ) { ?>
												<li class="checkbox checkbox-info">
													<input type="checkbox" name="TeacherColumn[]" value="<?php echo $key; ?>" id="<?php echo "export".$key; ?>" checked="checked">
													<label for="<?php echo $key; ?>"><?php echo $value; ?></label>
												</li>
												<?php } ?>	
												<input type="hidden" name="exportteacher" value="exportteacher">
												<input type="hidden" name="classid" id="export-classid" value="<?php echo $sel_classid;?>">
											</form>
										</ul>
									</div>
								</div>
								<?php if($current_user_role=='administrator') { ?>
								<a class="btn btn-success pull-right add-teacher-btn" href="<?php wpsp_admin_url();?>sch-teacher&tab=addteacher"><i class="fa fa-plus"></i>&nbsp; Add Teacher</a>
								<?php } ?>
							</div>
						</div>
						
						<div class="col-md-12 table-responsive">

						<table id="teacher_table" class="table table-bordered table-striped table-responsive" style="margin-top:10px">
						<thead>
							<tr>								
								<th class="nosort">
									<?php if($current_user_role=='administrator') { ?>
									<input type="checkbox" id="selectall" name="selectall" class="ccheckbox">
									<?php  } else if(  $current_user_role=='teacher' ) { ?>
										Sr. No.
									<?php } ?>
								</th>		
								<th> <?php _e( 'Employee Code', 'WPSchoolPress' );?></th>						
								<th> <?php _e( 'Name', 'WPSchoolPress' );?> </th>
								<th> <?php _e( 'Incharge Class', 'WPSchoolPress' );?></th>
								<th> <?php _e( 'Subjects Handling', 'WPSchoolPress' );?></th>								
								<th> <?php _e( 'Phone', 'WPSchoolPress' );?></th>
								<th class="nosort">
								<?php if($current_user_role=='administrator') { ?>
									<select name="bulkaction" class="form-control" id="bulkaction"><option value="">Select Action</option><option value="bulkUsersDelete">Delete</option></select>
									<!--<button name="bulkaction" id="bulkaction" value="Delete">Delete</button>-->
								<?php } else if(  $current_user_role=='teacher' ) { ?>
									Select Action	
								<?php } ?>	 	
								</th>
							</tr>
						</thead>
						<tbody>
							<?php							
							foreach($teachers as $key=>$tinfo) { ?>
								<tr>									
									<td>
										<?php if($current_user_role=='administrator') { ?>
										<input type="checkbox" class="ccheckbox tcrowselect" name="UID[]" value="<?php echo $tinfo->wp_usr_id;?>">
										<?php } else if(  $current_user_role=='teacher' ) { echo $key+1; } ?>	
									</td>
									<td><?php echo $tinfo->empcode; ?></td>
									<td><?php echo $tinfo->first_name." ".$tinfo->last_name;?></td>
									<td><?php if( isset( $cincharge[$tinfo->wp_usr_id] ) ) { echo implode( ", ", $cincharge[$tinfo->wp_usr_id] ); } else { echo '-';} ?></td>
									<td><?php if( isset( $sub_handling[$tinfo->wp_usr_id] ) ) { echo implode( "<br> ", $sub_handling[$tinfo->wp_usr_id] ); } else { echo '-';} ?></td>									
									<td><?php echo $tinfo->phone; ?></td>
									<td>
										<a href="<?php echo "?id=".$tinfo->wp_usr_id;?>" class="ViewTeacher" data-id="<?php echo $tinfo->wp_usr_id;?>" title="View"><i class="fa fa-eye btn btn-success"></i></a> 
										<?php if($current_user_role=='administrator') { ?>
											<a href="<?php wpsp_admin_url();?>sch-teacher&id=<?php echo $tinfo->wp_usr_id."&edit=true";?>" title="Edit"><i class="fa fa-pencil btn btn-warning"></i></a>
											<a href="javascript:;" id="d_teacher" title="Delete" data-id="<?php echo $tinfo->tid;?>">
                                        <i class="fa fa-trash btn btn-danger  ClassDeleteBt delete-btn" data-id="<?php echo $tinfo->tid;?>"></i></a>
										<?php } ?>	
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th><?php if(  $current_user_role=='teacher' ) { ?>
										Sr. No.
								<?php } ?>
								</th>
								<th> <?php _e( 'Employee Code', 'WPSchoolPress' );?></th>							
								<th><?php _e( 'Name', 'WPSchoolPress' );?> </th>
								<th> <?php _e( 'Incharge Class', 'WPSchoolPress' );?></th>
								<th> <?php _e( 'Subjects Handling', 'WPSchoolPress' );?></th>								
								<th> <?php _e( 'Phone', 'WPSchoolPress' );?></th>
							</tr>
						</tfoot>
					  </table>
					</div>
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div>
		</div>
	</section>
	</div>