<?php if (!defined( 'ABSPATH' ) )exit('No Such File'); 
global $current_user;
$role			=	$current_user->roles;
$cuserId		=	$current_user->ID;
$class_table	=	$wpdb->prefix."wpsp_class";	
									
if ( in_array( 'administrator', $role ) ) {
	$queryclass	=	"select cid,c_name from $class_table";
} else if( in_array( 'teacher', $role ) ) {
	$queryclass	=	"select cid,c_name from $class_table where teacher_id=$cuserId";
}
$classes		=	$wpdb->get_results( $queryclass );?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">New Student Entry</h3>								
				</div><!-- /.box-header -->
				<form name="StudentEntryForm" id="StudentEntryForm" method="post" enctype="multipart/form-data">
					<div class="box-body">
							<div id="formresponse"></div>
						<div class="col-md-6">
							<?php wp_nonce_field( 'StudentRegister', 'sregister_nonce', '', true ) ?>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label for="firstname">First Name</label><span class="red">*</span>
										<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
									</div>
									<div class="col-md-4">
										<label for="middlename">Middle Name</label><span class="red">*</span>
										<input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name">
									</div>
									<div class="col-md-4">
										<label for="lastname">Last Name</label><span class="red">*</span>
										<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
									</div>
								</div>
							</div>
								<div class="form-group">
									<label for="Username">Username</label><span class="red">*</span>
									<input type="text" class="form-control" id="Username" name="Username" placeholder="Student Username">
								</div>
								<div class="form-group">
									<label for="Email">Email Address</label><span class="red">*</span>
									<input type="email" class="form-control" id="Email" name="Email" placeholder="Student Email">											
								</div>
								<div class="form-group">
									<label for="Password">Password</label><span class="red">*</span>
									<input type="password" class="form-control" id="Password" name="Password" placeholder="Password">
								</div>
								<div class="form-group">
									<label for="ConfirmPassword">Confirm Password</label><span class="red">*</span>
									<input type="password" class="form-control" id="ConfirmPassword" name="ConfirmPassword" placeholder="Confirm Password">
								</div>
								<div class="form-group">
									<label for="Class">Class</label>									
									<select class="form-control" name="Class" id="selectstudclass">
										<?php foreach( $classes as $class ) { ?>
											<option value="<?php echo $class->cid;?>"><?php echo $class->c_name; ?></option>
										<?php	} ?>
										<option value="0"><?php _e( 'Unassigned Students', 'WPSchoolPress' ); ?></option>
										<?php if ( in_array( 'administrator', $role ) ) { ?>
											<option value="other" class="class-other">Other</option>
										<?php } ?>	
									</select>											
								</div>
								
								<div class="form-group">
									<label for="dateofbirth">Roll Number</label>
									<input type="text" class="form-control" id="Rollno" name="Rollno" placeholder="Roll Number">
								</div>
								<div class="form-group">
									<label for="dateofbirth">Date of Birth (mm/dd/yy)</label>
									<input type="text" class="form-control select_date" id="Dob" name="Dob" placeholder="Date of Birth">
								</div>
								
								
								<div class="form-group">
									<label for="displaypicture">Profile Image</label>
									<input type="file" name="displaypicture" id="displaypicture">
									<p id="test" style="color:red"></p>
									<p class="help-block">* Leave students can upload from their profile</p>
								</div>	

							</div>
							
							<div class="col-md-6">	
							<div class="form-group">
									<label for="dateofbirth">Date of Join (mm/dd/yy)</label>
									<input type="text" class="form-control select_date" id="Doj" name="Doj" value="<?php echo date('m/d/Y'); ?>" placeholder="Date of Join">
								</div>									
								<div class="form-group wpsp-gender-field">
									<label for="Class">Gender</label> <br/>
										<div class="radio">
											<input type="radio" name="Gender" value="Male" checked="checked">
											<label for="Male">Male</label>
										</div>										
										<div class="radio">
											<input type="radio" name="Gender" value="Female">
											<label for="Female">Female</label>
										</div>
										<div class="radio">
											<input type="radio" name="Gender" value="other">
											<label for="other">Other</label>
										</div>
								</div>
								
								
								
								<div class="form-group">
									<label for="Address" >Current Address</label>
									<textarea name="Address" class="form-control" rows="4"></textarea>											
								</div>
								<div class="form-group">
									<label for="Address" >Permanent Address</label>
									<textarea name="pAddress" class="form-control" rows="5"></textarea>											
								</div>
								<div class="form-group">
									<label for="Country">Country</label>
									<?php $countrylist = wpsp_county_list();?>
									<select class="form-control" id="Country" name="country">
										<option value="">Select Country</option>
										<?php foreach( $countrylist as $key=>$value ) { ?>
												<option value="<?php echo $value;?>"><?php echo $value;?></option>
										<?php } ?>
									</select>
								</div>
								
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label for="Zipcode">Zipcode</label>
											<input type="text" class="form-control" id="Zipcode" name="zipcode" placeholder="Zipcode">
										</div>
										<div class="col-md-6">
											<label for="phone">Phone (Optional)</label>
											<input type="text" class="form-control" id="phone" name="Phone" placeholder="Phone Number">
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label for="bloodgroup">Blood Group (Optional)</label>
									<select class="form-control" id="Bloodgroup" name="Bloodgroup">
										<option value="">Select Blood Group</option>
										<option value="O+">O +</option>
										<option value="O-">O -</option>
										<option value="A+">A +</option>
										<option value="A-">A -</option>
										<option value="B+">B +</option>
										<option value="B-">B -</option>
										<option value="AB+">AB +</option>
										<option value="AB-">AB -</option>
									</select>
								</div>	
							</div>
					</div>
					
					<div class="box-footer">
						<button type="submit" class="btn btn-primary" id="studentform">Submit</button>
					</div>
				</form>
			
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="AddModalClass" tabindex="-1" role="dialog" aria-labelledby="AddModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title">New Class Entry</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div><!-- /.box-header -->
					<form name="ClassAddForm" id="ClassAddForm" method="post">
						<?php include( WPSP_PLUGIN_PATH.'/includes/wpsp-classForm.php'); ?>
					</form>
				</div>
			</div>					
		</div>
	</div>
</div>