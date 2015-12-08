<?php /* Smarty version 2.6.27, created on 2015-10-15 19:22:02
         compiled from CRM/Dashlet/Page/Blog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Dashlet/Page/Blog.tpl', 1, false),array('block', 'ts', 'CRM/Dashlet/Page/Blog.tpl', 32, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_from = $this->_tpl_vars['blog']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['article']):
?>
<div id="civicrm-blog-feed">
  <div class="crm-accordion-wrapper collapsed">
    <div class="crm-accordion-header"><?php echo $this->_tpl_vars['article']['title']; ?>
</div>
    <div class="crm-accordion-body help">
      <div><?php echo $this->_tpl_vars['article']['description']; ?>
</div>
      <div><a href="<?php echo $this->_tpl_vars['article']['link']; ?>
" title="<?php echo $this->_tpl_vars['article']['title']; ?>
"><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>read more<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>.</a></div>
    </div>
  </div>
</div>
<?php endforeach; endif; unset($_from); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>