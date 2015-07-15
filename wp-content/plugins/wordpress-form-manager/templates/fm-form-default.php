<?php
/*
Template Name: Form Manager Default
Template Description: The default dislplay template for forms
Template Type: form

option: $showFormTitle, checkbox
	label: Show form title:
	default: checked
option: $showBorder, checkbox
	label: Show border:
	default: checked	
option: $labelPosition, select
	label: Label position:
	description: Labels can be placed to the left or above each field
	options: 'left' => 'Left', 'top' => 'Top'
	default: left
option: $labelWidth, text
	label: Label width (in pixels):
	description: Applies to checkboxes, and when labels are to the left
	default: 200

	
//////////////////////////////////////////////////////////////////////////////////////////

Below are the functions that can be used within a form display template:

fm_form_start(), fm_form_end() - These can be called (not echo'ed) to open and close the form, respectively.
fm_form_hidden() - Nonce and other hidden values required for the form; can be omitted if fm_form_end() is used.
fm_form_the_title() - The form's title

The following can be used in place of the fm_form_start() function:
fm_form_class() - The default form CSS class
fm_form_action() - The default form action
fm_form_ID() - Used for the opening form tag's 'name' and 'id' attributes.

fm_form_the_submit_btn() - A properly formed submit button
fm_form_submit_btn_name() - Submit button's 'name' attribute
fm_form_submit_btn_id() - Submit button's 'id' attribute
fm_form_submit_btn_text() - Submit button's 'value' attribute, as set in the form editor.
fm_form_submit_btn_script() - Validation script

fm_form_have_items() - Returns true if there are more items (used to loop through the form items, similar to have_posts() in wordpress themes)
fm_form_the_item() - Sets up the current item (similar to the_post() in wordpress themes)
fm_form_the_label() - The current item's label
fm_form_the_input() - The current item's input element
fm_form_the_nickname() - The current item's nickname

fm_form_is_separator() - Returns true if the current element is a horizontal line
fm_form_is_note() - Returns true if the current element is a note
fm_form_is_required() - Returns true if the current item is set as required
fm_form_item_type() - The current item's type

fm_form_get_item_input($nickname) - get an item's input by nickname
fm_form_get_item_label($nickname) - get an item's label by nickname

//////////////////////////////////////////////////////////////////////////////////////////

*/

/* translators: the following are for the options for the default form display template */
__("Show form title:", 'wordpress-form-manager');
__("Show border:", 'wordpress-form-manager');
__("Label position:", 'wordpress-form-manager');
__("Labels can be placed to the left or above each field", 'wordpress-form-manager');
_x('Left', 'template-option', 'wordpress-form-manager');
_x('Top', 'template-option', 'wordpress-form-manager');
__("Label width (in pixels):", 'wordpress-form-manager');
__("Applies to checkboxes, and when labels are to the left", 'wordpress-form-manager');

?>
<?php echo fm_form_start(); ?>

	<?php if($showBorder): ?><fieldset><?php endif; ?>
	
		<?php if($showFormTitle): ?>
			<?php if($showBorder): ?>
				<legend><?php echo fm_form_the_title(); ?></legend>
			<?php else: ?>
				<h2><?php echo fm_form_the_title(); ?></h2>
			<?php endif; ?>
		<?php endif; ?>
		
		<ul>
			<?php while(fm_form_have_items()): fm_form_the_item(); ?>
			<li id="fm-item-<?php echo (fm_form_the_nickname() != "" ? fm_form_the_nickname() : fm_form_the_ID()); ?>">
				<?php if($labelPosition == "top"): ?>
					<label style="display:block;width:<?php echo $labelwidth;?>px;"><?php echo fm_form_the_label(); ?>
					<?php if(fm_form_is_required()) echo "&nbsp;<em>*</em>"; ?>
					</label><?php echo fm_form_the_input(); ?>
				<?php else: ?>
					<table><tr>
						<td style="width:<?php echo $labelWidth; ?>px"><label><?php echo fm_form_the_label(); ?><?php if(fm_form_is_required()) echo "&nbsp;<em>*</em>"; ?></label></td>
						<td><?php echo fm_form_the_input(); ?></td>
					</tr></table>
				<?php endif; ?>
			</li>
			<?php endwhile; ?>
		</ul>

		<div>
		 <?php echo fm_form_the_submit_btn(); ?>
		</div>

	<?php if($showBorder): ?></fieldset><?php endif; ?>

	<?php echo fm_form_hidden(); ?>
<?php echo fm_form_end(); ?>