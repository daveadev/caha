<div class="paymentMethods form">
<?php echo $this->Form->create('PaymentMethod');?>
	<fieldset>
		<legend><?php __('Edit Payment Method'); ?></legend>
	<?php
		echo $this->Form->input('id',array('type'=>'text'));
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('icon');
		echo $this->Form->input('order');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('PaymentMethod.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('PaymentMethod.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Payment Methods', true), array('action' => 'index'));?></li>
	</ul>
</div>