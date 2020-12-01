"use strict";
define(['app','ngFileUpload','xlsx','jszip','atomic/bomb'],function(app){
	app.register.controller('LedgerReaderController',['$scope','$rootScope','$timeout','api','$window','AtomicAPI','aModal',
	function($scope,$rootScope,$timeout,api,$window,AtomicAPI,aModa){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Ledger Reader';
			$scope.Headers = ['Account ID','Type','ESP','date','Ref no','Detail','Amount'];
			$scope.Props = ['account_id','type','esp','transac_date','ref_no','details','amount'];
			$scope.Loading = false;
		}
		$scope.SelectFile = function (file) {
			console.log(file);
			$scope.SelectedFile = file;
		};
		
		$scope.Upload = function(){
			$scope.Loading = true;
			$scope.Data = '';
			var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
			if (regex.test($scope.SelectedFile.name.toLowerCase())) {
				if (typeof (FileReader) != "undefined") {
					var reader = new FileReader();
					//For Browsers other than IE.
					if (reader.readAsBinaryString) {
						reader.onload = function (e) {
							$scope.ProcessExcel(e.target.result);
						};
						reader.readAsBinaryString($scope.SelectedFile);
					} else {
						//For IE Browser.
						reader.onload = function (e) {
							var data = "";
							var bytes = new Uint8Array(e.target.result);
							for (var i = 0; i < bytes.byteLength; i++) {
								data += String.fromCharCode(bytes[i]);
							}
							$scope.ProcessExcel(data);
						};
						reader.readAsArrayBuffer($scope.SelectedFile);
					}
				} else {
					$window.alert("This browser does not support HTML5.");
				}
			} else {
				$window.alert("Please upload a valid Excel file.");
			}
		}
		
		$scope.ProcessExcel = function (data) {
			//Read the Excel File data.
			var workbook = XLSX.read(data, {
				type: 'binary'
			});

			//Fetch the name of First Sheet.
			var firstSheet = workbook.SheetNames[0];

			//Read all rows from First Sheet into an JSON array.
			var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
			console.log(excelRows);
			
			//Display the data from Excel file in Table.
			
			$selfScope.$apply(function () {
				$scope.Data = excelRows;
				$scope.Loading = false;
			});
		};
		
		
		$scope.UploadLedger = function(){
			$scope.Loading = true;
			$scope.Saving = true;
			var data = {bulk:$scope.Data}
			$scope.Data = '';
			api.POST('ledgers',data, function success(response){
				alert('Ledgers and Accounts created!');
				$scope.Saving = false;
				$scope.Loading = false;
				$scope.Saved = false;
			});
		}
		
	}]);
});