<div class="accountTransactions form">
<?php echo $this->Form->create('AccountTransaction');?>
	<fieldset>
		<legend><?php __('Edit Account Transaction'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('account_id');
		echo $this->Form->input('transaction_type_id');
		echo $this->Form->input('ref_no');
		echo $this->Form->input('amount');
		echo $this->Form->input('source');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('AccountTransaction.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('AccountTransaction.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Account Transactions', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Types', true), array('controller' => 'transaction_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Type', true), array('controller' => 'transaction_types', 'action' => 'add')); ?> </li>
	</ul>
</div>