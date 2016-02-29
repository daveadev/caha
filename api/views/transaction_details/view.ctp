<div class="transactionDetails view">
<h2><?php  __('Transaction Detail');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transactionDetail['TransactionDetail']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Transaction'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($transactionDetail['Transaction']['id'], array('controller' => 'transactions', 'action' => 'view', $transactionDetail['Transaction']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ref No'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transactionDetail['TransactionDetail']['ref_no']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Details'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transactionDetail['TransactionDetail']['details']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Amount'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $transactionDetail['TransactionDetail']['amount']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Transaction Detail', true), array('action' => 'edit', $transactionDetail['TransactionDetail']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Transaction Detail', true), array('action' => 'delete', $transactionDetail['TransactionDetail']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $transactionDetail['TransactionDetail']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Details', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Detail', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transactions', true), array('controller' => 'transactions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction', true), array('controller' => 'transactions', 'action' => 'add')); ?> </li>
	</ul>
</div>
