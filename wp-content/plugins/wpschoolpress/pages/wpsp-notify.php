<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();

	if( is_user_logged_in() ) {

		

		global $current_user, $wpdb,$wpsp_settings_data;

		$notify_table	=	$wpdb->prefix . "wpsp_notification";

		$status = $ins = 0;

		

		$receiverTypeList = array( 'all'  => __( 'All Users', 'WPSchoolPress' ), 

								'alls' => __( 'All Students', 'WPSchoolPress'),

							    'allp' => __( 'All Parents', 'WPSchoolPress'),

							    'allt' => __( 'All Teachers', 'WPSchoolPress' ) );

															   

		$notifyTypeList	=	array( 0 	=>	__( 'All', 'WPSchoolPress') , 

							   1 	=>	__( 'Email', 'WPSchoolPress'), 

							   2	=>	__( 'SMS', 'WPSchoolPress'), 

							   3	=> 	__( 'Web Notification', 'WPSchoolPress'),

							   4	=>	__( 'Push Notification (Android & IOS)', 'WPSchoolPress') );

				

		//to send notifications

		if( isset( $_POST['notifySubmit']) && sanitize_text_field($_POST['notifySubmit']) == 'Notify' ) {



			if( isset( $_POST['receiver'] ) && !empty( sanitize_text_field($_POST['receiver']) ) && isset( $_POST['type'] )  &&

				isset( $_POST['subject'])  && !empty( sanitize_text_field($_POST['subject']) ) && isset( $_POST['description'] ) && !empty( sanitize_text_field($_POST['description']) ) ) {
					$student_table	=	$wpdb->prefix.'wpsp_student';
					$parents_table	=	$wpdb->prefix.'wpsp_parent';
					$teacher_table	=	$wpdb->prefix.'wpsp_teacher';
					$users_table	=	$wpdb->prefix.'users ';
					$receiverType	=	sanitize_text_field($_POST['receiver']);
					$notifyType		=	sanitize_text_field($_POST['type']);
					$subject 		=	sanitize_text_field($_POST['subject']);
					$description 	=	sanitize_text_field($_POST['description']);
					$usersList		=	$student_ids	=	$parent_ids	=	$teacher_ids	=	array();

					$whereQuery	= 'where ut.ID = st.wp_usr_id';
					if ( $notifyType ==1 || $notifyType ==0 ) {
						$whereQuery	.=	' AND ut.user_email!=""';
					}					

					if ( $notifyType ==2 || $notifyType ==0 ) {
						$whereQuery	.=	' AND st.phone!=""';
					}

					

					if( $receiverType == 'alls' || $receiverType == 'all')	{
						$student_ids	=	$wpdb->get_results( "select * from $student_table st, $users_table ut $whereQuery",ARRAY_A );
					} if( $receiverType == 'allp' || $receiverType == 'all' ) {						
						$parent_ids		=	$wpdb->get_results( "select * from $student_table st ,$users_table ut where ut.ID=st.parent_wp_usr_id AND ut.user_email!=''", ARRAY_A );
					} if( $receiverType == 'allt' || $receiverType == 'all' ) {
						$teacher_ids	=	$wpdb->get_results( "select * from $teacher_table st, $users_table ut $whereQuery", ARRAY_A );
					}					

					$usersList	=	array_merge( $student_ids,$parent_ids,$teacher_ids );					
					if ( $notifyType ==1 || $notifyType ==0 ) { //If notification is mail/All
						$wpsp_settings_table=$wpdb->prefix."wpsp_settings";
						$wpsp_settings_edit=$wpdb->get_results( "SELECT * FROM $wpsp_settings_table" );
						foreach($wpsp_settings_edit as $sdat) {
							$settings_data[$sdat->option_name]=$sdat->option_value;
						}					

						add_filter( 'wp_mail_from', 'wpsp_new_mail_from' );
						add_filter( 'wp_mail_from_name', 'wpsp_new_mail_from_name' );

						function wpsp_new_mail_from($old) {

						   global $settings_data;
						  return isset( $settings_data['sch_email'] ) && !empty($settings_data['sch_email']) ? $settings_data['sch_email'] : $old;
						}
						
						function wpsp_new_mail_from_name($old) {
							global $settings_data;
							return isset( $settings_data['sch_name'] ) && !empty( $settings_data['sch_name'] ) ? $settings_data['sch_name'] : $old;
						}							

						$body = nl2br( $description );
						$headers = array('Content-Type: text/html; charset=UTF-8');	
						foreach( $usersList as $key =>$value ) {
							$to = $value['user_email'];
							if( !empty( $to ) ) {

								//if( wp_mail( $to, $subject, $body, $headers ) ) $status = 1;
								if( wpsp_send_mail( $to, $subject, $body ) ) $status = 1;
							}
						}
					}					

					if( isset( $wpsp_settings_data['notification_sms_alert'] ) && $wpsp_settings_data['notification_sms_alert'] == 1 ) { //if notification enable from setting page
						if ( $notifyType ==2 || $notifyType ==0 ) { //If notification is sms/All					
							foreach( $usersList as $key =>$value ) {
								$to = $value['phone'];
								if( !empty( $to ) ) {
									$notify_msg_response	= apply_filters( 'wpsp_send_notification_msg', false, $to, $description );
									if( $notify_msg_response ) $status = 1;
								}
							}
						}
					}
					$currentDate	=	wpsp_StoreDate( esc_attr( date('Y-m-d h:i:s') ) );
					$description	=	strlen( $description ) > 255 ? substr( $description, 0, 254 ) : $description;

					//insert into db
					$notify_table_data = array(
											'name' => $subject,
											'description' => $description,
											'receiver' => $receiverType,
											'type' => $notifyType,
											'status' => $status,
											'date'	=> $currentDate
		);	
					/*$ins = $wpdb->insert( $notify_table, array(
											'name' => $subject,
											'description' => $description,
											'receiver' => $receiverType,
											'type' => $notifyType,
											'status' => $status,
											'date'	=> $currentDate
										),
										array( '%s', '%s', '%s','%d','%d', '%s' )
									);*/
									$ins = $wpdb->insert( $notify_table,$notify_table_data);
									
				}
		}
		$current_user_role=$current_user->roles[0];
		wpsp_topbar();
		wpsp_sidebar();
		wpsp_body_start();
		$addUrl = add_query_arg( 'ac', 'add', get_permalink());
		if($current_user_role=='administrator' || $current_user_role=='teacher') { 	?>

		<section class="content-header">

			<h1><?php _e( 'Notification', 'WPSchoolPress'); ?></h1>

			<ol class="breadcrumb">

				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i><?php _e( 'Dashboard', 'WPSchoolPress'); ?></a></li>

				<li><a href="<?php wpsp_admin_url();?>sch-notify"><?php _e( 'Notification', 'WPSchoolPress'); ?> </a></li>



			</ol>

		</section>

		<?php

		if($ins) { ?>

			<div class="wpsp-notice-success">

				<p><?php _e('Notification Successfully Send!','WPSchoolPress');?></p>

			</div>

		<?php  } ?>

		

		<?php if( isset($_GET['ac']) && sanitize_text_field($_GET['ac'])=='add' ) { ?>

		<section class="content">

			<div class="row">

				<div class="col-md-12">

						<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-bell" aria-hidden="true"></i>&nbsp; Notification  </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">						

							<form action="" method="post" class="form-horizontal" id="NotifyEntryForm">

								<div class="form-group">

									<label class="col-md-4 control-label"><?php _e( 'Name', 'WPSchoolPress'); ?></label>

									<div class="col-md-4">

										<input type="text" name="subject" class="form-control">

									</div>

								</div>

								<div class="form-group">

									<label class="col-md-4 control-label"><?php _e( 'Description', 'WPSchoolPress'); ?></label>

									<div class="col-md-4">

										<textarea class="form-control" name="description" required minlength="15"></textarea>

									</div>

								</div>

								<div class="form-group">

										<label class="col-md-4 control-label"><?php _e( 'Receiver', 'WPSchoolPress'); ?></label>

										<div class="col-md-4">

											<select name="receiver" class="form-control">

												<option value=""><?php _e( 'Whom to notify?', 'WPSchoolPress'); ?></option>

												<?php

													foreach( $receiverTypeList as $key => $value ) {

														echo '<option value="'.$key.'">'.$value.'</option>';

													}

												?>

											</select>

										</div>

									</div>

									<div class="form-group">

										<label class="col-md-4 control-label"><?php _e( 'Notify Type', 'WPSchoolPress'); ?></label>

										<div class="col-md-4">

											<?php $proversion = wpsp_check_pro_version('wpsp_sms_version'); 

												

												$proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';

												$protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';

												$prodisable		=	!$proversion['status'] ? 'disabled="disabled"'	: '';												

											?>

											<select name="type" class="form-control">

												<option value=""><?php _e( 'How to notify?', 'WPSchoolPress'); ?></option>

												<option value="1"><?php _e( 'Email', 'WPSchoolPress'); ?></option>

												<option value="2" title="<?php echo $protitle; ?>" class="<?php echo $proclass; ?>"

													<?php //if( ( !isset( $wpsp_settings_data['notification_sms_alert'] ) || ( isset( $wpsp_settings_data['notification_sms_alert'] ) && $wpsp_settings_data['notification_sms_alert'] != 1 ) ) && !empty( $prodisable ) ) { echo 'disabled'; } ?>

													<?php if( !empty( $prodisable ) ) { ?> disabled <?php  } ?>>

													<?php _e( 'SMS', 'WPSchoolPress'); ?>

												</option>												

												<option value="0"><?php _e( 'All', 'WPSchoolPress'); ?></option>

											</select>

											<?php

											if( !isset( $wpsp_settings_data['notification_sms_alert'] ) || ( isset( $wpsp_settings_data['notification_sms_alert'] ) && $wpsp_settings_data['notification_sms_alert'] != 1 ) ) {

												echo '<label class="fa fa-info-circle" style="margin-top:10px;"> Enable SMS Notification Option from setting page to send SMS</label>';

											}

											?>

										</div>

									</div>

									<div class="form-group">

										<div class="col-md-8 col-md-offset-4">

											<input type="submit" class="btn btn-primary" name="notifySubmit" value="Notify" id="notifySubmit">

										</div>

									</div>

							</form>

						</div>

					</div>

				</div>

			</div>

		</section>



		<?php } else { ?>

		<section class="content">

			<div class="row">

				<div class="col-md-12">

					<div class="box box-solid bg-blue-gradient">
				<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-bell" aria-hidden="true"></i>&nbsp; Notification  </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                 <div class="box-footer text-black">						
						
							<div class="subject-head">

								<a href="<?php echo $addUrl; ?>" id="NewNotify" class="btn btn-success pull-right"><i class="fa fa-plus"></i>&nbsp; <?php _e( 'Notify', 'WPSchoolPress'); ?></a>

							</div>
							<div class="col-md-12 table-responsive">
							<table id="notify_table" class="table table-bordered table-striped table-responsive" style="margin-top:10px">

								<thead>

									<tr>

										<th class="nosort">#</th>

										<th><?php _e( 'Name', 'WPSchoolPress' ); ?></th>	

										<th><?php _e( 'Description', 'WPSchoolPress' );?></th>										

										<th><?php _e( 'Receiver', 'WPSchoolPress' ); ?></th>

										<th><?php _e( 'Type', 'WPSchoolPress' ); ?></th>

										<th><?php _e( 'Date', 'WPSchoolPress');  ?></th>

										<th class="nosort"><?php _e( 'Action', 'WPSchoolPress'); ?></th>

									</tr>

								</thead>

								<tbody>

									<?php

										//Last added will me shown first

										$notifyInfo = $wpdb->get_results("Select * from $notify_table order by nid desc");

										foreach( $notifyInfo as $key=>$value ) {

											$receiver	=	isset( $receiverTypeList[$value->receiver] ) ? $receiverTypeList[$value->receiver] : $value->receiver;

											$type		=	isset( $notifyTypeList[$value->type] ) ? $notifyTypeList[$value->type] : $value->type;

												echo '<tr>

													<td>'.($key+1).'</td>

													<td>'.$value->name.'</td>

													<td>'.substr( $value->description, 0, 20).'</td>

													<td>'.$receiver.'</td>

													<td>'.$type.'</td>

													<td>'.wpsp_ViewDate( $value->date ).'</td>

													<td>

													<i class="fa fa-eye btn btn-success notify-view" data-id="'.intval($value->nid).'"></i>

														<i class="fa fa-trash btn btn-danger notify-Delete" data-id="'.intval($value->nid).'"></i>

													</td>

													

												</tr>';

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

		<!-- Modal for View-->

		<div class="modal modal-wide" id="ViewModal" tabindex="-1" role="dialog" aria-labelledby="AVEModal" aria-hidden="true">

			<div class="modal-dialog">

				<div id="ViewModalContent"></div>

			</div>

		</div><!-- /.modal -->

		<?php }

		}

		else if($current_user_role=='parent' || $current_user_role='student')

		{



		}

		wpsp_body_end();

		wpsp_footer();

	}

	else{

		include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-login.php');

	}



		?>

