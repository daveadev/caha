<?php
App::import('Vendor','soa');

//pr($data);exit;

$pr= new SOA();
$pr->ledger($student,$data);
$pr->output();
?>

