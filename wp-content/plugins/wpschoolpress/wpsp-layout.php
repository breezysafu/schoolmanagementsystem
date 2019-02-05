<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
function wpsp_customcss(){
	  global $current_user, $wp_roles, $current_user_name;
  $current_user_role=$current_user->roles[0];
	if($current_user_role=='administrator'){
		echo "<style>
    .owncls {
      display:none !important;
    } 
	.content-wrapper, .right-side, .main-footer{
		margin-left:0px;
	}
	.ifnotadmin
	{
      display:none !important;
    } 
    #wpfooter{position: relative !important;}
  </style>";
		
	}else {
	echo "<style>
    .update-nag {
      display:none !important;
    } 
	#wpadminbar{display:none !important;} 
	#adminmenumain{display:none !important;}	
	#wpcontent, #wpfooter{margin-left: 0;}
	#wpcontent{padding-left:0px;}
    #wpfooter{position: relative !important;}
    html.wp-toolbar{ padding-top: 0 !important;}
  </style>";
	}
}

function wpsp_header(){
    echo "<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>
        <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
    <![endif]-->";
    echo "</head>";
  wpsp_customcss();
}


  function wpsp_topbar()
{
     global $current_user, $wpdb, $wpsp_settings_data,$post,$current_user_name;	
    $loc_avatar	=	get_user_meta( $current_user->ID,'simple_local_avatar',true);
	$role		=	isset( $current_user->roles[0] ) ? $current_user->roles[0] : '';
    $img_url	=	$loc_avatar ? $loc_avatar['full'] : WPSP_PLUGIN_URL.'img/avatar.png';	
	$schoolname	=	isset( $wpsp_settings_data['sch_name'] ) && !empty( $wpsp_settings_data['sch_name'] ) ? $wpsp_settings_data['sch_name'] : __( 'WPSchoolPress','WPSchoolPress' );
	$imglogo	=	isset( $wpsp_settings_data['sch_logo'] ) ? $wpsp_settings_data['sch_logo'] : '';
	$schoolyear	=	isset( $wpsp_settings_data['sch_wrkingyear'] ) ? $wpsp_settings_data['sch_wrkingyear'] : '';
	$postname	=	isset( $post->post_name ) ? $post->post_name :'';
	$roles		=	$current_user->roles;	
	$query	=	'';
	$current_user_name	=	$current_user->user_login;
	if( in_array( 'teacher', $roles ) ) {
		$table	= $wpdb->prefix."wpsp_teacher";
		$query	=	"SELECT CONCAT_WS(' ', first_name, middle_name, last_name ) AS full_name FROM $table WHERE wp_usr_id=$current_user->ID";
	} else if( in_array( 'student', $roles ) ) {
		$table  = 	$wpdb->prefix."wpsp_student";
		$query	=	"SELECT CONCAT_WS(' ', s_fname, s_mname, s_lname ) AS full_name FROM $table WHERE wp_usr_id=$current_user->ID";
	} else if( in_array( 'parent', $roles ) )	{
		$table  = 	$wpdb->prefix."wpsp_student";
		$query	=	"SELECT CONCAT_WS(' ', p_fname, p_mname, p_lname ) AS full_name FROM $table WHERE parent_wp_usr_id=$current_user->ID";
	}
	if( !empty( $query ) ) {
		$full_name = $wpdb->get_var( $query );
		$current_user_name	=	!empty( $full_name ) ? $full_name : $current_user_name;
	}
	
	
	?>
	<div class="skin-blue sidebar-mini  <?php if($current_user_role=='administrator') {echo "mainadmin";}?><?php echo $postname;?>">
      <header class='main-header ifnotadmin'>
        <!-- Logo -->
          <a href='<?php site_url('wp-admin/admin.php?page=sch-dashboard'); ?>' class='logo'>
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class='logo-mini'>
			<?php if( !empty($imglogo) ) { ?>
				<img src="<?php echo $imglogo; ?>" class="img img-circle school-logo" style="" width="50px" height="50px">
			<?php } ?></span>
          <!-- logo for regular state and mobile devices -->
          <span class='logo-lg'><?php echo $schoolname;?></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class='navbar navbar-static-top' role='navigation'>
          <!-- Sidebar toggle button
          <a href='#' class='sidebar-toggle' data-toggle='offcanvas' role='button'>
            <span class='sr-only'>Toggle navigation</span>
          </a>-->
		  <?php if ( !empty($schoolyear ) ) { ?>
			<button class="btn btn-success gap-academicyear ">Academic year <span class="badge"> <?php echo $schoolyear; ?></span></button>
		  <?php } ?>
          <!-- Navbar Right Menu -->
          <div class='navbar-custom-menu'>
            <ul class='nav navbar-nav'>                  
                 <!-- <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
						<?php $totalMsg	=	wpsp_UnreadCount(); ?>
                        <span class="label label-success"><?php echo $totalMsg; ?></span>
                        </a>                        
                    </li>-->
            <!-- User Account: style can be found in dropdown.less -->
              <li class='dropdown user user-menu' style="margin-bottom: 0;">
                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                  <span class='hidden-xs'>
				  <?php echo $current_user_name;?>
				  <?php //echo $current_user->ID.':'.$current_user->roles[0]; ?>
				  <i class="fa fa-angle-down pull-right" style="margin-top:4px;"></i></span>
                </a>
                <ul class='dropdown-menu'>
                  <!-- User image -->
                  <li class='user-header'>
                    <img src='<?php echo $img_url; ?>' class='img-circle' alt='User Image' />
                    <p><?php echo $current_user_name;?></p>
                  </li>
                  <!-- Menu Footer-->
                  <li class='user-footer'>
				  <?php if( $role == 'administrator' ) { ?>
						<div class='pull-left'>
						  <a href="<?php echo admin_url(); ?> " class='btn btn-primary'>WP Admin</a>
						</div>
				  <?php } ?>
                    <div class='pull-right'>
                      <a href='<?php echo wp_logout_url();?>' class='btn btn-danger'>Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->              
            </ul>
          </div>
        </nav>
      </header>
<?php 
}


