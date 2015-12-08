<?php /* Smarty version 2.6.27, created on 2015-10-15 19:21:57
         compiled from CRM/common/info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/info.tpl', 1, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php if ($this->_tpl_vars['infoMessage']): ?>
  <div class="messages status <?php echo $this->_tpl_vars['infoType']; ?>
"<?php if ($this->_tpl_vars['infoOptions']): ?> data-options='<?php echo $this->_tpl_vars['infoOptions']; ?>
'<?php endif; ?>>
    <div class="icon inform-icon"></div>
    <span class="msg-title"><?php echo $this->_tpl_vars['infoTitle']; ?>
</span>
    <span class="msg-text"><?php echo $this->_tpl_vars['infoMessage']; ?>
</span>
  </div>
<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>