<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');

	
    wpsp_header();

    if( is_user_logged_in() ) {
    	global $current_user, $wp_roles, $wpdb;
    	//get_currentuserinfo();
    	foreach ($wp_roles->role_names as $role => $name) :
    	if (current_user_can($role))
    		$current_user_role = $role;
    	endforeach;
		if( $current_user_role == 'administrator' || $current_user_role == 'teacher' || $current_user_role == 'parent' || $current_user_role == 'student' ) {
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start(); 
			global $wpdb;
			$student_table		=	$wpdb->prefix."wpsp_student";
			$teacher_table		=	$wpdb->prefix."wpsp_teacher";
			$class_table		=	$wpdb->prefix."wpsp_class";
			$attendance_table	=	$wpdb->prefix."wpsp_attendance";
			$users_count		=	$wpdb->get_row("SELECT(SELECT COUNT(*)FROM $student_table)AS stcount,(SELECT COUNT(*)FROM $teacher_table) AS tcount,(SELECT COUNT(DISTINCT parent_wp_usr_id) FROM $student_table where `parent_wp_usr_id`!='') AS pcount,(SELECT COUNT(*) FROM $class_table) AS clcount");
		?>
		
		<section class="content-header">
			<h1>Dashboard</h1>
			<ol class="breadcrumb">
				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i><?php  _e( 'Home', 'WPSchoolPress'); ?></a></li>
				<li class="active"><?php _e( 'Dashboard', 'WPSchoolPress'); ?> </li>
			</ol>
		</section>
		<section class="content">
			<!-- Info boxes -->
			<div class="row">
				<div class="col-md-3 col-sm-6 col-xs-6">
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3><?php echo isset( $users_count->stcount ) ?  intval($users_count->stcount) : 0; ?></h3>
							<p>Students</p>
						</div>
						<div class="icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>                
						<a href="<?php wpsp_admin_url();?>sch-student" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>           
				</div>
				<!-- /.col -->
				<div class="col-md-3 col-sm-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3><?php echo isset(  $users_count->tcount)  ?  intval($users_count->tcount) : 0; ?></h3>
							<p>Teachers</p>
						</div>
						<div class="icon"><i class="fa fa-user-plus"></i></div>
						<a href="<?php wpsp_admin_url();?>sch-teacher" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				
				<!-- fix for small devices only -->
				<div class="clearfix visible-sm-block"></div>
				<div class="col-md-3 col-sm-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3><?php echo isset(  $users_count->pcount ) ?  intval($users_count->pcount) : 0; ?></h3>
							<p>Parents</p>
						</div>
						<div class="icon">
							<i class="fa fa-users"></i>
						</div>
						<a href="<?php wpsp_admin_url();?>sch-parent" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-red">
						<div class="inner">
							<h3><?php echo isset(  $users_count->clcount ) ?  intval($users_count->clcount) : 0; ?></h3>
							<p>Classes</p>
						</div>
						<div class="icon"><i class="fa fa-bell"></i></div>
						<a href="<?php wpsp_admin_url();?>sch-class" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>		    
			<div class="row">
				<!-- Left col -->
				<div class="col-md-8">
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">Activities</h3>
						</div>
						<div class="box-body">
							<div class="dahboard-icon-content">
								<div class="dashboard-icon-event label-primary"></div>
								<label>Events</label>
							</div>
							<div class="dahboard-icon-content">
								<div class="dashboard-icon-event bg-red"></div>
								<label>Exams</label>
							</div>
							<div class="dahboard-icon-content">
								<div class="dashboard-icon-event bg-green"></div>
								<label>Holidays</label>
							</div>
							<div id="multiple-events"></div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">Exams</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<?php
								$exam_table=$wpdb->prefix."wpsp_exam";
								$examinfo=$wpdb->get_results("select * from $exam_table order by e_s_date DESC");
								?>
							<table class="table table-bordered">
								<th>Date</th>
								<th>Exam</th>
								<?php foreach($examinfo as $exam) { ?>
								<tr>
									<td><?php echo wpsp_ViewDate($exam->e_s_date)." TO ".wpsp_ViewDate($exam->e_e_date);?></td>
									<td><?php echo $exam->e_name; ?></td>
								</tr>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
		</div>
		</section>
	<?php 
		wpsp_body_end();
		wpsp_footer();
	} else {
		echo WPSP_PERMISSION_MSG;
	}  
 } else {
    include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-login.php');
	}
?>