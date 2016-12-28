<div class="accountHistories view">
<h2><?php  __('Account History');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountHistory['AccountHistory']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Account'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($accountHistory['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $accountHistory['Account']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Transac Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountHistory['AccountHistory']['transac_date']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Transac Time'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountHistory['AccountHistory']['transac_time']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ref No'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountHistory['AccountHistory']['ref_no']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Details'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountHistory['AccountHistory']['details']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Flag'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountHistory['AccountHistory']['flag']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Amount'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountHistory['AccountHistory']['amount']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountHistory['AccountHistory']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $accountHistory['AccountHistory']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Account History', true), array('action' => 'edit', $accountHistory['AccountHistory']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Account History', true), array('action' => 'delete', $accountHistory['AccountHistory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountHistory['AccountHistory']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Account Histories', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account History', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>
