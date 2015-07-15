<table class="widefat rss_pi-table" id="rss_pi-settings-table">
	<thead>
		<tr>
			<th colspan="5"><?php _e('Settings', 'rss_pi'); ?></th>
		</tr>
	</thead>
	<tbody class="setting-rows">
		<tr class="edit-row show">
			<td colspan="4">
				<table class="widefat edit-table">
					<tr>
						<td>
							<label for="frequency"><?php _e('Frequency', "rss_pi"); ?></label>
							<p class="description"><?php _e('How often will the import run.', "rss_pi"); ?></p>
						</td>
						<td>
							<select name="frequency" id="frequency">
								<?php $x = wp_get_schedules(); ?>
								<?php foreach (array_keys($x) as $interval) : ?>
									<option value="<?php echo $interval; ?>" <?php
									if ($this->options['settings']['frequency'] == $interval) : echo('selected="selected"');
									endif;
									?>><?php echo $x[$interval]['display']; ?></option>
										<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="feeds_api_key"><?php _e('Full Text RSS Feed API Key', "rss_pi"); ?></label>
							<?php if ( ! $this->is_key_valid ) : ?>
							<p class="description">
								<?php _e('Boost Your traffic with Full RSS Content - ', "rss_pi"); ?> 
								Request a Free 14 Days <a href="http://www.feedsapi.com/?utm_source=rsspi-full-rss-key-here" target="_blank"> Full RSS Key Here !</a> 
							</p>
							<?php endif; ?>
						</td>
						<td>
							<?php $feeds_api_key = isset($this->options['settings']["feeds_api_key"]) ? $this->options['settings']["feeds_api_key"] : ""; ?>
							<input type="text" name="feeds_api_key" id="feeds_api_key" value="<?php echo $feeds_api_key; ?>" />
						</td>
					</tr>

					<tr>
						<td>
							<label for="post_template"><?php _e('Template', 'rss_pi'); ?></label>
							<p class="description"><?php _e('This is how the post will be formatted.', "rss_pi"); ?></p>
							<p class="description">
								<?php _e('Available tags:', "rss_pi"); ?>
							<dl>
								<dt><code>&lcub;$content&rcub;</code></dt>
								<dt><code>&lcub;$permalink&rcub;</code></dt>
								<dt><code>&lcub;$title&rcub;</code></dt>
								<dt><code>&lcub;$feed_title&rcub;</code></dt>
								<dt><code>&lcub;$excerpt:n&rcub;</code></dt>
							</dl>
							</p>
						</td>
						<td>
							<textarea name="post_template" id="post_template" cols="30" rows="10"><?php
								$value = (
										$this->options['settings']['post_template'] != '' ? $this->options['settings']['post_template'] : '{$content}' . "\nSource: " . '{$feed_title}'
										);

								$value = str_replace(array('\r', '\n'), array(chr(13), chr(10)), $value);

								echo stripslashes($value);
								?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label for="post_template"><?php _e('Keywords Filter', 'rss_pi'); ?></label>
							<p class="description"><?php _e('Enter keywords and/or regex, separated by commas', "rss_pi"); ?></p>
							<p class="description">
								<?php _e('Only posts matching these keywords/regex will be imported', "rss_pi"); ?>
							</p>
						</td>
						<td>
							<?php
							$disabled = '';
							if (!$this->is_key_valid) {
								$disabled = ' disabled="disabled"';
								$this->key_error( sprintf( $this->key_prompt, '', 'http://www.feedsapi.com/?utm_source=rsspostimporter&utm_medium=upgrade&utm_term=keywords-filters&utm_content=rsspi-full-rss-key-here&utm_campaign=wordpress' ), true );
							}
							?>
							<textarea name="keyword_filter" id="post_template" cols="30" rows="10"<?php echo $disabled; ?>><?php
								echo implode(', ', $this->options['settings']['keywords']);
								?></textarea>
						</td>
					</tr>
					<tr>
						<td><label for="post_status"><?php _e('Post status', "rss_pi"); ?></label></td>
						<td>

							<select name="post_status" id="post_status">
								<?php
								$statuses = get_post_stati('', 'objects');

								foreach ($statuses as $status) {
									?>
									<option value="<?php echo($status->name); ?>" <?php
									if ($this->options['settings']['post_status'] == $status->name) : echo('selected="selected"');
									endif;
									?>><?php echo($status->label); ?></option>
											<?php
										}
										?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php _e('Author', 'rss_pi'); ?></td>
						<td>
							<?php
							$args = array(
								'id' => 'author_id',
								'name' => 'author_id',
								'selected' => $this->options['settings']['author_id']
							);
							wp_dropdown_users($args);
							?> 
						</td>
					</tr>
					<tr>
						<td><?php _e('Allow comments', "rss_pi"); ?></td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="allow_comments_open" name="allow_comments" value="open" <?php echo($this->options['settings']['allow_comments'] == 'open' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_pi'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="allow_comments_false" name="allow_comments" value="false" <?php echo($this->options['settings']['allow_comments'] == 'false' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_pi'); ?></label>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td>
							<?php _e('Block search indexing?', "rss_pi"); ?>
							<p class="description"><?php _e('Prevent your content from appearing in search results.', "rss_pi"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="block_indexing_true" name="block_indexing" value="true" <?php echo($this->options['settings']['block_indexing'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_pi'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="block_indexing_false" name="block_indexing" value="false" <?php echo($this->options['settings']['block_indexing'] == 'false' || $this->options['settings']['block_indexing'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_pi'); ?></label>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td>
							<?php _e('Nofollow option for all outbound links?', "rss_pi"); ?>
							<p class="description"><?php _e('Add rel="nofollow" to all outbounded links.', "rss_pi"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="nofollow_outbound_true" name="nofollow_outbound" value="true" <?php echo($this->options['settings']['nofollow_outbound'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_pi'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="nofollow_outbound_false" name="nofollow_outbound" value="false" <?php echo($this->options['settings']['nofollow_outbound'] == 'false' || $this->options['settings']['nofollow_outbound'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_pi'); ?></label>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td>
							<?php _e('Enable logging?', "rss_pi"); ?>
							<p class="description"><?php _e('The logfile can be found <a href="#" class="load-log">here</a>.', "rss_pi"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="enable_logging_true" name="enable_logging" value="true" <?php echo($this->options['settings']['enable_logging'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_pi'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="enable_logging_false" name="enable_logging" value="false" <?php echo($this->options['settings']['enable_logging'] == 'false' || $this->options['settings']['enable_logging'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_pi'); ?></label>
								</li>
							</ul>
						</td> 
					</tr>
					<tr>
						<td>
							<?php _e('Download and save images locally?', "rss_pi"); ?>
							<p class="description"><?php _e('Images in the feeds will be downloaded and saved in the WordPress media.', "rss_pi"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="import_images_locally_true" name="import_images_locally" value="true" <?php echo($this->options['settings']['import_images_locally'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_pi'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="import_images_locally_false" name="import_images_locally" value="false" <?php echo($this->options['settings']['import_images_locally'] == 'false' || $this->options['settings']['enable_logging'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_pi'); ?></label>
								</li>
							</ul>
						</td> 
					</tr>
					<tr>
						<td>
							<?php _e('Disable the featured image?', "rss_pi"); ?>
							<p class="description"><?php _e('Don\'t set a featured image for the imported posts.', "rss_pi"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="disable_thumbnail_true" name="disable_thumbnail" value="true" <?php echo($this->options['settings']['disable_thumbnail'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_pi'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="disable_thumbnail_false" name="disable_thumbnail" value="false" <?php echo($this->options['settings']['disable_thumbnail'] == 'false' || $this->options['settings']['disable_thumbnail'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_pi'); ?></label>
								</li>
							</ul>
						</td> 
					</tr>
					<tr>
						<td>
							<?php _e('Import already deleted posts?', "rss_pi"); ?>
							<p class="description"><?php _e('Allow imported and later deleted posts to be imported once again.', "rss_pi"); ?></p>
						</td>
						<td>
							<?php
							$disabled = '';
							if (!$this->is_key_valid) {
								$disabled = ' disabled="disabled"';
								$this->key_error( sprintf( $this->key_prompt, '', 'http://www.feedsapi.com/?utm_source=rsspostimporter&utm_medium=upgrade&utm_term=import-deleted&utm_content=rsspi-full-rss-key-here&utm_campaign=wordpress' ), true );
							}
							?>
							<ul class="radiolist">
								<li>
									<label class="tooltips"><input type="radio" id="cache_deleted_true" name="cache_deleted" value="false" <?php echo($this->options['settings']['cache_deleted'] == 'false' ? 'checked="checked"' : ''); ?><?php echo $disabled; ?> /> <?php _e('Yes', 'rss_pi'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="cache_deleted_false" name="cache_deleted" value="true" <?php echo($this->options['settings']['cache_deleted'] == 'true' || $this->options['settings']['cache_deleted'] == '' ? 'checked="checked"' : ''); ?><?php echo $disabled; ?> /> <?php _e('No', 'rss_pi'); ?></label>
								</li>
							</ul>
						</td> 
					</tr>
					<?php if ( isset($this->options['upgraded']['deleted_posts']) ) { ?>
					<tr>
						<td>
							<?php _e('Purge deleted posts cache', "rss_pi"); ?>
							<p class="description"><?php _e('This option will allow you to reset the deleted posts cache and re-import posts you have deleted in the past.', "rss_pi"); ?></p>
						</td>
						<td>
							<?php
							$disabled = '';
							if (!$this->is_key_valid) {
								$disabled = ' disabled="disabled"';
								$this->key_error( sprintf( $this->key_prompt, '', 'http://www.feedsapi.com/?utm_source=rsspostimporter&utm_medium=upgrade&utm_term=purge-deleted-cache&utm_content=rsspi-full-rss-key-here&utm_campaign=wordpress' ), true );
							}
							?>
							<?php $rss_pi_deleted_posts = count( get_option( 'rss_pi_deleted_posts', array() ) ); ?>
							<p><?php printf( _n('Cached: <strong>%d</strong> deleted post', 'Cached: <strong>%d</strong> deleted posts', $rss_pi_deleted_posts, 'rss_pi'), $rss_pi_deleted_posts ); ?></p>
							<input type="submit" value="Purge Cache" name="purge_deleted_cache" class="button button-primary button-large"<?php echo $disabled; ?> />     
						</td> 
					</tr>
					<?php } ?>
					<tr>
						<td>
							<?php _e('Export and backup your Feeds and setting as CSV File', "rss_pi"); ?>
							<p class="description"><?php _e('This option will help you download a csv file with all your feeds setting , you can upload it back later.', "rss_pi"); ?></p>
						</td>
						<td>
						<?php
						$disabled = '';
						if (!$this->is_key_valid) {
							$disabled = ' disabled="disabled"';
							$this->key_error( sprintf( $this->key_prompt, '', 'http://www.feedsapi.com/?utm_source=rsspostimporter&utm_medium=upgrade&utm_term=export-feeds&utm_content=rsspi-full-rss-key-here&utm_campaign=wordpress' ), true );
						}
						?>
							<input type="submit" value="Export your Feeds and Setting as CSV File" name="csv_download" class="button button-primary button-large"<?php echo $disabled; ?> />     
						</td> 

					</tr>
					<tr>
						<td>
							<?php _e('Import your CSV file with your feeds\' settings', "rss_pi"); ?>
							<p class="description"><?php _e('Create and Import a CSV file with your Feeds\' Setting with the following Structure and heading:<br/>
<br/>
url , name, max_posts, author_id, category_id, tags, keywords, strip_html<br/>
<br/>
url = your feed url<br/>
name = the name you gives to your feed<br/>
max_posts = the number of posts to simultaneously import<br/>
author_id = your author ID, this is a number<br/>
category_id = the Category IDs - number(s) separated with comma (,)<br/>
tags = the Tag IDs - number(s) separated with comma (,)<br/>
keywords = the filter keywords - string(s) separated with comma (,)<br/>
strip_html = strip html tags - "true" or "false"', "rss_pi"); ?></p>
						</td>
						<td>
						<?php
						$disabled = '';
						if (!$this->is_key_valid) {
							$disabled = ' disabled="disabled"';
//								$this->key_error($this->key_prompt, true);
							$this->key_error( sprintf( $this->key_prompt, '', 'http://www.feedsapi.com/?utm_source=rsspostimporter&utm_medium=upgrade&utm_term=import-feeds&utm_content=rsspi-full-rss-key-here&utm_campaign=wordpress' ), true );
						}
						?>
							<input type="file" name="import_csv"<?php echo $disabled; ?> />
						</td> 
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>