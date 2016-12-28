<div class="transactionTypes index">
	<h2><?php __('Transaction Types');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('default_amount');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($transactionTypes as $transactionType):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $transactionType['TransactionType']['id']; ?>&nbsp;</td>
		<td><?php echo $transactionType['TransactionType']['name']; ?>&nbsp;</td>
		<td><?php echo $transactionType['TransactionType']['default_amount']; ?>&nbsp;</td>
		<td><?php echo $transactionType['TransactionType']['created']; ?>&nbsp;</td>
		<td><?php echo $transactionType['TransactionType']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $transactionType['TransactionType']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $transactionType['TransactionType']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $transactionType['TransactionType']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $transactionType['TransactionType']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Transaction Type', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Transaction Payments', true), array('controller' => 'transaction_payments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Payment', true), array('controller' => 'transaction_payments', 'action' => 'add')); ?> </li>
	</ul>
</div>