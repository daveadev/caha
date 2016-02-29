<div class="accounts view">
<h2><?php  __('Account');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Account Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['account_type']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Account Details'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['account_details']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Outstanding Balance'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['outstanding_balance']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Account', true), array('action' => 'edit', $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Account', true), array('action' => 'delete', $account['Account']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('action' => 'add')); ?> </li>
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
<div class="related">
	<h3><?php __('Related Account Adjustments');?></h3>
	<?php if (!empty($account['AccountAdjustment'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Account Id'); ?></th>
		<th><?php __('Item Code'); ?></th>
		<th><?php __('Flag'); ?></th>
		<th><?php __('Amount'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($account['AccountAdjustment'] as $accountAdjustment):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $accountAdjustment['id'];?></td>
			<td><?php echo $accountAdjustment['account_id'];?></td>
			<td><?php echo $accountAdjustment['item_code'];?></td>
			<td><?php echo $accountAdjustment['flag'];?></td>
			<td><?php echo $accountAdjustment['amount'];?></td>
			<td><?php echo $accountAdjustment['created'];?></td>
			<td><?php echo $accountAdjustment['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'account_adjustments', 'action' => 'view', $accountAdjustment['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'account_adjustments', 'action' => 'edit', $accountAdjustment['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'account_adjustments', 'action' => 'delete', $accountAdjustment['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountAdjustment['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Account Adjustment', true), array('controller' => 'account_adjustments', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Account Fees');?></h3>
	<?php if (!empty($account['AccountFee'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Account Id'); ?></th>
		<th><?php __('Fee Id'); ?></th>
		<th><?php __('Due Amount'); ?></th>
		<th><?php __('Adjust Amount'); ?></th>
		<th><?php __('Paid Amount'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($account['AccountFee'] as $accountFee):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $accountFee['id'];?></td>
			<td><?php echo $accountFee['account_id'];?></td>
			<td><?php echo $accountFee['fee_id'];?></td>
			<td><?php echo $accountFee['due_amount'];?></td>
			<td><?php echo $accountFee['adjust_amount'];?></td>
			<td><?php echo $accountFee['paid_amount'];?></td>
			<td><?php echo $accountFee['created'];?></td>
			<td><?php echo $accountFee['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'account_fees', 'action' => 'view', $accountFee['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'account_fees', 'action' => 'edit', $accountFee['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'account_fees', 'action' => 'delete', $accountFee['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountFee['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Account Fee', true), array('controller' => 'account_fees', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Account Histories');?></h3>
	<?php if (!empty($account['AccountHistory'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Account Id'); ?></th>
		<th><?php __('Transac Date'); ?></th>
		<th><?php __('Transac Time'); ?></th>
		<th><?php __('Ref No'); ?></th>
		<th><?php __('Details'); ?></th>
		<th><?php __('Flag'); ?></th>
		<th><?php __('Amount'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($account['AccountHistory'] as $accountHistory):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $accountHistory['id'];?></td>
			<td><?php echo $accountHistory['account_id'];?></td>
			<td><?php echo $accountHistory['transac_date'];?></td>
			<td><?php echo $accountHistory['transac_time'];?></td>
			<td><?php echo $accountHistory['ref_no'];?></td>
			<td><?php echo $accountHistory['details'];?></td>
			<td><?php echo $accountHistory['flag'];?></td>
			<td><?php echo $accountHistory['amount'];?></td>
			<td><?php echo $accountHistory['created'];?></td>
			<td><?php echo $accountHistory['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'account_histories', 'action' => 'view', $accountHistory['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'account_histories', 'action' => 'edit', $accountHistory['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'account_histories', 'action' => 'delete', $accountHistory['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountHistory['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Account History', true), array('controller' => 'account_histories', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Account Schedules');?></h3>
	<?php if (!empty($account['AccountSchedule'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Account Id'); ?></th>
		<th><?php __('Bill Month'); ?></th>
		<th><?php __('Due Date'); ?></th>
		<th><?php __('Due Amount'); ?></th>
		<th><?php __('Paid Amount'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($account['AccountSchedule'] as $accountSchedule):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $accountSchedule['id'];?></td>
			<td><?php echo $accountSchedule['account_id'];?></td>
			<td><?php echo $accountSchedule['bill_month'];?></td>
			<td><?php echo $accountSchedule['due_date'];?></td>
			<td><?php echo $accountSchedule['due_amount'];?></td>
			<td><?php echo $accountSchedule['paid_amount'];?></td>
			<td><?php echo $accountSchedule['created'];?></td>
			<td><?php echo $accountSchedule['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'account_schedules', 'action' => 'view', $accountSchedule['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'account_schedules', 'action' => 'edit', $accountSchedule['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'account_schedules', 'action' => 'delete', $accountSchedule['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountSchedule['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Account Schedule', true), array('controller' => 'account_schedules', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Ledgers');?></h3>
	<?php if (!empty($account['Ledger'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Account Id'); ?></th>
		<th><?php __('Type'); ?></th>
		<th><?php __('Transac Date'); ?></th>
		<th><?php __('Transac Time'); ?></th>
		<th><?php __('Ref No'); ?></th>
		<th><?php __('Details'); ?></th>
		<th><?php __('Amount'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($account['Ledger'] as $ledger):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $ledger['id'];?></td>
			<td><?php echo $ledger['account_id'];?></td>
			<td><?php echo $ledger['type'];?></td>
			<td><?php echo $ledger['transac_date'];?></td>
			<td><?php echo $ledger['transac_time'];?></td>
			<td><?php echo $ledger['ref_no'];?></td>
			<td><?php echo $ledger['details'];?></td>
			<td><?php echo $ledger['amount'];?></td>
			<td><?php echo $ledger['created'];?></td>
			<td><?php echo $ledger['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'ledgers', 'action' => 'view', $ledger['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'ledgers', 'action' => 'edit', $ledger['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'ledgers', 'action' => 'delete', $ledger['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $ledger['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Ledger', true), array('controller' => 'ledgers', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Transactions');?></h3>
	<?php if (!empty($account['Transaction'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Type'); ?></th>
		<th><?php __('Status'); ?></th>
		<th><?php __('Ref No'); ?></th>
		<th><?php __('Transac Date'); ?></th>
		<th><?php __('Transac Time'); ?></th>
		<th><?php __('Account Id'); ?></th>
		<th><?php __('Amount'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($account['Transaction'] as $transaction):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $transaction['id'];?></td>
			<td><?php echo $transaction['type'];?></td>
			<td><?php echo $transaction['status'];?></td>
			<td><?php echo $transaction['ref_no'];?></td>
			<td><?php echo $transaction['transac_date'];?></td>
			<td><?php echo $transaction['transac_time'];?></td>
			<td><?php echo $transaction['account_id'];?></td>
			<td><?php echo $transaction['amount'];?></td>
			<td><?php echo $transaction['created'];?></td>
			<td><?php echo $transaction['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'transactions', 'action' => 'view', $transaction['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'transactions', 'action' => 'edit', $transaction['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'transactions', 'action' => 'delete', $transaction['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $transaction['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Transaction', true), array('controller' => 'transactions', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
