<?php

// Exit if accessed directly

if (!defined('ABSPATH')) exit;
/**
 * Admin Class
 *
 * Handles generic Admin functionality.
 *
 * @package WPSchoolPress
 * @since 2.0.0
 */
class Wpsp_Admin

{
	public 

	function __construct()
	{
	}

	/*
	* Add menu for manage license code
	* @package WPSchoolPress
	* @since 2.0.0
	*/
	function wpsp_admin_menu()
	{
		add_menu_page(__('WPSchoolPress', 'WPSchoolPress') , __('WPSchoolPress', 'WPSchoolPress') , 'manage_options', 'WPSchoolPress', array(
			$this,
			'wpsp_admin_details'
		) , WPSP_PLUGIN_URL . 'img/favicon.png');
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-dashboard"></i>&nbsp; Dashboard', 'edit_posts', 'sch-dashboard', array(
			$this,
			'wpsp_callback_dashboard'
		));
		/*add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-inbox"></i>&nbsp; Messages', 'edit_posts', 'sch-messages', array(
			$this,
			'wpsp_callback_messages'
		));*/
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-users"></i>&nbsp; Students', 'edit_posts', 'sch-student', array(
			$this,
			'wpsp_callback_students'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-users"></i>&nbsp; Teachers', 'edit_posts', 'sch-teacher', array(
			$this,
			'wpsp_callback_teachers'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-users"></i>&nbsp; Parents', 'edit_posts', 'sch-parent', array(
			$this,
			'wpsp_callback_parents'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-bell"></i>&nbsp; Classes', 'edit_posts', 'sch-class', array(
			$this,
			'wpsp_callback_classes'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-table"></i>&nbsp; Attendance', 'edit_posts', 'sch-attendance', array(
			$this,
			'wpsp_callback_attendance'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-book"></i>&nbsp; Subjects', 'edit_posts', 'sch-subject', array(
			$this,
			'wpsp_callback_subject'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-edit"></i>&nbsp; Exams', 'edit_posts', 'sch-exams', array(
			$this,
			'wpsp_callback_exams'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-check-square-o"></i>&nbsp; Marks', 'edit_posts', 'sch-marks', array(
			$this,
			'wpsp_callback_marks'
		));
		
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-calendar"></i>&nbsp; Events', 'edit_posts', 'sch-events', array(
			$this,
			'wpsp_callback_events'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-clock-o"></i>&nbsp; Time Table', 'edit_posts', 'sch-timetable', array(
			$this,
			'wpsp_callback_timetable'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-upload"></i>&nbsp; Import History', 'edit_posts', 'sch-importhistory', array(
			$this,
			'wpsp_callback_importhistory'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-bullhorn"></i>&nbsp; Notify', 'edit_posts', 'sch-notify', array(
			$this,
			'wpsp_callback_notify'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-road"></i>&nbsp; Transport', 'edit_posts', 'sch-transport', array(
			$this,
			'wpsp_callback_transport'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-signal"></i>&nbsp; Teacher Attendance', 'edit_posts', 'sch-teacherattendance', array(
			$this,
			'wpsp_callback_teacherattendance'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-cog"></i>&nbsp; General Settings', 'edit_posts', 'sch-settings', array(
			$this,
			'wpsp_callback_settings'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-check-square-o"></i>&nbsp; Subject Mark Fields', 'edit_posts', 'sch-settings&sc=subField', array(
			$this,
			'wpsp_callback_subfield'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-clock-o"></i>&nbsp; Working Hours', 'edit_posts', 'sch-settings&sc=WrkHours', array(
			$this,
			'wpsp_callback_wrkhours'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-key fa-fw"></i>&nbsp; Change Password', 'edit_posts', 'sch-changepassword', array(
			$this,
			'wpsp_callback_changepassword'
		));
		add_submenu_page('WPSchoolPress', 'WPSchoolPress', '<i class="fa fa-strikethrough"></i>&nbsp; Leave Calendar', 'edit_posts', 'sch-leavecalendar', array(
			$this,
			'wpsp_callback_leavecalendar'
		));
	}

	/*
	* Call html of purchase code validation and contact
	* @package WPSchoolPress
	* @since 2.0.0
	*/
	function wpsp_admin_details()
	{
		require_once (WPSP_PLUGIN_PATH . 'lib/wpsp-admin-options.php');

	}

	function wpsp_callback_dashboard()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-dashboard.php');

	}

	function wpsp_callback_students()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-student.php');

	}

	function wpsp_callback_teachers()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-teacher.php');

	}

	function wpsp_callback_messages()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-messages.php');

	}

	function wpsp_callback_parents()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-parent.php');

	}

	function wpsp_callback_classes()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-class.php');

	}

	function wpsp_callback_attendance()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-attendance.php');

	}

	function wpsp_callback_subject()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-subject.php');

	}

	function wpsp_callback_marks()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-marks.php');

	}

	function wpsp_callback_exams()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-exams.php');

	}

	function wpsp_callback_events()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-events.php');

	}

	function wpsp_callback_timetable()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-timetable.php');

	}

	function wpsp_callback_importhistory()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-importhistory.php');

	}

	function wpsp_callback_notify()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-notify.php');

	}

	function wpsp_callback_transport()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-transport.php');

	}

	function wpsp_callback_teacherattendance()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-teacher-attendance.php');

	}

	function wpsp_callback_settings()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-settings.php');

	}

	function wpsp_callback_changepassword()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-changepassword.php');

	}

	function wpsp_callback_leavecalendar()
	{
		require_once (WPSP_PLUGIN_PATH . 'pages/wpsp-leavecalendar.php');

	}

	/*
	* Add required css and js for purchase code validation page
	* @package WPSchoolPress
	* @since 2.0.0
	*/
	function wpsp_add_admin_scripts($hook)
	{ 
		if (isset($_GET['page'])) { 
			
			$page = $_GET['page'];

			if ( in_array( $page, array( 'WPSchoolPress', 'sch-dashboard', 'sch-student', 'sch-teacher', 'sch-parent', 'sch-class', 'sch-attendance', 'sch-subject', 'sch-exams', 'sch-marks', 'sch-events', 'sch-timetable', 'sch-importhistory', 'sch-notify', 'sch-transport', 'sch-teacherattendance', 'sch-settings', 'sch-changepassword', 'sch-leavecalendar') ) ) { 

				wp_register_style('wpsp_wp_admin_bootstrap', WPSP_PLUGIN_URL . 'css/bootstrap.min.css', false, '1.0.0');
				wp_enqueue_style('wpsp_wp_admin_bootstrap');
				wp_register_style('wpsp_wp_admin_font_awesome', WPSP_PLUGIN_URL . 'css/font-awesome.min.css', false, '1.0.0');
				wp_enqueue_style('wpsp_wp_admin_font_awesome');
				wp_register_style('wpsp_wp_admin_ionicons', WPSP_PLUGIN_URL . 'css/ionicons.min.css', false, '1.0.0');
				wp_enqueue_style('wpsp_wp_admin_ionicons');
				wp_register_style('wpsp_wp_admin_skins', WPSP_PLUGIN_URL . 'css/skins/_all-skins.css', false, '1.0.0');
				wp_enqueue_style('wpsp_wp_admin_skins');
				wp_register_style('wpsp_wp_admin_fjquery-ui', WPSP_PLUGIN_URL . 'plugins/otherui/wpsp_css-ui.min.css', false, '1.0.0');
				wp_enqueue_style('wpsp_wp_admin_fjquery-ui');
				wp_register_style('wpsp_wp_admin_pnotify', WPSP_PLUGIN_URL . 'css/pnotify.min.css', false, '1.0.0');
				wp_enqueue_style('wpsp_wp_admin_pnotify');
				if (is_user_logged_in())
				{
					wp_register_style('wpsp_wp_admin_dataTablesresp', WPSP_PLUGIN_URL . 'plugins/datatables/dataTables.responsive.css', false, '1.0.0');
					wp_enqueue_style('wpsp_wp_admin_dataTablesresp');
					wp_register_style('wpsp_wp_admin_dataTablesboot', WPSP_PLUGIN_URL . 'plugins/datatables/dataTables.bootstrap.css', false, '1.0.0');
					wp_enqueue_style('wpsp_wp_admin_dataTablesboot');
				}

				if ($hook == 'wpschoolpress_page_sch-student')
				{
					wp_register_style('wpsp_wp_admin_blueimp-gallery', WPSP_PLUGIN_URL . 'plugins/gallery/blueimp-gallery.min.css', false, '1.0.0');
					wp_enqueue_style('wpsp_wp_admin_blueimp-gallery');
				}

				if ($hook == 'wpschoolpress_page_sch-dashboard')
				{
					wp_register_style('wpsp_wp_admin_fullcalendar', WPSP_PLUGIN_URL . 'plugins/fullcalendar/fullcalendar.min.css', false, '1.0.0');
					wp_enqueue_style('wpsp_wp_admin_fullcalendar');
				}

				if ($hook == 'wpschoolpress_page_sch-events')
				{
					wp_register_style('wpsp_wp_admin_fullcalendar', WPSP_PLUGIN_URL . 'plugins/fullcalendar/fullcalendar.min.css', false, '1.0.0');
					wp_enqueue_style('wpsp_wp_admin_fullcalendar');
					wp_register_style('wpsp_wp_admin_timepicker', WPSP_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.css', false, '1.0.0');
					wp_enqueue_style('wpsp_wp_admin_timepicker');
				}

				if ($hook == 'wpschoolpress_page_sch-messages' || $hook == 'wpschoolpress_page_sch-payment')
				{
					wp_register_style('wpsp_wp_admin_multiselect', WPSP_PLUGIN_URL . 'plugins/multiselect/jquery.multiselect.css', false, '1.0.0');
					wp_enqueue_style('wpsp_wp_admin_multiselect');
				}

				if ($hook == 'wpschoolpress_page_sch-settings' || $hook == 'wpschoolpress_page_sch-teacher')
				{
					wp_register_style('wpsp_wp_admin_boottimepicker', WPSP_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.css', false, '1.0.0');
					wp_enqueue_style('wpsp_wp_admin_boottimepicker');
				}

				wp_register_style('wpsp_wp_admin_custome1', WPSP_PLUGIN_URL . 'css/custome.css', false, '1.0.0');
				wp_enqueue_style('wpsp_wp_admin_custome1');
				wp_register_style('wpsp_wp_admin_AdminLTE', WPSP_PLUGIN_URL . 'css/AdminLTE.css', false, '1.0.0');
				wp_enqueue_style('wpsp_wp_admin_AdminLTE');
				wp_register_style('wpsp_wp_admin_css', WPSP_PLUGIN_URL . 'css/wpadmin.css', false, '1.0.0');
				wp_enqueue_style('wpsp_wp_admin_css');

							
				wp_enqueue_script('wpsp_wp_admin_jquery1', WPSP_PLUGIN_URL . 'plugins/otherjs/wpsp.validate.min.js', array(
					'jquery'
				) , '1.0.0', true);
				wp_enqueue_script('wpsp_wp_admin_jquery90', WPSP_PLUGIN_URL . 'js/wpsp-wp-admin.js', array(
						'jquery'
					) , '1.0.0', true);
				wp_enqueue_script('wpsp_wp_admin_jquery2', WPSP_PLUGIN_URL . 'js/lib/bootstrap.min.js', array(
					'jquery'
				) , '1.0.0', true);
				wp_enqueue_script('wpsp_wp_admin_jquery3', WPSP_PLUGIN_URL . 'plugins/fastclick/fastclick.min.js', array(
					'jquery'
				) , '1.0.0', true);
				wp_enqueue_script('wpsp_wp_admin_jquery4', WPSP_PLUGIN_URL . 'js/lib/app.js', array(
					'jquery'
				) , '1.0.0', true);
				wp_enqueue_script('wpsp_wp_admin_jquery5', WPSP_PLUGIN_URL . 'js/lib/pnotify.min.js', array(
					'jquery'
				) , '1.0.0', true);
				wp_enqueue_script('wpsp_wp_admin_jquery6', WPSP_PLUGIN_URL . 'plugins/slimScroll/jquery.slimscroll.min.js', array(
					'jquery'
				) , '1.0.0', true);
				wp_enqueue_script('wpsp_wp_admin_jquery7', WPSP_PLUGIN_URL . 'plugins/otherui/wpsp_ui_s.min.js', array(
					'jquery'
				) , '1.0.0', true);
				if ($hook == 'toplevel_page_WPSchoolPress')
				{
					
					wp_enqueue_script('wpsp_wp_admin_jquery90', WPSP_PLUGIN_URL . 'js/wpsp-wp-admin.js', array(
						'jquery'
					) , '1.0.0', true);
				}
				if (is_user_logged_in())
				{
					wp_enqueue_script('wpsp_wp_admin_jquery8', WPSP_PLUGIN_URL . 'plugins/datatables/jquery.dataTables.min.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery9', WPSP_PLUGIN_URL . 'plugins/datatables/dataTables.bootstrap.min.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-dashboard')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery10', WPSP_PLUGIN_URL . 'plugins/fullcalendar/moment.min.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery11', WPSP_PLUGIN_URL . 'plugins/fullcalendar/fullcalendar.min.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery12', WPSP_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery13', WPSP_PLUGIN_URL . 'js/wpsp-dashboard.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-student')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery14', WPSP_PLUGIN_URL . 'plugins/fileupload/jquery.fileupload.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery15', WPSP_PLUGIN_URL . 'plugins/fileupload/jquery.iframe-transport.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery16', WPSP_PLUGIN_URL . 'plugins/gallery/jquery.blueimp-gallery.min.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery17', WPSP_PLUGIN_URL . 'js/wpsp-student.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-teacher')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery27', WPSP_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.js', array(
						'jquery'
					) , '1.0.0', true); 
					wp_enqueue_script('wpsp_wp_admin_jquery18', WPSP_PLUGIN_URL . 'js/wpsp-teacher.js', array(
						'jquery'
					) , '1.0.0', true);

				}

				if ($hook == 'wpschoolpress_page_sch-parent')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery19', WPSP_PLUGIN_URL . 'js/wpsp-parent.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-class')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery20', WPSP_PLUGIN_URL . 'js/wpsp-class.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-attendance')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery21', WPSP_PLUGIN_URL . 'js/wpsp-attendance.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-subject')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery22', WPSP_PLUGIN_URL . 'js/wpsp-subject.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-exams')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery23', WPSP_PLUGIN_URL . 'js/wpsp-exam.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-marks')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery24', WPSP_PLUGIN_URL . 'js/wpsp-mark.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-timetable')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery25', WPSP_PLUGIN_URL . 'plugins/otherui/wpsp_easyui.min.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery26', WPSP_PLUGIN_URL . 'js/wpsp_timetable.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-settings')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery27', WPSP_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery28', WPSP_PLUGIN_URL . 'js/wpsp-settings.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-importhistory')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery29', WPSP_PLUGIN_URL . 'js/wpsp-importhistory.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-transport')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery30', WPSP_PLUGIN_URL . 'js/wpsp-transport.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-teacherattendance')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery31', WPSP_PLUGIN_URL . 'js/wpsp-teacherattendance.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-notify')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery32', WPSP_PLUGIN_URL . 'js/wpsp-notify.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-events')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery33', WPSP_PLUGIN_URL . 'plugins/fullcalendar/moment.min.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery34', WPSP_PLUGIN_URL . 'plugins/fullcalendar/fullcalendar.min.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery35', WPSP_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery36', WPSP_PLUGIN_URL . 'js/wpsp-events.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-notify')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery37', WPSP_PLUGIN_URL . 'js/wpsp-leavecalendar.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-messages' || $hook == 'wpschoolpress_page_sch-parent')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery38', WPSP_PLUGIN_URL . 'plugins/multiselect/jquery.multiselect.js', array(
						'jquery'
					) , '1.0.0', true);
					wp_enqueue_script('wpsp_wp_admin_jquery39', WPSP_PLUGIN_URL . 'js/wpsp-messages.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-changepassword')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery40', WPSP_PLUGIN_URL . 'js/wpsp-changepassword.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-payment')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery41', WPSP_PLUGIN_URL . 'plugins_url("plugins/multiselect/jquery.multiselect.js', array(
						'jquery'
					) , '1.0.0', true);
				}

				if ($hook == 'wpschoolpress_page_sch-leavecalendar')
				{
					wp_enqueue_script('wpsp_wp_admin_jquery42', WPSP_PLUGIN_URL . 'js/wpsp-leavecalendar.js', array(
						'jquery'
					) , '1.0.0', true);
				}
			} 
		} 
	}

	/*
	* Add pages in menu default
	* @package WPSchoolPress
	* @since 2.0.0
	*/
	function wpsp_add_adminbar()
	{
		global $wp_admin_bar;
		$wpsp_wpschooldashboard_url = site_url() . '/wp-admin/admin.php?page=sch-dashboard';
		$wpsp_wpschoolstudent_url = site_url() . '/wp-admin/admin.php?page=sch-student';
		$wpsp_wpschoolteacher_url = site_url() . '/wp-admin/admin.php?page=sch-teacher';
		$wpsp_wpschoolclass_url = site_url() . '/wp-admin/admin.php?page=sch-class';
		$wpsp_wpschoolparent_url = site_url() . '/wp-admin/admin.php?page=sch-parent';
		$wp_admin_bar->add_menu(array(
			'parent' => false,
			'id' => 'dashboard',
			'title' => _('WPSchoolPress Dashboard') ,
			'href' => $wpsp_wpschooldashboard_url
		));
		$wp_admin_bar->add_menu(array(
			'parent' => 'dashboard',
			'id' => 'teacher',
			'title' => _('Teacher') ,
			'href' => $wpsp_wpschoolteacher_url
		));
		$wp_admin_bar->add_menu(array(
			'parent' => 'dashboard',
			'id' => 'student',
			'title' => _('Student') ,
			'href' => $wpsp_wpschoolstudent_url
		));
		$wp_admin_bar->add_menu(array(
			'parent' => 'dashboard',
			'id' => 'class',
			'title' => _('Class') ,
			'href' => $wpsp_wpschoolclass_url
		));
		$wp_admin_bar->add_menu(array(
			'parent' => 'dashboard',
			'id' => 'parent',
			'title' => _('Parent') ,
			'href' => $wpsp_wpschoolparent_url
		));
	}

	function add_hooks()
	{
		function wpsp_custom_loginlogo()
		{
			echo '<style type="text/css">
.login h1 a {background-image: url(' . plugin_dir_url(__FILE__) . '/img/wpschoolpresslogo.jpg) !important; }
</style>';
		}

		// Add menu page for purchase code validation

		add_action('admin_menu', array(
			$this,
			'wpsp_admin_menu'
		));
		add_action('login_enqueue_scripts', 'wpsp_custom_loginlogo');

		// Add css and js

		add_action('admin_enqueue_scripts', array(
			$this,
			'wpsp_add_admin_scripts'
		));

		// Add pages into admin menu

		add_action('wp_before_admin_bar_render', array(
			$this,
			'wpsp_add_adminbar'
		));
	}
}