<!-- assets/templates/utilities.php -->
<div id="icon-options-general" class="icon32"></div>

<div class="wrap">

	<h2 class="nav-tab-wrapper">
		<a href="<?php echo $urls['settings']; ?>" class="nav-tab"><?php _e( 'Settings', 'bp-groups-civicrm-sync' ); ?></a>
		<a href="<?php echo $urls['utilities']; ?>" class="nav-tab nav-tab-active"><?php _e( 'Utilities', 'bp-groups-civicrm-sync' ); ?></a>
	</h2>

	<?php

	// if we've got any messages, show them...
	if ( isset( $messages ) AND ! empty( $messages ) ) echo $messages;

	?>

	<form method="post" id="bp_groups_civicrm_sync_utilities_form" action="<?php echo $this->admin_form_url_get(); ?>">

		<?php wp_nonce_field( 'bp_groups_civicrm_sync_utilities_action', 'bp_groups_civicrm_sync_nonce' ); ?>

		<h3><?php _e( 'BuddyPress to CiviCRM Sync', 'bp-groups-civicrm-sync' ); ?></h3>

		<p><?php _e( 'WARNING: this will probably only work when there are a small number of groups. If you have lots of groups, it would be worth writing some kind of chunked update routine. I will upgrade this plugin to do so at some point.', 'bp-groups-civicrm-sync' ); ?></p>

		<table class="form-table">

			<tr valign="top">
				<th scope="row"><?php _e( 'Sync BP Groups to CiviCRM', 'bp-groups-civicrm-sync' ); ?></th>
				<td><input type="submit" id="bp_groups_civicrm_sync_bp_check" name="bp_groups_civicrm_sync_bp_check" value="<?php _e( 'Sync Now', 'bp-groups-civicrm-sync' ); ?>" class="button-primary" /></td>
			</tr>

		</table>

		<hr>

		<h3><?php _e( 'Check BuddyPress and CiviCRM Sync', 'bp-groups-civicrm-sync' ); ?></h3>

		<p><?php _e( 'Check this to find out if there are BuddyPress Groups with no CiviCRM Group and vice versa.', 'bp-groups-civicrm-sync' ); ?></p>

		<table class="form-table">

			<tr valign="top">
				<th scope="row"><label for="bp_groups_civicrm_sync_bp_check_sync"><?php _e( 'Check BP Groups and CiviCRM Groups', 'bp-groups-civicrm-sync' ); ?></label></th>
				<td><input type="submit" id="bp_groups_civicrm_sync_bp_check_sync" name="bp_groups_civicrm_sync_bp_check_sync" value="<?php _e( 'Check Now', 'bp-groups-civicrm-sync' ); ?>" class="button-primary" /></td>
			</tr>

		</table>

		<hr>

		<?php if ( $og_to_bp_do_sync ) : ?>

			<h3><?php _e( 'Convert OG groups in CiviCRM to BP groups', 'bp-groups-civicrm-sync' ); ?></h3>

			<p><?php _e( 'WARNING: this will probably only work when there are a small number of groups. If you have lots of groups, it would be worth writing some kind of chunked update routine. I will upgrade this plugin to do so at some point.', 'bp-groups-civicrm-sync' ) ?></p>

			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php _e( 'Convert OG groups to BP groups', 'bp-groups-civicrm-sync' ); ?></th>
					<td><input type="submit" id="bp_groups_civicrm_sync_convert" name="bp_groups_civicrm_sync_convert" value="<?php _e( 'Convert Now', 'bp-groups-civicrm-sync' ); ?>" class="button-primary" /></td>
				</tr>

			</table>

		<?php else : ?>

			<h3><?php _e( 'CiviCRM to BuddyPress Sync', 'bp-groups-civicrm-sync' ); ?></h3>

			<?php if ( $checking_og ) : ?>

				<p><?php _e( 'No OG Groups found', 'bp-groups-civicrm-sync' ); ?></p>

			<?php else : ?>

				<table class="form-table">

					<tr valign="top">
						<th scope="row"><?php _e( 'Check for OG groups', 'bp-groups-civicrm-sync' ); ?></th>
						<td><input id="bp_groups_civicrm_sync_og_check" name="bp_groups_civicrm_sync_og_check" value="<?php _e( 'Check Now', 'bp-groups-civicrm-sync' ); ?>" type="submit" class="button-primary" /></td>
					</tr>

				</table>

			<?php endif; ?>

		<?php endif; ?>

	</form>

</div><!-- /.wrap -->



