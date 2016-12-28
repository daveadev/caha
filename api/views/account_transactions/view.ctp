<div class="accountTransactions view">
<h2><?php  __('Account Transaction');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountTransaction['AccountTransaction']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Account'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($accountTransaction['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $accountTransaction['Account']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Transaction Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($accountTransaction['TransactionType']['name'], array('controller' => 'transaction_types', 'action' => 'view', $accountTransaction['TransactionType']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ref No'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountTransaction['AccountTransaction']['ref_no']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Amount'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountTransaction['AccountTransaction']['amount']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Source'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountTransaction['AccountTransaction']['source']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountTransaction['AccountTransaction']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountTransaction['AccountTransaction']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Account Transaction', true), array('action' => 'edit', $accountTransaction['AccountTransaction']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Account Transaction', true), array('action' => 'delete', $accountTransaction['AccountTransaction']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountTransaction['AccountTransaction']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Account Transactions', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account Transaction', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Transaction Types', true), array('controller' => 'transaction_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transaction Type', true), array('controller' => 'transaction_types', 'action' => 'add')); ?> </li>
	</ul>
</div>
