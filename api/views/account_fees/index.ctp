<div class="accountFees index">
	<h2><?php __('Account Fees');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('fee_id');?></th>
			<th><?php echo $this->Paginator->sort('due_amount');?></th>
			<th><?php echo $this->Paginator->sort('adjust_amount');?></th>
			<th><?php echo $this->Paginator->sort('paid_amount');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($accountFees as $accountFee):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $accountFee['AccountFee']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($accountFee['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $accountFee['Account']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($accountFee['Fee']['name'], array('controller' => 'fees', 'action' => 'view', $accountFee['Fee']['id'])); ?>
		</td>
		<td><?php echo $accountFee['AccountFee']['due_amount']; ?>&nbsp;</td>
		<td><?php echo $accountFee['AccountFee']['adjust_amount']; ?>&nbsp;</td>
		<td><?php echo $accountFee['AccountFee']['paid_amount']; ?>&nbsp;</td>
		<td><?php echo $accountFee['AccountFee']['created']; ?>&nbsp;</td>
		<td><?php echo $accountFee['AccountFee']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $accountFee['AccountFee']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $accountFee['AccountFee']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $accountFee['AccountFee']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountFee['AccountFee']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Account Fee', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Fees', true), array('controller' => 'fees', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Fee', true), array('controller' => 'fees', 'action' => 'add')); ?> </li>
	</ul>
</div>