<?php /* Smarty version 2.6.27, created on 2015-10-15 19:21:57
         compiled from CRM/common/openFlashChart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/openFlashChart.tpl', 1, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><script type="text/javascript" src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
packages/OpenFlashChart/js/json/openflashchart.packed.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
packages/OpenFlashChart/js/swfobject.js"></script>
<?php echo '
<script type="text/javascript">
    function createSWFObject( chartID, divName, xSize, ySize, loadDataFunction ) {
       var flashFilePath = '; ?>
"<?php echo $this->_tpl_vars['config']->resourceBase; ?>
packages/OpenFlashChart/open-flash-chart.swf"<?php echo ';

       //create object.
       swfobject.embedSWF( flashFilePath, divName,
                         xSize, ySize, "9.0.0",
                         "expressInstall.swf",
                         {"get-data":loadDataFunction, "id":chartID},
                         null,
                         {"wmode": \'transparent\'}
                        );
    }
  OFC = {};
  OFC.jquery = {
           name: "jQuery",
             image: function(src) { return "<img src=\'data:image/png;base64," + $(\'#\'+src)[0].get_img_binary() + "\' />"},
             popup: function(src) {
             var img_win = window.open(\'\', \'Save Chart as Image\');
           img_win.document.write(\'<html><head><title>Save Chart as Image<\\/title><\\/head><body>\' + OFC.jquery.image(src) + \' <\\/body><\\/html>\');
           img_win.document.close();
                       }
                 }

function save_image( divName ) {
      var divId = '; ?>
"<?php echo $this->_tpl_vars['contriChart']; ?>
"<?php echo ' ? \'open_flash_chart_\'+divName : '; ?>
"<?php echo $this->_tpl_vars['divId']; ?>
"<?php echo ';
          if( !divId ) {
               divId = \'open_flash_\'+divName;
        }
      OFC.jquery.popup( divId );
}

</script>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>