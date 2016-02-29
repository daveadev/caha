<div class="transactionPayments index">
	<h2><?php __('Transaction Payments');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('transaction_id');?></th>
			<th><?php echo $this->Paginator->sort('transaction_type_id');?></th>
			<th><?php echo $this->Paginator->sort('details');?></th>
			<th><?php echo $this->Paginator->sort('amount');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($transactionPayments as $transactionPayment):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $transactionPayment['TransactionPayment']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($transactionPayment['Transaction']['id'], array('controller' => 'transactions', 'action' => 'view', $transactionPayment['Transaction']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($transactionPayment['TransactionType']['name'], array('controller' => 'transaction_types', 'action' => 'view', $transactionPayment['TransactionType']['id'])); ?>
		</td>
		<td><?php echo $transactionPayment['TransactionPayment']['details']; ?>&nbsp;</td>
		<td><?php echo $transactionPayment['TransactionPayment']['amount']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $transactionPayment['TransactionPayment']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $transactionPayment['TransactionPayment']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $transactionPayment['TransactionPayment']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $transactionPayment['TransactionPayment']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Transaction Payment', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Transactions', true), array('controller' => 'transactions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction', true), array('controller' => 'transactions', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Types', true), array('controller' => 'transaction_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Type', true), array('controller' => 'transaction_types', 'action' => 'add')); ?> </li>
	</ul>
</div>