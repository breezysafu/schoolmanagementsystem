<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();
	if( is_user_logged_in() ) {
		global $current_user, $wp_roles, $wpdb;
		//get_currentuserinfo();
		foreach($wp_roles->role_names as $role => $name) :
			if(current_user_can($role))
				$current_user_role =  $role;
		endforeach;
		if($current_user_role=='administrator' || $current_user_role=='teacher')
		{
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			?>
			<section class="content-header">
					<h1>Teachers</h1>
			  <ol class="breadcrumb">
				<li><a href="<?php wpsp_admin_url();?>sch-dashboard "><i class="fa fa-dashboard"></i><?php _e( 'Dashboard', 'WPSchoolPress'); ?></a></li>
				<li><a href="<?php wpsp_admin_url();?>sch-teacher"><?php _e( 'Teachers', 'WPSchoolPress'); ?></a></li>
				<?php if(isset($_GET['ac']) && sanitize_text_field($_GET['ac'])=='add')
				{
				?>
					<li class="active"><?php _e( 'Add New', 'WPSchoolPress'); ?></li>
				<?php } ?>
				<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
				{
				?>
					<li class="active"><?php _e( 'Edit Teacher', 'WPSchoolPress'); ?></li>
				<?php } ?>
			  </ol>
			</section>
			<?php 			
				if(isset( $_GET['tab'] ) && sanitize_text_field($_GET['tab'])=='addteacher')
				{
					include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-teacherForm.php' );
				}
				else if(isset($_GET['id']) && is_numeric($_GET['id']))
				{
					include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-teacherProfile.php' );					
				}
				else {
						include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-teacherList.php' );
				?>
				<!-- Modal for Add-->
				<div class="modal modal-wide" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="AddModal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="col-md-12">
								<?php include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-teacherForm.php' ); ?>
							</div>
						</div>
					</div>
				</div><!-- /.modal -->
				<!-- Modal for Import-->
				<?php do_action( 'wpsp_teacher_import_html' ); ?>
			<?php
			}
			wpsp_body_end();
			wpsp_footer();
		}
		if($current_user_role=='parent' || $current_user_role=='student')
		{
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			$ID	=	intval($current_user->ID);
			$teacher_table	=	$wpdb->prefix."wpsp_teacher";
			$class_table	=	$wpdb->prefix."wpsp_class";
			$subjects_table = 	$wpdb->prefix."wpsp_subject";
			$student_table  = 	$wpdb->prefix."wpsp_student";
			$queryFiels		=	$current_user_role=='student' ? 'wp_usr_id' : 'parent_wp_usr_id';
			$classlist		=	array();
			$classquery		=	'';
			$classID	=	$wpdb->get_results("select class_id,s_fname,sid  from $student_table where  $queryFiels=$ID"); //GET CLASS LISTS
			?>
			<section class="content-header">
				<h1>Teachers</h1>
				<ol class="breadcrumb">
					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
					<li><a href="<?php wpsp_admin_url();?>sch-teacher">Teachers</a></li>
			</section>			
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						
						<div class="box box-info">
							<div class="box-header ui-sortable-handle" style="cursor: move;">
                                    <i class="fa fa-graph"></i>
                                    <h3 class="box-title"><i class="fa fa-users" aria-hidden="true"></i>&nbsp; Teacher's Details </h3>
                                    <!-- tools box -->
                                  
                                    <!-- /. tools -->
                                </div>
							<div class="box-body">
								<div class="col-md-12 table-responsive">
								<table id="teacher_table" class="table table-bordered table-striped">
									<thead>
									<tr>
										<th class="nosort">#</th>
										<!-- <th class="nosort">Photo</th> -->
										 <th><?php _e( 'Full Name', 'WPSchoolPress' ); ?></th>
										<th><?php _e( 'Incharge of Class', 'WPSchoolPress' ); ?></th>
										<th><?php _e( 'Subjects Handling', 'WPSchoolPress' ); ?></th>
										<th><?php _e( 'Phone', 'WPSchoolPress' ); ?></th>
									</tr>
									</thead>
									<tbody>
									<?php
									if( !empty( $classID ) ) {
											foreach( $classID as $value ) {
												$classlist[]	=	$value->class_id;
											}										
										if( !empty( $classlist ) ) {
											$classquery	=	'AND c.cid IN ('.implode( ", " , $classlist ).") ";										
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
										if( !empty( $teacher ) && !empty($classID) ) {
											$teacherQuery	=	' WHERE wp_usr_id IN ('.implode( ", " , $teacher ).") ";
										}
										$teachers=$wpdb->get_results("select * from $teacher_table $teacherQuery order by tid DESC");
										$sno		=	0;
										foreach($teachers as $tinfo)
										{
											$loc_avatar	=	get_user_meta($tinfo->wp_usr_id,'simple_local_avatar',true);
											$img_url	=	$loc_avatar ? $loc_avatar['full'] : WPSP_PLUGIN_URL.'img/avatar.png';					
											$sno	=	$sno+1;
										?>
										<tr>
											<td><?php echo $sno;?></td>
											<!-- <td><img src="<?php //echo $img_url;?>" class="image img-circle" height="30" width="30"> </td> -->
											<td><?php echo $tinfo->first_name." ". $tinfo->middle_name." ".$tinfo->last_name;?></td>
											<td><?php if(isset($cincharge[$tinfo->wp_usr_id])) { echo implode( ", ", $cincharge[$tinfo->wp_usr_id] ); } else { echo '-'; } ?></td>
											<td><?php if(isset($sub_handling[$tinfo->wp_usr_id])) { echo implode( ", ", $sub_handling[$tinfo->wp_usr_id] ); } else { echo '-'; } ?></td>
											<td><?php echo $tinfo->phone;?></td>
										</tr>
										<?php }	?>
									<?php } ?>
									</tbody>									
									<tfoot>
									<tr>
										<th>#</th>
										<!-- <th>Photo</th> -->
										<th> <?php _e( 'Full Name', 'WPSchoolPress' ); ?></th>
										<th> <?php _e( 'Incharge of Class', 'WPSchoolPress' ); ?></th>
										<th> <?php _e( 'Subjects Handling', 'WPSchoolPress' ); ?></th>
										<th><?php _e( 'Phone', 'WPSchoolPress' ); ?></th>
									</tr>
									</tfoot>
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
		}?>
		<!-- Modal for View-->
		<div class="modal modal-wide" id="ViewModal" tabindex="-1" role="dialog" aria-labelledby="AVEModal" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div id="ViewModalContent">
				</div>
			</div>
		</div><!-- /.modal -->
		<?php	
	}
	else {
		include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-login.php');
	}
	?>