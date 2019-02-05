<?php if (!defined( 'ABSPATH' ) )exit('No Such File');?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                     
                <div class="box-header">
                    <h3 class="box-title">New Teacher Entry</h3>
                </div>
                <div class="col-md-12 gap-top-bottom">
					<div id="formresponse"></div>
                </div>
                <!-- /.box-header -->
                <form name="TeacherEntryForm" id="TeacherEntryForm" method="post">
                    <div class="box-body">
                         
                        <div class="col-md-6">
                            <h3 class="box-title"> <i class="fa fa-info" aria-hidden="true"></i>&nbsp;Personal Details</h3>
                            <div class="line_box">

                                <?php wp_nonce_field( 'TeacherRegister', 'tregister_nonce', '', true ) ?>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="firstname">First Name</label><span class="red">*</span>
                                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
                                        <input type="hidden"  id="wpsp_locationginal" value="<?php echo admin_url();?>"/>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="lastname">Last Name</label><span class="red">*</span>
                                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="dateofbirth">Date of Birth</label>
                                        <input type="text" class="form-control select_date" id="Dob" name="Dob" placeholder="Date of Birth">
                                    </div>
                                    <div class="col-lg-6 col-md-8 form-group wpsp-gender-field">
                                        <label for="gender">Gender</label> <br/>
                                        <div class="radio-inline">
                                            <input type="radio" name="Gender" value="Male" checked="checked" id="Male">
                                            <label for="Male">Male</label>
                                        </div>
                                        <div class="radio-inline">
                                            <input type="radio" name="Gender" value="Female" id="Female">
                                            <label for="Female">Female</label>
                                        </div>
                                        <div class="radio-inline">
                                            <input type="radio" name="Gender" value="other" id="Other">
                                            <label for="other">Other</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Email">Email Address</label><span class="red">*</span>
                                    <input type="email" class="form-control" id="Email" name="Email" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label for="Address" >Current Address</label><span class="red">*</span>
                                    <input type="text" name="Address" class="form-control" placeholder="Street Address">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                      <!--  <label for="CityName">City Name</label>-->
                                        <input type="text" class="form-control" id="CityName" name="city" placeholder="City Name" >
                                    </div>
                                    <div class="form-group col-md-4">
                                      <!--  <label for="Country">Country</label>-->
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
                                    <div class="form-group col-md-4">
                                      <!--  <label for="Zipcode">Zipcode</label>-->
                                        <input type="text" class="form-control" id="Zipcode" name="zipcode" placeholder="Zipcode">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="phone">Phone (Optional)</label>
                                        <input type="text" class="form-control" id="phone" name="Phone" placeholder="Phone Number">
                                    </div>
                                    <div class="form-group col-md-6">
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
                                <div class="form-group">
                                    <label for="Username">Username</label><span class="red">*</span>
                                    <input type="text" class="form-control" id="Username" name="Username" placeholder="Username">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="Password">Password</label><span class="red">*</span>
                                        <input type="password" class="form-control" id="Password" name="Password" placeholder="Password">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="ConfirmPassword">Confirm Password</label><span class="red">*</span>
                                        <input type="password" class="form-control" id="ConfirmPassword" name="ConfirmPassword" placeholder="Confirm Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="educ">Qualification</label>
                                    <input type="text" class="form-control" id="Qual" name="Qual" placeholder="Highest Education Degree">
                                </div>
                                <div class="form-group">
                                     <label for="displaypicture">
                                      Profile Image<span class="red">*</span>
                                  </label> </br>
                                    <label class="customUpload btnUpload  btn btn-success"  style="color: #ffffff;  margin-right: 10px;">
                                        <span class="logo-label"><i class="fa fa-upload" aria-hidden="true"></i>  Choose File </span>
                                        <input name="displaypicture" class="upload" type="file" id="displaypicture" accept="image/jpg, image/jpeg">
                                      </label>
                                    <p id="test" style="color:red"></p>
                                    <p class="help-block">* Only JPEG and JPG supported </p>
                                    <p class="help-block">* Max 3 MB Upload </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                        	 <h3 class="box-title"> <i class="fa fa-building" aria-hidden="true"></i>&nbsp;School Details</h3>
                            <div class="line_box">

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="Doj">Joining Date</label>
                                        <input type="text" class="form-control select_date Doj" id="Doj" name="Doj" value="" placeholder="Date of Join">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="Dol">Leaving Date</label>
                                        <input type="text" class="form-control select_date Dol" id="Dol" name="dol" value="" placeholder="Date of Leave">
                                    </div>
                                </div>
							<div class="row">
							 <div class="col-md-4 form-group">
                                <label for="position">Current Position</label>
                                <input type="text" class="form-control" id="Position" name="Position" placeholder="Designation">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="empcode">Employee Code</label>
                                <input type="text" class="form-control" id="EmpCode" name="EmpCode" placeholder="Employee Code">
                            </div>
                             <div class="col-md-4 form-group">
                                        <label for="whours">Working Hours</label>
                                        <input type="text" class="form-control" id="whours" name="whours" placeholder="HH:MM">
                                    </div>
                            </div>                            
                        </div>
                    </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary" id="teacherform">Submit</button>&nbsp;&nbsp;
                        <a href="<?php wpsp_admin_url();?>sch-teacher" class="btn btn-info">Back</a>
                    </div>
                </form>
             

            </div>
        </div>
    </div>
</section>