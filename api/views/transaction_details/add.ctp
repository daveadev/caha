<div class="transactionDetails form">
<?php echo $this->Form->create('TransactionDetail');?>
	<fieldset>
		<legend><?php __('Add Transaction Detail'); ?></legend>
	<?php
		echo $this->Form->input('transaction_id');
		echo $this->Form->input('ref_no');
		echo $this->Form->input('details');
		echo $this->Form->input('amount');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Transaction Details', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Transactions', true), array('controller' => 'transactions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction', true), array('controller' => 'transactions', 'action' => 'add')); ?> </li>
	</ul>
</div>