<div class="yearLevels view">
<h2><?php  __('Year Level');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $yearLevel['YearLevel']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Department Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $yearLevel['YearLevel']['department_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $yearLevel['YearLevel']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $yearLevel['YearLevel']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Alias'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $yearLevel['YearLevel']['alias']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Esp'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $yearLevel['YearLevel']['esp']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Order'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $yearLevel['YearLevel']['order']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $yearLevel['YearLevel']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $yearLevel['YearLevel']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Year Level', true), array('action' => 'edit', $yearLevel['YearLevel']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Year Level', true), array('action' => 'delete', $yearLevel['YearLevel']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $yearLevel['YearLevel']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Year Levels', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Year Level', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students', true), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student', true), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sections', true), array('controller' => 'sections', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Section', true), array('controller' => 'sections', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Students');?></h3>
	<?php if (!empty($yearLevel['Student'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Classroom User Id'); ?></th>
		<th><?php __('Sno'); ?></th>
		<th><?php __('Lrn'); ?></th>
		<th><?php __('Rfid'); ?></th>
		<th><?php __('Program Id'); ?></th>
		<th><?php __('Year Level Id'); ?></th>
		<th><?php __('Section Id'); ?></th>
		<th><?php __('First Name'); ?></th>
		<th><?php __('Middle Name'); ?></th>
		<th><?php __('Last Name'); ?></th>
		<th><?php __('Prefix'); ?></th>
		<th><?php __('Suffix'); ?></th>
		<th><?php __('Email'); ?></th>
		<th><?php __('Gender'); ?></th>
		<th><?php __('Age'); ?></th>
		<th><?php __('Age Bmi'); ?></th>
		<th><?php __('Weight'); ?></th>
		<th><?php __('Height'); ?></th>
		<th><?php __('Height M2'); ?></th>
		<th><?php __('Bmi'); ?></th>
		<th><?php __('Bmi Category'); ?></th>
		<th><?php __('Height Fa'); ?></th>
		<th><?php __('Remarks'); ?></th>
		<th><?php __('Birthday'); ?></th>
		<th><?php __('Nationality'); ?></th>
		<th><?php __('Mother Tongue'); ?></th>
		<th><?php __('Ethnic Group'); ?></th>
		<th><?php __('Religion'); ?></th>
		<th><?php __('Status'); ?></th>
		<th><?php __('Admission Date'); ?></th>
		<th><?php __('Elgb Type'); ?></th>
		<th><?php __('Elgb School'); ?></th>
		<th><?php __('Elgb School Id'); ?></th>
		<th><?php __('Elgb School Type'); ?></th>
		<th><?php __('Elgb School Address'); ?></th>
		<th><?php __('Elgb Completion Date'); ?></th>
		<th><?php __('Elgb Gen Avg'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($yearLevel['Student'] as $student):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $student['id'];?></td>
			<td><?php echo $student['user_id'];?></td>
			<td><?php echo $student['classroom_user_id'];?></td>
			<td><?php echo $student['sno'];?></td>
			<td><?php echo $student['lrn'];?></td>
			<td><?php echo $student['rfid'];?></td>
			<td><?php echo $student['program_id'];?></td>
			<td><?php echo $student['year_level_id'];?></td>
			<td><?php echo $student['section_id'];?></td>
			<td><?php echo $student['first_name'];?></td>
			<td><?php echo $student['middle_name'];?></td>
			<td><?php echo $student['last_name'];?></td>
			<td><?php echo $student['prefix'];?></td>
			<td><?php echo $student['suffix'];?></td>
			<td><?php echo $student['email'];?></td>
			<td><?php echo $student['gender'];?></td>
			<td><?php echo $student['age'];?></td>
			<td><?php echo $student['age_bmi'];?></td>
			<td><?php echo $student['weight'];?></td>
			<td><?php echo $student['height'];?></td>
			<td><?php echo $student['height_m2'];?></td>
			<td><?php echo $student['bmi'];?></td>
			<td><?php echo $student['bmi_category'];?></td>
			<td><?php echo $student['height_fa'];?></td>
			<td><?php echo $student['remarks'];?></td>
			<td><?php echo $student['birthday'];?></td>
			<td><?php echo $student['nationality'];?></td>
			<td><?php echo $student['mother_tongue'];?></td>
			<td><?php echo $student['ethnic_group'];?></td>
			<td><?php echo $student['religion'];?></td>
			<td><?php echo $student['status'];?></td>
			<td><?php echo $student['admission_date'];?></td>
			<td><?php echo $student['elgb_type'];?></td>
			<td><?php echo $student['elgb_school'];?></td>
			<td><?php echo $student['elgb_school_id'];?></td>
			<td><?php echo $student['elgb_school_type'];?></td>
			<td><?php echo $student['elgb_school_address'];?></td>
			<td><?php echo $student['elgb_completion_date'];?></td>
			<td><?php echo $student['elgb_gen_avg'];?></td>
			<td><?php echo $student['created'];?></td>
			<td><?php echo $student['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'students', 'action' => 'view', $student['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'students', 'action' => 'edit', $student['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'students', 'action' => 'delete', $student['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $student['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Student', true), array('controller' => 'students', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Sections');?></h3>
	<?php if (!empty($yearLevel['Section'])):?>
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
		foreach ($yearLevel['Section'] as $section):
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
