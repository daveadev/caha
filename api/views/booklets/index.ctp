<div class="booklets index">
	<h2><?php __('Booklets');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('series_start');?></th>
			<th><?php echo $this->Paginator->sort('series_end');?></th>
			<th><?php echo $this->Paginator->sort('series_counter');?></th>
			<th><?php echo $this->Paginator->sort('status');?></th>
			<th><?php echo $this->Paginator->sort('cashier');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($booklets as $booklet):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $booklet['Booklet']['id']; ?>&nbsp;</td>
		<td><?php echo $booklet['Booklet']['series_start']; ?>&nbsp;</td>
		<td><?php echo $booklet['Booklet']['series_end']; ?>&nbsp;</td>
		<td><?php echo $booklet['Booklet']['series_counter']; ?>&nbsp;</td>
		<td><?php echo $booklet['Booklet']['status']; ?>&nbsp;</td>
		<td><?php echo $booklet['Booklet']['cashier']; ?>&nbsp;</td>
		<td><?php echo $booklet['Booklet']['created']; ?>&nbsp;</td>
		<td><?php echo $booklet['Booklet']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $booklet['Booklet']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $booklet['Booklet']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $booklet['Booklet']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $booklet['Booklet']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Booklet', true), array('action' => 'add')); ?></li>
	</ul>
</div>