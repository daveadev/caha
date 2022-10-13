<?php
App::import('Vendor','daily_remittance');
//echo json_encode($data);exit;
$pr= new DailyRemittance();
$pr->hdr($data['date']);
$pr->booklet($data['booklet'],$data['doctype']);
$pr->cash_breakdown($data['breakdown']);
$pr->non_cash_breakdown($data['noncash']);
$pr->total_breakdown($data['breakdownDetails'][0]);
//pr($data); exit();
$pr->output();
?>

