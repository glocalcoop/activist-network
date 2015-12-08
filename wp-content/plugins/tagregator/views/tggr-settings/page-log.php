<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2><?php esc_html_e( TGGR_NAME ); ?> Log</h2>

	<p><strong>Warning:</strong> These logs will contain your private API keys, so don't post them on public support forums, etc.</p>

	<table class="widefat">
		<thead>
			<tr>
				<th>Time</th>
				<th>Method</th>
				<th>Message</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $log_entries as $entry ) : ?>
				<tr>
					<td><?php echo esc_html( $entry['timestamp'] ); ?></td>
					<td><?php echo esc_html( $entry['method'] ); ?></td>
					<td>
						<h3><?php echo esc_html( $entry['message'] ); ?></h3>

						<div>
							<pre><?php echo esc_html( print_r( $entry['data'], true ) ); ?></pre>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div> <!-- .wrap -->
