<div class="accountHistories index">
	<h2><?php __('Account Histories');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('transac_date');?></th>
			<th><?php echo $this->Paginator->sort('transac_time');?></th>
			<th><?php echo $this->Paginator->sort('ref_no');?></th>
			<th><?php echo $this->Paginator->sort('details');?></th>
			<th><?php echo $this->Paginator->sort('flag');?></th>
			<th><?php echo $this->Paginator->sort('amount');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($accountHistories as $accountHistory):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $accountHistory['AccountHistory']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($accountHistory['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $accountHistory['Account']['id'])); ?>
		</td>
		<td><?php echo $accountHistory['AccountHistory']['transac_date']; ?>&nbsp;</td>
		<td><?php echo $accountHistory['AccountHistory']['transac_time']; ?>&nbsp;</td>
		<td><?php echo $accountHistory['AccountHistory']['ref_no']; ?>&nbsp;</td>
		<td><?php echo $accountHistory['AccountHistory']['details']; ?>&nbsp;</td>
		<td><?php echo $accountHistory['AccountHistory']['flag']; ?>&nbsp;</td>
		<td><?php echo $accountHistory['AccountHistory']['amount']; ?>&nbsp;</td>
		<td><?php echo $accountHistory['AccountHistory']['created']; ?>&nbsp;</td>
		<td><?php echo $accountHistory['AccountHistory']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $accountHistory['AccountHistory']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $accountHistory['AccountHistory']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $accountHistory['AccountHistory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountHistory['AccountHistory']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Account History', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>