<div class="transactionPayments form">
<?php echo $this->Form->create('TransactionPayment');?>
	<fieldset>
		<legend><?php __('Edit Transaction Payment'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('transaction_id');
		echo $this->Form->input('transaction_type_id');
		echo $this->Form->input('details');
		echo $this->Form->input('amount');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('TransactionPayment.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('TransactionPayment.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Transaction Payments', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Transactions', true), array('controller' => 'transactions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction', true), array('controller' => 'transactions', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Types', true), array('controller' => 'transaction_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Type', true), array('controller' => 'transaction_types', 'action' => 'add')); ?> </li>
	</ul>
</div>