<div class="transactions form">
<?php echo $this->Form->create('Transaction');?>
	<fieldset>
		<legend><?php __('Edit Transaction'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('type');
		echo $this->Form->input('status');
		echo $this->Form->input('ref_no');
		echo $this->Form->input('transac_date');
		echo $this->Form->input('transac_time');
		echo $this->Form->input('account_id');
		echo $this->Form->input('amount');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Transaction.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Transaction.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Transactions', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Details', true), array('controller' => 'transaction_details', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Detail', true), array('controller' => 'transaction_details', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Payments', true), array('controller' => 'transaction_payments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Payment', true), array('controller' => 'transaction_payments', 'action' => 'add')); ?> </li>
	</ul>
</div>