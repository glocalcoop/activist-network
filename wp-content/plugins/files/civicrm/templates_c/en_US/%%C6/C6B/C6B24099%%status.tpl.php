<?php /* Smarty version 2.6.27, created on 2015-10-15 19:21:57
         compiled from CRM/common/status.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/status.tpl', 1, false),array('modifier', 'json_encode', 'CRM/common/status.tpl', 36, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php if ($this->_tpl_vars['session']->getStatus(false)): ?>
  <?php $this->assign('status', $this->_tpl_vars['session']->getStatus(true)); ?>
  <?php $_from = $this->_tpl_vars['status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['statLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['statLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['statItem']):
        $this->_foreach['statLoop']['iteration']++;
?>
    <?php if ($this->_tpl_vars['urlIsPublic']): ?>
      <?php $this->assign('infoType', "no-popup"); ?>
    <?php else: ?>
      <?php $this->assign('infoType', $this->_tpl_vars['statItem']['type']); ?>
    <?php endif; ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/info.tpl", 'smarty_include_vars' => array('infoTitle' => $this->_tpl_vars['statItem']['title'],'infoMessage' => $this->_tpl_vars['statItem']['text'],'infoOptions' => json_encode($this->_tpl_vars['statItem']['options']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>