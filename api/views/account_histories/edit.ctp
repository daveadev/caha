<div class="accountHistories form">
<?php echo $this->Form->create('AccountHistory');?>
	<fieldset>
		<legend><?php __('Edit Account History'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('account_id');
		echo $this->Form->input('transac_date');
		echo $this->Form->input('transac_time');
		echo $this->Form->input('ref_no');
		echo $this->Form->input('details');
		echo $this->Form->input('flag');
		echo $this->Form->input('amount');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('AccountHistory.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('AccountHistory.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Account Histories', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>