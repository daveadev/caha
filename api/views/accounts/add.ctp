<div class="accounts form">
<?php echo $this->Form->create('Account');?>
	<fieldset>
		<legend><?php __('Add Account'); ?></legend>
	<?php
		echo $this->Form->input('id',array('type'=>'text'));
		echo $this->Form->input('account_type');
		echo $this->Form->input('account_details');
		echo $this->Form->input('outstanding_balance');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Accounts', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Account Adjustments', true), array('controller' => 'account_adjustments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account Adjustment', true), array('controller' => 'account_adjustments', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Account Fees', true), array('controller' => 'account_fees', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account Fee', true), array('controller' => 'account_fees', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Account Histories', true), array('controller' => 'account_histories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account History', true), array('controller' => 'account_histories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Account Schedules', true), array('controller' => 'account_schedules', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account Schedule', true), array('controller' => 'account_schedules', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Ledgers', true), array('controller' => 'ledgers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ledger', true), array('controller' => 'ledgers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transactions', true), array('controller' => 'transactions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction', true), array('controller' => 'transactions', 'action' => 'add')); ?> </li>
	</ul>
</div>