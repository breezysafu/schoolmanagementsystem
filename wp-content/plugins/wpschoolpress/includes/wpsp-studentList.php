<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
 $proversion	=	wpsp_check_pro_version();
	  $proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
	  $protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';
	  $prodisable	=	!$proversion['status'] ? 'disabled="disabled"'	: '';
	  $studentFieldList =  array(	's_rollno'			=>	__('Roll Number', 'WPSchoolPress'),
									's_fname'			=>	__('Student First Name', 'WPSchoolPress'),	
									's_mname'			=>	__('Student Middle Name', 'WPSchoolPress'),	
									's_lname'			=>	__('Student Last Name', 'WPSchoolPress'),	
									's_zipcode'			=>	__('Zip Code', 'WPSchoolPress'),	
									's_country'			=>	__('Country', 'WPSchoolPress'),	
									's_gender'			=>	__('Gender', 'WPSchoolPress'),	
									's_address'			=>	__('Current Address', 'WPSchoolPress'),	
									's_paddress'		=>	__('Permanent Address', 'WPSchoolPress'),	
									'p_fname '			=>	__('Parent First Name', 'WPSchoolPress'),	
									's_bloodgrp'		=>	__('Blood Group', 'WPSchoolPress'),	
									's_dob'				=>	__('Date Of Birth', 'WPSchoolPress'),	
									's_doj'				=>	__('Date Of Join', 'WPSchoolPress'),										
									's_phone'			=>	__('Phone Number', 'WPSchoolPress'),	
							);
	$teacherId	=	0;
	global $current_user;	
	$role		=	 $current_user->roles;
	$cuserId	=	 $current_user->ID;