function wpsp_sidebar()
{
  global $current_user, $wp_roles, $current_user_name;
  $current_user_role=$current_user->roles[0];
  $page=get_the_title();
  $dashboard_page=$message_page=$student_page=$teacher_page=$parent_page=$class_page=$attendance_page=$subject_page=$mark_page=$exam_page=$event_page=$timetable_page=$import_page=$notify_page=$sms_page=$transport_page=$settings_page=$settings_general_page=$settings_wrkhours_page=$settings_subfield_page=$leave_page=$teacher_attendance_page=$settings_chgpw_page = $viewpayment= $addpayment =$payment_page_main='';
  switch( $page )
  {
    case 'Dashboard':
      $dashboard_page="active";
      break;
    case 'Messages':
      $message_page="active";
       break;
    case 'Student':
      $student_page="active";
      break;
    case 'Teacher':
      $teacher_page="active";
      break;
    case 'Parent':
      $parent_page="active";
      break;
    case 'Class':
      $class_page="active";
      break;
    case 'Attendance':
      $attendance_page="active";
      break;
    case 'Subject':
      $subject_page="active";
      break;
    case 'Exams':
      $exam_page="active";
      break;
	case 'Marks':
      $mark_page="active";
      break;
    case 'ImportHistory':
      $import_page="active";
      break;
	case 'Notify':
      $notify_page="active";
      break;
  case 'Payment':
	  if( isset( $_GET['type'] ) && $_GET['type'] =='addpayment' ) 
		$addpayment="active";
	  else
		$viewpayment="active";
	  $payment_page_main = "class='treeview active'";
      break;
    case 'Events':
      $event_page="active";
      break;
    case 'Transport':
      $transport_page="active";
      break;
    case 'LeaveCalendar':
          $leave_page="active";
          break;
    case 'Timetable' :
        $timetable_page='active';
          break;
	case 'Settings':
		$settings_page="class='treeview active'";
		if(isset($_GET['sc']) && $_GET['sc']=='subField')
			$settings_subfield_page="active";
		else if(isset($_GET['sc']) && $_GET['sc']=='WrkHours')
			$settings_wrkhours_page="active";		
		else
			$settings_general_page="active";
      break;
	case 'ChangePassword' :
        $settings_chgpw_page="active";
        break;
	case 'TeacherAttendance':
		$teacher_attendance_page="active";
		break;
  } 
  
  $loc_avatar=get_user_meta($current_user->ID,'simple_local_avatar',true);
  if( $current_user->ID == 1 )
	$img_url	=	  WPSP_PLUGIN_URL.'img/admin.png';  
 else
	$img_url	=	$loc_avatar ? $loc_avatar['full'] : WPSP_PLUGIN_URL.'img/avatar.png';

  echo "<!-- Left side column. contains the logo and sidebar -->
      <aside class='main-sidebar ifnotadmin'>
        <!-- sidebar: style can be found in sidebar.less -->
        <section class='sidebar'>
          <!-- Sidebar user panel -->
          <div class='user-panel'>
            <div class='pull-left image'> 
      <img src='".$img_url."' class='img-circle' alt='User Image' />
             
            </div>
            <div class='pull-left info'>
              <p>".$current_user_name."</p>
            </div>
          </div>
           <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class='sidebar-menu'>       
            <li class=".$dashboard_page.">
             <a href='".site_url('wp-admin/admin.php?page=sch-dashboard')."'>
                <i class='fa fa-dashboard'></i>
        <span>".__('Dashboard','WPSchoolPress')."</span>
              </a>
            </li>
           <!-- <li class=".$message_page.">
             <a href='".site_url('wp-admin/admin.php?page=sch-messages')."'>
                <i class='fa fa-inbox'></i>
        <span>".__('Messages','WPSchoolPress')."</span><span class='pull-right label label-primary pull-right'>".wpsp_UnreadCount()."</span>
              </a>
            </li>-->
            <li class=".$student_page.">
              <a href='".site_url('wp-admin/admin.php?page=sch-student')."'>
                <i class='fa fa-users'></i>
                <span>".__('Students','WPSchoolPress')."</span>
              </a>
            </li>
            <li class=".$teacher_page.">
              <a href='".site_url('wp-admin/admin.php?page=sch-teacher')."'>
                <i class='fa fa-users'></i>
                <span>".__('Teachers','WPSchoolPress')."</span>
              </a>
            </li>
            <li class=".$parent_page.">
              <a href='".site_url('wp-admin/admin.php?page=sch-parent')."'>
                <i class='fa fa-users'></i>
                <span>".__('Parents','WPSchoolPress')."</span>
              </a>
            </li>
      <li class=".$class_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-class')."'>
          <i class='fa fa-bell'></i><span>".__('Classes','WPSchoolPress')."</span>
        </a>
            </li>
      <li class=".$attendance_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-attendance')."'>
          <i class='fa fa-table'></i><span>".__('Attendance','WPSchoolPress')."</span>
        </a>
            </li>
      <li class=".$subject_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-subject')."'>
          <i class='fa fa-book'></i><span>".__('Subjects','WPSchoolPress')."</span>
        </a>
      </li>
      <li class=".$mark_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-marks')."'>
          <i class='fa fa-check-square-o'></i><span>".__('Marks','WPSchoolPress')."</span>
        </a>
            </li>
      <li class=".$exam_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-exams')."'>
          <i class='fa fa-edit'></i><span>".__('Exams','WPSchoolPress')."</span>
        </a>
            </li>
      <li class=".$event_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-events')."'>
          <i class='fa fa-calendar'></i><span>".__('Events','WPSchoolPress')."</span>
        </a>
            </li>
      <li class=".$timetable_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-timetable')."'>
          <i class='fa fa-clock-o'></i><span>".__('Time Table','WPSchoolPress')."</span>
        </a>
      </li> ";
    if($current_user_role=='administrator' || $current_user_role=='teacher') {
        echo "<li class=" . $import_page . ">
        <a href='" . site_url('wp-admin/admin.php?page=sch-importhistory') . "'>
          <i class='fa fa-upload'></i><span>" . __('Import History', 'WPSchoolPress') . "</span>
        </a>
       </li>
        <li class=".$notify_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-notify')."'>
          <i class='fa fa-bullhorn'></i><span>".__('Notify','WPSchoolPress')."</span>
        </a>
      </li>     
      ";
    }
  /*if($current_user_role=='administrator' || $current_user_role=='teacher') {  
    echo "<li ".$payment_page_main.">
              <a href='#'>
                <i class='fa fa-cog'></i>
                <span>".__('Payment','WPSchoolPress')."</span>
                <i class='fa fa-angle-left pull-right'></i>
              </a>
              <ul class='treeview-menu'>
                <li class='".$viewpayment."'><a href='".site_url('sch-payment')."'><i class='fa fa-wrench'></i>".__('View Fees','WPSchoolPress')."</a></li>
        <li class='".$addpayment."'><a href='".site_url('sch-payment?type=addpayment')."'><i class='fa fa-check-square-o'></i>".__('Add Fees','WPSchoolPress')."</a></li>       
      </ul>
        </li>";
  } else {
    echo "<li ".$payment_page_main.">
            <a href='".site_url('sch-payment')."'>
        <i class='fa fa-cog'></i>
          <span>".__('View Fees','WPSchoolPress')."</span>                
            </a></li>";
  }*/
     echo "<li class=".$transport_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-transport')."'>
          <i class='fa fa-road'></i><span>".__('Transport','WPSchoolPress')."</span>
        </a>
       </li>";
      if($current_user_role=='administrator')
      {
          echo "<li class=".$teacher_attendance_page.">
        <a href='".site_url('wp-admin/admin.php?page=sch-teacherattendance')."'>
          <i class='fa fa-signal'></i><span>".__('Teacher Attendance','WPSchoolPress')."</span>
        </a>
       </li>";
        echo"
    
    <li ".$settings_page.">
              <a href='#'>
                <i class='fa fa-cog'></i>
                <span>".__('Settings','WPSchoolPress')."</span>
                <i class='fa fa-angle-left pull-right'></i>
              </a>
              <ul class='treeview-menu'>
                <li class='".$settings_general_page."'><a href='".site_url('wp-admin/admin.php?page=sch-settings')."'><i class='fa fa-wrench'></i>".__('General Settings','WPSchoolPress')."</a></li>
                <li class='".$settings_subfield_page."'><a href='".site_url('wp-admin/admin.php?page=sch-settings&sc=subField')."'><i class='fa fa-check-square-o'></i>".__('Subject Mark Fields','WPSchoolPress')."</a></li>
        <li class='".$settings_wrkhours_page."'><a href='".site_url('wp-admin/admin.php?page=sch-settings&sc=WrkHours')."'><i class='fa fa-clock-o'></i>".__('Working Hours','WPSchoolPress')."</a></li>        
              </ul>
            </li>";
      }
    echo "<li class='".$settings_chgpw_page."'><a href='".site_url('wp-admin/admin.php?page=sch-changepassword')."'><i class='fa fa-key fa-fw'></i>".__('Change Password','WPSchoolPress')."</a></li>";
      echo "<li class='".$leave_page."'><a href='".site_url('wp-admin/admin.php?page=sch-leavecalendar')."'><i class='fa fa-strikethrough'></i>".__('Leave Calendar','WPSchoolPress')."</a></li>
          </ul>
        </section>
      </aside>";
}
function wpsp_body_start()
{
  echo "<!-- Content Wrapper. Contains page content -->
      <div class='content-wrapper'>        
        <!-- Main content -->";
         
}
function wpsp_body_end()
{
  echo "</div><!-- /.content-wrapper -->
      <footer class='main-footer'>
        <div class='pull-right hidden-xs'>
          <b>WPSchoolPress Version</b> ".WPSP_PLUGIN_VERSION."
        </div>
        <br/>
        <strong>Copyright &copy;".date('Y')." <a href='http://wpschoolpress.com' target='_blank'>WPSchoolPress</a>.</strong> All rights reserved. 
      </footer>
    <!-- Control Sidebar -->
     
    </div><!-- ./wrapper -->";
}

function wpsp_footer()
{
 echo "	 
  <script>
    jQuery(function($) {
    ajax_url ='".admin_url( 'admin-ajax.php' )."';
    date_format='mm/dd/yy';
    $('.content-wrapper').on('click',function(){
      $('.control-sidebar').removeClass('control-sidebar-open');
    });
    
  });
  </script>";

  do_action( 'wpsp_footer_script' );
  echo "</div><div id='overlay'></div></body></html>";
}

function wpsp_admin_url(){
    $admin_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?page=';
    echo  $admin_link;
  }
  
?>