<div class="students index">
	<h2><?php __('Students');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('classroom_user_id');?></th>
			<th><?php echo $this->Paginator->sort('sno');?></th>
			<th><?php echo $this->Paginator->sort('lrn');?></th>
			<th><?php echo $this->Paginator->sort('rfid');?></th>
			<th><?php echo $this->Paginator->sort('program_id');?></th>
			<th><?php echo $this->Paginator->sort('year_level_id');?></th>
			<th><?php echo $this->Paginator->sort('section_id');?></th>
			<th><?php echo $this->Paginator->sort('first_name');?></th>
			<th><?php echo $this->Paginator->sort('middle_name');?></th>
			<th><?php echo $this->Paginator->sort('last_name');?></th>
			<th><?php echo $this->Paginator->sort('prefix');?></th>
			<th><?php echo $this->Paginator->sort('suffix');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('gender');?></th>
			<th><?php echo $this->Paginator->sort('age');?></th>
			<th><?php echo $this->Paginator->sort('age_bmi');?></th>
			<th><?php echo $this->Paginator->sort('weight');?></th>
			<th><?php echo $this->Paginator->sort('height');?></th>
			<th><?php echo $this->Paginator->sort('height_m2');?></th>
			<th><?php echo $this->Paginator->sort('bmi');?></th>
			<th><?php echo $this->Paginator->sort('bmi_category');?></th>
			<th><?php echo $this->Paginator->sort('height_fa');?></th>
			<th><?php echo $this->Paginator->sort('remarks');?></th>
			<th><?php echo $this->Paginator->sort('birthday');?></th>
			<th><?php echo $this->Paginator->sort('nationality');?></th>
			<th><?php echo $this->Paginator->sort('mother_tongue');?></th>
			<th><?php echo $this->Paginator->sort('ethnic_group');?></th>
			<th><?php echo $this->Paginator->sort('religion');?></th>
			<th><?php echo $this->Paginator->sort('status');?></th>
			<th><?php echo $this->Paginator->sort('admission_date');?></th>
			<th><?php echo $this->Paginator->sort('elgb_type');?></th>
			<th><?php echo $this->Paginator->sort('elgb_school');?></th>
			<th><?php echo $this->Paginator->sort('elgb_school_id');?></th>
			<th><?php echo $this->Paginator->sort('elgb_school_type');?></th>
			<th><?php echo $this->Paginator->sort('elgb_school_address');?></th>
			<th><?php echo $this->Paginator->sort('elgb_completion_date');?></th>
			<th><?php echo $this->Paginator->sort('elgb_gen_avg');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($students as $student):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $this->Html->link($student['Student']['id'], array('controller' => 'students', 'action' => 'view', $student['Student']['id'])); ?>
		</td>
		<td><?php echo $student['Student']['user_id']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['classroom_user_id']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['sno']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['lrn']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['rfid']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($student['Program']['name'], array('controller' => 'programs', 'action' => 'view', $student['Program']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($student['YearLevel']['name'], array('controller' => 'year_levels', 'action' => 'view', $student['YearLevel']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($student['Section']['name'], array('controller' => 'sections', 'action' => 'view', $student['Section']['id'])); ?>
		</td>
		<td><?php echo $student['Student']['first_name']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['middle_name']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['last_name']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['prefix']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['suffix']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['email']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['gender']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['age']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['age_bmi']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['weight']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['height']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['height_m2']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['bmi']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['bmi_category']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['height_fa']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['remarks']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['birthday']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['nationality']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['mother_tongue']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['ethnic_group']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['religion']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['status']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['admission_date']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['elgb_type']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['elgb_school']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['elgb_school_id']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['elgb_school_type']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['elgb_school_address']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['elgb_completion_date']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['elgb_gen_avg']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['created']; ?>&nbsp;</td>
		<td><?php echo $student['Student']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $student['Student']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $student['Student']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $student['Student']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $student['Student']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Student', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Year Levels', true), array('controller' => 'year_levels', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Year Level', true), array('controller' => 'year_levels', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sections', true), array('controller' => 'sections', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Section', true), array('controller' => 'sections', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Programs', true), array('controller' => 'programs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Program', true), array('controller' => 'programs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Assessments', true), array('controller' => 'assessments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Assessment', true), array('controller' => 'assessments', 'action' => 'add')); ?> </li>
	</ul>
</div>