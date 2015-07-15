<?php
/*
Template Name: Summary for Data List
Template Description: This is used by for displaying the submission data as a list of summaries, for example the [formdata] shortcode
Template Type: email, summary

//////////////////////////////////////////////////////////////////////////////////////////

*/
?>
<?php /* The user's first and last name, if there is a logged in user */ ?>
<?php 
$userName = fm_summary_the_user(); 
if($userName != ""){
	$userData = get_userdatabylogin($userName);
	echo "Submitted by: <strong>".$userData->last_name.", ".$userData->first_name."</strong><br />";
}
?>

<?php /* The time and date of the submission.  Look up date() in the PHP reference at php.net for more info on how to format timestamps. */ ?>
On: <strong><?php echo date("M j, Y @ g:i A", strtotime(fm_summary_the_timestamp())); ?></strong> <br />
IP: <strong><?php echo fm_summary_the_IP(); ?></strong> <br />
<?php /* The code below displays each form element, in order, along with the submitted data. */ ?>
<ul id="fm-summary-multi">
<?php while(fm_summary_have_items()): fm_summary_the_item(); ?>
	<?php if(fm_summary_the_type() == 'separator'): ?>
		<hr />
	<?php elseif(fm_summary_has_data()): ?>
		<li<?php if(fm_summary_the_nickname() != "") echo " id=\"fm-item-".fm_summary_the_nickname()."\"";?>><?php echo fm_summary_the_label();?>: <strong><?php echo fm_summary_the_value();?></strong></li>
	<?php endif; ?>
<?php endwhile; ?>
</ul>
<hr />