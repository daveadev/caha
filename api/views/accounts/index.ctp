<div class="accounts index">
	<h2><?php __('Accounts');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('account_type');?></th>
			<th><?php echo $this->Paginator->sort('account_details');?></th>
			<th><?php echo $this->Paginator->sort('outstanding_balance');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($accounts as $account):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $account['Account']['id']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['account_type']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['account_details']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['outstanding_balance']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['created']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $account['Account']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $account['Account']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $account['Account']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $account['Account']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Account', true), array('action' => 'add')); ?></li>
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