"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('CashierController',['$scope','$rootScope','api','Atomic','$filter','$timeout','aModal',
	function($scope,$rootScope,api,atomic,$filter,$timeout,aModal){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Cashier Collection';
			$scope.Options = ['OR','AR']
			$scope.ActiveOpt = 'OR';
			$scope.CHeaders = ['cnt','Sno','Received from','Level','Section','Status','Date','Particular','Ref no',{label:'Amount',class:'amount total'}];
			$scope.Headers = ['cnt','Sno','Received from','Level','Section','Status','Date','Particular','Ref no',{label:'Amount',class:'amount total'},{label:'Total Due',class:'amount total'},{label:'Total Paid',class:'amount total'},{label:'Balance',class:'amount total'},];
			$scope.CProps = ['cnt','sno','received_from','level','section','status','date','particulars','ref_no','amount'];
			$scope.Props = ['cnt','sno','received_from','level','section','status','date','particulars','ref_no','amount','total_due','total_paid','balance'];
			$scope.Tabs = [{id:1,name:'Breakdown'},{id:2,name:'Booklets'}];
			$scope.Tabss = [{id:1,name:'Cash'},{id:2,name:'Non-cash'}];
			$scope.ActiveTab = 1;
			$scope.ActiveTab1 = 1;
			$scope.Dinominations = [
				{denomination:1000.00,quantity:0},
				{denomination:500.00,quantity:0},
				{denomination:200.00,quantity:0},
				{denomination:100.00,quantity:0},
				{denomination:50.00,quantity:0},
				{denomination:20.00,quantity:0},
				{denomination:10.00,quantity:0},
				{denomination:5.00,quantity:0},
				{denomination:1.00,quantity:0},
				{denomination:0.50,quantity:0},
				{denomination:0.25,quantity:0},
				{denomination:0.05,quantity:0},
				{denomination:0.01,quantity:0},
			];
			$scope.Total = 0;
			getTransacs();
			$scope.ActiveUser = $rootScope.__USER.user;
			getCashier();
			$selfScope.$watch("CS.Active",function(active){
				console.log(active);
				if(!active) return false;
				console.log(active);
				$scope.ActiveSY =  active.sy;
				if($scope.date_to)
					getCollections(1);
			});
			
		}
		/* $selfScope.$watch("CS.Active",function(active){
			console.log(active);
			if(!active) return false;
			console.log(active);
			$scope.ActiveSY =  active.sy;
			if($scope.date_to)
				getCollections(1);
		}); */
		$scope.setActOption = function(opt){
			$scope.ActiveOpt = opt;
			getCollections(1);
		}
		$scope.LoadReport = function(){
			getCollections(1);
			getOrs();
		}
		
		$scope.gotoPage = function(page){
			getCollections(page);
		}
		
		$scope.Clear = function(){
			$scope.date_from = '';
			$scope.date_to = '';
			$scope.Collections = '';
		}
		
		$scope.PrintData = function(){
			$timeout(function(){
				document.getElementById('PrintCashierCollection').submit();
			},1000);
		}
		$scope.PrintRemit = function(data){
			$timeout(function(){
				document.getElementById('PrintRemittance').submit();
			},1000);
		}
		
		$scope.openModal = function(){
			$scope.Saving = false;
			$scope.Total = 0;
			$scope.ComputeTotal();
			aModal.open("RemitModal");
		}
		
		$scope.Cancel = function(){
			$scope.Total = 0;
			aModal.close("RemitModal");
		}
		
		$scope.ComputeTotal = function(){
			$scope.Total = 0;
			$scope.TotalNon = 0;
			angular.forEach($scope.Remittance.breakdown, function(d){
				d.amount = d.denomination*d.quantity;
				$scope.Total += d.amount;
			});
			angular.forEach($scope.CashierData.data[0].collections, function(trx){
				if(trx.payment)
					$scope.TotalNon += trx.amount;
			});
		}
		
		$scope.setActiveTab = function(type){
			$scope.ActiveTab1 = type.id;
		}
		
		$scope.SaveNPrint = function(){
			$scope.Saving = true;
			var data = {cashier_id: $scope.ActiveUser.cashier_id};
			data.remittance_date = $filter('date')(new Date($scope.cash_date),'yyyy-MM-dd');
			data.total_collection = 0;
			data.details = [];
			angular.forEach($scope.Dinominations, function(d){
				data.total_collection+=d.amount;
				data.details.push(d)
			});
			data.booklets = $scope.Booklet;
			var noncash = [];
			angular.forEach($scope.CashierData.data[0].collections,function(non){
				if(non.payment){
					var a = {};
					a.check_date = non.check_date;
					a.bank_details = non.payment;
					a.OR = non.ref_no;
					a.amount = non.amount;
					noncash.push(a);
				}
			});
			data.noncash = noncash;
			
			console.log(data); //return;
			var success = function(response){
				data.date = data.remittance_date;
				data.booklet = data.booklets;
				data.doctype = $scope.ActiveOpt;
				data.breakdown = data.details;
				$scope.PrintRemittanceData = data;
				$scope.PrintRemit();
				aModal.close("RemitModal");
				getRemittance();
				$scope.Saving = false;
			}
			var error = function(response){
				
			}
			api.POST('remittances', data, success, error);
		}
		
		function getCashier(){
			var data = {id:$scope.ActiveUser.id}
			api.GET('users',data,function success(response){
				$scope.ActiveUser.cashier_id = response.data[0].cashier_id;
				$scope.ActiveUser.cashier_name = response.data[0].cashier;
			});
		}
		
		function getCollections(page){
			var data = {
				type:$scope.ActiveOpt,
				'page': page,
			}
			if($scope.ActiveUser.user_type!='cashr'){
				data.from = $scope.date_from;
				data.to = $scope.date_to;
				data.from = $filter('date')(new Date(data.from),'yyyy-MM-dd');
				data.to = $filter('date')(new Date(data.to),'yyyy-MM-dd');
			}else{
				data.date = $filter('date')(new Date($scope.cash_date),'yyyy-MM-dd');
				data.cashr = true;
			}
			//data.limit = 'less';
			api.GET('cashier_collections',data, function success(response){
				
				$scope.NoCollections = 0;
				$scope.Collections = response.data[0];
				angular.forEach($scope.Collections.collections, function(col){
					col.amount = $filter('currency')(col.amount);
					if(col.balance!='N/A'){
						col.balance = $filter('currency')(col.balance);
						col.total_due = $filter('currency')(col.total_due);
						col.total_paid = $filter('currency')(col.total_paid);
					}
				});
				
				$scope.Meta = response.meta;
				if($scope.Meta.page==1) getForPrinting(data);
			},function error(response){
				$scope.NoCollections = 1;
			});
		}
		
		
		//to page add limit per page
		function getForPrinting(data){
			data.limit = 'less';
			api.GET('cashier_collections',data, function success(response){
				var print = {data:response.data};
				$scope.CashierData =  print;
				console.log($scope.CashierData);
			});
		}
		
		function getTransacs(){
			var data = {limit:'less'};
			api.GET('transaction_types',data, function success(response){
				var Trnx = {};
				angular.forEach(response.data, function(tr){
					console.log(tr);
					Trnx[tr.id] = tr.name;
				})
				$scope.Transacs = Trnx;
			});
		}
		
		function getRemittance(){
			var data = {
				cashier_id: $scope.ActiveUser.cashier_id
			};
			data.remittance_date = $filter('date')(new Date($scope.cash_date),'yyyy-MM-dd');
			
			api.GET('remittances',data, function success(response){
				console.log(response.data);
				$scope.Remittance = {};
				$scope.Remittance.breakdown = response.data[0].breakdown;
				$scope.Remittance.doctype = $scope.ActiveOpt;
				$scope.Remittance.date = data.remittance_date;
				$scope.Total = 0;
				angular.forEach($scope.Remittance.breakdown, function(rem){
					$scope.Total += rem.amount;
				});
				$scope.Remittance.booklet = response.data[0].booklets;
				$scope.Remittance.noncash = response.data[0].noncash;
				$scope.Remitted = true;
				$scope.PrintRemittanceData = $scope.Remittance;
			},function error(response){
				$scope.Remittance = {};
				$scope.Remittance.breakdown = $scope.Dinominations;
				$scope.Remittance.booklet = $scope.Booklet;
				$scope.Remittance.date = data.remittance_date;
				$scope.Remittance.doctype = $scope.ActiveOpt;
				$scope.PrintRemittanceData = $scope.Remittance;
				var noncash = [];
				angular.forEach($scope.CashierData.data[0].collections,function(non){
					if(non.payment){
						var a = {};
						a.check_date = non.check_date;
						a.bank_details = non.payment;
						a.OR = non.ref_no;
						a.amount = non.amount;
						noncash.push(a);
					}
				});
				$scope.PrintRemittanceData.noncash = noncash;
				$scope.Remitted = false;
			});
		}
		
		// temporary
		function getOrs(page){
			var data = {
				type:$scope.ActiveOpt,
				limit: "less",
			}
			if($scope.ActiveUser.user_type!='cashr'){
				data.from = $scope.date_from;
				data.to = $scope.date_to;
				data.from = $filter('date')(new Date(data.from),'yyyy-MM-dd');
				data.to = $filter('date')(new Date(data.to),'yyyy-MM-dd');
			}else{
				data.date = $filter('date')(new Date($scope.cash_date),'yyyy-MM-dd');
				data.cashr = true;
			}
			//data.limit = 'less';
			api.GET('cashier_collections',data, function success(response){
				console.log(response.data[0]);
				$scope.Booklet = response.data[0].booklets;
				getRemittance();
			},function error(response){
				getRemittance();
			});
		}
		
		
	}]);

});