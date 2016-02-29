<div class="transactionDetails index">
	<h2><?php __('Transaction Details');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('transaction_id');?></th>
			<th><?php echo $this->Paginator->sort('ref_no');?></th>
			<th><?php echo $this->Paginator->sort('details');?></th>
			<th><?php echo $this->Paginator->sort('amount');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($transactionDetails as $transactionDetail):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $transactionDetail['TransactionDetail']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($transactionDetail['Transaction']['id'], array('controller' => 'transactions', 'action' => 'view', $transactionDetail['Transaction']['id'])); ?>
		</td>
		<td><?php echo $transactionDetail['TransactionDetail']['ref_no']; ?>&nbsp;</td>
		<td><?php echo $transactionDetail['TransactionDetail']['details']; ?>&nbsp;</td>
		<td><?php echo $transactionDetail['TransactionDetail']['amount']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $transactionDetail['TransactionDetail']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $transactionDetail['TransactionDetail']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $transactionDetail['TransactionDetail']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $transactionDetail['TransactionDetail']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Transaction Detail', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Transactions', true), array('controller' => 'transactions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction', true), array('controller' => 'transactions', 'action' => 'add')); ?> </li>
	</ul>
</div>