?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-solid bg-blue-gradient">
					<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-graduation-cap" aria-hidden="true"></i>&nbsp; Student List by class </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
            <div class="box-footer text-black">
						<div class="subject-head">
							<div class="subject-inner" style="padding:0;">
								<form name="StudentClass" id="StudentClass" method="post" action="" class="class-filter">
									<label><?php _e( 'Select Class Name', 'WPSchoolPress' ); ?></label>
									<select name="ClassID" id="ClassID" class="form-control">
										<?php
										$sel_classid	=	isset( $_POST['ClassID'] ) ? intval($_POST['ClassID']) : '';										
										$class_table	=	$wpdb->prefix."wpsp_class";
										$sel_class		=	$wpdb->get_results("select cid,c_name from $class_table Order By cid ASC");
										
										?> 
										<option value="all" <?php if($sel_classid=='all') echo "selected"; ?>><?php _e( 'All', 'WPSchoolPress' ); ?></option>
										
										<?php foreach( $sel_class as $classes ){ ?> 
											<option value="<?php echo $classes->cid;?>" <?php if($sel_classid==$classes->cid) echo "selected"; ?>><?php echo $classes->c_name;?></option>
										<?php } ?> 
									</select>
								</form>								
							</div>
							<div class="pull-right">
							
								<div class="button-group btn-pro" <?php echo $prodisable;?> title="<?php echo $protitle;?>">


									<div class="dropdown"> 
									<a class="btn  btn-success st-btn add-student-btn" href="<?php wpsp_admin_url();?>sch-student&tab=addstudent" style="margin-right: 5px;"> <i class="fa fa-plus"></i> &nbsp; Add student</a>
										<button type="button" class="btn btn-primary dropdown-toggle print" id="PrintStudent"  <?php echo $prodisable;?> title="<?php echo $protitle;?>">
											<i class="fa fa-print" ></i> <?php _e( 'Print', 'WPSchoolPress'); ?> 
										</button>
										<button type="button" class="btn btn-primary dropdown-toggle toggle-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" <?php echo $prodisable;?> title="<?php echo $protitle;?>">
											<span class="caret"></span>
											<span class="sr-only"><?php _e( 'Toggle Dropdown', 'WPSchoolPress' );?></span>
										</button>
										<ul class="dropdown-menu">
											<li class="dropdown-header"><?php _e( 'Select Columns to Print', 'WPSchoolPress' );?> </li>
											<form id="StudentColumnForm" name="StudentColumnForm" method="POST">
												<?php foreach( $studentFieldList as $key=>$value ) { ?>
													<li class="checkbox checkbox-info" >
														<input type="checkbox" name="StudentColumn[]" value="<?php echo $key; ?>" id="<?php echo $key; ?>" checked="checked">
														<label for="<?php echo $key; ?>"><?php echo $value; ?></label>
													</li>
												<?php } ?>
												<?php 
												$currentSelectClass =	isset($_POST['ClassID']) ? intval($_POST['ClassID']) : intval($sel_class[0]->cid); ?>
												<input type="hidden" name="ClassID" value="<?php  echo $sel_classid; ?>">
												<input type="hidden" name="exportstudent" value="exportstudent">
											</form>
										</ul>
									</div>
									<div class="btn-group dropdown">
										<button id="ImportStudent" class="btn btn-primary dropdown-toggle impt" <?php echo $prodisable;?> title="<?php echo $protitle;?>"><i class="fa fa-upload"></i> Import </button>									
										<button type="button" class="btn btn-primary print" id="ExportStudents" <?php echo $prodisable;?> title="<?php echo $protitle;?>"><i class="fa fa-download"></i> <?php _e( 'Export', 'WPSchoolPress'); ?> </button>
										<button type="button" class="btn btn-primary dropdown-toggle export-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" <?php echo $prodisable;?> title="<?php echo $protitle;?>">
											<span class="caret"></span>
											<span class="sr-only"><?php _e( 'Toggle Dropdown', 'WPSchoolPress' );?></span>
										</button>
										<ul class="dropdown-menu">
											<li class="dropdown-header"><?php _e( 'Select Columns to Export', 'WPSchoolPress' );?> </li>
											<form id="ExportColumnForm" name="ExportStudentColumn" method="POST">
												<?php foreach( $studentFieldList as $key=>$value ) { ?>
												<li class="checkbox checkbox-info">
													<input type="checkbox" name="StudentColumn[]" value="<?php echo $key; ?>" id="<?php echo $key; ?>" checked="checked">
													<label for="<?php echo $key; ?>"><?php echo $value; ?></label>
												</li>
												<?php } ?>											
												<input type="hidden" name="ClassID" value="<?php  echo $sel_classid; ?>">
												<input type="hidden" name="exportstudent" value="exportstudent">
											</form>
										</ul>
									</div>
								</div>
								
							</div>
						</div>
						<div class="col-md-12 table-responsive">
						<table id="student_table" class="table table-bordered table-striped table-responsive" style="margin-top:10px">
						<thead>
							<tr>
								<th class="nosort">
								<?php if ( in_array( 'administrator', $role ) ) { ?><input type="checkbox" id="selectall" name="selectall" class="ccheckbox"><?php } else echo 'Sr. No.'; ?>
								</th>
								<th>Roll No.</th>
								<th>Full Name</th>
								<th>Parent</th>
								<th>Street Address</th>
								<th>Phone</th>
								<th class="nosort">
									<?php if ( in_array( 'administrator', $role ) ) { ?>
									<select name="bulkaction" class="form-control" id="bulkaction">
										<option value="">Select Action</option>
										<option value="bulkUsersDelete">Delete</option>
									</select>
									<?php } else  echo 'Select Action'; ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$student_table	=	$wpdb->prefix."wpsp_student";							
							$users_table	=	$wpdb->prefix."users";
							$class_id='';							
							if( isset($_POST['ClassID'] ) ) {
								$class_id=intval($_POST['ClassID']);
							}else if( !empty( $sel_class ) ) {
								$class_id = 'all';
							}
							$classquery	=	" AND class_id='$class_id' ";
							if($class_id=='NULL'){
								$classquery	=	"";
							}elseif($class_id=='all'){
								$classquery="";
							}
							
							$students	=	$wpdb->get_results("select * from $student_table s, $users_table u where u.ID=s.wp_usr_id $classquery order by sid desc");
							
							$plugins_url=plugins_url();
							$teacherId = '';
							if( $currentSelectClass != 'all' )
								$teacherId	=	$wpdb->get_var("select teacher_id from $class_table WHERE cid=$currentSelectClass");
							$key =0;
							foreach($students as $stinfo)
							{	

								$key++;						
							?>
									<tr>
									<td>
									<?php if ( in_array( 'administrator', $role ) ) { ?>
										<input type="checkbox" class="ccheckbox strowselect" name="UID[]" value="<?php echo $stinfo->wp_usr_id;?>">
									<?php }else echo $key; ?>
									</td>
									<td><?php echo $stinfo->s_rollno;?></td>									
									<td><?php 
										$mname = $stinfo->s_mname;
							            $lname = $stinfo->s_lname;
									echo $stinfo->s_fname .' '. $mname .' '.  $lname;?></td>
									<td><?php  echo $stinfo->p_fname." ".$stinfo->p_lname; ?>
									</td>
									<td><?php 
										$country = !empty( $stinfo->s_country ) ? ", ".$stinfo->s_country : '';
										$city    = !empty( $stinfo->s_city ) ? ", ".$stinfo->s_city : '';
										$zipcode    = !empty( $stinfo->s_zipcode ) ? ", ".$stinfo->s_zipcode : '';
										echo $stinfo->s_address.' '.$city. ' ' . $country.' '.$zipcode;

									?></td>
									<td><?php echo $stinfo->s_phone;?></td>
									<td>
										
										<a href="<?php echo "?id=".$stinfo->wp_usr_id;?>" class="ViewStudent" data-id="<?php echo $stinfo->wp_usr_id;?>" title="View"><i class="fa fa-eye btn btn-success"></i></a> 										
										<a href="javascript:;" data-id="<?php echo $stinfo->wp_usr_id;?>" class="viewAttendance" title="Attendance"><i class="fa fa-table btn btn-primary"></i></a>	
										
										<?php if ( in_array( 'administrator', $role ) || ( !empty( $teacherId ) && $teacherId==$cuserId ) ) { ?>
											<a href="<?php wpsp_admin_url();?>sch-student&id=<?php echo $stinfo->wp_usr_id.'&edit=true';?>" title="Edit"><i class="fa fa-pencil btn btn-warning"></i></a> 
											
										<a href="javascript:;" title="Delete" data-id="<?php echo $stinfo->wp_usr_id;?>">
										<i class="fa fa-trash btn btn-danger  ClassDeleteBt delete-btn" data-id="<?php echo $stinfo->sid;?>"></i></a>	
											
										<?php } ?>
										
										
									</td>
								</tr>
							<?php
							}
							?>
						</tbody>
						<tfoot>
						  <tr>
							<th><?php if ( in_array( 'administrator', $role ) ) { } 
								else echo 'Sr. No'; ?></th>
							<th>Roll No.</th>							
							<th>Full Name</th>
							<th>Parent</th>
							<th>Street Address</th>
							<th>Phone</th>
							<th>Action</th>
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