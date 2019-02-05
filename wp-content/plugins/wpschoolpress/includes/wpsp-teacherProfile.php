<?php if (!defined( 'ABSPATH' ) )exit('No Such File');
    $teacher_table=$wpdb->prefix."wpsp_teacher";
    $class_table=$wpdb->prefix."wpsp_class";
    $users_table=$wpdb->prefix."users";
    $tid=intval($_GET['id']);
	$msg =	'';
    if(isset($_GET['edit']) && sanitize_text_field($_GET['edit'])=='true')
    {
    	if($current_user_role == 'administrator' || ($current_user_role == 'teacher' && sanitize_text_field($current_user->ID) == $tid)){
    		$edit=true;
    	}else{
    		$edit=false;
    	}
    	if (isset( $_POST['tedit_nonce'] ) && wp_verify_nonce( sanitize_text_field($_POST['tedit_nonce']), 'TeacherEdit' )){
			ob_start();
    		wpsp_UpdateTeacher();
			$msg = ob_get_clean();
    	}
    }
    else{
    	$edit=false;
    }
    $tinfo=$wpdb->get_row("select teacher.*,user.user_email from $teacher_table teacher LEFT JOIN $users_table user ON user.ID=teacher.wp_usr_id where teacher.wp_usr_id='$tid'");
    if(!empty($tinfo)){
    ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div class="box box-solid bg-blue-gradient">
                <div class="box-header ui-sortable-handle">
                    <h3 class="box-title"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo $tinfo->first_name.' '.$tinfo->middle_name.' '.$tinfo->last_name;?></h3>
                </div>
				 <div class="col-md-12 gap-top-bottom">
					<div id="formresponse"><?php echo $msg; ?></div>
                </div>
                <div class="box-footer text-black">
                    <div class="">						
                        <?php if($edit) { ?>
                        <form name='TeacherEditForm' id='TeacherEditForm' action='' method='POST' enctype='multipart/form-data'>
                        <?php } ?>
                        <div class="col-md-6 col-lg-6">
						<input type="hidden"  id="wpsp_locationginal" value="<?php echo admin_url();?>"/>
                            <h3 class="box-title"> <i class="fa fa-info" aria-hidden="true"></i>&nbsp;Personal Details</h3>
                            <div class="line_box">
                                <div class="row">
                                    <div class="col-md-12  ">
                                        <div class="col-md-12 text-center">
                                            <?php 
                                                $loc_avatar	=	get_user_meta($tid,'simple_local_avatar',true);
                                                $img_url	=	$loc_avatar ? $loc_avatar['full'] : WPSP_PLUGIN_URL.'img/default_avtar.jpg';
                                                ?>
                                            <img src="<?php echo $img_url;?>" height="150px" width="150px" class="img img-circle"/> </div>
                                            <?php if($edit) { ?>
                                                 <div class="">
                                            <label class="customUpload btnUpload btnM btn btn-primary">
                                                <i class="fa fa-upload" aria-hidden="true"></i> Upload Photo
                                                <input type="file" name="displaypicture" class="upload" id="displaypicture">
                                             </label>
                                            <p id="test" style="color:red"></p>
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="row">
                                    <?php 
                                        if($edit)
                                        {
                                        ?>
                                    <div class="col-md-4 form-group">
                                        <label for="firstname">First Name <?php if( $edit ) {?><span class="red">*</span><?php }?></label>
                                        <input type="text" class="form-control" id="first_name" name="firstname" value="<?php echo $tinfo->first_name ; ?>" placeholder="First Name">
                                        <?php wp_nonce_field( 'TeacherEdit', 'tedit_nonce', '', true ) ?>
                                        <input type="hidden" id="UserID" name="UserID" value="<?php echo $tinfo->wp_usr_id; ?>">
                                        <input type="hidden"  id="wpsp_locationginal" value="<?php echo admin_url();?>"/>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" class="form-control" id="name" name="middlename" value="<?php echo $tinfo->middle_name ;?>" placeholder="Middle Name">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="lastname">Last Name <?php if( $edit ) {?><span class="red">*</span><?php }?></span></label>
                                        <input type="text" class="form-control" id="name" name="lastname" value="<?php echo $tinfo->last_name; ?>" placeholder="Last Name">
                                    </div>
                                    <?php
                                        } ?>
                                    <div class="form-group col-md-4">
                                        <label for="dateofbirth">Date of Birth</label>
                                        <?php 
                                            if($edit){
                                            ?>
                                        <input type="text" class="form-control select_date datepicker" value="<?php echo wpsp_viewDate($tinfo->dob); ?>" id="Dob" name="Dob" placeholder="Date of Birth">
                                        <?php } else 
                                            echo wpsp_viewDate($tinfo->dob);
                                            ?> 
                                    </div>
                                    <div class="form-group col-md-8">
                                        <div class="wpsp-gender-field">
                                            <label for="gender">Gender</label></br>
                                            <?php 
                                                if($edit)
                                                {
                                           ?>
                                            <div class="radio-inline">
                                                <input type="radio" name="Gender" <?php if($tinfo->gender=='Male') echo "checked";?> value="Male">
                                                <label for="Male">Male</label>
                                            </div>
                                            <div class="radio-inline">
                                                <input type="radio" name="Gender" <?php if($tinfo->gender=='Female') echo "checked";?> value="Female">
                                                <label for="Female">Female</label>
                                            </div>
                                            <div class="radio-inline">
                                                <input type="radio" name="Gender" <?php if($tinfo->gender=='other') echo "checked";?> value="other">
                                                <label for="other">Other</label>
                                            </div>
                                            <?php
                                                }
                                                else
                                                	echo $tinfo->gender;
                                                ?> 
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="Email">Email Address</label><span class="red">*</span>
                                        <?php if($edit) { ?>
                                        <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $tinfo->user_email; ?>" placeholder="Teacher Email">
                                        <?php } else echo $tinfo->user_email; ?> 
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="address">Current Address<span class="red">*</label>
                                        <?php if($edit) { ?>
                                        <textarea name="Address" class="form-control" rows="1"><?php echo $tinfo->address; ?></textarea>
                                        <?php } else  echo $tinfo->address; ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="CityName">City Name</label>
                                        <?php if($edit) { ?>
                                        <input type="text" class="form-control" id="CityName" name="city" placeholder="City Name" value="<?php echo $tinfo->city;?>">
                                        <?php } else  echo $tinfo->city; ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="Country">Country</label>
                                        <?php if($edit) { $countrylist = wpsp_county_list(); ?>
                                        <select class="form-control" id="Country" name="country">
                                            <option value="">Select Country</option>
                                            <?php 
                                                foreach( $countrylist as $key=>$value ) { ?>
                                            <option value="<?php echo $value;?>" <?php echo selected( $tinfo->country, $value ); ?>><?php echo $value;?></option>
                                            <?php	
                                                }
                                                ?>
                                        </select>
                                        <?php } else echo $stinfo->country; ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="Zip Code">Zip Code</label>
                                        <?php if($edit) { ?>
                                        <input type="text" name="zipcode" class="form-control" value="<?php echo $tinfo->zipcode; ?>">
                                        <?php } else  echo $stinfo->zipcode;
                                            ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="Phone">Phone Number</label>
                                        <?php if($edit) { ?>
                                        <input type="text" class="form-control" id="Phone" name="Phone" value="<?php echo $tinfo->phone; ?>" placeholder="Phone Number">
                                        <?php } else echo $tinfo->phone; ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="Blood">Blood Group</label>
                                        <?php	if($edit) { ?>
                                        <select class="form-control" id="Bloodgroup" name="Bloodgroup">
                                            <option value="">Select Blood Group</option>
                                            <option <?php if($tinfo->bloodgrp=='O+') echo "selected"; ?> value="O+">O +</option>
                                            <option <?php if($tinfo->bloodgrp=='O-') echo "selected"; ?> value="O-">O -</option>
                                            <option <?php if($tinfo->bloodgrp=='A+') echo "selected"; ?> value="A+">A +</option>
                                            <option <?php if($tinfo->bloodgrp=='A-') echo "selected"; ?> value="A-">A -</option>
                                            <option <?php if($tinfo->bloodgrp=='B+') echo "selected"; ?> value="B+">B +</option>
                                            <option <?php if($tinfo->bloodgrp=='B-') echo "selected"; ?> value="B-">B -</option>
                                            <option <?php if($tinfo->bloodgrp=='AB+') echo "selected"; ?> value="AB+">AB +</option>
                                            <option <?php if($tinfo->bloodgrp=='AB-') echo "selected"; ?> value="AB-">AB -</option>
                                        </select>
                                        <?php
                                            }
                                            else
                                            	echo $tinfo->bloodgrp;
                                            ?>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="Qualification">Qualification</label>
                                        <?php if($edit) { ?>
                                        <input type="text" class="form-control" id="Qual" name="Qual" value="<?php echo $tinfo->qualification; ?>" placeholder="Qualification">
                                        <?php } else echo $tinfo->qualification; ?> 
                                    </div>
                                </div>
                                <?php } ?>
                                <!--<a href="#" class="btn btn-primary"><i class="fa fa-envelope"></i> Message</a>-->
                            </div>
                        </div>
                        <div class=" col-md-6">
                            <h3 class="box-title"> <i class="fa fa-building" aria-hidden="true"></i>&nbsp;School Details</h3>
                            <div class="line_box">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="Join">Joining Date (mm/dd/yyyy)</label>
                                        <?php  if($edit) {
                                          ?>
                                        <input type="text" class="form-control select_date" value="<?php if(wpsp_viewDate($tinfo->doj) == "0000-00-00"){ echo "00-00-0000";}else { echo wpsp_viewDate($tinfo->doj);} ?>" id="Doj" name="Doj" placeholder="Date of Join">
                                        <?php } else echo wpsp_viewDate($tinfo->doj); ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="Releaving">Leaving Date (mm/dd/yyyy)</label>
                                        <?php  if($edit) { ?>
                                        <input type="text" class="form-control select_date" value="<?php if(wpsp_viewDate($tinfo->dol) == "0000-00-00"){ echo "00-00-0000";}else { echo wpsp_viewDate($tinfo->dol);} ?>" id="Dol" name="dol" placeholder="Date of Leave">
                                        <?php } else echo wpsp_viewDate($tinfo->dol); ?>
                                        
                                    </div>
                                
                               
                                <div class="form-group col-md-4">
                                    <label for="Position">Current Position</label>
                                    <?php 
                                        if($edit){
                                        ?>
                                    <input type="text" class="form-control" id="Position" name="Position" value="<?php echo $tinfo->position; ?>" placeholder="Designation">
                                    <?php } else 
                                        echo $tinfo->position;
                                        ?> 
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Employee">Employee Code</label>
                                    <?php 
                                        if($edit){
                                        	if($current_user_role=='administrator'){                                        
                                        ?>
                                    <input type="text" class="form-control" id="Empcode" name="Empcode" value="<?php echo $tinfo->empcode; ?>" placeholder="Employee Code">
                                    <?php } } else
                                        echo $tinfo->empcode;
                                        ?> 
                                </div>
                                 <div class="form-group col-md-4">
                                    <label for="Working">Working Hours</label>
                                    <?php  if($edit) { ?>
                                    <input type="text" name="whours" class="form-control" id="whours" value="<?php echo $tinfo->whours; ?>" placeholder="HH:MM">
                                    <?php } else echo $tinfo->whours; ?>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-right">
                    <?php 
                        if($edit)
                        { ?> 
                        	<button type='submit' id="u_teacher" class='btn btn-success'>Update</button>
                        	<a href='<?php wpsp_admin_url();?>sch-teacher' class='btn btn-info'>Back</a>
                        	</form>
                     <?php   }
                        else {
                        ?>
                    <a href="?id=<?php echo $tinfo->wp_usr_id; ?>&edit=true" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                    <a data-original-title="Remove this user" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } 
    else
    {
    	echo "Sorry!No data retrived";
    }
    ?>