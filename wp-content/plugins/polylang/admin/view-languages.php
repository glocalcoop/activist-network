<?php
// displays the Languages admin panel
?>
<div class="wrap"><?php
	screen_icon('options-general'); ?>

	<h2 class="nav-tab-wrapper"><?php
	// display tabs
	foreach ($tabs as $key => $name)
		printf(
<<<<<<< HEAD
			'<a href="options-general.php?page=mlang&amp;tab=%1$s" id="nav-tab-%1$s" class="nav-tab %2$s">%3$s</a>',
=======
			'<a href="options-general.php?page=mlang&amp;tab=%s" class="nav-tab %s">%s</a>',
>>>>>>> bc2eb663d075e7d5adde91b15739950076b25eb2
			$key,
			$key == $this->active_tab ? 'nav-tab-active' : '',
			$name
		);?>
	</h2><?php

	switch($this->active_tab) {

		case 'lang': // Languages tab
		case 'strings': // string translations tab
		case 'settings': // settings tab
			include(PLL_ADMIN_INC.'/view-tab-' . $this->active_tab . '.php');
			break;

		default:
			do_action('pll_settings_active_tab_' . $this->active_tab);
			break;
	}?>

</div><!-- wrap -->
