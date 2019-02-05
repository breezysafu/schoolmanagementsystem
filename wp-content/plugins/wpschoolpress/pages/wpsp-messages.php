<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpsp_header();
	if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role=$current_user->roles[0];
		if($current_user_role=='administrator' || $current_user_role=='teacher' || $current_user_role=='parent' || $current_user_role='student') {
            wpsp_topbar();
            wpsp_sidebar();
            wpsp_body_start();
			$messages_table	=	$wpdb->prefix."wpsp_messages";
			$currentTab 	=	isset( $_GET['tab'] ) && !empty( sanitize_text_field($_GET['tab']) ) ? sanitize_text_field($_GET['tab']) : 'inbox';
            ?>
            <section class="content-header">
                <h1>Messages</h1>
                <ol class="breadcrumb">
                    <li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li><a href="<?php wpsp_admin_url();?>sch-messages">Messages</a></li>
                </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-solid bg-blue-gradient">
                        		 <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp; Message </h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
                            <div class="box-footer text-black">
								<div class="mailbox-header">
									<div class="col-md-2">
										<button id="createMessage" class="btn btn-primary btn-block"  data-toggle="modal" data-target="#newMessage"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Compose </button>
										
										<ul class="list-unstyled mailbox-nav">
											<li class="active">
											<a href="<?php wpsp_admin_url();?>sch-messages&tab=inbox"><i class="fa fa-inbox"></i> Inbox<span class="badge badge-success pull-right btn-primary"><?php echo wpsp_UnreadCount(); ?></span></a></li>
											<li><a href="<?php wpsp_admin_url();?>sch-messages&tab=sentbox"><i class="fa fa-sign-out"></i>Sent</a></li>                                
											<li><a href="<?php wpsp_admin_url();?>sch-messages&tab=trash"><i class="fa fa-trash"></i>Trash</a></li>                                
										</ul>
									    
									</div>
									<div class="col-md-10 wpsp-mail-title">
										
										<h2><?php 			
										$columnFirstTitle = __( 'From', 'WPSchoolPress' );
										if( isset( $_GET['mid']) && !empty( sanitize_text_field($_GET['mid']) ) ) {
											echo esc_html( __( 'View Message', 'WPSchoolPress' ) );											
										} else if(!isset($_REQUEST['tab']) || (sanitize_text_field($_REQUEST['tab']) == 'inbox')) {
											echo esc_html( __( 'Inbox', 'WPSchoolPress' ) );
											$columnFirstTitle = __( 'From', 'WPSchoolPress' );
										} else if(isset( $_REQUEST['tab']) && sanitize_text_field($_REQUEST['tab']) == 'sentbox') {
											echo esc_html( __( 'Sent Item', 'WPSchoolPress' ) );
											$columnFirstTitle = __( 'To', 'WPSchoolPress' );
										}	else if(isset( $_REQUEST['tab']) && sanitize_text_field($_REQUEST['tab']) == 'trash') {
											echo esc_html( __( 'Trash', 'WPSchoolPress' ) );
											$columnFirstTitle = __( 'To', 'WPSchoolPress' );
										}									
									?> </h2>
								<div class="col-md-12 table-responsive">
										<?php 
											if( isset( $_GET['mid'] ) && !empty( sanitize_text_field($_GET['mid'] )) )
												echo '<div id="viewMessageContainer">'.wpsp_ViewMessage( sanitize_text_field($_GET['mid']), true ).'</div>';
											else { ?>
												<table class="table table-bordered " id="message-list">
												<thead><tr>
													<th class="nosort"><input type="checkbox" id="checkAll" class="ccheckbox"></th>
													<th><?php echo $columnFirstTitle; ?></th>
													<th>Subject</th><th>Date</th>
													<th class="nosort">
													<select name="bulkaction" class="form-control" id="bulkaction">
														<option value="">Select Action</option>
														<option value="bulkUsersDelete">Delete</option>
													</select>											
												</tr></thead>	
												<tbody>
												<?php                                
												//$msg_block	=	$wpdb->get_results("select * from $messages_table where (s_id=$current_user->ID or r_id=$current_user->ID) and del_stat !=$current_user->ID order by mid DESC");
												if( $currentTab== 'trash' ) {
													$msg_block	=	$wpdb->get_results("select * from $messages_table where ( r_id=$current_user->ID or s_id=$current_user->ID ) and del_stat =$current_user->ID order by mid DESC");
												}else if( $currentTab== 'inbox' ) {
													/*$msg_block	=	$wpdb->get_results("select * from $messages_table where ( r_id=$current_user->ID or s_id=$current_user->ID )and del_stat !=$current_user->ID order by mid DESC");*/
													//echo "select * from $messages_table where ( r_id=$current_user->ID or s_id=$current_user->ID )and del_stat !=$current_user->ID order by mid DESC";
													$msg_block	=	$wpdb->get_results("select * from $messages_table where ( r_id=$current_user->ID )and del_stat !=$current_user->ID order by mid DESC");
												} else {
													$msg_block	=	$wpdb->get_results("select * from $messages_table where ( r_id=$current_user->ID or s_id=$current_user->ID ) and del_stat !=$current_user->ID order by mid DESC");
												}
													
												foreach( $msg_block as $msgb ) {
												
													$rep			=	json_decode( $msgb->msg );													
													$senderlist		=	json_decode( $msgb->msg, true );													
													/*$receiverids 	=	array_column( $senderlist, 's_id', 's_id' );*/
													$rep			=	(is_array($rep))?end($rep):array();												
													$s_mid			=	$currentTab== 'inbox' ? $msgb->s_id : $msgb->r_id;											
													$msgs_n			=	get_userdata($s_mid);												
													$role			=	isset( $msgs_n->roles[0] ) ? sanitize_text_field($msgs_n->roles[0]) : '';
													$stat			=	isset( $rep->stat ) ? sanitize_text_field($rep->stat) :'';
													$rep_sID		=	isset( $rep->s_id ) ? sanitize_text_field($rep->s_id) :'';
													$mstat			=	$stat!=$current_user->ID && $rep_sID!=$current_user->ID && $currentTab == 'inbox'  ? "unread" : "read";
													$msg_text		=	substr($msgb->subject, 0, 50 );
													$display		=	true;
													$nickname		=	isset( $msgs_n->user_nicename) ? sanitize_text_field($msgs_n->user_nicename) :'';
													/*
													if( $currentTab == 'inbox' && !in_array( $current_user->ID, $receiverids ) ) {
														$display	=	false;
													}
													if( $currentTab == 'sentbox' && !in_array( $current_user->ID, $receiverids ) ) {
														$display	=	false;
													}*/
													
													if( $display ) { ?> 

														<tr class="<?php echo $mstat; ?>">
															<td><input type="checkbox" class="mid_checkbox ccheckbox" name="mid[]" value="<?php echo intval($msgb->mid); ?>" /></td>
															<td class="name"><?php echo $nickname; ?><span class="label label-role-msg label-role-<?php echo $role;?>"><?php echo $role; ?></span></td>
															<td class="subject"><?php echo $msg_text; ?></td>
															<td class="time"><?php echo wpsp_ViewDate($msgb->m_date); ?></td>
															<td class="small-col">
																<a class="pointer text-blue viewMess" title="view" href="<?php wpsp_admin_url(); ?>sch-messages&mid=<?php echo intval($msgb->mid);?>"><i class="fa fa-eye btn btn-success"></i></a>
																<a href="javascript:;" title="Delete"  class="delete_messages" mid="<?php echo intval($msgb->mid);?>"><i class="fa fa-trash btn btn-danger ClassDeleteBt" data-id="<?php echo intval($msgb->mid); ?> "></i></a>											
															</td>
															</tr>
												<?php 	}
												}
												?>
												</tbody>
												</table>
										<?php } ?>
									</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="modal fade" id="newMessage" tabindex="-1" role="dialog" aria-labelledby="AddModal"
                 aria-hidden="true">
                <div class="modal-lg modal-dialog">
                    <div class="modal-content">
                        <div class="col-md-12">
                            <div class="box box-success">
                                <div class="box-header">
                                    <h3 class="box-title">New Message</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <form class="form-horizontal group-border-dashed" action="" id="newMessageForm"
                                          style="border-radius: 0px;" method="post">
                                        <div id="message_response"></div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Receiver</label>
                                            <div class="col-sm-6" id="receiverUsers">
												<?php  if( $current_user_role=='administrator' || $current_user_role=='teacher' ) { ?>
                                                <select name="r_id[]" multiple id="r_id" class="form-control">													 
                                                        <?php
                                                        $class_table 	=	$wpdb->prefix . "wpsp_class";
														$studenttable	=	$wpdb->prefix.'wpsp_student';
                                                        $class_ids = $wpdb->get_results("select cid,c_name from $class_table");
                                                        foreach ($class_ids as $clss) { ?>
															<?php
																$total_user_list = $wpdb->get_results( "SELECT CONCAT_WS(' ', s_fname, s_mname, s_lname ) AS full_name, wp_usr_id FROM $studenttable WHERE class_id=$clss->cid" );
																if( !empty( $total_user_list ) ) { ?>
																	<optgroup label="Class <?php echo $clss->c_name; ?> Students">
																	<?php
																	foreach ($total_user_list as $usr) {
																		if ( intval($usr->wp_usr_id) != intval($current_user->ID) ) { ?>
																			<option	value="<?php echo intval($usr->wp_usr_id); ?>"><?php echo $usr->full_name; ?></option>
																	<?php }
																	}
																	echo '</optgroup>';
																}
														}
														
														foreach ($class_ids as $clss) { ?>
															<?php																
																$total_parent_user_list = $wpdb->get_results("select parent_wp_usr_id, p_fname from $studenttable where class_id=$clss->cid");																
																if( !empty( $total_parent_user_list ) ) { ?>
																	<optgroup label="Class <?php echo $clss->c_name; ?> Parents">
																	<?php
																	foreach ($total_parent_user_list as $usr) {
																		if ( intval($usr->parent_wp_usr_id) != intval($current_user->ID) ) { ?>
																			<option	value="<?php echo intval($usr->parent_wp_usr_id); ?>"><?php echo $usr->p_fname; ?></option>
																	<?php }
																	}
																	echo '</optgroup>';
																}
														}
														
														$teacher_table	=	$wpdb->prefix."wpsp_teacher";
														$teachers		=	$wpdb->get_results("select * from $teacher_table order by tid DESC");
														if( !empty( $teachers ) ) {
															echo '<optgroup label="All Teachers">';
															foreach( $teachers as $key=>$tinfo ) { ?>
																<option	value="<?php echo intval($tinfo->wp_usr_id); ?>"><?php echo $tinfo->first_name; ?></option>
															<?php	
															}
															echo '</optgroup>';
														}
														/*$total_user_list = get_users_of_blog();
														foreach ($total_user_list as $usr) {
															if ($usr->ID != $current_user->ID) { ?>
																<option	value="<?php echo $usr->ID; ?>"><?php echo $usr->user_login; ?></option>
															<?php }
														}*/													
														?>
                                                </select>
												<a href="#" id="showGroup">Select Group</a>
												<?php } else if( $current_user_role=='parent') {
														$parent_id		=	intval($current_user->ID);
														$student_table	=	$wpdb->prefix."wpsp_student";
														$class_table	=	$wpdb->prefix."wpsp_class";																												
														$students		=	$wpdb->get_results("select st.wp_usr_id, st.class_id, CONCAT_WS(' ', st.s_fname, st.s_mname, st.s_lname ) AS full_name, st.first_name,cl.c_name,cl.teacher_id from $student_table st LEFT JOIN $class_table cl ON cl.cid=st.class_id where st.parent_wp_usr_id='$parent_id'");
														$child			=	$classlist	=	$clist	=	array();		
														foreach( $students as $childinfo ) {
															$studentName	=	!empty( sanitize_text_field($childinfo->first_name) ) ? sanitize_text_field($childinfo->first_name) : sanitize_text_field($childinfo->full_name);;
															$child[]		=	array('student_id'=>$childinfo->wp_usr_id,'name'=>$studentName,'class_id'=>$childinfo->class_id,'class_name'=>$childinfo->c_name,'teacher_id'=>$childinfo->teacher_id);
															$classlist[]	=	$childinfo->class_id;
														}
														foreach( $child as $childlist ) { ?>
															<input type="radio" name="childname" id="student-<?php echo intval($childlist['student_id']);?>" value="<?php echo intval($childlist['student_id']);?>" teacherid="<?php echo intval($childlist['teacher_id']);?>" class="msg-child-list" classid="<?php echo $childlist['class_id'];?>">
															<label for="student-<?php echo $childlist['student_id'];?>"><?php echo $childlist['name'];?></label>
														<?php	
														} 
														$clist	=	array_unique( $classlist );
														?>
														<div class="wpsps-message-list none">
															<input type="checkbox" name="r_id[]" id="student_classteacher" value="" class="ccheckbox">
															<label for="student_classteacher">Class Teacher</label>
															<input type="checkbox" name="r_id[]" id="student_principal" value="1" class="ccheckbox">
															<label for="student_principal">Principal</label>
															<input type="checkbox" name="teacher" id="student_teacher" value="teacher" class="ccheckbox">
															<label for="student_teacher">Subject Teacher</label>
														</div>
														<?php 
															$subjecttable	=	$wpdb->prefix.'wpsp_subject';
															$teachertable	=	$wpdb->prefix.'wpsp_teacher ';
															foreach( $clist	as $classid ) {
																$students		=	$wpdb->get_results("select s.*, t.first_name from $subjecttable s, $teachertable t where s.class_id=$classid AND s.sub_teach_id=t.wp_usr_id");
																foreach( $students as $stud) { ?>
																	<div class="none wp-subject-list class-name-<?php echo intval($stud->class_id);?>">
																		<input type="checkbox" class="ccheckbox" name="r_id[]" id="" value="<?php echo $stud->sub_teach_id; ?>" class="wp-subject-name">
																		<label for="student_classteacher"><?php echo $stud->s_fname.' - '.$stud->sub_name; ?></label>
																	</div>
																<?php	
																}
															}
														?>
												<?php		
												} else if( $current_user_role=='student') {
													$studenttable	=	$wpdb->prefix.'wpsp_student';													
													$student_id		=	intval($current_user->ID);
													?>
													<select name="r_id[]" multiple id="r_id" class="form-control">
														<option	value="1">Principal</option>
														<?php
															$total_user_list = $wpdb->get_results( "SELECT * FROM $studenttable WHERE class_id IN( select class_id from $studenttable where wp_usr_id=$student_id )" );
															foreach ($total_user_list as $usr) {
																if ( intval($usr->wp_usr_id) != intval($student_id )) { ?>
																	<option	value="<?php echo intval($usr->wp_usr_id); ?>"><?php echo $usr->s_fname; ?></option>
																<?php }
															}
														?>
													</select>
												<?php	
												} ?>
                                            </div>
                                            <div class="col-lg-6 col-md-6 none" id="receiverGroups">
                                                <select name="group" id="group" class="form-control">
                                                    <option value="">Select Group</option>
                                                    <option value="parents">All Parents</option>
                                                    <option value="teachers">All Teachers</option>
                                                    <option value="students">All Students</option>
                                                    <optgroup label="Class Students">
                                                        <?php
                                                        $class_table = $wpdb->prefix . "wpsp_class";
                                                        $class_ids = $wpdb->get_results("select cid,c_name from $class_table");
                                                        foreach ($class_ids as $clss) {
                                                            ?>
                                                            <option value="s.<?php echo intval($clss->cid); ?>">
                                                                Class <?php echo $clss->c_name; ?> Students
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </optgroup>
                                                    <optgroup label="Class Parents">
                                                        <?php
                                                        foreach ($class_ids as $clss) {
                                                            ?>
                                                            <option value="p.<?php echo intval($clss->cid); ?>">
                                                                Class <?php echo $clss->c_name; ?> Parents
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </optgroup>
                                                </select> <a href="#" id="showUser">Select Users</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Subject</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="subject" id="subject">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Message</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" name="message" id="message" rows="5"
                                                          cols="10"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-4 col-sm-8">
                                                <input type="submit" class="btn btn-primary" name="send" id="send" value="Send"/>
                                                <button id="cancel" data-dismiss="modal" class="btn btn-default">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="formresponse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
            <div class="modal fade" id="replyMessageModal" tabindex="-1" role="dialog" aria-labelledby="replyMessageModal"
                 aria-hidden="true">
                <div class="modal-lg modal-dialog">
                    <div class="modal-content">
                        <div class="col-md-12">
                            <div class="box box-success">
                                <div class="box-header">
                                    <h3 class="box-title">Reply to Message</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                </div><!-- /.box-header -->
                                <div class="box-body" id="viewMessageContainer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.Reply modal -->
            <?php
            wpsp_body_end();
            wpsp_footer();
        }
	}
	else{
		//Include Login Section
		include_once( WPSP_PLUGIN_PATH.'/includes/wpsp-login.php');
	}
		?>
		