<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$class_id	=	intval($_POST['ClassID']);
$subject_id	=	intval($_POST['SubjectID']);
$exam_id	=	intval($_POST['ExamID']);
$error		=	'';
if( empty( $class_id ) ) {
	$error .='<li>ClassID Missing!</li>';
} if( empty( $subject_id ) ) {
	$error .='<li>SubjectID Missing!</li>';
} if( empty( $exam_id ) ) {
	$error .='<li>ExamID Missing!</li>';
}

if(  empty( $error ) && (wpsp_IsMarkEntered( $class_id, $subject_id, $exam_id ) ) ) {
	$extra_tbl		=	$wpdb->prefix."wpsp_mark_fields";
	$student_table	=	$wpdb->prefix."wpsp_student";
	$extra_fields	=	$wpdb->get_results("select * from $extra_tbl where subject_id='$subject_id'");
	$wpsp_marks		=	wpsp_GetMarks($class_id,$subject_id,$exam_id);
	$wpsp_exmarks	=	wpsp_GetExMarks($subject_id,$exam_id);
	$class_students	=	$wpdb->get_results("select s_rollno,wp_usr_id,s_fname,s_mname,s_lname from $student_table where class_id='$class_id'",ARRAY_A);
	$students_list	=	$extra_marks	=	array();
	foreach($class_students as $cstud){
		$students_list[$cstud['wp_usr_id']]=array('rollno'=>$cstud['s_rollno'],'name'=>$cstud['s_fname'].' '.$cstud['s_mname'].' '.$cstud['s_lname']);
	}
	foreach($wpsp_exmarks as $exmark){
		$extra_marks[$exmark->student_id][$exmark->field_id]=$exmark->mark;
	}
?>

	<div class="col-md-12 col-lg-12 col-sm-12" id="marks-information">

		<?php

			$classTable		=	$wpdb->prefix.'wpsp_class';			

			$className	=	$wpdb->get_var( "SELECT c_name FROM `$classTable` where cid=$class_id" );

			

			

			$subTable	=	$wpdb->prefix."wpsp_subject";

			$subName	=	$wpdb->get_var( "SELECT sub_name FROM `$subTable` where id=$subject_id" );

			

			

			$exTable	=	$wpdb->prefix.'wpsp_exam';

			$examName	=	$wpdb->get_var( "SELECT e_name FROM `$exTable` where eid=$exam_id" );

			

			echo '<div class="wp-mark-info" style="display:none;">';

			echo !empty( $className ) ? '<b>Class Name 		: </b>'.$className : '';

			echo !empty( $subName ) ? '<br><b>Subject Name	: </b>'.$subName : '';

			echo !empty( $examName ) ? '<br><b>Exam Name	: </b>'.$examName : '';

			echo '</div>';

		?>

		

		<table class="table table-bordered table-striped table-responsive" id="wp-student-mark" style="width:100%">

			<thead>

				<tr>

					<th width="10%" class="nosort">#</th>

					<th>RollNo.</th>

					<th>Name</th>

					<th>Mark</th>

					<?php if(!empty($extra_fields)){

							foreach($extra_fields as $extf){

							?>

								<th><?php echo $extf->field_text;?></th>

							<?php } } ?>

				</tr>

			</thead>

			<tbody>

				<?php $sno=1; foreach($wpsp_marks as $mark){ ?>

				<tr>

					<td> <?php echo $sno;?> </td>

					<td> <?php echo isset( $students_list[$mark->student_id]['rollno'] ) ? $students_list[$mark->student_id]['rollno'] : '';?> </td>

					<td> <?php echo isset( $students_list[$mark->student_id]['name'] ) ? $students_list[$mark->student_id]['name'] : '';?> </td>

					<td> <?php echo $mark->mark; ?> </td>

					<?php if(!empty($extra_fields)){

						foreach($extra_fields as $extf){

					?>

					<td> <?php echo isset( $extra_marks[$mark->student_id][$extf->field_id] ) ? $extra_marks[$mark->student_id][$extf->field_id] : ''; ?> </td>

					<?php } } ?>

				</tr>

				<?php $sno++; } ?>

			

			</tbody>

		</table>			

	</div>

	<?php

		$proversion		=	wpsp_check_pro_version();

		$proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';

		$protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';

		$prodisable		=	!$proversion['status'] ? 'disabled="disabled"'	: '';

	?>

	<div class="col-md-8 col-md-offset-4">	

		<form id="ExportMarksForm" name="ExportMarks" method="POST">

			<input type="hidden" name="ClassID" value="<?php echo $class_id;?>">

			<input type="hidden" name="SubjectID" value="<?php echo $subject_id;?>">

			<input type="hidden" name="ExamID" value="<?php echo $exam_id;?>">

			<input type="hidden" name="exportmarks" value="exportmarks">

			<button type="submit" class="btn btn-primary <?php echo $proclass;?>" title="<?php echo $protitle;?>" <?php echo $prodisable; ?>><i class="fa fa-download"></i> Export </button>

			<button type="button" class="btn btn-primary <?php echo $proclass;?>" title="<?php echo $protitle;?>" <?php echo $prodisable; ?> id="PrintMarks"><i class="fa fa-print"></i> Print </button>

		</form>			

	</div>



<?php 	

} else { 

	$error .="<li>Marks not yet entered!</li>";

?>

	<div class="col-md-12"><div class="alert alert-danger"><?php echo $error; ?></div></div>



<?php }

?>