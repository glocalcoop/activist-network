<?php

$current = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'attachments';

if ($current != 'toolbox') {
    $this->upgrade_notice();
}

$tabs = array(
    'attachments' => __("Settings", "gd-bbpress-attachments"), 
    'faq' => __("FAQ", "gd-bbpress-attachments"), 
    'toolbox' => __("Toolbox", "gd-bbpress-attachments"), 
    'd4p' => __("Dev4Press", "gd-bbpress-attachments"), 
    'about' => __("About", "gd-bbpress-attachments")
);

if (!isset($tabs[$current])) {
    $current = 'attachments';
}

?>
<div class="wrap">
    <h2>GD bbPress Attachments</h2>
    <div id="icon-upload" class="icon32"><br></div>
    <h2 class="nav-tab-wrapper">
    <?php

    foreach($tabs as $tab => $name){
        $class = ($tab == $current) ? ' nav-tab-active' : '';

        if ($tab == 'toolbox') {
            $class.= ' d4p-tab-toolbox';
        }

        echo '<a class="nav-tab'.$class.'" href="edit.php?post_type=forum&page=gdbbpress_attachments&tab='.$tab.'">'.$name.'</a>';
    }

    ?>
    </h2>
    <div id="d4p-panel" class="d4p-panel-<?php echo $current; ?>">
        <?php include(GDBBPRESSATTACHMENTS_PATH."forms/tabs/".$current.".php"); ?>
    </div>
</div>