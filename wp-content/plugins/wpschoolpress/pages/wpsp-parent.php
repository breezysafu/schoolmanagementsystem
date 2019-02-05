<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();
	if( is_user_logged_in() ) {
		global $current_user, $wp_roles, $wpdb;
		//get_currentuserinfo();
		foreach ( $wp_roles->role_names as $role => $name ) :
		if ( current_user_can( $role ) )
			$current_user_role =  $role;
		endforeach;
		if($current_user_role=='administrator' || $current_user_role=='teacher')
		{
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			?>
			<section class="content-header">
					<h1>Parents</h1>
			  <ol class="breadcrumb">
				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="<?php wpsp_admin_url();?>sch-parent">Parent</a></li>
				<?php if(isset($_GET['ac']) && $_GET['ac']=='add')
				{
				?>
					<!--<li class="active"><?php _e( 'Add New', 'WPSchoolPress'); ?></li>-->
				<?php } ?>
				<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
				{
				?>
					<li class="active"><?php _e( 'View/Edit Parent', 'WPSchoolPress'); ?></li>
				<?php } ?>
			  </ol>
			</section>
			<?php 			
				if(isset($_GET['tab']) && sanitize_text_field($_GET['tab'])=='addparent')
				{
					include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-parentForm.php');
				} else {
						include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-parentList.php');
			?>
			<!-- Modal for View-->
				<div class="modal modal-wide" id="ViewModal" tabindex="-1" role="dialog" aria-labelledby="AVEModal" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div id="ViewModalContent">
						</div>
					</div>
				</div><!-- /.modal -->
				
				<!-- Modal for Add-->
				<div class="modal modal-wide" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="AddModal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="col-lg-12 col-md-12">
							<?php include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-parentForm.php'); ?>
							</div>							
						</div>
					</div>
				</div><!-- /.modal -->
				<div class="modal modal-wide" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="ImportModal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="col-md-12">
								<div class="box box-info">
									<div class="box-header">
										<h3 class="box-title">Import Parents</h3>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									</div><!-- /.box-header -->
									<form action="#" name="ImportDetails" id="ImportDetails">
										<div class="form-group">
											<?php wp_nonce_field( 'UserImport', 'import_nonce', '', true ) ?>
											<input type="hidden" name="userType" value="2">
										</div>
									</form>
									<?php do_action('wpsp_parent_import_html'); ?>                                       									
								</div>
							</div>					
						</div>
					</div>
				</div><!-- /.modal -->				
			<?php
		}
			wpsp_body_end();
			wpsp_footer();
		}if($current_user_role=='parent' || $current_user_role=='student' ) {
			wpsp_topbar();
			wpsp_sidebar();
			wpsp_body_start();
			$parent_id	=	intval($current_user->ID);
			$label	=	'Your Profile';
			if( $current_user_role=='student' ) {
				$student_id		=	intval($current_user->ID);
				$student_table	=	$wpdb->prefix."wpsp_student";
				$parent_info	=	$wpdb->get_row("select parent_wp_usr_id from $student_table where wp_usr_id='$student_id'");
				$parent_id		=	intval($parent_info->parent_wp_usr_id);	
				$label	=	'Parent Profile';				
			}
			?>
			<section class="content-header">
				<h1><?php echo $label; ?></h1>
				<ol class="breadcrumb">
					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
					<li><a href="<?php wpsp_admin_url();?>sch-parent">Parent</a></li>
				</ol>
			</section>
			<section class="content">			
				<?php
					if($parent_id>0){
						wpsp_ParentPublicProfile($parent_id, 0);
					}else{
						echo "<p>Parent profile not linked with this account, Kindly contact to School!</p>";
					}
				?>
			</section>
			<?php
			wpsp_body_end();
			wpsp_footer();
		}
	}
	else {
		include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-login.php');
	}
	?>