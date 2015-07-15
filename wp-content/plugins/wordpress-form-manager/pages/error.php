<?php
global $wpdb;

$tables = array(
	$wpdb->prefix.get_option( 'fm-forms-table-name' ),
	$wpdb->prefix.get_option( 'fm-items-table-name' ),
	$wpdb->prefix.get_option( 'fm-settings-table-name' ),
	$wpdb->prefix.get_option( 'fm-templates-table-name' )
	);
	
if ( isset( $_POST['fm-reinstall-submit'] ) ){
	fm_uninstall();
}

?>
<h3>
<?php
_e("There was a problem accessing the database.", 'wordpress-form-manager');
?>
</h3>
<p>
	<?php
	_e("If you recently made changes to your database, you may have renamed or deleted some of the following tables:", 'wordpress-form-manager');
	?>
	<ul>
		<?php
		foreach( $tables as $tableName ){
			echo '<li>'.$tableName.'</li>';
		}
		?>
	</ul>
</p>

<form method="post" action="">
	<p>
		<?php
		_e("Click here to reinstall Form Manager", 'wordpress-form-manager');
		?>
	</p>
	<p>
		<input type="submit" name="fm-reinstall-submit" value="<?php _e("Reinstall",'wordpress-form-manager');?>" />
	</p>
</form>
