<div class="fees form">
<?php echo $this->Form->create('Fee');?>
	<fieldset>
		<legend><?php __('Add Fee'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('order');
		echo $this->Form->input('Account');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Fees', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>