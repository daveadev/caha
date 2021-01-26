<?php
App::import('Vendor','accounting/income_statement');

$pr= new IncomeStatement();
$pr->hdr();
$pr->output();
?>

