<?php 
if ( !defined( 'ABSPATH' ) ) exit;
	$subjectid=intval($_GET['id']);
	$teacher_table=	$wpdb->prefix."wpsp_teacher";
	$teacher_data = $wpdb->get_results("select * from $teacher_table");
	$subtable=$wpdb->prefix."wpsp_subject";
	$wpsp_subjects =$wpdb->get_results("select * from $subtable where id='$subjectid'");
	foreach ($wpsp_subjects as $subject_data) {
		$subid = $subject_data->id;
		$classid = $subject_data->class_id;
		$subname = $subject_data->sub_name;
		$subcode = $subject_data->sub_code;
		$subteacherid = $subject_data->sub_teach_id;
		$subbookname = $subject_data->book_name;
	}
	?>
<section class="content-header">
				<h1>Edit Subject</h1>
				<ol class="breadcrumb">
					<li><a href="<?php wpsp_admin_url();?>sch-dashboard"><i class="fa fa-dashboard"></i>Dashboard </a></li>
					<li><a href="<?php wpsp_admin_url();?>sch-subject">Subject</a></li>
				</ol>
			</section>
<section class="content">
    			<div class="row">
        			<div class="col-md-12 ">			
<div class="box box-solid bg-blue-gradient">
	<div class="box-header ui-sortable-handle">
		<h3 class="box-title">Edit Subject Details</h3>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	</div><!-- /.box-header -->
	<form action="" name="SubjectEditForm"  id="SEditForm" method="post">
		<input type="hidden" name="cid" value="<?php echo $subid;?>">
		<div class="box-footer text-black">
			<div class="col-md-12 line_box">
					<div id="editformresponse"></div>
					<div class="row">
				<div class="form-group col-md-6">
					<label for="Name">Subject </label><span class="red">*</span>
					<input type="text" class="form-control" ID="EditSName" name="EditSName" placeholder="Subject Name" value="<?php echo $subname;?>">
					<input type="hidden" class="form-control" value="<?php echo $subid;?>" id="SRowID" name="SRowID">
					<input type="hidden" class="form-control" value="" id="ESClassID" name="ClassID">
					<input type="hidden"  id="wpsp_locationginal1" value="<?php echo admin_url();?>"/>
				</div>
				<div class="form-group col-md-6">
					<label for="Name">Subject Code</label><span class="text-gray"> (Optional)</span>
					<input type="text" class="form-control" ID="EditSCode" name="EditSCode" placeholder="Subject Code" value="<?php echo $subcode;?>">
				</div></div>
				<div class="row">
				<div class="form-group col-md-6">
					<label for="Name">Subject Teacher</label><span> (Incharge)</span>
					<select name="EditSTeacherID" id="EditSTeacherID" class="form-control">
						<option value="">Select Teacher </option>
						<?php foreach ($teacher_data as $teacher_list) { 
							$teacherlistid= $teacher_list->wp_usr_id;
								?>
							<option value="<?php echo $teacherlistid;?>" <?php if($teacherlistid == $subteacherid) echo "selected"; ?> ><?php echo $teacher_list->first_name ." ". $teacher_list->last_name;?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group col-md-6">
					<label for="BName">Book Name</label><span class="text-gray"> (Optional)</span>
					<input type="text" class="form-control" name="EditBName" id="EditBName" placeholder="Book Name" value="<?php echo $subbookname;?>">
				</div></div>
			</div>
		</div>
		<div class="box-footer">
			<span class="pull-right">
				<input type="submit" id="SEditSave" class="btn btn-primary" value="Update">
				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> -->
				<a href="<?php wpsp_admin_url();?>sch-subject" class="btn btn-info" >Back</a>
			</span>
		</div>
	
	</form>
</div></div></div></section>