"use strict";
define(['app','api'], function (app) {
    app.register.controller('CashierController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
		$scope.index=function(){
			$rootScope.__MODULE_NAME = 'Cashier';
			$scope.Steps = [
				{id:1, title: "Student", description:"Select Student"},
				{id:2, title: "Transaction", description:"Select Transactions"},
				{id:3, title: "Payment", description:"Select Payment Methods"},
				{id:4, title: "Confirmation", description:"Confirmation"}
			];
			$scope.initCashier = function(){
				$scope.ActiveStep=1;
				$scope.ActiveStudent={};
				$scope.SelectedStudent={};
				$scope.ActiveTransactions={};
				$scope.SelectedTransactions={};
				$scope.ActivePayments={};
				$scope.SelectedPayments={};
				$scope.TotalDue=0;
				$scope.TotalPaid=0;			
			};
			
			$scope.initCashier();
			api.GET('students',function success(response){
				console.log(response.data);
				$scope.Students=response.data;	
			});
			api.GET('transaction_types',function success(response){
				console.log(response.data);
				$scope.TransactionTypes=response.data;	
			});
			api.GET('payment_methods',function success(response){
				console.log(response.data);
				$scope.Payments=response.data;	
			});
			
			$scope.nextStep = function(){
				if($scope.ActiveStep===1){
					$scope.ActiveStudent = $scope.SelectedStudent;
					console.log($scope.ActiveStudent);
				}
				if($scope.ActiveStep===2){
					$scope.ActiveTransactions=[];
					$scope.TotalDue = 0;
					for(var index in $scope.TransactionTypes){
						var transactionType=$scope.TransactionTypes[index];
						var transaction = {
										  id:transactionType.id,
										  amount:transactionType.amount
										  };
						if($scope.SelectedTransactions[transactionType.id]){
							$scope.TotalDue= $scope.TotalDue + transaction.amount;
							$scope.ActiveTransactions.push(transaction);
							console.log($scope.ActiveTransactions);
							};
					};
				}
				if($scope.ActiveStep===3){
					$scope.ActivePayments=[];
					$scope.TotalPaid = 0;
					for(var index in $scope.Payments){
						var paymentMethod=$scope.Payments[index];
						var payment = {
										  id:paymentMethod.id,
										  amount:paymentMethod.amount
										  };
						if($scope.SelectedPayments[paymentMethod.id]){
							$scope.TotalPaid = $scope.TotalPaid + payment.amount;
							$scope.ActivePayments.push(payment);
							};
					};
				};
				if($scope.ActiveStep===4){
					$scope.Payment={
									student:$scope.ActiveStudent,
									transactions:$scope.ActiveTransactions,
									payments:$scope.ActivePayments,
								   };
					api.POST('payments',$scope.Payment,function success(response){
						console.log(response.data);
					});
					
				};
				if($scope.ActiveStep<$scope.Steps.length){
					$scope.ActiveStep++;
				}
			};
			$scope.prevStep = function(){
				if($scope.ActiveStep>1){
					$scope.ActiveStep--;
				};
			};
			$scope.updateStep=function(step){
				$scope.ActiveStep = step.id;
			};
			$scope.toggleSelectTransaction=function(id){
				$scope.SelectedTransactions[id] = !$scope.SelectedTransactions[id]; 
			}
			$scope.setSelecetedStudent=function(student){
				$scope.SelectedStudent = {
										 id:student.id,
										 name:student.first_name+" "+student.middle_name+" "+student.last_name+" "+student.suffix_name,
										 yearlevel:student.year_level_id
				                         };
			};
			$scope.toggleSelectPayment=function(id){
				$scope.SelectedPayments[id] = !$scope.SelectedPayments[id]; 
			}
			$scope.resetStudent = function(){
				$scope.SelectedStudent = null;
			};
			$scope.resetTransactions = function(){
				$scope.SelectedTransactions={};
				$scope.ActiveTransactions = null;
			};
			$scope.resetPayments = function(){
				$scope.SelectedPayments={};
				$scope.ActivePayments = null;
			};
			$scope.filterIncludedTransactions = function(transaction){
				return $scope.SelectedTransactions[transaction.id] && $scope.ActiveTransactions.length;
			};
			$scope.filterIncludedPayments = function(payment){
				return $scope.SelectedPayments[payment.id] && $scope.ActivePayments.length;
			};
			$scope.filterStudent=function(student){
				var searchBox = $scope.searchStudent;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(student.first_name) || keyword.test(student.middle_name) || keyword.test(student.last_name) || keyword.test(student.suffix_name) || keyword.test(student.id);
				return !searchBox || test;
			};
		};
    }]);
});


