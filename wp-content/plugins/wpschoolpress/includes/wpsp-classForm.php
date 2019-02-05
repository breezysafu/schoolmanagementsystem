	<?php
	if ( !defined( 'ABSPATH' ) ) exit;
	$ctable=$wpdb->prefix."wpsp_class";
	$teacher_table=	$wpdb->prefix."wpsp_teacher";
	$teacher_data = $wpdb->get_results("select wp_usr_id,CONCAT_WS(' ', first_name, middle_name, last_name ) AS full_name from $teacher_table order by tid");
	$classname	= $classnumber	= $classcapacity = $classlocation = $classstartingdate = $classendingdate= $teacherid = '';
	if( isset( $_GET['id']) ) {
			$classid =	intval($_GET['id']);
			$wpsp_classes =$wpdb->get_results("select * from $ctable where cid='$classid'");
			
		foreach ($wpsp_classes as $wpsp_editclass) {
			$classname=$wpsp_editclass->c_name;	
			$classnumber=$wpsp_editclass->c_numb;	
			$classcapacity=$wpsp_editclass->c_capacity;
			$classlocation=$wpsp_editclass->c_loc;
			$classstartingdate=$wpsp_editclass->c_sdate;
			$classendingdate=$wpsp_editclass->c_edate;
			$teacherid=$wpsp_editclass->teacher_id;
		}
	}
	$label			=	isset( $_GET['id'] ) ? 'Update Class Information' : 'Add Class Information';
	$formname		=	isset( $_GET['id'] ) ? 'ClassEditForm' : 'ClassAddForm';
	$buttonname	=	isset( $_GET['id'] ) ? 'Update' : 'Submit';
	?>

	<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-blue" style="position: relative; left: 0px; top: 0px;">
                <div class="box-header" style="cursor: move;">
                    <i class="fa fa-graph"></i>
                    <h3 class="box-title"><i class="fa fa-child" aria-hidden="true"></i> <?php echo $label; ?></h3>
                    <!-- tools box -->

                    <!-- /. tools -->
                </div>
				
	<form name="<?php echo $formname;?>" id="<?php echo $formname; ?>" method="post">			
	<?php if( isset( $_GET['id']) ) { ?>
		<input type="hidden" name="cid" value="<?php echo $classid;?>">
	<?php } ?>
		<div class="box-body">
			<div class="formresponse"></div>
			<div class="col-md-12 line_box">
				<?php wp_nonce_field( 'ClassAction', 'caction_nonce', '', true ) ?>
				<div class="row">
					<div class="form-group col-md-6">
						<label for="Name">Class Name</label><span class="red">*</span>
						<input type="text" class="form-control"  name="Name" placeholder="Class Name" value="<?php echo $classname; ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="Name">Class Number</label><span class="red">*</span>
						<input type="text" class="form-control"  name="Number" placeholder="Class Number" value="<?php echo $classnumber; ?>">
						<input type="hidden"  id="wpsp_locationginal" value="<?php echo admin_url();?>"/>
					</div>
				</div>
	
				<div class="row">
					<div class="form-group col-md-6">
						<label for="Name">Class Capacity</label><span class="red">*</span>
						<input type="text" pattern="[0-9]*" class="form-control numbers"  name="capacity" placeholder="Class Capacity" id="c_capacity" value="<?php echo $classcapacity; ?>" min="0">
					</div>
					<div class="form-group col-md-6">
						<label for="Name">Class Teacher</label><span> (Incharge)</span>
						<select name="ClassTeacherID" class="form-control">
							<option value="">Select Teacher </option>
							<?php foreach ($teacher_data as $teacher_list) {
								$teacherlistid= $teacher_list->wp_usr_id;
							?>
								<option value="<?php echo $teacherlistid;?>" <?php if($teacherlistid == $teacherid) echo "selected"; ?> ><?php echo $teacher_list->full_name;?></option>
							<?php } ?>
						</select>
					</div>
				</div>
		
				<div class="row">					
					<div class="form-group col-md-6">
						<label for="Name">Class Starting on<span class="red">*</span></label><span class="text-gray"></span>
						<input type="text" class="form-control select_date wpsp-start-date" name="Sdate" placeholder="Class Starting date" value="<?php echo $classstartingdate; ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="Name">Class Ending on<span class="red">*</span></label><span class="text-gray"> (Approximate)</span>
						<input type="text" class="form-control select_date wpsp-end-date" name="Edate" placeholder="Class Ending date" value="<?php echo $classendingdate; ?>">				
					</div>
				</div>
		
				<div class="row">
					<div class="form-group col-md-6">
						<label for="Name">Class Location</label><span class="text-gray"> (Optional)</span>
						<input type="text" class="form-control" name="Location" placeholder="Class Location" value="<?php echo $classlocation; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<span class="pull-right">			
				<button type="submit" class="btn btn-primary" id="c_submit"><?php echo $buttonname; ?></button>
				<a href="<?php wpsp_admin_url();?>sch-class" class="btn btn-info" >Back</a>
			</span>
		</div>
	</form>
</div>
</div>
</div>
</section>		