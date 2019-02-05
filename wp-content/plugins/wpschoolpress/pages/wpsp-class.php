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
			$filename	=	'';
			$header	=	'Classes';
			if( isset( $_GET['tab'] ) && sanitize_text_field($_GET['tab']) == 'addclass' ) {
				$header	=	$label	=	__( 'Add New Class', 'WPSchoolPress');
				$filename	=	WPSP_PLUGIN_PATH .'includes/wpsp-classForm.php';				
			}elseif(( isset($_GET['id']) && is_numeric($_GET['id'])))  {
				$header	=	$label	=	__( 'Update Class', 'WPSchoolPress');
				$filename	=	WPSP_PLUGIN_PATH .'includes/wpsp-classForm.php';				
			}
		?>
		<section class="content-header">
			<h1><?php echo $header; ?></h1>
			<ol class="breadcrumb">
				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="<?php wpsp_admin_url();?>sch-class">Classes</a></li>
				<?php if( !empty($label) ) { ?><li><?php echo $label;?></li> <?php } ?>
			</ol>
		</section>
		<?php 
		if( !empty( $filename) ) {
			include_once ( $filename );
		} else {
		?>
		<section class="content">
			<div class="row">
				<div class="col-md-12">
			<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-building" aria-hidden="true"></i>&nbsp; Class List</h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">	
							<?php  if( $current_user_role=='administrator' ) { ?>
								<div class="subject-head" style="margin-bottom:10px;">
									<!-- <button id="AddClass" class="btn btn-primary pull-right" data-toggle="modal" data-target="#AddModal"><i class="fa fa-plus"></i> Add Class</button> -->
									<a class="btn btn-success pull-right" href="<?php wpsp_admin_url();?>sch-class&tab=addclass"><i class="fa fa-plus"></i> Add Class</a>
								</div>
							<?php } ?>
							<div class="col-md-12 table-responsive"	>
							<table id="class_table" class="table table-bordered table-striped " style="margin-top:10px">
								<thead>
									<tr>
										<th class="nosort">#</th>
										<th>Class Number</th>
										<th>Class Name</th>
										<th>Teacher Incharge</th>
										<th>Number of Students</th>
										<th>Capacity</th>
										<th>Location</th>
										<?php  if( $current_user_role=='administrator' ) { ?> <th class="nosort">Action</th> <?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php
									$ctable=$wpdb->prefix."wpsp_class";
									$stable=$wpdb->prefix."wpsp_student";
									$wpsp_classes =$wpdb->get_results("select * from $ctable order by cid DESC");
									$sno=1;
									$teacher_table=	$wpdb->prefix."wpsp_teacher";
									$teacher_data = $wpdb->get_results("select wp_usr_id,CONCAT_WS(' ', first_name, middle_name, last_name ) AS full_name from $teacher_table order by tid");
									$teacherlist	=	array();
									if( !empty( $teacher_data ) ) {
										foreach( $teacher_data  as $value )
											$teacherlist[$value->wp_usr_id] = $value->full_name;
									}									
									foreach ($wpsp_classes as $wpsp_class) 
									{
										$cid=intval($wpsp_class->cid);
										$class_students_count = $wpdb->get_var( "SELECT COUNT(`wp_usr_id`) FROM $stable WHERE class_id = '$cid'" );
										$teach_id= intval($wpsp_class->teacher_id);
										$teachername	=	'';
									?>
										<tr id="<?php echo intval($wpsp_class->cid);?>" class="pointer">
											<td><?php echo $sno;?><td><?php echo  $wpsp_class->c_numb;?> </td> 
											<td><?php echo $wpsp_class->c_name;?></td>
											<td><?php echo isset( $teacherlist[$teach_id] ) ? $teacherlist[$teach_id] : '';?></td>
											<td><?php echo $class_students_count;?></td>
											<td><?php echo $wpsp_class->c_capacity;?></td>
											<td><?php echo $wpsp_class->c_loc;?></td>
											<?php  if( $current_user_role=='administrator' ) { ?>
												<td>
													<!-- <a href="javascript:;" title="Edit"><i class="fa fa-pencil btn btn-warning  ClassEditBt" cid="<?php echo $wpsp_class->cid;?>"></i></a> -->
													<a class="edit-btn" href="<?php wpsp_admin_url();?>sch-class&id=<?php echo intval($wpsp_class->cid).'&edit=true';?>"><i class="fa fa-pencil btn btn-warning "></i></a>
													
													<a href="javascript:;" title="Delete"><i class="fa fa-trash btn btn-danger  ClassDeleteBt delete-btn" data-id="<?php echo intval($wpsp_class->cid);?>" ></i></a>	
												</td>
											<?php } ?>	
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
		<?php  } if( $current_user_role=='administrator' ) { ?>
		<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="AddModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="col-md-12">
						<div class="box box-success">
							<div class="box-header">
								<h3 class="box-title">New test Entry</h3>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							</div><!-- /.box-header -->
								<form name="ClassAddForm" id="ClassAddForm" method="post">
									<?php include( WPSP_PLUGIN_PATH.'/includes/wpsp-classForm.php'); ?>
								</form>
						</div>
					</div>					
				</div>
			</div>
		</div><!-- /.modal -->
		<div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="EditModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="col-md-12">
						<div class="box box-success">
							<div class="box-header">
								<h3 class="box-title">Edit Class Info</h3>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							</div><!-- /.box-header -->
							<form name="ClassEditForm" id="ClassEditForm" method="post">
								<input type="hidden" name="cid" value="">
								<?php include( WPSP_PLUGIN_PATH .'/includes/wpsp-classForm.php'); ?>
							</form>
						</div>
					</div>					
				</div>
			</div>
		</div><!-- /.modal -->
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

		<?php  } ?>
		<?php
			//include_once ( $filename );	
			wpsp_body_end();
			wpsp_footer();
		}
		else if($current_user_role=='parent' || $current_user_role='student')
		{
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			?>
			<section class="content-header">
				<h1>Classes</h1>
				<ol class="breadcrumb">
					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
					<li><a href="<?php wpsp_admin_url();?>sch-class">Classes</a></li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-info">

						<div class="box-header ui-sortable-handle" style="cursor: move;">
                                    <i class="fa fa-graph"></i>
                                    <h3 class="box-title"><i class="fa fa-building-o" aria-hidden="true"></i>&nbsp; Classes Details </h3>
                                    <!-- tools box -->
                                  
                                    <!-- /. tools -->
                                </div>
							<div class="box-body">
							<div class="col-md-12 table-responsive">							
								<table id="class_table" class="table table-bordered table-striped " style="margin-top:10px">
									<thead>
									<tr>
										<th class="nosort">#</th>
										<th>Class Number</th>
										<th>Class Name</th>
										<th>Teacher Incharge</th>
										<th>Number of Students</th>
										<th>Location</th>
									</tr>
									</thead>
									<tbody>
									<?php
									$ctable=$wpdb->prefix."wpsp_class";
									$stable=$wpdb->prefix."wpsp_student";
									$teacher_table=	$wpdb->prefix."wpsp_teacher";
									$teacher_data = $wpdb->get_results("select wp_usr_id,CONCAT_WS(' ', first_name, middle_name, last_name ) AS full_name from $teacher_table order by tid");
									$teacherlist	=	array();
									if( !empty( $teacher_data ) ) {
										foreach( $teacher_data  as $value )
											$teacherlist[$value->wp_usr_id] = $value->full_name;
									}		
									if( $current_user_role=='student' ) {
										$wpsp_classes =$wpdb->get_results("SELECT cls.* FROM $ctable cls, $stable st where st.wp_usr_id = $current_user->ID AND st.class_id=cls.cid");
									} else {
										$wpsp_classes =$wpdb->get_results("SELECT DISTINCT cls.* FROM $ctable cls, $stable st where st.parent_wp_usr_id = $current_user->ID AND st.class_id=cls.cid");
									}
									$sno=1;
									foreach ($wpsp_classes as $wpsp_class)
									{
										$cid = intval($wpsp_class->cid);
										$class_students_count = $wpdb->get_var( "SELECT COUNT(`wp_usr_id`) FROM $stable WHERE class_id = '$cid'" );
										$teach_id= intval($wpsp_class->teacher_id);
										$teacher=get_userdata($teach_id);
										?>
										<tr id="<?php echo  intval($wpsp_class->cid);?>" class="pointer">
											<td><?php echo $sno;?><td><?php echo  $wpsp_class->c_numb;?> </td>
											<td><?php echo $wpsp_class->c_name;?></td>
										    <td><?php echo isset( $teacherlist[$teach_id] ) ? $teacherlist[$teach_id] : '';?></td>
											<td><?php echo $class_students_count;?></td>
											<td><?php echo $wpsp_class->c_loc;?></td>
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
		