<?php /* Smarty version 2.6.27, created on 2015-10-15 19:21:57
         compiled from CRM/common/wysiwyg.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/wysiwyg.tpl', 1, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php if ($this->_tpl_vars['includeWysiwygEditor']): ?>
    <?php if ($this->_tpl_vars['defaultWysiwygEditor'] == 1): ?>
        <script>
                    if (typeof window.jQuery !== 'function') window.jQuery = CRM.$;
        </script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
packages/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
packages/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
    <?php elseif ($this->_tpl_vars['defaultWysiwygEditor'] == 2): ?>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
packages/ckeditor/ckeditor.js"></script>
    <?php endif; ?>
<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>