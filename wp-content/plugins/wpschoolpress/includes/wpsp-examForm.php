<?php
if ( !defined( 'ABSPATH' ) ) exit;
$extable	=	$wpdb->prefix."wpsp_exam";	
$examname = $examsdate = $examedate = $classid = $examid = '';
$subjectid	=	array();
 	
if( isset( $_GET['id']) ) { 
	$examid =	intval($_GET['id']);
	$wpsp_exams =	$wpdb->get_results( "select * from $extable where eid='$examid'");
	foreach ($wpsp_exams as $examdata) {
		$classid = $examdata->classid;
		$examname = $examdata->e_name;
		$examsdate = $examdata->e_s_date;
		$examedate = $examdata->e_e_date;
		$subjectid = explode( ",",$examdata->subject_id);		
	}
}
$label			=	isset( $_GET['id'] ) ? 'Update Exam Information' : 'Add Exam Information';
$formname		=	isset( $_GET['id'] ) ? 'ExamEditForm' : 'ExamEntryForm';
$buttonname		=	isset( $_GET['id'] ) ? 'Update' : 'Submit';
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title"><?php echo $label; ?></h3>
				</div><!-- /.box-header -->
				<form name="<?php echo $formname;?>" action="#" id="<?php echo $formname;?>" method="post">
					<div class="box-body">
						<div class="formresponse"></div>
						<div class="col-md-12">
							<div class="form-group">
								<input type="hidden"  id="wpsp_locationginal" value="<?php echo admin_url();?>"/>
								<label for="Name">Class Name </label><span class="red">*</span>
							<?php
								$classQuery	=	"select cid,c_name from $ctable";
								if( $current_user_role=='teacher' ) {
									$cuserId		=	intval($current_user->ID);
									$classQuery		=	"select cid,c_name from $ctable where teacher_id=$cuserId";														
								}
								$wpsp_classes 	=	$wpdb->get_results( $classQuery );
								if( $current_user_role=='teacher' ) {
									echo ' : '.$wpsp_classes[0]->c_name;
									echo '<input type="hidden" name="class_name" id="class_name" value="'.$wpsp_classes[0]->cid.'">';
								} else {	
								?>
									<select name="class_name" id="class_name" class="form-control">
										<option value="">Select Class</option>
										<?php
											foreach( $wpsp_classes as $value ) { 
												$classlistid = intval($value->cid);?>
												<option value="<?php echo intval($value->cid);?>" <?php if($classlistid == $classid) echo "selected"; ?>><?php echo $value->c_name;?></option>
										<?php }	?>
									</select>
								<?php } ?>	
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group exam-subject-list">
								<label for="Name">Subject Name </label><br>
							 	<input type="checkbox" name="subjectall" value="All" class="exam-all-subjects" id="all"><label for="all">All</label>
									<div class="exam-class-list">
									<?php
										 $sub_table		=	$wpdb->prefix."wpsp_subject";
										/*if( !empty( $subjectid ) ) {										
											$subjectlist	=	$wpdb->get_results("select sub_name,id from $sub_table where class_id=$classid", ARRAY_A );
											foreach ($subjectlist as $examsubject) {
												$subjectid = $examsubject['id'];
												$subjectname = $examsubject['sub_name'];
											?>
													<input type="checkbox" name="subjectid[]" value="<?php echo $subjectid ?>" class="exam-subjects" id="subject-<?php echo $subjectid;?>" checked>
													<label for="subject-<?php echo $subjectid;?>"><?php echo $subjectname;?></label> 
											<?php }
										} */?>
												
										<?php 
										
										if( !empty( $classid ) ) {
												$subjectlist	=	$wpdb->get_results("select sub_name,id from $sub_table where class_id=$classid");
												//print_r($subjectlist);
												foreach( $subjectlist as $svalue ) { ?>
													<input type="checkbox" name="subjectid[]" value="<?php echo $svalue->id; ?>" class="exam-subjects" id="subject-<?php echo $svalue->id;?>" 
													<?php if( in_array( $svalue->id, $subjectid ) ) { ?> checked <?php } ?> >
													<label for="subject-<?php echo $svalue->id;?>"><?php echo $svalue->sub_name;?></label>
												<?php
												}
											} ?>
												
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label for="Name">Exam Name </label><span class="red">*</span>
												<input type="text" class="form-control" ID="ExName" name="ExName" placeholder="Exam Name" value="<?php echo $examname; ?>">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label for="Name">Exam Start Date </label><span class="red">*</span>
												<input type="text" class="form-control select_date ExStart" ID="ExStart" name="ExStart" placeholder="Exam Start date" value="<?php echo $examsdate; ?>">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label for="Name">Exam End Date </label><span class="red">*</span>
												<input type="text" class="form-control select_date ExEnd" ID="ExEnd" name="ExEnd" placeholder="Exam End date" value="<?php echo $examedate; ?>">
											</div>
										</div>
									</div>
									<?php 
										if( !empty($examid) ) { ?>
											<input type="hidden" ID="ExamID" name="ExamID" value="<?php echo $examid; ?>">
									<?php } ?>
									<div class="box-footer text-right">
											<button type="submit" class="btn btn-primary action-button" id="e_submit"><?php echo $buttonname; ?></button>
											<a href="<?php wpsp_admin_url();?>sch-exams" class="btn btn-info" >Back</a>
									</div>
								</form>							
							</div>
						</div>
					</div>
</section>