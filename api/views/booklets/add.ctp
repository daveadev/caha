<div class="booklets form">
<?php echo $this->Form->create('Booklet');?>
	<fieldset>
		<legend><?php __('Add Booklet'); ?></legend>
	<?php
		echo $this->Form->input('series_start');
		echo $this->Form->input('series_end');
		echo $this->Form->input('series_counter');
		echo $this->Form->input('status');
		echo $this->Form->input('cashier');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Booklets', true), array('action' => 'index'));?></li>
	</ul>
</div>