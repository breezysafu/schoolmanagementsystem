<?php
	if ( ! defined( 'ABSPATH' ) ) 
		exit('No Such File');
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
    $dashboard_page				=	array( 'slug' => 'sch-dashboard', 'title' =>'Dashboard' );
    $student_page				=	array( 'slug' => 'sch-student',	'title' =>'Student' );
	$teacher_page				=	array( 'slug' => 'sch-teacher', 'title' =>'Teacher'	);
	$parent_page				=	array( 'slug' => 'sch-parent', 'title' =>'Parent' );
	$class_page					=	array( 'slug' => 'sch-class','title' =>'Class' );
    $attendance_page			=	array( 'slug' => 'sch-attendance', 'title' =>'Attendance' );
    $subject_page				=	array( 'slug' => 'sch-subject',	'title' =>'Subject' );
    $marks_page					= 	array( 'slug' => 'sch-marks','title' =>'Marks');
	$exams_page					=	array( 'slug' => 'sch-exams', 'title' =>'Exams' );
    $events_page				=	array( 'slug' => 'sch-events', 'title' =>'Events' );
    $leave_page					=	array( 'slug' => 'sch-leavecalendar','title' =>'LeaveCalendar' );
	$timetable_page				= 	array( 'slug' => 'sch-timetable', 'title' =>'Timetable'	);
    $notify_page				=	array( 'slug' => 'sch-notify', 'title' =>'Notify' );
	$transport_page				= 	array( 'slug' => 'sch-transport', 'title' =>'Transport'	);
	$settings_page				= 	array( 'slug' => 'sch-settings', 'title' =>'Settings' );
	$importhistory_page			=	array( 'slug' => 'sch-importhistory', 'title' =>'ImportHistory' );
    $messages_page				= 	array( 'slug' => 'sch-messages', 'title' =>'Messages' );
	$teacher_attendance_page	=	array( 'slug' => 'sch-teacherattendance', 'title' =>'TeacherAttendance' );
	$change_password_page		=	array( 'slug' => 'sch-changepassword', 'title' =>'Change Password' );
	$payment_page				=	array( 'slug' => 'sch-payment', 'title' =>'Payment' );
	
 	$teacher_found	=	$student_found	=	$parent_found	=	$class_found	=	$dashboard_found	=	
	$messages_found	=	$exams_found	=	$attendance_found	=	$timetable_found	=	$events_found	=
	$leave_found	=	$subject_found	=	$settings_found	=	$transport_found	=	$marks_found	=
	$sms_found	=	$notify_found =	$importhistory_found	=	$teacher_attendance_found = $change_password = $payment_found = 0;
	
	$pages = get_pages();
	foreach ($pages as $page) { 
		$apage = $page->post_name; 
		switch ( $apage ){ 
			case 'sch-teacher' :   		$teacher_found= '1';			break;			
			case 'sch-student' :   		$student_found= '1';			break;			
			case 'sch-parent' :   		$parent_found= '1';				break;			
			case 'sch-class' :   		$class_found= '1';				break;			
			case 'sch-dashboard' :  	$dashboard_found= '1';			break;			
			case 'sch-messages' :   	$messages_found= '1';			break;
			case 'sch-exams' :   		$exams_found= '1';				break;			
			case 'sch-attendance' :   	$attendance_found= '1';			break;			
			case 'sch-timetable' :   	$timetable_found= '1';			break;
			case 'sch-events' :   		$events_found= '1';				break;
            case 'sch-leavecalendar' :  $leave_found= '1';				break;
            case 'sch-subject' :   		$subject_found= '1';			break;
			case 'sch-settings' :   	$settings_found= '1';			break;			
			case 'sch-transport' :   	$transport_found= '1';			break;
			case 'sch-marks' : 			$marks_found= '1';				break;
			//case 'sch-sms' : 			$sms_found='1';					break;
			case 'sch-notify' : 		$notify_found='1';				break;
			case 'sch-importhistory' : 	$importhistory_found='1';		break;
            case 'sch-teacherattendance' : 	$teacher_attendance_found='1';		break;
            case 'sch-changepassword' : 	$change_password='1';		break;
            case 'sch-payment' 		: 		$payment_found='1';		break;
            
			default:					$no_page;			
		}		
	}
	if( $teacher_attendance_found !='1' ){
		$teacher_attendance_found_data = array('post_title'	=> $teacher_attendance_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$teacher_attendance_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Teacher Attendance' );
        $page_id = wp_insert_post($teacher_attendance_found_data);
    }
    
	if( $marks_found != '1' ){
		$marks_found_data = array('post_title'	=> $marks_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$marks_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Student marks are entered and viewed ! ' );
		$page_id = wp_insert_post($marks_found_data);
	}
	if( $teacher_found != '1' ){
		$teacher_found_data = array('post_title'	=> $teacher_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$teacher_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Teacher profile and author page details page ! ' );
		$page_id = wp_insert_post($teacher_found_data);	
	}
	if( $transport_found != '1' ){
		$transport_found_data = array('post_title'	=> $transport_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$transport_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Transport details page ! ' );
		$page_id = wp_insert_post($transport_found_data);	
	}
	
	if( $dashboard_found != '1' ){
		$dashboard_found_data = array('post_title'	=> $dashboard_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$dashboard_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Dashboard contains all the overview ! ' );
		$page_id = wp_insert_post($dashboard_found_data);
	}	
	
	if( $student_found != '1' ){
		$student_found_data = array('post_title'	=> $student_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$student_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Student profile and author page details page ! ' );
		$page_id	=	wp_insert_post($student_found_data);	
	}
	
	if ($parent_found != '1') {
		$parent_found_data = array('post_title'	=> $parent_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$parent_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Parent profile and author page details page ! ' );
	    $page_id = wp_insert_post($parent_found_data);
	}
	if ($class_found != '1') {
		$class_found_data = array('post_title'	=> $class_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$class_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Class details page ! ' );
	    $page_id = wp_insert_post($class_found_data);
	}
	if ($settings_found != '1') {
		$settings_found_data = array('post_title'	=> $settings_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$settings_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Settings page ! ' );
	    $page_id = wp_insert_post($settings_found_data);
	}
	if ($subject_found != '1') {
		$subject_found_data = array('post_title'	=> $subject_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$subject_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Subject details page ! ' );
	    $page_id = wp_insert_post($subject_found_data);
	}
	if ($events_found != '1') {
		$events_found_data = array('post_title'	=> $events_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$events_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'School Events page ! ' );
	    $page_id = wp_insert_post($events_found_data);
	}
	if ($timetable_found != '1') {
		$timetable_found_data = array('post_title'	=> $timetable_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$timetable_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Academic daily Timetable ! ' );
	    $page_id = wp_insert_post($timetable_found_data);
	}
	if ($attendance_found != '1') {
		$attendance_found_data = array('post_title'	=> $attendance_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$attendance_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Student attendance page ! ' );
	    $page_id = wp_insert_post($attendance_found_data);
	}
	if ($exams_found != '1') {
		$exams_found_data = array('post_title'	=> $exams_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$exams_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Exam details page ! ' );
	    $page_id = wp_insert_post($exams_found_data);
	}
	if ($messages_found != '1') {
		$messages_found_data = array('post_title'	=> $messages_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$messages_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Messages page ! ' );
	    $page_id = wp_insert_post($messages_found_data);
	}
	if ($notify_found != '1') {
		$notify_found_data = array('post_title'	=>	$notify_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$notify_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Send notification page ! ' );
	    $page_id = wp_insert_post($notify_found_data);
	}
	if ($importhistory_found != '1') {
		
		$importhistory_found_data = array('post_title'	=>	$importhistory_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$importhistory_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Import history page ! ' );
	    $page_id = wp_insert_post($importhistory_found_data);
	}
	if ($leave_found != 1) {
		$leave_found_data = array('post_title'	=>	$leave_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$leave_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Leave calendar page ! ' );
	    $page_id = wp_insert_post($leave_found_data);
	}
	if ( $change_password != 1) {
		
		$change_password_data = array('post_title'	=>	$change_password_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$change_password_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Change Password' );
	    $page_id = wp_insert_post($change_password_data);
	}
	if ( $payment_found != 1) {
		
		$payment_data = array('post_title'	=>	$payment_page['title'],
								'post_type'		=>	'page',
								'post_name'	=>	$payment_page['slug'],
								'post_status'	=>	'publish',
								'post_excerpt'	=>	'Fees' );
	    $page_id = wp_insert_post($payment_data);
	}
		
	global $wpdb;
	$teacher_table            = $wpdb->prefix . 'wpsp_teacher';
	$student_table            = $wpdb->prefix . 'wpsp_student';
	$class_table              = $wpdb->prefix . 'wpsp_class';	
	$exams_table              = $wpdb->prefix . 'wpsp_exam';
	$mark_fields_table        = $wpdb->prefix . 'wpsp_mark_fields';
	$mark_table               = $wpdb->prefix . 'wpsp_mark';
	$mark_extract_table       = $wpdb->prefix . 'wpsp_mark_extract';
	$messages_table           = $wpdb->prefix . 'wpsp_messages';
	$time_table               = $wpdb->prefix . 'wpsp_timetable';
	$notification_table       = $wpdb->prefix . 'wpsp_notification';
	$subject_table            = $wpdb->prefix . 'wpsp_subject';
	$workinghours_table       = $wpdb->prefix . 'wpsp_workinghours';
	$transport_table          = $wpdb->prefix . 'wpsp_transport';
	$settings_table           = $wpdb->prefix . 'wpsp_settings';
	$attendance_table         = $wpdb->prefix . 'wpsp_attendance';
	$teacher_attendance_table = $wpdb->prefix . 'wpsp_teacher_attendance';
	$events_table             = $wpdb->prefix . 'wpsp_events';
	$grade_settings_table     = $wpdb->prefix . 'wpsp_grade';
	$import_history_table     = $wpdb->prefix . 'wpsp_import_history';
	$leave_table              = $wpdb->prefix . 'wpsp_leavedays';
        $sql_teacher_attendance_table="CREATE TABLE IF NOT EXISTS $teacher_attendance_table (
                  `id` bigint(11) NOT NULL AUTO_INCREMENT,
                  `teacher_id` bigint(11) NOT NULL,
                  `status` VARCHAR(10),
                  `leave_date` date,
                  `reason` VARCHAR(250),
                  `entry_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1" ;
        dbDelta($sql_teacher_attendance_table);
        $sql_leave_table = "CREATE TABLE IF NOT EXISTS $leave_table (
                  `id` bigint(11) NOT NULL AUTO_INCREMENT,
                  `class_id` int(11) NOT NULL,
                  `leave_date` date,
                  `description` VARCHAR(150),
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1" ;
        dbDelta($sql_leave_table);
		$sql_import_history = "CREATE TABLE IF NOT EXISTS $import_history_table (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `type` int(1) NOT NULL,
		  `imported_id` longtext NOT NULL,
		  `time` datetime NOT NULL,
		  `count` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1" ;
		dbDelta($sql_import_history);
		
		$sql_grade = "CREATE TABLE IF NOT EXISTS $grade_settings_table  (
	  `gid` int(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,	  
	  `g_name` varchar(60),
	  `g_point` varchar(5),
	  `mark_from` int(3),
	  `mark_upto` int(3),
	  `comment` varchar(60) 
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";
	dbDelta($sql_grade);
	$sql_events_table = "CREATE TABLE IF NOT EXISTS $events_table  (
	  `id` bigint(15)  UNSIGNED NOT NULL AUTO_INCREMENT,	  
	  `start` varchar(50) DEFAULT NULL,
      `end` varchar(50) DEFAULT NULL,
      `type` varchar(10) DEFAULT NULL,
      `title` text,
      `description` longtext,
      `color` varchar(20) DEFAULT NULL,     
       PRIMARY KEY (`id`)
     ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1" ;		
	dbDelta($sql_events_table);
	
	
	$sql_attendance_table = "CREATE TABLE IF NOT EXISTS $attendance_table  (
	  `aid` int(15)  UNSIGNED NOT NULL AUTO_INCREMENT, 
	  `class_id` varchar(15) DEFAULT NULL,
	  `absents` text DEFAULT NULL,
	  `date` date,
      `entry` timestamp,
       PRIMARY KEY (`aid`)
     ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1" ;
		
	dbDelta($sql_attendance_table);
	
	$sql_settings_table = "CREATE TABLE $settings_table  (
	  `id` int(15)  UNSIGNED NOT NULL AUTO_INCREMENT,	  
	  `option_name` varchar(50) DEFAULT NULL,
      `option_value` text DEFAULT NULL,
       PRIMARY KEY (`id`)
     ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1" ;
		
	dbDelta($sql_settings_table);
		
	$sql_transport_table = "CREATE TABLE $transport_table  (
	  `id` int(15)  UNSIGNED NOT NULL AUTO_INCREMENT,	  
	  `bus_no` varchar(30) DEFAULT NULL,
      `bus_name` varchar(50) DEFAULT NULL,
      `driver_name` varchar(50) DEFAULT NULL,
      `bus_route` mediumtext DEFAULT NULL,
	  `route_fees` varchar(5) DEFAULT NULL,
      `phone_no` varchar(50) DEFAULT NULL,
       PRIMARY KEY (`id`)
     ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1" ;
		
	dbDelta($sql_transport_table);
	
	$sql_time_table = "CREATE TABLE IF NOT EXISTS $time_table  (
	  `id` int(15)  UNSIGNED NOT NULL AUTO_INCREMENT,	  
	  `class_id` int(10) NOT NULL,
      `time_id` int(10) NOT NULL,
      `subject_id` int(10) NOT NULL,
      `day` int(2) NOT NULL,
      `heading` text DEFAULT NULL,
      PRIMARY KEY (`id`)
     ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1" ;
	dbDelta($sql_time_table);
	
	$sql_workinghours_table="CREATE TABLE IF NOT EXISTS `$workinghours_table` (
	  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
	  `hour` varchar(20) DEFAULT NULL,
	  `begintime` VARCHAR(10) NOT NULL,
	  `endtime` VARCHAR(10) NOT NULL,
	  `type` varchar(20) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_workinghours_table);
	
	$sql_subject = "CREATE TABLE IF NOT EXISTS $subject_table  (
	  `id` int(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,	  
	  `sub_code` varchar(8),
	  `class_id` int(15),
	  `sub_name` varchar(60),
	  `sub_teach_id` varchar(15),
	  `book_name` varchar(60),
	  `sub_desc` varchar(250),
	  `max_mark` int(4),
	  `pass_mark` int(4)
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	dbDelta($sql_subject);
	$sql_notification = "CREATE TABLE IF NOT EXISTS $notification_table  (
	  `nid` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,	  
	  `name` varchar(50),
	  `description` varchar(255),	 
	  `receiver` varchar(25),	 
	  `type` int(11),	 
	  `date` datetime,
	  `status` int(11)
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_notification);
	
	$sql_message = "CREATE TABLE IF NOT EXISTS $messages_table  (
	  `mid` int(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,	  
	  `s_id` int(15),
	  `r_id` int(15),
	  `subject` varchar(250),	 	  
	  `msg` longtext,	 
	  `del_stat` int(15),	
	  `m_date` timestamp
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_message);
	$sql_mark_fields = "CREATE TABLE IF NOT EXISTS $mark_fields_table  (
	  `field_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,	  
	  `subject_id` int(12),
	  `field_text` varchar(60)	  
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_mark_fields);
	$sql_mark = "CREATE TABLE IF NOT EXISTS $mark_table  (
	  `mid` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,	  
	  `subject_id` varchar(128),
	  `class_id` int(15),	 
	  `student_id` int(15),	 
	  `exam_id` int(15),	 
	  `mark` varchar(60) ,
	  `attendance` varchar(60) 
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_mark);
	$sql_mark_extract = "CREATE TABLE IF NOT EXISTS $mark_extract_table  (
	  `id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,	
	  `student_id` bigint(20),
	  `field_id` bigint(20),
	  `exam_id` int(12),
	  `subject_id` int(12),	 
	  `mark` varchar(10)
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_mark_extract);
	
	$sql_exam = "CREATE TABLE $exams_table  (
	  `eid` int(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `classid` int(15),
	  `subject_id` varchar(128),
	  `e_name` varchar(128),	 
	  `e_s_date` date,	 
	  `e_e_date` date,
	  `entry_date` timestamp
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_exam);
	
		
	$sql_class = "CREATE TABLE $class_table  (
	  `cid` int(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,	  
	  `c_numb` varchar(128),
	  `c_name` varchar(128),
	  `teacher_id` int(15),
	  `c_capacity` int(5),
	  `c_loc` varchar(60),
	   `c_sdate` date,	 
	  `c_edate` date	
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_class);
	
	
$sql_student = "CREATE TABLE $student_table  (
	  `sid` int(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `wp_usr_id` bigint(20),
	  `parent_wp_usr_id` int(15),
	  `s_rollno` varchar(15),	  
	  `s_fname` varchar(30),
	  `s_mname` varchar(30),
	  `s_lname` varchar(30),
	  `s_dob` date,
	  `s_gender` varchar(10),
	  `s_address` varchar(200),
	  `s_paddress` varchar(200),
	  `s_country` varchar(20),
	  `s_zipcode` varchar(10),
	  `s_phone` varchar(25),
	  `s_bloodgrp` varchar(20),
	  `s_doj` date,
	  `class_id` int(10),
	  `s_pzipcode` varchar(10),
	  `s_pcountry` varchar(20),	  
	  `s_city` varchar(20),
	  `s_pcity` varchar(20),
	  `p_fname` varchar(30),
	  `p_mname` varchar(30), 
	  `p_lname` varchar(30), 
	  `p_gender` varchar(10),
	  `p_edu` varchar(50),
	  `p_phone` varchar(25), 
	  `p_profession` varchar(60),
	  `p_bloodgrp` varchar(10)
	  )ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_student);
	
	$sql_teacher = "CREATE TABLE $teacher_table  (
	  `tid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `wp_usr_id` bigint(20),	
	  `first_name` varchar(30),
	  `middle_name` varchar(30),
	  `last_name` varchar(30),
	  `zipcode` varchar(10),
	  `country` varchar(20),
	  `city` varchar(20),
	  `address` varchar(200),
	  `empcode` varchar(60),
	  `dob` date,
	  `doj` date,
	  `dol` date,
	  `phone` varchar(25), 
	  `qualification` varchar(25),	  
	  `gender` varchar(12),
	  `bloodgrp` varchar(5),
	  `position` varchar(50),
	  `whours` varchar(2)	  
	)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	dbDelta($sql_teacher);
		
	//Duration after how many times it occurs
	//due time  after that it consider as due date	
	$sql_fees = $wpdb->prefix . 'wpsp_fees'; 
	$sql = "CREATE TABLE IF NOT EXISTS ".$sql_fees." (			  
			  `fees_id` int(11) NOT NULL AUTO_INCREMENT,			  
			  `class_id` int(11) NOT NULL,
			  `student_id` int(11) NOT NULL,
			  `fees_amount` float NOT NULL,
			  `description` text NOT NULL,
			  `duration` text NOT NULL,
			  `paymentType` text NOT NULL,
			  `due_time` int(2) NOT NULL,
			  `start_date` datetime NOT NULL,
			  `end_date` datetime NOT NULL,
			  `created_date` datetime NOT NULL,
			  `created_by` int(11) NOT NULL,
			  PRIMARY KEY (`fees_id`)
			) DEFAULT CHARSET=utf8";
		dbDelta($sql);
			
	$sql_fees_payment = $wpdb->prefix . 'wpsp_fees_payment';
	$sql = "CREATE TABLE IF NOT EXISTS ".$sql_fees_payment." (			  
			  `fees_pay_id` int(11) NOT NULL AUTO_INCREMENT,
			  `class_id` int(11) NOT NULL,
			  `student_id` bigint(20) NOT NULL,
			  `fees_id` int(11) NOT NULL,			  
			  `fees_paid_amount` float NOT NULL,
			  `payment_status` varchar(10) NOT NULL,			  
			  `paid_due_date` date NOT NULL,
			  PRIMARY KEY (`fees_pay_id`)
			) DEFAULT CHARSET=utf8";
		dbDelta($sql);
		// 1 for suceess & 2 for fail
	$table_fee_payment_history = $wpdb->prefix . 'wpsp_fee_payment_history';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_fee_payment_history." (			  
			  `payment_history_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `fees_pay_id` int(11) NOT NULL,
			  `amount` float NOT NULL,
			  `payment_method` varchar(50) NOT NULL,
			  `paid_date` date NOT NULL,
			  `paid_by` bigint(20) NOT NULL,
			  `paid_status` int(2) NOT NULL,
			  `paymentdescription` text NOT NULL,
			  PRIMARY KEY (`payment_history_id`)
			) DEFAULT CHARSET=utf8";
		dbDelta($sql);
		
	global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    $wp_rewrite->flush_rules();
	// Incresing Maximum Time Execution
	
	$wpspmax = "
	# WP Increse Maximum Execution Time
	<IfModule mod_php5.c>
		php_value max_execution_time 300
	</IfModule>";
	$htaccess = get_home_path().'.htaccess';
	$contents = @file_get_contents($htaccess);
	if(!strpos($htaccess,$wpspmax))
	file_put_contents($htaccess,$contents.$wpspmax);
	$wp_roles = new WP_Roles();
	$wp_roles->remove_role('subscriber');
    $wp_roles->remove_role('editor');
    $wp_roles->remove_role('contributor');
    $wp_roles->remove_role('author');
	$wp_roles->remove_role('client');

	
     $teacher_cap = array(
	    'add_student' => true,
	    'upload_mark' => true,
	    'attendance_entry' => true,
		'read' => true,
        'edit_posts'   => true,
        'upload_files' => true
	);
	$student_cap = array( 'send_message' => true,'read' => true,'edit_posts'   => true,'upload_files' => true);
	$parent_cap  = array( 'send_message' => true,'read' => true,'edit_posts'   => true,'upload_files' => true );
	$admin_cap = array( 'action_all' => true );
	$teacher_role_new = $wp_roles->add_role('teacher', __('Teacher' ),$teacher_cap);
	$student_role_new = $wp_roles->add_role('student',__('Student' ),$student_cap);
	$parent_role_new  = $wp_roles->add_role('parent', 'Parent', $parent_cap);
	
	?>