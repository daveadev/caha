<div class="accountTransactions index">
	<h2><?php __('Account Transactions');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('transaction_type_id');?></th>
			<th><?php echo $this->Paginator->sort('ref_no');?></th>
			<th><?php echo $this->Paginator->sort('amount');?></th>
			<th><?php echo $this->Paginator->sort('source');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($accountTransactions as $accountTransaction):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $accountTransaction['AccountTransaction']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($accountTransaction['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $accountTransaction['Account']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($accountTransaction['TransactionType']['name'], array('controller' => 'transaction_types', 'action' => 'view', $accountTransaction['TransactionType']['id'])); ?>
		</td>
		<td><?php echo $accountTransaction['AccountTransaction']['ref_no']; ?>&nbsp;</td>
		<td><?php echo $accountTransaction['AccountTransaction']['amount']; ?>&nbsp;</td>
		<td><?php echo $accountTransaction['AccountTransaction']['source']; ?>&nbsp;</td>
		<td><?php echo $accountTransaction['AccountTransaction']['created']; ?>&nbsp;</td>
		<td><?php echo $accountTransaction['AccountTransaction']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $accountTransaction['AccountTransaction']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $accountTransaction['AccountTransaction']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $accountTransaction['AccountTransaction']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountTransaction['AccountTransaction']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Account Transaction', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Types', true), array('controller' => 'transaction_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Type', true), array('controller' => 'transaction_types', 'action' => 'add')); ?> </li>
	</ul>
</div>