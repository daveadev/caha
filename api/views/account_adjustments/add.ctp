<div class="accountAdjustments form">
<?php echo $this->Form->create('AccountAdjustment');?>
	<fieldset>
		<legend><?php __('Add Account Adjustment'); ?></legend>
	<?php
		echo $this->Form->input('account_id');
		echo $this->Form->input('item_code');
		echo $this->Form->input('flag');
		echo $this->Form->input('amount');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Account Adjustments', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>