<div class="accountAdjustments index">
	<h2><?php __('Account Adjustments');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('item_code');?></th>
			<th><?php echo $this->Paginator->sort('flag');?></th>
			<th><?php echo $this->Paginator->sort('amount');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($accountAdjustments as $accountAdjustment):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $accountAdjustment['AccountAdjustment']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($accountAdjustment['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $accountAdjustment['Account']['id'])); ?>
		</td>
		<td><?php echo $accountAdjustment['AccountAdjustment']['item_code']; ?>&nbsp;</td>
		<td><?php echo $accountAdjustment['AccountAdjustment']['flag']; ?>&nbsp;</td>
		<td><?php echo $accountAdjustment['AccountAdjustment']['amount']; ?>&nbsp;</td>
		<td><?php echo $accountAdjustment['AccountAdjustment']['created']; ?>&nbsp;</td>
		<td><?php echo $accountAdjustment['AccountAdjustment']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $accountAdjustment['AccountAdjustment']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $accountAdjustment['AccountAdjustment']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $accountAdjustment['AccountAdjustment']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $accountAdjustment['AccountAdjustment']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Account Adjustment', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>