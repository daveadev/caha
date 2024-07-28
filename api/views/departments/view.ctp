<div class="departments view">
<h2><?php  __('Department');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $department['Department']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $department['Department']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $department['Department']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Alias'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $department['Department']['alias']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Esp'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $department['Department']['esp']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Order'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $department['Department']['order']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $department['Department']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $department['Department']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Department', true), array('action' => 'edit', $department['Department']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Department', true), array('action' => 'delete', $department['Department']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $department['Department']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Departments', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Department', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Programs', true), array('controller' => 'programs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Program', true), array('controller' => 'programs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sections', true), array('controller' => 'sections', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Section', true), array('controller' => 'sections', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subjects', true), array('controller' => 'subjects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subject', true), array('controller' => 'subjects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Year Levels', true), array('controller' => 'year_levels', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Year Level', true), array('controller' => 'year_levels', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Programs');?></h3>
	<?php if (!empty($department['Program'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Department Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Track'); ?></th>
		<th><?php __('Alias'); ?></th>
		<th><?php __('Order'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($department['Program'] as $program):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $program['id'];?></td>
			<td><?php echo $program['department_id'];?></td>
			<td><?php echo $program['name'];?></td>
			<td><?php echo $program['description'];?></td>
			<td><?php echo $program['track'];?></td>
			<td><?php echo $program['alias'];?></td>
			<td><?php echo $program['order'];?></td>
			<td><?php echo $program['created'];?></td>
			<td><?php echo $program['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'programs', 'action' => 'view', $program['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'programs', 'action' => 'edit', $program['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'programs', 'action' => 'delete', $program['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $program['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Program', true), array('controller' => 'programs', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Sections');?></h3>
	<?php if (!empty($department['Section'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Department Id'); ?></th>
		<th><?php __('Year Level Id'); ?></th>
		<th><?php __('Program Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Alias'); ?></th>
		<th><?php __('Esp'); ?></th>
		<th><?php __('Order'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($department['Section'] as $section):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $section['id'];?></td>
			<td><?php echo $section['department_id'];?></td>
			<td><?php echo $section['year_level_id'];?></td>
			<td><?php echo $section['program_id'];?></td>
			<td><?php echo $section['name'];?></td>
			<td><?php echo $section['description'];?></td>
			<td><?php echo $section['alias'];?></td>
			<td><?php echo $section['esp'];?></td>
			<td><?php echo $section['order'];?></td>
			<td><?php echo $section['created'];?></td>
			<td><?php echo $section['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'sections', 'action' => 'view', $section['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'sections', 'action' => 'edit', $section['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'sections', 'action' => 'delete', $section['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $section['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Section', true), array('controller' => 'sections', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Subjects');?></h3>
	<?php if (!empty($department['Subject'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Department Id'); ?></th>
		<th><?php __('Year Level Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Alias'); ?></th>
		<th><?php __('Type'); ?></th>
		<th><?php __('Units'); ?></th>
		<th><?php __('Status'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($department['Subject'] as $subject):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $subject['id'];?></td>
			<td><?php echo $subject['department_id'];?></td>
			<td><?php echo $subject['year_level_id'];?></td>
			<td><?php echo $subject['name'];?></td>
			<td><?php echo $subject['description'];?></td>
			<td><?php echo $subject['alias'];?></td>
			<td><?php echo $subject['type'];?></td>
			<td><?php echo $subject['units'];?></td>
			<td><?php echo $subject['status'];?></td>
			<td><?php echo $subject['created'];?></td>
			<td><?php echo $subject['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'subjects', 'action' => 'view', $subject['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'subjects', 'action' => 'edit', $subject['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'subjects', 'action' => 'delete', $subject['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $subject['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Subject', true), array('controller' => 'subjects', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Year Levels');?></h3>
	<?php if (!empty($department['YearLevel'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Department Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Alias'); ?></th>
		<th><?php __('Esp'); ?></th>
		<th><?php __('Order'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($department['YearLevel'] as $yearLevel):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $yearLevel['id'];?></td>
			<td><?php echo $yearLevel['department_id'];?></td>
			<td><?php echo $yearLevel['name'];?></td>
			<td><?php echo $yearLevel['description'];?></td>
			<td><?php echo $yearLevel['alias'];?></td>
			<td><?php echo $yearLevel['esp'];?></td>
			<td><?php echo $yearLevel['order'];?></td>
			<td><?php echo $yearLevel['created'];?></td>
			<td><?php echo $yearLevel['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'year_levels', 'action' => 'view', $yearLevel['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'year_levels', 'action' => 'edit', $yearLevel['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'year_levels', 'action' => 'delete', $yearLevel['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $yearLevel['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Year Level', true), array('controller' => 'year_levels', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
