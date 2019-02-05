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
		
		wpsp_topbar(); 
		wpsp_sidebar();
		wpsp_body_start();
		
		$proversion		=	wpsp_check_pro_version( 'wpsp_sms_version' ); 												
		$proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
		$protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';
		$prodisable		=	!$proversion['status'] ? 'disabled="disabled"'	: '';
		
		$paymentproversion	=	wpsp_check_pro_version( 'wpsp_payment_version' );
		$payproclass		=	!$paymentproversion['status'] && isset( $paymentproversion['class'] )? $paymentproversion['class'] : '';
		$payprotitle		=	!$paymentproversion['status'] && isset( $paymentproversion['message'] )? $paymentproversion['message']	: '';
		$payprodisable		=	!$paymentproversion['status'] ? 'disabled="disabled"'	: '';
		
		if($current_user_role=='administrator') {
			$ex_field_tbl	=	$wpdb->prefix."wpsp_mark_fields";
			$subject_tbl	=	$wpdb->prefix."wpsp_subject";
			$class_tbl		=	$wpdb->prefix."wpsp_class";
		?>
		<section class="content-header">
			<h1><?php _e( 'Settings', 'WPSchoolPress'); ?> </h1>
			<ol class="breadcrumb">
				<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i><?php _e( 'Dashboard', 'WPSchoolPress'); ?></a></li>
				<li><a href="<?php wpsp_admin_url();?>sch-settings"><?php _e( 'Settings', 'WPSchoolPress'); ?> </a></li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-md-12 ">
					<div class="box box-info">
								<div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbsp; <?php _e( 'Sub-Division Fields', 'WPSchoolPress'); ?> </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
						<div class="box-body">
							<?php
							if(isset($_GET['sc'])&& sanitize_text_field($_GET['sc'])=='subField') {
								//Fields Edit Section 
								if( isset( $_GET['sid'] ) && intval($_GET['sid'])>0 ) { 
									$subject_id	=	intval($_GET['sid']);
									$fields		=	$wpdb->get_results("select f.*,s.sub_name,c.c_name from $ex_field_tbl f LEFT JOIN $subject_tbl s ON s.id=f.subject_id LEFT JOIN $class_tbl c ON c.cid=s.class_id where f.subject_id=$subject_id");
									?>
									<div class="col-md-12 col-lg-12">
										<div class="col-md-6">
											<label><?php _e( 'Class:', 'WPSchoolPress'); ?></label> <?php echo $fields[0]->c_name;?>
										</div>
										<div class="col-md-6">
											<label><?php _e( 'Subject:', 'WPSchoolPress'); ?></label> <?php echo $fields[0]->sub_name;?>
										</div>
									</div>
									<div class="form-horizontal">
										<input type="hidden"  id="wpsp_locationginal" value="<?php echo admin_url();?>"/>
									<?php
										if(count($fields)>0){
											$sno=1;
											foreach($fields as $field){ ?>
												<div class="form-group">
													<label class="col-sm-1 control-label"><?php echo $sno; ?></label>
													<div class="col-sm-6">
													  <input type="text" id="<?php echo intval($field->field_id);?>SF" value="<?php echo $field->field_text;?>" class="form-control">

													</div>
													<div class="col-sm-5">
													  <button id="sf_update" class="btn btn-success  SFUpdate" data-id="<?php echo intval($field->field_id);?>">Update</button> <button id="sf_delete" class="btn btn-warning  SFDelete" data-id="<?php echo intval($field->field_id);?>">Delete</button>
													</div>
											  </div>
											<?php $sno++; }
										}else{
											echo "<div class='col-md-8 col-md-offset-4'>".__( 'No data retrived!', 'WPSchoolPress')."</div>";
										}
									?>
									</div>
									<div class="col-md-6">
										<a href="<?php wpsp_admin_url();?>sch-settings&sc=subField" class="btn btn-primary"><?php _e( 'Back', 'WPSchoolPress'); ?></a>
									</div>
									
								<?php }else{
								//Subject Mark Extract fields
								$all_fields	=	$wpdb->get_results("select mfields.subject_id, GROUP_CONCAT(mfields.field_text) AS fields,class.c_name,subject.sub_name from $ex_field_tbl mfields LEFT JOIN $subject_tbl subject ON subject.id=mfields.subject_id LEFT JOIN $class_tbl class ON class.cid=subject.class_id group by mfields.subject_id");
								
							?>
								<div class="subject-head">
								
									<div class="float-right">
										<button class="btn btn-success pull-right gap-bottom" data-toggle="modal" data-target="#AddFieldsModal" id="AddFieldsButton"><i class="fa fa-plus"></i>&nbsp; Add Fields</button>
									</div>
								</div>	
								<div class="col-md-12 table-responsive">
								<table id="wpsp_sub_division_table" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th class="nosort">#</th>
										<th><?php _e( 'Class', 'WPSchoolPress'); ?></th>
										<th><?php _e( 'Subject', 'WPSchoolPress'); ?></th>
										<th><?php _e( 'Fields', 'WPSchoolPress'); ?></th>
										<th class="nosort"><?php _e( 'Action', 'WPSchoolPress'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php $sno=1;
									foreach($all_fields as $exfield){ ?>
										<tr>
											<td><?php echo $sno; ?></td><td><?php echo $exfield->c_name;?></td><td><?php echo $exfield->sub_name;?></td><td><?php echo $exfield->fields;?></td>
											<td>
												<a href="<?php wpsp_admin_url();?>sch-settings&sc=subField&ac=edit&sid=<?php echo $exfield->subject_id;?>" title="Edit"><i class="fa fa-pencil btn btn-warning"></i></a>
											</td>
										</tr>
									<?php $sno++; } ?>
								</tbody>
								<tfoot>
								  <tr>
									<th>#</th>
									<th><?php _e( 'Class', 'WPSchoolPress'); ?></th>
									<th><?php _e( 'Subject', 'WPSchoolPress'); ?></th>
									<th><?php _e( 'Fields', 'WPSchoolPress'); ?></th>
									<th><?php _e( 'Action', 'WPSchoolPress'); ?></th>
								  </tr>
								</tfoot>
							  </table></div>

							  <!-- Add Field Modal -->
							  <div class="modal moda-lg" id="AddFieldsModal" tabindex="-1" role="dialog" aria-labelledby="AddFields" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="col-md-12">
											<div class="box box-info">
												<div class="box-header">
													<h3 class="box-title">Add Subject Mark Fields</h3>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												</div><!-- /.box-header -->
												<form action="#" method="POST" name="SubFieldsForm" id="SubFieldsForm">
													<div class="box-body">
														<div class="form-group">
															<?php wp_nonce_field( 'SubjectFields', 'subfields_nonce', '', true ) ?>
															<label for="Class">Class <span class="red">*</span></label>
															<select name="ClassID" id="SubFieldsClass" class="form-control">
																<option value="">Select Class</option>
																<?php $classes=$wpdb->get_results("select cid,c_name from $class_tbl");
																	foreach($classes as $class){
																?>
																	<option value="<?php echo intval($class->cid);?>"><?php echo $class->c_name;?></option>
																	<?php } ?>
															</select>
														</div>
														<div class="form-group">
															<label for="Subject">Subject <span class="red">*</span></label>
															<select name="SubjectID" id="SubFieldSubject" class="form-control">
																<option value="">Select Class</option>
															</select>
														</div>
														<div class="form-group">
															<label for="Field">Field <span class="red">*</span></label>
															<input type="text" name="FieldName" class="form-control">
														</div>
														
													</div>
													<div class="box-footer">
														<span class="pull-right">
															<button type="submit" class="btn btn-primary">Submit</button>
															<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
														</span>
													</div>
												</form>
											</div>
										</div>					
									</div>
								</div>
							</div><!-- /.modal -->
							
							<?php
								}
						} else if(isset($_GET['sc'])&& sanitize_text_field($_GET['sc'])=='WrkHours') {
								//Class Hours
								if(isset($_POST['AddHours'])){
									$workinghour_table	=	$wpdb->prefix."wpsp_workinghours";
									if( empty( $_POST['hname'] ) || empty( $_POST['hstart'] ) || empty( $_POST['hend'])  || sanitize_text_field($_POST['htype'])=='' ) {
										echo "<div class='col-md-12'><div class='alert alert-danger'>".__( 'Please fill all values.', 'WPSchoolPress')."</div></div>";
									} elseif( strtotime( $_POST['hend'] ) <= strtotime( $_POST['hstart'] ) ) {
										echo "<div class='col-md-12'><div class='alert alert-danger'>".__( 'Invalid Class Time.', 'WPSchoolPress')."</div></div>";
									} else {
										$workinghour_namelist = $wpdb->get_var( $wpdb->prepare( "SELECT count( * ) AS total_hour FROM $workinghour_table WHERE HOUR = %s", $_POST['hname'] ) );
										if( $workinghour_namelist > 0 ) {
											echo "<div class='col-md-12'><div class='alert alert-danger'>".__( 'Class Hour Name Already exists.', 'WPSchoolPress')."</div></div>";
										} else {	
					$workinghour_table_data = array('hour'		=>	sanitize_text_field($_POST['hname']),
													'begintime'	=>	sanitize_text_field( $_POST['hstart'] ),
													'endtime'	=>	sanitize_text_field( $_POST['hend'] ),
													'type'		=>	sanitize_text_field( $_POST['htype'] )
													);											
											$ins=$wpdb->insert( $workinghour_table,$workinghour_table_data);
										}							
									}
								}
								if( isset($_GET['ac']) && sanitize_text_field($_GET['ac'])=='DeleteHours' ) {
									$workinghour_table=$wpdb->prefix."wpsp_workinghours";
									$hid=intval($_GET['hid']);
									$del=$wpdb->delete($workinghour_table,array('id'=>$hid));
								}
								//Save hours
								
							?>	
								<h3>Class hours</h3>
									<div class="row">
										<form name="working_hour" method="post" action="">
											<div class="col-md-3">
												<div class="input-group">
													<span class="input-group-addon">Name</span>
													<input type="text" name="hname" class="form-control" placeholder="Hour Name">
												 </div>
											</div>
											<div class="col-md-2">
												<div class="input-group">
													<span class="input-group-addon">From</span>
													<input type="text" name="hstart" class="form-control" placeholder="HH:MM" id="timepicker1">
												 </div>
											</div>
											<div class="col-md-2">
												<div class="input-group">
													<span class="input-group-addon">To</span>
													<input type="text" name="hend" class="form-control" placeholder="HH:MM" id="wp-end-time" data-provide="timepicker">
												 </div>
											</div>
											<div class="col-md-3">
												<div class="input-group">
													<span class="input-group-addon">Type</span>
													<select name="htype" class="form-control">
														<option value="1">Teaching</option>
														<option value="0">Break</option>
													</select>
												 </div>
											</div>
											<div class="col-md-2">
												<div class="input-group">
													<button type="submit" class="btn btn-success" name="AddHours" value="AddHours"><i class="fa fa-plus"></i>&nbsp; Add Hour</button>
												</div>
											</div>
										</form>
									</div>
									<br/>
									<br/>
									<div class="col-md-12 table-responsive">
									<table class="table table-bordered table-striped" id="wpsp_class_hours">
										<thead><tr>
											<th> Class Hour </th>
											<th>Begin Time</th>
											<th>End Time</th>
											<th>Type</th>
											<th class="nosort">Action</th>
										</tr> </thead>
										<tbody>
											<?php
												$htypes=array('Break','Teaching');
												$workinghour_table=$wpdb->prefix."wpsp_workinghours";
												$workinghour_list =$wpdb->get_results("SELECT * FROM $workinghour_table") ;
													foreach ($workinghour_list as $single_workinghour) {
														$hourtype=$htypes[$single_workinghour->type]; ?>
													<tr> <td><?php echo stripslashes( $single_workinghour->hour ) ?></td>
															<td><?php echo $single_workinghour->begintime ?></td>
															<td><?php echo $single_workinghour->endtime ?></td>
															<td><?php echo $hourtype ?></td>
															<td><a href="<?php wpsp_admin_url();?>sch-settings&sc=WrkHours&ac=DeleteHours&hid=<?php echo intval($single_workinghour->id); ?>" class="btn btn-danger">Delete</a></td>
															</tr>
												<?php 	}
											?>
										</tbody>
								</table>
							</div>
								<?php 
							}else{
								//General Settings
								$wpsp_settings_table	=	$wpdb->prefix."wpsp_settings";
								$wpsp_settings_edit		=	$wpdb->get_results("SELECT * FROM $wpsp_settings_table" );	
									

								foreach( $wpsp_settings_edit as $sdat ) {
									$settings_data[$sdat->option_name]	=	$sdat->option_value;
								}

							?>
							<div class="nav-tabs-custom">
								<!-- Tabs within a box -->
								<ul class="nav nav-tabs">
									<li class="active"><a href="#school-info" data-toggle="tab">Info</a></li>
									<li><a href="#school-social" data-toggle="tab">Social</a></li>
									<li><a href="#sms" data-toggle="tab" class="<?php echo $proclass; ?>" title="<?php echo $protitle;?>" <?php echo $prodisable; ?>>SMS</a></li>
									<!-- <li><a href="#school-principal" data-toggle="tab">Principal and Chairman</a></li> -->
									<!-- <li><a href="#grade" data-toggle="tab">Grade</a></li> -->
									<!-- <li><a href="#paymentgateway" data-toggle="tab" class="<?php echo $payproclass; ?>" title="<?php echo $payprotitle;?>" <?php echo $payprodisable; ?>>Payment Gateway</a></li>-->
								</ul>		
								<div class="tab-content">
									<div class="tab-pane active" id="school-info">
										<form name="schinfo_form" id="SettingsInfoForm" class="form-horizontal" method="post"  enctype="multipart/form-data">
											<div  class="form-group">
												<div class="col-md-4" >
													<label class="control-label"> School Name </label><span class="red">*</span>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_name"  class="form-control" value="<?php echo isset( $settings_data['sch_name'] ) ? $settings_data['sch_name'] : '';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4" >
													<label class="control-label">School Logo</label><span class="red">*</span>
												</div>
												<div class="col-md-4">
													<?php 
													$imglogo	=	isset( $settings_data['sch_logo'] ) ? $settings_data['sch_logo'] : '';
													$imaglabel	=	empty( $imglogo ) ? ' Upload Logo ' : 'Change Logo';
													$default_img= $loc_avatar ? $loc_avatar['full'] : WPSP_PLUGIN_URL.'img/default_avtar.jpg';
													?>
													
													<div class="sch-logo-container" style="margin-bottom: 15px">
													<img src="<?php if( !empty( $imglogo ) ) {echo $imglogo;} else { echo $default_img; }?>" id="image" class="img img-circle school-logo" width="150" height="150" /></div>
													
													<?php if( !empty( $imglogo ) ) {/* ?>
														<div class="sch-logo-container" style="margin-bottom: 15px">
															<img src="<?php echo $settings_data['sch_logo']; ?>" class="img img-circle school-logo" style="" width="150px" height="150px">
														</br>
															
														</div>*/?>
															
													<?php } ?>
													
													<?php /* <a href="javascript:void(0);" style="padding:7px;" class="sch-remove-logo btn-danger btn">Remove</a> */?>
													<label class="customUpload btnUpload  btn btn-success" style="color: #ffffff; float: left; margin-right: 10px;"> <span class="logo-label"><?php echo $imaglabel; ?> </span>
														
														<input name="displaypicture" class="upload" type="file" id="displaypicture">
														
													</label>
													<input type="hidden" name="sch_logo" class="form-control" value="<?php echo $imglogo;?>" id="sch_logo_control">
												</div>
											</div>
											<!-- <div class="form-group">
												<div class="col-md-4" >
													<label class="control-label">Working Hours</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_wrkinghrs" class="form-control" value="<?php //echo isset( $settings_data['sch_wrkinghrs'] ) ? $settings_data['sch_wrkinghrs'] : '';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4" >
													<label class="control-label">Working Year</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_wrkingyear" class="form-control" value="<?php //echo isset( $settings_data['sch_wrkingyear'] ) ? $settings_data['sch_wrkingyear'] : '';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4" >
													<label class="control-label">Working Hours</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_wrkinghrs" class="form-control" value="<?php //echo isset( $settings_data['sch_wrkinghrs'] ) ? $settings_data['sch_wrkinghrs'] : '';?>">
												</div>
											</div> -->
											<div class="form-group">
												<div class="col-md-4" >
													<label class="control-label">Address </label><span class="red">*</span>
												</div>
												<div class="col-md-4">
													<textarea rows="2" cols="45" name="sch_addr"><?php echo isset( $settings_data['sch_addr'] ) ? $settings_data['sch_addr'] : '';?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4" >
													<label class="control-label">City </label><span class="red">*</span>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_city" class="form-control" value="<?php echo isset( $settings_data['sch_city'] ) ? $settings_data['sch_city'] : '';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">State </label><span class="red">*</span>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_state" class="form-control" value="<?php echo isset( $settings_data['sch_state'] ) ? $settings_data['sch_state'] : '';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Country</label><span class="red">*</span>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_country"  class="form-control" value="<?php echo isset( $settings_data['sch_country'] ) ? $settings_data['sch_country'] : '';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Phone Number</label><span class="red">*</span>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_pno"  class="form-control" value="<?php echo isset( $settings_data['sch_pno'] ) ? $settings_data['sch_pno'] : '';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Fax</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_fax"  class="form-control" value="<?php echo isset( $settings_data['sch_fax'] ) ? $settings_data['sch_fax'] :'';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Email</label><span class="red">*</span>
												</div>
												<div class="col-md-4">
													<input type="email" name="sch_email"  class="form-control" value="<?php echo isset( $settings_data['sch_email'] ) ? $settings_data['sch_email'] :'';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Website</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="sch_website"  class="form-control" value="<?php echo isset( $settings_data['sch_website'] ) ? $settings_data['sch_website'] : '';?>">
													<input type="hidden" name="type"  value="info">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Date Format</label>
												</div>
												<div class="col-md-4">
													<select name="date_format"  class="form-control">
														<option value="m/d/Y" <?php echo  isset( $settings_data['date_format'] ) && ( $settings_data['date_format']=='m/d/Y')?'selected':''?>>mm/dd/yyyy</option>
														<option value="Y-m-d" <?php echo  isset( $settings_data['date_format'] ) && ($settings_data['date_format']=='Y-m-d')?'selected':''?> >yyyy-mm-dd</option>
														<option value="d-m-Y" <?php echo  isset( $settings_data['date_format'] ) && ($settings_data['date_format']=='d-m-Y')?'selected':''?>>dd-mm-yyyy</option>
													</select>
												</div>
											</div>											
											<div class="form-group <?php echo $proclass; ?>" title="<?php echo $protitle;?>" <?php echo $prodisable; ?>>
												<div class="col-md-4">
													<label class="control-label"><?php _e( 'SMS Setting' ,'WPSchoolPress'); ?></label>
												</div>
												<div class="col-md-4">
													<input id="absent_sms_alert" type="checkbox" class="ccheckbox <?php echo $proclass; ?>" title="<?php echo $protitle;?>" <?php echo $prodisable; ?> <?php if(isset($settings_data['absent_sms_alert']) && $settings_data['absent_sms_alert']==1) echo "checked"; ?> name="absent_sms_alert" value="1" >
													<label for="absent_sms_alert"> <?php _e( 'Send SMS to parent when student absent','WPSchoolPress');?></label><br>
													<input id="notification_sms_alert" type="checkbox" class="ccheckbox <?php echo $proclass; ?>" title="<?php echo $protitle;?>" <?php echo $prodisable; ?> <?php if(isset($settings_data['notification_sms_alert']) && $settings_data['notification_sms_alert']==1) echo "checked"; ?> name="notification_sms_alert" value="1" >
													<label for="notification_sms_alert"> <?php _e( 'Enable SMS Notification','WPSchoolPress');?></label>
												</div>
											</div>
											<div class="col-md-12 text-center"> 		
												<button type="submit" class="btn btn-primary" id="setting_submit" name="submit" style="margin-top: 20px;!important" >  <i class="fa fa-save"></i>  Save  </button></div>
								 
										</form>
									</div>
									<div class="tab-pane" id="school-social">
										<form name="social_form" id="SettingsSocialForm" class="form-horizontal" method="post">
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Facebook:</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="sfb"  class="form-control" value="<?php echo isset( $settings_data['sfb'] ) ? $settings_data['sfb'] : '';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Twitter:</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="stwitter"  class="form-control" value="<?php echo isset( $settings_data['stwitter'] ) ? $settings_data['stwitter'] : '';?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Google+:</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="sgoogle"  class="form-control" value="<?php echo isset( $settings_data['sgoogle'] ) ? $settings_data['sgoogle'] : '';?>">
												</div>
											</div>
																				 
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Pinterest:</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="spinterest"  class="form-control" value="<?php echo isset( $settings_data['spinterest'] ) ? $settings_data['spinterest'] : '';?>">
													<input type="hidden" name="type"  value="social">
												</div>
											</div>
											<button type="submit" id="s_save" class="btn btn-primary" name="submit">  <i class="fa fa-save"></i>  Save  </button>
										</form>		
									</div>
									<div class="tab-pane" id="school-principal">
										<form name="mgmt_info_form" id="SettingsMgmtForm" class="form-horizontal" method="post">
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Principal Name</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="principal"  class="form-control" value="<?php echo isset( $settings_data['principal']) ? $settings_data['principal'] : '' ;?>"><br>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Principal Email Address</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="p_email"  class="form-control" value="<?php echo isset( $settings_data['p_email'] ) ? $settings_data['p_email'] : '' ;?>"><br>
												</div>
											</div>
											 <div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Principal Phone</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="p_phone"  class="form-control" value="<?php echo isset( $settings_data['p_phone'] ) ? $settings_data['p_phone'] : '';?>"><br>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Chairman Name</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="chairman"  class="form-control" value="<?php echo isset( $settings_data['chairman'] ) ? $settings_data['chairman'] :'';?>"><br>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Chairman Email Address</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="c_email"  class="form-control" value="<?php echo isset( $settings_data['c_email'] ) ? $settings_data['c_email'] : '';?>"><br>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-4">
													<label class="control-label">Chairman Phone</label>
												</div>
												<div class="col-md-4">
													<input type="text" name="c_phone"  class="form-control" value="<?php echo isset( $settings_data['c_phone'] ) ? $settings_data['c_phone'] : '';?>"><br>
													<input type="hidden" name="type"  value="mgmt">
												</div>
											</div>
												<button type="submit" class="btn btn-primary" name="submit">  <i class="fa fa-save"></i>  Save  </button>
										</form>
									</div>
									<div class="tab-pane" id="grade">
										<form name="SettingsGradeForm" class="form-horizontal" method="post" id="SettingsGradeForm">
											<div class="form-group">
												<div class="col-md-2">
													<label>Show Grade</label>
												</div>
												<div class="col-md-10">
													<input type="checkbox" class="ccheckbox" <?php if(isset($settings_data['show_grade']) && $settings_data['show_grade']==1) echo "checked"; ?> name="show_grade" value="1" > Yes
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-2">
													<label>Show Mark</label>
												</div>
												<div class="col-md-10">
													<input type="checkbox" <?php if(isset($settings_data['show_grade']) && $settings_data['show_mark']==1) echo "checked"; ?> class="ccheckbox" name="show_mark" value="1" > Yes
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-2">
													<label>Maximum Mark</label>
												</div>
												<div class="col-md-1">
													<input type="text" name="max_marks" value="<?php echo isset($settings_data['max_marks']) ? $settings_data['max_marks'] : '';?>" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-10 col-md-offset-2">
													<input type="hidden" name="type" value="grade">
													<button type="submit" class="btn btn-primary" name="submit">  <i class="fa fa-save"></i>  Save  </button>
												</div>
											</div>
										</form>
						
										<?php
											$wpsp_grade_table=$wpdb->prefix."wpsp_grade";
											$grade_data=$wpdb->get_results("select * from $wpsp_grade_table");			
										?>
										<div class="col-md-12" style="margin-bottom:10px;">
											<div class="pull-right">
												<span class="btn btn-primary" data-toggle="modal" data-target="#gradeModal">Add New</span>
											</div>
										</div>
										<table class="table table-bordered" id="wpsp_grade_list">
											<thead>
												<tr>
													<th class="nosort">Sno.</th>
													<th>Grade Name</th>
													<th>Grade Point</th>
													<th>Mark From</th>
													<th>Mark Upto</th>
													<th>Comment</th>
													<th class="nosort">Action</th>
												</tr>
											</thead>	
											<tbody>
												<?php
												if(count($grade_data)){
													$sno=1;
													foreach($grade_data as $grade_info)
													{
													?>
													<tr>
														<td><?php echo $sno; ?></td>
														<td><?php echo $grade_info->g_name; ?></td>
														<td><?php echo $grade_info->g_point; ?></td>
														<td><?php echo $grade_info->mark_from; ?></td>
														<td><?php echo $grade_info->mark_upto; ?></td>
														<td><?php echo $grade_info->comment; ?></td>
														<td>
															<span title="Delete" class="DeleteGrade" data-id="<?php echo intval($grade_info->gid);?>"> <i class="fa fa-trash-o btn btn-danger"></i></span>
														
														</td>
													</tr>
													<?php
													$sno++;
													}
												}
												?>
											</tbody>
										</table>
															
										<div class="modal fade" id="gradeModal" tabindex="-1" role="dialog" aria-labelledby="gradeModal" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="modalTitle"> Add Grade <span class="red">*</span></h4>
													</div>
													<form class="form-horizontal group-border-dashed" action="" id="AddGradeForm" method="post">
														<div class="modal-body">
																<div class="form-group">
																	<label class="col-sm-3 control-label"> Grade <span class="red">*</span></label>
																		<div class="col-sm-6">     <input type="text" class="form-control" name="grade_name"   id="grade_name" ></div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label"> Grade Point <span class="red">*</span></label>
																		<div class="col-sm-6">     <input type="text" class="form-control" name="grade_point"   id="grade_point" ></div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label"> Mark From <span class="red">*</span></label>
																		<div class="col-sm-6">     <input type="text" class="form-control" name="mark_from"   id="mark_from" ></div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label"> Mark Upto <span class="red">*</span></label>
																		<div class="col-sm-6">     <input type="text" class="form-control" name="mark_upto"   id="mark_upto" ></div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label"> Comment</label>
																		<div class="col-sm-6">     <textarea name="grade_comment" class="form-control"></textarea></div>
																</div>
												
														</div>
														<div class="modal-footer">
															<input type="hidden" name="actype" value="add" id="actype">
															<input type="hidden" name="grade_id" value="" id="grade_id">
															<button type="button" id="modal_close" class="btn btn-default" data-dismiss="modal">Close</button>															
															<button type="submit"  id="grade_save" class="btn btn-primary">Save </button>
														</div>
													</form>	
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="sms">
										<?php 
										if( isset( $proversion['status'] ) && $proversion['status'] ) {											
											do_action( 'wpsp_sms_setting_html', $settings_data );
										} else {											
											_e( 'Upgrade To Pro Version to use this feature', 'WPSchoolPress' );											
										}
										?>
									</div>
                                      	<div class="tab-pane" id="paymentgateway">
											<?php												
												if( isset( $paymentproversion['status'] ) && $paymentproversion['status'] ) {											
													do_action( 'wpsp_payment_setting_html', $settings_data );
												} else {											
													_e( 'Upgrade To Pro Version to use this feature', 'WPSchoolPress' );											
												}
											?>																			
									</div>                                          	
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section>
			<?php } else if($current_user_role=='parent' || $current_user_role=='student') {
			
				}		
		wpsp_body_end();
		wpsp_footer(); ?>
	<?php	
	}else {
		include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-login.php');
	}
?>