<div class="yearLevels index">
	<h2><?php __('Year Levels');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('department_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo $this->Paginator->sort('alias');?></th>
			<th><?php echo $this->Paginator->sort('esp');?></th>
			<th><?php echo $this->Paginator->sort('order');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($yearLevels as $yearLevel):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $yearLevel['YearLevel']['id']; ?>&nbsp;</td>
		<td><?php echo $yearLevel['YearLevel']['department_id']; ?>&nbsp;</td>
		<td><?php echo $yearLevel['YearLevel']['name']; ?>&nbsp;</td>
		<td><?php echo $yearLevel['YearLevel']['description']; ?>&nbsp;</td>
		<td><?php echo $yearLevel['YearLevel']['alias']; ?>&nbsp;</td>
		<td><?php echo $yearLevel['YearLevel']['esp']; ?>&nbsp;</td>
		<td><?php echo $yearLevel['YearLevel']['order']; ?>&nbsp;</td>
		<td><?php echo $yearLevel['YearLevel']['created']; ?>&nbsp;</td>
		<td><?php echo $yearLevel['YearLevel']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $yearLevel['YearLevel']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $yearLevel['YearLevel']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $yearLevel['YearLevel']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $yearLevel['YearLevel']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Year Level', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Students', true), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student', true), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sections', true), array('controller' => 'sections', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Section', true), array('controller' => 'sections', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('New Department', true), array('controller' => 'departments', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Deparments', true), array('controller' => 'departments', 'action' => 'index')); ?> </li>
	</ul>
</div>