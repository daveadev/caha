<div class="transactions view">
<h2><?php  __('Transaction');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transaction['Transaction']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transaction['Transaction']['type']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transaction['Transaction']['status']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ref No'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transaction['Transaction']['ref_no']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Transac Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transaction['Transaction']['transac_date']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Transac Time'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transaction['Transaction']['transac_time']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Account'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($transaction['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $transaction['Account']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Amount'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transaction['Transaction']['amount']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transaction['Transaction']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transaction['Transaction']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Transaction', true), array('action' => 'edit', $transaction['Transaction']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Transaction', true), array('action' => 'delete', $transaction['Transaction']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $transaction['Transaction']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Transactions', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Details', true), array('controller' => 'transaction_details', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Detail', true), array('controller' => 'transaction_details', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Payments', true), array('controller' => 'transaction_payments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Payment', true), array('controller' => 'transaction_payments', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Transaction Details');?></h3>
	<?php if (!empty($transaction['TransactionDetail'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Transaction Id'); ?></th>
		<th><?php __('Ref No'); ?></th>
		<th><?php __('Details'); ?></th>
		<th><?php __('Amount'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($transaction['TransactionDetail'] as $transactionDetail):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $transactionDetail['id'];?></td>
			<td><?php echo $transactionDetail['transaction_id'];?></td>
			<td><?php echo $transactionDetail['ref_no'];?></td>
			<td><?php echo $transactionDetail['details'];?></td>
			<td><?php echo $transactionDetail['amount'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'transaction_details', 'action' => 'view', $transactionDetail['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'transaction_details', 'action' => 'edit', $transactionDetail['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'transaction_details', 'action' => 'delete', $transactionDetail['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $transactionDetail['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Transaction Detail', true), array('controller' => 'transaction_details', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Transaction Payments');?></h3>
	<?php if (!empty($transaction['TransactionPayment'])):?>
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
		foreach ($transaction['TransactionPayment'] as $transactionPayment):
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
