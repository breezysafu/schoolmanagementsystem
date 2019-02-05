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

            if ($current_user_role == 'administrator' || $current_user_role == 'teacher') {

                wpsp_topbar();

                wpsp_sidebar();

                wpsp_body_start();

                $class_table=$wpdb->prefix."wpsp_class";

                $leave_table=$wpdb->prefix."wpsp_leavedays";

                $class_r=$wpdb->get_results("select cid,c_name,c_sdate,c_edate from $class_table");

                $classes=array();

                foreach($class_r as $classinfo){

                    $classes[ $classinfo->cid]=array('c_name'=>$classinfo->c_name,'c_sdate'=>$classinfo->c_sdate,'c_edate'=>$classinfo->c_edate);

                }

                if(isset($_POST['submit']) && sanitize_text_field($_POST['submit'])=='Save Dates'){
					

                    $class_id	=	intval($_POST['ClassID']);

					if( $class_id!='' )

						$check		=	$wpdb->get_row("select * from $leave_table where class_id=$class_id");

                    if(!is_numeric($class_id)){

                        echo "<div class='col-md-12 red off-dates'>Select class to generate weekly off dates</div>";

                    }else if(!empty($check)){

                        echo "<div class='col-md-12 red off-dates'>Dates were already generated for <span class='text-blue'>".$classes[$class_id]['c_name']."</span> please delete or use add option to add leave dates</div>";

                    }else if(sanitize_text_field($_POST['from'])=='' || sanitize_text_field($_POST['to'])=='') {

                        echo "<div class='col-md-12 red off-dates'>Enter valid  <span class='text-blue'> school year</span> dates before trying!</div>";

                    }else{

                        $strDateFrom=date('Y-m-d',strtotime($_POST['from']));

                        $strDateTo=date('Y-m-d',strtotime($_POST['to']));

                        $check_class=$wpdb->get_row("select c_sdate,c_edate from $class_table where cid=$class_id");

                        if($check_class->c_sdate =='' || $check_class->c_sdate ==NULL){

                            $wpdb->update($class_table,array('c_sdate'=>$strDateFrom),array('cid'=>$class_id));

                        }

                        if($check_class->c_edate =='' || $check_class->c_edate ==NULL){

                            $wpdb->update($class_table,array('c_edate'=>$strDateTo),array('cid'=>$class_id));

                        }

                        if(sanitize_text_field($_POST['weeklyoff'])=='0'){

                            $weeklyoff=array('Saturday','Sunday');

                        }else{

                            $weeklyoff=array(sanitize_text_field($_POST['weeklyoff']));

                        }

                        $leave_dates=wpsp_leaveDates($strDateFrom,$strDateTo,$weeklyoff);

                        foreach($leave_dates as $ldate){
					        $leave_table_data = array('class_id'=>intval($_POST['ClassID']),'leave_date'=>$ldate,'description'=>'Weekend');	
                            $dt_ins=$wpdb->insert($leave_table,$leave_table_data);

                        }

                    }



                }

                ?>

                <section class="content-header">

                    <h1>Leave Days</h1>

                    <ol class="breadcrumb">

                        <li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>

                        <li><a href="<?php wpsp_admin_url();?>sch-leavecalendar">Leave Calendar</a></li>

                </section>

                <section class="content">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="box box-info">
                                <div class="box-header ui-sortable-handle" style="cursor: move;">
                                    <i class="fa fa-graph"></i>
                                    <h3 class="box-title"><i class="fa fa-calendar" aria-hidden="true"></i> Generate Leave days for a class  </h3>
                                    <!-- tools box -->
                                  
                                    <!-- /. tools -->
                                </div>


                                <div class="box-body" id="addLeaveDaysBody">

                                    <div class="col-md-6">

                                        <form action="" name="leaveDaysForm" id="leaveDaysForm" method="post">

                                            <div class="form-group">

                                                <label>Class<span class="red">*</span></label>

                                                <select name="ClassID" id="ClassID" class="form-control">

                                                    <option value="">Select Class</option>

                                                    <?php foreach($classes as $cid=>$cname){ ?>

                                                       <option value="<?php echo $cid;?>"><?php echo $cname['c_name'];?></option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <div class="form-group display-none">

                                                <label>Class Year<span class="red">*</span></label>

                                                <div class="col-md-12 PLRZero">

                                                    <div class="row">

                                                        <div class="col-md-6">Start Date<span class="red">*</span><input type="text" name="from" id="CSDate" class="form-control select_date"></div>

                                                        <div class="col-md-6">End Date<span class="red">*</span><input type="text" name="to" id="CEDate" class="form-control select_date"></div>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="clearfix"></div>

                                            <div class="form-group MTTen">

                                                <label>Weekly off<span class="red">*</span></label>

                                                <select name="weeklyoff" class="form-control">

                                                    <option value="0">All Saturday and Sunday</option>

                                                    <option value="Monday">Monday</option>

                                                    <option value="Tuesday">Tuesday</option>

                                                    <option value="Wednesday">Wednesday</option>

                                                    <option value="Thursday">Thursday</option>

                                                    <option value="Friday">Friday</option>

                                                    <option value="Saturday">Saturday</option>

                                                    <option value="Sunday">Sunday</option>

                                                </select>

                                            </div>

                                            <div class="clearfix"></div>

                                            <div class="form-group MTTen">

                                                <input type="submit" name="submit" class="btn btn-primary" value="Save Dates">

                                            </div>

                                        </form>

                                    </div>

                                    <div class="col-md-6 MTTen">

                                        <ul class="text-gray">

                                            <li>Use this form only once for a class to generate all weekly off dates</li>

                                            <li>All these dates are excluded for attendance calculation</li>

                                            <li>You can delete any date if you have a school on that date</li>

                                            <li>This is for attendance calculation purpose only</li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

					 <div class="row">

                        <div class="col-md-12 ">

                            <?php

                            $leaves=$wpdb->get_results("select class_id,description,count(*) as numleaves from $leave_table group by class_id");

                            ?>

                            <div class="box box-info">
                                    <div class="box-header ui-sortable-handle" style="cursor: move;">
                                    <i class="fa fa-graph"></i>
                                    <h3 class="box-title"><i class="fa fa-calendar" aria-hidden="true"></i> List of Leave days for a class  </h3>
                                    <!-- tools box -->
                                  
                                    <!-- /. tools -->
                                </div>

								<div class="box-body">
                                <div class="col-md-12 table-responsive">
                                <table class="table table-bordered " id="wpsp_leave_days">

                                    <thead>

                                    <tr>

										<th>Class</th>

										<th>School Year</th>

										<th>Number of leave days</th>

										<th class="nosort">Action</th>

									</tr>

                                    </thead>

                                    <tbody>

                                    <?php foreach($leaves as $leave){ 
 if($classes[$leave->class_id]['c_name'] == ""){ }else{ ?>
                                        <tr>

                                            <td><?php echo $classes[$leave->class_id]['c_name'];?></td>

                                            <td>

                                                <?php

                                                echo wpsp_ViewDate($classes[$leave->class_id]['c_sdate']).' to '.wpsp_ViewDate($classes[$leave->class_id]['c_edate']);

                                                ?></td>

                                            <td><?php echo $leave->numleaves-1;?></td>

                                            <td><a href="javascript:;" class="leaveView" data-id="<?php echo intval($leave->class_id);?>"><i class="fa fa-eye btn btn-success gap-bottom-small"></i></a>
                                                <a href="javascript:;" class="leaveAdd" data-id="<?php echo intval($leave->class_id);?>"><i class="fa fa-plus-circle btn btn-primary gap-bottom-small"></i></a>
                                                <a href="javascript:;" class="leaveDelete" data-id="<?php echo intval($leave->class_id);?>"><i class="fa fa-trash btn btn-danger gap-bottom-small"></i></a></td></tr>
                                            

                                    <?php } } ?>

                                    </tbody>

                                </table>
                            </div>

								</div>

                            </div>

                        </div>

                    </div>

                </section>

                <div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="leaveModal" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header" id="leaveModalHeader">

                            </div>

                            <div id="leaveModalBody" class="modal-body"></div>
                            <div class="modal-footer text-right">                           
                            <a data-original-title="Remove this user" type="button" data-dismiss="modal" class="btn btn-sm btn-default">Close</a>
                        </div>
                            



                        </div>

                    </div>

                </div>



                <?php

                wpsp_body_end();

                wpsp_footer();

            }else if($current_user_role == 'parent' || $current_user_role == 'student'){

                wpsp_topbar();

                wpsp_sidebar();

                wpsp_body_start();

                $class_table=$wpdb->prefix."wpsp_class";

                $leave_table=$wpdb->prefix."wpsp_leavedays";

                $class_r=$wpdb->get_results("select cid,c_name,c_sdate,c_edate from $class_table");

                $classes=array();

                foreach($class_r as $classinfo){

                    $classes[ $classinfo->cid]=array('c_name'=>$classinfo->c_name,'c_sdate'=>$classinfo->c_sdate,'c_edate'=>$classinfo->c_edate);

                }

                ?>

                <section class="content-header">

                    <h1>Leave Days</h1>

                    <ol class="breadcrumb">

                        <li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>

                        <li><a href="<?php wpsp_admin_url();?>sch-leavecalendar">Leave Calendar</a></li>

                </section>

                <section class="content">

                    <div class="row">

                        <div class="col-md-12">

                            <?php

                            $leaves=$wpdb->get_results("select class_id,description,count(*) as numleaves from $leave_table group by class_id");

                            ?>

                            <div class="box box-info">
                                   <div class="box-header ui-sortable-handle" style="cursor: move;">
                                    <i class="fa fa-graph"></i>
                                    <h3 class="box-title"><i class="fa fa-calendar" aria-hidden="true"></i> Leave Calender  </h3>
                                    <!-- tools box -->
                                  
                                    <!-- /. tools -->
                                </div>

								<div class="box-body">
                                    <div class="col-md-12 table-responsive">
                                <table class="table table-bordered table-striped table-responsive" style="margin-top:10px" id="wpsp_leave_days">

                                    <thead>

                                    <tr>

										<th>Class</th>

										<th>School Year</th>

										<th>Number of leave days</th>

										<th class="nosort">Action</th>

									</tr>

                                    </thead>

                                    <tbody>

                                    <?php foreach($leaves as $leave){ ?>

                                        <tr>

                                            <td><?php echo $classes[$leave->class_id]['c_name'];?></td>

                                            <td>

                                                <?php

                                                echo wpsp_ViewDate($classes[$leave->class_id]['c_sdate']).' to '.wpsp_ViewDate($classes[$leave->class_id]['c_edate']);

                                                ?></td>

                                            <td><?php echo $leave->numleaves-1;?></td>

                                            <td><span><a href="javascript:;" class="leaveView" data-id="<?php echo intval($leave->class_id);?>"><i class="fa fa-eye btn btn-success btn-flat"></i></a></span></td></tr>

                                    <?php } ?>

                                    </tbody>

                                </table>
                            </div>
								</div>

								</div>

                            </div>

                        </div>

                    </div>

                </section>

                <div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="leaveModal" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header" id="leaveModalHeader">

                            </div>

                            <div id="leaveModalBody" class="modal-body">



                            </div>
                             <div class="modal-footer text-right">                           
                            <a data-original-title="Remove this user" type="button" data-dismiss="modal" class="btn btn-sm btn-default">Close</a>
                        </div>

                        </div>

                    </div>

                </div>



                <?php

                wpsp_body_end();

                wpsp_footer();

            }



    }else{

       include_once( WPSP_PLUGIN_PATH .'/includes/wpsp-login.php');

    }

?>