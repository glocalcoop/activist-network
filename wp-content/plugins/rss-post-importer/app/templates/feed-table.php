<table class="widefat rss_pi-table" id="rss_pi-feed-table">
	<thead>
		<tr>
			<th><?php _e("Feed name", 'rss_pi'); ?></th>
			<th><?php _e("Feed url", 'rss_pi'); ?></th>
			<th><?php _e("Max posts / import", 'rss_pi'); ?></th>
			<!--<th><?php _e("Category", 'rss_pi'); ?></th>-->
		</tr>
	</thead>
	<tbody class="rss-rows">
		<?php
		$saved_ids = array();

		if (is_array($this->options['feeds']) && count($this->options['feeds']) > 0) :
			foreach ($this->options['feeds'] as $f) :
				$category = get_the_category($f['category_id']);
				array_push($saved_ids, $f['id']);
				include( RSS_PI_PATH . 'app/templates/feed-table-row.php');
			endforeach;
		else :
			?>
			<tr>
				<td colspan="4" class="empty_table">
					<?php _e('You haven\'t specified any feeds to import yet, why don\'t you <a href="#" class="add-row">add one now</a>?', "rss_pi"); ?>
				</td>
			</tr>
		<?php
		endif
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4">
				<a href="#" class="button button-large button-primary add-row">
					<?php _e('Add new feed', "rss_pi"); ?>
				</a>
				<input type="hidden" name="ids" id="ids" value="<?php echo(join($saved_ids, ',')); ?>" />
			</td>
		</tr>
<?php
		// preload an empty (and hidden by css) "new feed" row
		unset($f);
		include( RSS_PI_PATH . 'app/templates/feed-table-row.php');
?>
	</tfoot>
</table>
<style>
.rss_pi-table tfoot tr.data-row,.rss_pi-table tfoot tr.edit-row{display:none;}
</style>