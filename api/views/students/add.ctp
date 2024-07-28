<div class="students form">
<?php echo $this->Form->create('Student');?>
	<fieldset>
		<legend><?php __('Add Student'); ?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('classroom_user_id');
		echo $this->Form->input('sno');
		echo $this->Form->input('lrn');
		echo $this->Form->input('rfid');
		echo $this->Form->input('program_id');
		echo $this->Form->input('year_level_id');
		echo $this->Form->input('section_id');
		echo $this->Form->input('first_name');
		echo $this->Form->input('middle_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('prefix');
		echo $this->Form->input('suffix');
		echo $this->Form->input('email');
		echo $this->Form->input('gender');
		echo $this->Form->input('age');
		echo $this->Form->input('age_bmi');
		echo $this->Form->input('weight');
		echo $this->Form->input('height');
		echo $this->Form->input('height_m2');
		echo $this->Form->input('bmi');
		echo $this->Form->input('bmi_category');
		echo $this->Form->input('height_fa');
		echo $this->Form->input('remarks');
		echo $this->Form->input('birthday');
		echo $this->Form->input('nationality');
		echo $this->Form->input('mother_tongue');
		echo $this->Form->input('ethnic_group');
		echo $this->Form->input('religion');
		echo $this->Form->input('status');
		echo $this->Form->input('admission_date');
		echo $this->Form->input('elgb_type');
		echo $this->Form->input('elgb_school');
		echo $this->Form->input('elgb_school_id');
		echo $this->Form->input('elgb_school_type');
		echo $this->Form->input('elgb_school_address');
		echo $this->Form->input('elgb_completion_date');
		echo $this->Form->input('elgb_gen_avg');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Students', true), array('action' => 'index'));?></li>
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