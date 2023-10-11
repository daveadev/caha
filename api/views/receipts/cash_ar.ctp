<?php
App::import('Vendor','receipts/acknowledgement_receipt');


$pr= new AcknowledgementReceipt();
$pr->template();
//$pr->data($data);
$pr->output();