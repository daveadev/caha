"use strict";
define(['app','api'], function (app) {
    app.register.controller('CashierController',['$log','$scope','$rootScope','$uibModal','api', function ($log,$scope,$rootScope,$uibModal,api) {
		$scope.index=function(){
			$rootScope.__MODULE_NAME = 'Cashiers';
			//Steps in Nav-pills
			$scope.Steps = [
				{id:1, title: "Student", description:"Select Student"},
				{id:2, title: "Transaction", description:"Select Transactions"},
				{id:3, title: "Payment", description:"Select Payment Methods"},
				{id:4, title: "Confirmation", description:"Confirmation"}
			];
			//Initialize components
			$scope.initCashier = function(){
				$scope.ActiveStep=1;
				$scope.ActiveStudent={};
				$scope.SelectedStudent={};
				$scope.ActiveTransactions=[];
				$scope.SelectedTransactions={};
				$scope.ActivePayments=[];
				$scope.SelectedPayments={};
				$scope.SelectedPaymentDetails={};
				$scope.PopoverDetails={};
				$scope.TotalDue=0;
				$scope.TotalPaid=0;
				$scope.TotalChange=0;
				$scope.CurrentChange=0;
				$scope.hasInfo = false;
				$scope.hasStudentInfo = false;
				$scope.hasTransactionInfo = false;
				$scope.hasPaymentInfo = false;
				$scope.CashierSaving=false;
				$scope.FocusPayment = {};
				$scope.FocusTransaction = {};
				$scope.$watch('hasStudentInfo',updateHasInfo);
				$scope.$watch('hasTransactionInfo',updateHasInfo);
				$scope.$watch('hasPaymentInfo',updateHasInfo);
				$scope.$watch('ActiveStudent', function(){
					$scope.hasStudentInfo = $scope.ActiveStudent.id;
				});
				$scope.$watch('ActiveTransactions',function(){
					$scope.hasTransactionInfo = $scope.ActiveTransactions.length;
				});
				$scope.$watch('ActivePayments',function(){
					$scope.hasPaymentInfo = $scope.ActivePayments.length;
				});
				function updateHasInfo(){
					$scope.hasInfo = $scope.hasStudentInfo || $scope.hasTransactionInfo || $scope.hasPaymentInfo;
				};
			};
			$scope.initCashier();
			//Get BookletID
			var data = {id :1}
			api.GET('booklets',function success(response){
				$scope.ActiveBooklet = response.data;	
			});
			
			//Get students.js
			api.GET('students',function success(response){
				$scope.Students=response.data;	
			});
			//Get transaction_types.js
			api.GET('transaction_types',function success(response){
				$scope.TransactionTypes=response.data;	
			});
			//Get payment_methods.js
			api.GET('payment_methods',function success(response){
				$scope.Payments=response.data;	
			});
			//Change the step for navigation
			$scope.nextStep = function(){
				if($scope.ActiveStep===1){
					//Pass value of student information
					$scope.ActiveStudent = $scope.SelectedStudent;
					$log.debug($scope.ActiveStudent);
					var data = {};
						data.account_id = $scope.ActiveStudent.account_id;
					api.GET('transaction_types',data,function success(response){
						$scope.TransactionTypes=response.data;	
					});
									
				}
				if($scope.ActiveStep===2){
					//Pass value of transaction information
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
							$log.debug($scope.ActiveTransactions);
							};
					};
				}
				if($scope.ActiveStep===3){
					//Pass value of payment information
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
					$scope.TotalChange=$scope.TotalPaid-$scope.TotalDue;
				};
				if($scope.ActiveStep===4){
					//Push the gathered info to payments.js
					$scope.Payment={
									booklets:$scope.ActiveBooklet.series_counter,
									student:$scope.ActiveStudent,
									transactions:$scope.ActiveTransactions,
									payments:$scope.ActivePayments,
									
									
								   };
					$scope.CashierSaving=true;		   
					api.POST('payments',$scope.Payment,function success(response){
						$scope.openModal();
					});
					
				};
				if($scope.ActiveStep<$scope.Steps.length){
					$scope.ActiveStep++;
				}
				
			};
			//Previous step for navigation
			$scope.prevStep = function(){
				if($scope.ActiveStep>1){
					$scope.ActiveStep--;
				};
			};
			//Make the navigation clickable
			$scope.updateStep=function(step){
				$scope.ActiveStep = step.id;
			};
			//Take the value if it is true or false
			$scope.toggleSelectTransaction=function(id){
				$scope.SelectedTransactions[id] = !$scope.SelectedTransactions[id]; 
				if($scope.SelectedTransactions[id]){
					$scope.FocusTransaction[id] = true;
				}
			}
			//Set the selected student 
			$scope.setSelecetedStudent=function(student){
				$scope.SelectedStudent = {
										 id:student.id,
										 name:student.first_name+" "+student.middle_name+" "+student.last_name+" "+student.suffix_name,
										 yearlevel:student.year_level_id,
										 account_id:student.account_id
				                         };
			};
			//Take the value if it is true or false
			$scope.toggleSelectPayment=function(id){
				$scope.SelectedPayments[id] = !$scope.SelectedPayments[id]; 
				if($scope.SelectedPayments[id]){
					$scope.SelectedPaymentDetails[id]={};
					$scope.FocusPayment[id] = true;
				}
			}
			//Reset the value of student
			$scope.resetStudent = function(){
				$scope.SelectedStudent = {};
				$scope.ActiveStudent = {};
			};
			//Reset the value of transaction
			$scope.resetTransactions = function(){
				$scope.SelectedTransactions={};
				$scope.ActiveTransactions = [];
			};
			//Reset the value of payment
			$scope.resetPayments = function(){
				$scope.SelectedPayments={};
				$scope.ActivePayments = [];
			};
			//Filter transaction if selected
			$scope.filterIncludedTransactions = function(transaction){
				return $scope.SelectedTransactions[transaction.id] && $scope.ActiveTransactions.length;
			};
			//Filter payments if selected
			$scope.filterIncludedPayments = function(payment){
				return $scope.SelectedPayments[payment.id] && $scope.ActivePayments.length;
			};
			//Filter student
			$scope.filterStudent=function(student){
				var searchBox = $scope.searchStudent;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(student.first_name) || keyword.test(student.middle_name) || keyword.test(student.last_name) || keyword.test(student.suffix_name) || keyword.test(student.id);
				return !searchBox || test;
			};
			//Filter transaction
			$scope.filterTransaction=function(transaction){
				var searchBox = $scope.searchTransaction;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(transaction.name);
				return !searchBox || test;
			};
			//Clear search student
			$scope.clearSearchStudent=function(){
				$scope.searchStudent = null;
			};
			//Clear search transaction
			$scope.clearSearchTransaction=function(){
				$scope.searchTransaction = null;
			};
			$scope.isEmpty =function(obj){
				 for(var key in obj) {
					if(obj.hasOwnProperty(key))
						return false;
				}
				return true;
			}
			//Opening the modal
			$scope.displaySettings=function(){
				var modalInstance = $uibModal.open({
					animation: true,
					templateUrl: 'bookletModal.html',
					controller: 'BookletModalController',
				});
				modalInstance.opened.then(function(){$rootScope.__MODAL_OPEN=true;});
				
			};
			$scope.openModal=function(){
				var modalInstance = $uibModal.open({
						animation: true,
						size:'sm',
						templateUrl: 'successModal.html',
						controller: 'SuccessModalController',
					});
					modalInstance.result.then(function () {
					  
					}, function (source) {
						$scope.initCashier();
					});
			}
			$scope.setActivePopover = function(payment){
				$scope.ActivePaymentMethod=angular.copy(payment);
			}
			$scope.$watch('ActivePaymentMethod',function(avp){
				$scope.shouldOpen = {};
				if(typeof avp == "object")
					if(avp.id) $scope.shouldOpen[avp.id] = true;
			});
			$scope.shouldOpen =function(payment_id){
				return $scope.PopoverDetails.is_open && $scope.ActivePaymentMethod.id==payment_id;
			}
			$scope.savePopoverDetails =function(payment_id){
				$scope.SelectedPaymentDetails[payment_id] = angular.copy($scope.PopoverDetails);
				$scope.PopoverDetails={};
				$scope.PopoverDetails.is_open =false;
				$scope.ActivePaymentMethod={};
			}
			$scope.updateCurrentChange = function(){
				var cash = 0;
				var noncash = 0;
				for(var index in $scope.Payments){
					var payment = $scope.Payments[index];
					if(payment.id=='CASH'){ cash	+=payment.amount;}
					if(payment.id!='CASH'){ noncash	+=payment.amount;}
				}
				$scope.CurrentChange =  $scope.TotalDue - ( cash + noncash );
			}
		};
    }]);
	app.register.controller('BookletModalController',['$scope','$rootScope','$uibModalInstance','api', function ($scope, $rootScope, $uibModalInstance, api){
		//Get the data entered and push it to booklets.js
		$scope.confirmBooklet = function(){
			$rootScope.__MODAL_OPEN=false;
			 $uibModalInstance.dismiss('confirm');
		};
		//Close modal
		$scope.cancelBooklet = function(){
			$rootScope.__MODAL_OPEN=false;
			$uibModalInstance.dismiss('cancel');
		};
	}]);
	app.register.controller('SuccessModalController',['$scope','$rootScope','$timeout','$uibModalInstance','api', function ($scope,$rootScope,$timeout, $uibModalInstance, api){
		$rootScope.__MODAL_OPEN = true;
		$timeout(function(){
			$scope.ShowButton = true;
		},333);
		//Dismiss modal
		$scope.dismissModal = function(){
			$rootScope.__MODAL_OPEN = false;
			$uibModalInstance.dismiss('ok');
		};
	}]);
});


