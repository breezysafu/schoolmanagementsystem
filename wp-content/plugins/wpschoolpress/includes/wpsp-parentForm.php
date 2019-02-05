<?php if (!defined( 'ABSPATH' ) )exit('No Such File');
	global $current_user, $wpdb;
	$current_user_role=$current_user->roles[0];
?>

			<section class="content">

				<div class="row">

					<div class="col-md-12">

						<div class="box box-info">


							<div class="box-header">

								<h3 class="box-title">New Parent Entry</h3>

								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

							</div><!-- /.box-header -->
							<div class="col-md-12 gap-top-bottom">
								<div id="formresponse"></div>
								</div>

							<form name="ParentEntryForm" id="ParentEntryForm" method="post">

										<div class="box-body">
											<div class="row">
											
											</div>

											<div class="col-md-6">

												<?php wp_nonce_field( 'ParentRegister', 'pregister_nonce', '', true ) ?>

												<div class="form-group">

													<div class="row">

														<div class="col-md-4">

															<label for="firstname">Firstname</label><span class="red">*</span>

															<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">

														</div>

														<div class="col-md-4">

															<label for="middlename">Middlename</label><span class="red">*</span>

															<input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name">

														</div>

														<div class="col-md-4">

															<label for="lastname">Lastname</label><span class="red">*</span>

															<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">

														</div>

												</div>

												</div>

												<div class="form-group">

													<label for="Username">Username</label><span class="red">*</span>

													<input type="text" class="form-control" id="Username" name="Username" placeholder="Parent Username">

												</div>

												<div class="form-group">

													<label for="Email">Email address</label><span class="red">*</span>

													<input type="email" class="form-control" id="Email" name="Email" placeholder="parent Email">

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

													<label for="educ">Education</label>

													<input type="text" class="form-control" id="Qual" name="Qual" placeholder="Highest Education Degree">

												</div>

												<div class="form-group">

													<label for="dateofbirth">Date of Birth</label>

													<input type="text" class="form-control select_date" id="Dob" name="Dob" placeholder="Date of Birth">
													
												</div>

												<div class="form-group">

													<label for="displaypicture">Profile Image</label>

													<input type="file" name="displaypicture" id="displaypicture">
													<p id="test" style="color:red"></p>
												</div>

												

											</div>

											<div class="col-md-6">	

												<div class="form-group parent-student-list">

													<label for="position">Select Student</label>

													<?php 

													$class_table	=	$wpdb->prefix."wpsp_class"; 

													$classQuery		=	"select cid,c_name from $class_table";

													if( $current_user_role=='teacher' ) {

														$cuserId	=	intval($current_user->ID);

														$classQuery	=	"select cid,c_name from $class_table where teacher_id=$cuserId";														

													}

													$classList		=	$wpdb->get_results( $classQuery );

													?>

													 <select name="child_list[]" id="child_list" multiple class="form-control">

														<?php foreach( $classList as $classkey=>$classvalue ) { ?>

															<optgroup label="Class Name:<?php echo $classvalue->c_name; ?>">

																<?php 

																	$student_table		=	$wpdb->prefix."wpsp_student"; 

																	$studentList		=	$wpdb->get_results("select wp_usr_id,s_fname from $student_table where class_id=$classvalue->cid");

																	foreach( $studentList as $studentkey=> $studentvalue ) {

																?>

																<option value="<?php echo intval($studentvalue->wp_usr_id); ?>"><?php echo $studentvalue->s_fname; ?></option>

																	<?php } ?>

															</optgroup>

														<?php } ?>

													</select> 

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

														<input type="radio" name="Gender" value="Other">

														<label for="Female">Other</label>

													</div>													
												

												</div>												

												<div class="form-group">

													<label for="position">Profession</label>

													<input type="text" class="form-control" id="profession" name="Profession" placeholder="profession">

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

														<?php 

														foreach( $countrylist as $key=>$value ) { ?>

															<option value="<?php echo $value;?>"><?php echo $value;?></option>

														<?php	

														}

														?>

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

											<button type="submit" class="btn btn-primary" id="parentform">Submit</button>

										</div>

									</form>
						</div>

					</div>

				</div>

			</section>