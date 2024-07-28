<div class="sections form">
<?php echo $this->Form->create('Section');?>
	<fieldset>
		<legend><?php __('Edit Section'); ?></legend>
	<?php
		echo $this->Form->input('id',array('type'=>'text'));
		echo $this->Form->input('department_id');
		echo $this->Form->input('year_level_id');
		echo $this->Form->input('program_id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('alias');
		echo $this->Form->input('esp');
		echo $this->Form->input('order');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Section.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Section.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Sections', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Year Levels', true), array('controller' => 'year_levels', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Year Level', true), array('controller' => 'year_levels', 'action' => 'add')); ?> </li>
	</ul>
</div>