<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();
if (is_user_logged_in()) {
	global $current_user, $wp_roles, $wpdb;
	//get_currentuserinfo();
	foreach ($wp_roles->role_names as $role => $name) :
		if ( current_user_can( $role ) )
			$current_usr_rle = $role;
	endforeach;
    if( $current_usr_rle == 'administrator' || $current_usr_rle == 'teacher' ) {
		wpsp_topbar();
		wpsp_sidebar();
		wpsp_body_start();
		$wpsp_teacher_table =	$wpdb->prefix . 'wpsp_teacher';
		$class_table		=	$wpdb->prefix."wpsp_class";
		$classQuery			=	"select cid,c_name from $class_table Order By cid ASC";
		$msg				=	'Please Add Class Before Adding Subjects';
		if( $current_usr_rle=='teacher' ) {
			$cuserId		=	intval($current_user->ID);
			$classQuery		=	"select cid,c_name from $class_table where teacher_id=$cuserId";
			$msg			=	'Please Ask Principal To Assign Class';
		}
		$sel_class		=	$wpdb->get_results( $classQuery );
		$sel_classid	=	isset( $_POST['ClassID'] ) ? intval($_POST['ClassID']) : $sel_class[0]->cid;
		$wpsp_class_name	=	isset( $_POST['wpsp_class_name'] ) ? sanitize_text_field($_POST['wpsp_class_name']) : '';
		$sel_classname	=	$ctablename = '';
		foreach( $sel_class as $key=>$value ) {						
			if( $value->cid	==	$sel_classid ) {
				$sel_classname	=	$value->c_name;
				//break;
			}
			if( $wpsp_class_name	== $value->cid ){
				$ctablename	= ' For Class '.$value->c_name;
			}
		}
        ?>
		<section class='content-header'>
				<?php if( isset($_GET['ac']) && sanitize_text_field($_GET['ac'])=='add' ) { ?>
					<h1>Add New Time Table <?php echo $ctablename; ?></h1>
				<?php } else { ?>
					<h1>Timetable of Class <?php echo $sel_classname; ?></h1>
				<?php } ?>	
				<ol class="breadcrumb">
					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
					<li><a href="<?php wpsp_admin_url();?>sch-timetable">Timetable</a></li>
				</ol>
			</section>
		<?php
			if( isset($_GET['ac']) && sanitize_text_field($_GET['ac'])=='add' && !empty( $sel_classid ) ) { ?>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<?php include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-createTimetable.php'); ?>
						</div>
					</div>
				</section>
		<?php } else if( isset( $_GET['timetable'] )  && intval($_GET['timetable']) > 0 ) {
				include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-editTimetable.php');
		} else { ?>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-info">
								
								<?php if( !empty( $sel_classid ) ) { 
									include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-viewTimetable.php');
									$response	=	wpsp_ViewTimetable($sel_classid);									
								?>
								<div class="box-header">
									<div class="col-md-12 col-lg-12">
								<div class="col-md-9">	
										<form name="TimetableClass" id="TimetableClass" method="post" action="" class="class-filter">
								<div class="">
											<label>Select Class Name</label>
											<select name="ClassID" id="ClassID" class="form-control" style="width: auto!important;">
												<?php foreach($sel_class as $classes) {	?>
													<option value="<?php echo intval($classes->cid);?>" <?php if($sel_classid==$classes->cid) echo "selected"; ?>><?php echo $classes->c_name;?></option>
												<?php } ?>
											</select>
									</div>
											<?php if( isset( $response['status'] ) && $response['status']==2 ) { ?>
												
												<div class="">
													<a href="?page=sch-timetable&timetable=<?php echo $sel_classid; ?>" title="Delete" data-id="<?php echo $sel_classid; ?>" class="gap-all wp-edit-timetable">
														<i class="fa fa-pencil btn btn-warning icn-gap"></i>
													</a>
													<a href="javascript:void(0);" title="Delete" data-id="<?php echo $sel_classid; ?>" class=" wp-delete-timetable">
														<i class="fa fa-trash btn btn-danger ClassDeleteBt icn-gap " data-id="<?php echo $sel_classid; ?>"></i>
													</a>
												</div>
											<?php } ?>
										

								
									</form>
									</div>
																		
									<div class="co-md-3"><span class="pull-right"><a href="<?php wpsp_admin_url();?>sch-timetable&ac=add" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Add New</a></span></div>
									</div>
								</div>
								<?php echo isset( $response['msg'] ) ? $response['msg'] :'';
							} else {
								echo '<div class="alert alert-danger col-lg-2">'.$msg.'</div>';
							}?>
							
							</div>
						</div>
					</div>
				</section >
			<?php
		}
		wpsp_body_end();
		wpsp_footer();
	} elseif ($current_usr_rle == 'student') {
		wpsp_topbar();
		wpsp_sidebar();
		wpsp_body_start();
			include_once(  WPSP_PLUGIN_PATH.'/includes/wpsp-viewTimetable.php');
			$wpsp_student_table = $wpdb->prefix . "wpsp_student";
			$stud_info = $wpdb->get_row("select class_id from $wpsp_student_table where wp_usr_id=$current_user->ID",ARRAY_A);
			$class_id=intval($stud_info['class_id']);
			$result = wpsp_ViewTimetable($class_id);
			echo $result['msg'];
		wpsp_body_end();
		wpsp_footer();
	} else if ($current_usr_rle == 'parent') {
		wpsp_topbar();
		wpsp_sidebar();
		wpsp_body_start();
			include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-viewTimetable.php');
			$wpsp_student_table = $wpdb->prefix . "wpsp_student";
			$child_info = $wpdb->get_results("select * from $wpsp_student_table where parent_wp_usr_id=$current_user->ID");
			?>
			<section class="content-header">
				<h1>Timetable</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo site_url('sch-dashboard'); ?> "><i class="fa fa-dashboard"></i> Dashboard</a></li>
					<li><a href="<?php echo site_url('sch-timetable'); ?>">Timetable</a></li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-info">
							<div class="box-body">
								<div class="tabbable-line">
									<ul class="nav nav-tabs ">
										<?php
										$i=0;
										foreach ($child_info as $child_inf) {
										?>
											<li class="<?php echo ($i==0)?'active':''?>"><a href="#<?php echo 'child-'.$i; ?>" data-toggle="tab"><?php echo $child_inf->s_fname; ?></a></li>
										<?php $i++; } ?>
									</ul>
									<div class="tab-content">
										<?php
										$i=0;
										foreach ($child_info as $child_inf) {
										?>
											<div class="tab-pane <?php echo ($i==0)?'active':''?>" id="<?php echo 'child-'.$i; ?>">
												<?php
												if ($child_inf->class_id != '') {
														$class_id = $child_inf->class_id;
														$result = wpsp_ViewTimetable($class_id);
														echo $result['msg'];
												} else {
													echo "<div class='col-md-12'><div class='alert alert-danger'>Class missing..</div></div>";
												}  ?>
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
		}
} else{
	include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-login.php');
}?>