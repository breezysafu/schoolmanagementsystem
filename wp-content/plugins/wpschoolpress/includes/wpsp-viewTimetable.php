<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
function wpsp_ViewTimetable($class_id){
        global $wpdb;
		$tt_table = $wpdb->prefix . "wpsp_timetable";
		$subject_table = $wpdb->prefix . "wpsp_subject";
		$wpsp_hours_table = $wpdb->prefix . "wpsp_workinghours";
       // echo "SELECT * from $tt_table where class_id=$class_id and heading!=''";
        $get_heading = $wpdb->get_row("SELECT * from $tt_table where class_id=$class_id and heading!=''");
		$response	=	array();
		ob_start();
        if ($get_heading == null) {
            echo '<section class="content">				
					<div class="alert alert-danger">Timetable Has Not Created For This Class.</div>				
				</section>';			
			$response['status']	=	1;
        } else {
			$response['status']	=	2;
            $session = unserialize($get_heading->heading);
            $tt_days=$wpdb->get_results("select * from $tt_table where class_id='$class_id'",ARRAY_A);
            foreach($tt_days as $ttd){
                $timetable[$ttd['day']][$ttd['time_id']]=$ttd['subject_id'];
            }
            $ses_info = $wpdb->get_results("Select * from $wpsp_hours_table");
            foreach($ses_info as $time){
                $period_times[$time->id]=array('start'=>$time->begintime,'end'=>$time->endtime,'type'=>$time->type);
            }			
            ?>
           
            <section class="content">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-calendar" aria-hidden="true"></i> Time Table </h3>
                </div>
                <div class="box box-info box-body table-responsive">
                    <table class="table table-bordered" id="timetable_table">
                        <thead>
                            <tr>
                                <th><select class="daytype"><option value="0">Days</option><option value="1">Week</option></select></th>
                                <?php
                                foreach ($session as $sess) {
                                ?>
                                    <th><?php echo isset( $period_times[$sess]['start'] ) ? $period_times[$sess]['start'] :'';
											  echo " - " ;
											echo isset( $period_times[$sess]['end'] ) ? $period_times[$sess]['end'] :''; ?></th>
                                    <?php
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $week_days=array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday');
                            for ($j = 1; $j <= 7; $j++) {
                                if(empty($timetable[$j])){
                                    continue;
                                }
                            ?>
                                <tr id="<?php echo $j; ?>">
                                    <td> <span class="dayval">Day <?php echo $j; ?></span><span class="daynam" style="display: none"><?php echo $week_days[$j];?></span></td>
                                    <?php
                                        foreach ($session as $ses) {
                                            $ses_type = isset( $period_times[$ses]['type'] ) ? $period_times[$ses]['type'] :'';
                                            if(isset($timetable[$j][$ses]))
                                                $sub_id=$timetable[$j][$ses];
                                            else
                                                $sub_id='';
                                            if ($sub_id == '') {
                                                if($ses_type==0)
                                                    $sub_name = '<i>Break</i>';
                                                else
                                                    $sub_name='-';
                                            } else {												
                                                $sub_name_f = $wpdb->get_row("SELECT sub_name from $subject_table where id=$sub_id");
                                                $sub_name = isset( $sub_name_f->sub_name ) ? $sub_name_f->sub_name : 'N/A';
                                            }
                                            ?>
                                                <td tid="<?php echo $ses; ?>"> <?php echo $sub_name; ?> </td>
                                                <?php
                                        } ?>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <?php
       }
	   $response['msg']	=	ob_get_clean();
	   return $response;
}?>