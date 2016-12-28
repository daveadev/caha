<div class="transactionTypes view">
<h2><?php  __('Transaction Type');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transactionType['TransactionType']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transactionType['TransactionType']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Default Amount'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transactionType['TransactionType']['default_amount']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transactionType['TransactionType']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transactionType['TransactionType']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Transaction Type', true), array('action' => 'edit', $transactionType['TransactionType']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Transaction Type', true), array('action' => 'delete', $transactionType['TransactionType']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $transactionType['TransactionType']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Types', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Type', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Payments', true), array('controller' => 'transaction_payments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Payment', true), array('controller' => 'transaction_payments', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Transaction Payments');?></h3>
	<?php if (!empty($transactionType['TransactionPayment'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Transaction Id'); ?></th>
		<th><?php __('Transaction Type Id'); ?></th>
		<th><?php __('Details'); ?></th>
		<th><?php __('Amount'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($transactionType['TransactionPayment'] as $transactionPayment):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $transactionPayment['id'];?></td>
			<td><?php echo $transactionPayment['transaction_id'];?></td>
			<td><?php echo $transactionPayment['transaction_type_id'];?></td>
			<td><?php echo $transactionPayment['details'];?></td>
			<td><?php echo $transactionPayment['amount'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'transaction_payments', 'action' => 'view', $transactionPayment['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'transaction_payments', 'action' => 'edit', $transactionPayment['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'transaction_payments', 'action' => 'delete', $transactionPayment['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $transactionPayment['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Transaction Payment', true), array('controller' => 'transaction_payments', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
