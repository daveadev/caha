<div class="sections index">
	<h2><?php __('Sections');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('department_id');?></th>
			<th><?php echo $this->Paginator->sort('year_level_id');?></th>
			<th><?php echo $this->Paginator->sort('program_id');?></th>
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
	foreach ($sections as $section):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $section['Section']['id']; ?>&nbsp;</td>
		<td><?php echo $section['Section']['department_id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($section['YearLevel']['name'], array('controller' => 'year_levels', 'action' => 'view', $section['YearLevel']['id'])); ?>
		</td>
		<td><?php echo $section['Section']['program_id']; ?>&nbsp;</td>
		<td><?php echo $section['Section']['name']; ?>&nbsp;</td>
		<td><?php echo $section['Section']['description']; ?>&nbsp;</td>
		<td><?php echo $section['Section']['alias']; ?>&nbsp;</td>
		<td><?php echo $section['Section']['esp']; ?>&nbsp;</td>
		<td><?php echo $section['Section']['order']; ?>&nbsp;</td>
		<td><?php echo $section['Section']['created']; ?>&nbsp;</td>
		<td><?php echo $section['Section']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $section['Section']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $section['Section']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $section['Section']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $section['Section']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Section', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Year Levels', true), array('controller' => 'year_levels', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Year Level', true), array('controller' => 'year_levels', 'action' => 'add')); ?> </li>
	</ul>
</div>