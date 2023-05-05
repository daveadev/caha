<style>
	tr.error td{background: #eab1b1 !important;}
</style>
<h2>Student: <?php echo $student['sno'];?> | <?php echo $student['id'];?> | <?php echo $student['full_name'];?></h2>
<?php if(!$hasCorrections):?>
<pre class="cake-debug"><h3> Payschedule correct!</h3></pre>
<?php endif;?>
<table>
	<thead>
		<tr>
			<th colspan="2">Original</th>
			<th colspan="2">Adjusment</th>
			<th rowspan="2">Status</th>
		</tr>
		<tr>
			<th>DATE</th>
			<th>AMOUNT</th>
			<th>DATE</th>
			<th>AMOUNT</th>
		</tr>
	</thead>
	<tbody>
		<?php
		 foreach($sched as $s): 
		 	$hasErr = $s['hasErr'];
		 	?>
		<tr <?php if($hasErr) echo 'class="error"';?>>
			<td><?php echo $s['og_bill_month'];?></td>
			<td><?php echo $s['og_due_amount'];?></td>
			<td><?php echo $s['bill_month'];?></td>
			<td><?php echo $s['due_amount'];?></td>
			<td><?php echo $s['status'];?></td>

		</tr>
		<?php  endforeach;?>
	</tbody>
</table>
<?php if($hasCorrections):?>
	<?php echo $this->Form->create('Ledger',array('action'=>'correction/'.$student['sno']));
		$ctr=0;
		foreach($sched as $s):  
			if($s['hasErr']):
				$prefix = 'Ledger.corrections.'.$ctr;
				echo $this->Form->input($prefix.'.id',array('value'=>$s['id']));
				echo $this->Form->input($prefix.'.bill_month',array('value'=>$s['bill_month']));
				echo $this->Form->input($prefix.'.due_amount',array('value'=>$s['due_amount']));
				$ctr++;
			endif;
		 endforeach;?>
	<?php echo $this->Form->end(__('Adjust', true));?>
<?php endif;?>
