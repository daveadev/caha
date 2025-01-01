"use strict";
define(['app', 'fee','api', 'simple-sheet','atomic/bomb'], function(app,fee) {
    app.register.controller('AccountController', ['$scope', '$rootScope', '$uibModal', 'api', 'aModal','Atomic',
    	function($scope, $rootScope, $uibModal, api, aModal, atomic) {
        $scope.list = function() {
            $rootScope.__MODULE_NAME = 'Accounts';
            atomic.ready(function(){
            	$scope.Sections = atomic.Sections;
            });
            //Get accounts.js
            function getAccounts(data) {
                $scope.DataLoading = true;
                api.GET('accounts', data, function success(response) {
                    console.log(response.data);
                    $scope.Accounts = response.data;
                    updatePaginate(response);
                });
            }
			function getFees(id){
				var data = {account_id:id};
				api.GET('account_fees',data, function success(response){
					$scope.Fees = response.data;
				});
			}
			function getSched(id){
				var data = {account_id:id};
				api.GET('account_schedules',data, function success(response){
					$scope.Scheds = response.data;
				});
			}
			function getHist(id){
				var data = {account_id:id};
				api.GET('account_histories',data, function success(response){
					$scope.Histories = response.data;
				});
			}
			
			function updatePaginate(response){
				$scope.NextPage = response.meta.next;
				$scope.PrevPage = response.meta.prev;
				$scope.TotalItems = response.meta.count;
				$scope.LastItem = response.meta.page * response.meta.limit;
				$scope.FirstItem = $scope.LastItem - (response.meta.limit - 1);
				if ($scope.LastItem > $scope.TotalItems) {
					$scope.LastItem = $scope.TotalItems;
				};
				$scope.DataLoading = false;
			}
			
			$scope.SearchStudent = function(){
				$scope.Search = 1;
				$scope.Accounts = '';
				var data = {
					keyword:$scope.SearchWord,
					fields:['first_name','middle_name','last_name','id'],
					limit:'less'
				}
				var success = function(response){
					$scope.Accounts = response.data;
                    updatePaginate(response);
				}
				var error = function(response){
					
				}
				api.GET('accounts',data,success,error);
			}
			
			$scope.ClearSearch = function(){
				$scope.Search = 0;
				$scope.SearchWord = '';
				$scope.Accounts = '';
				api.GET('accounts', function success(response) {
					$scope.Accounts = response.data;
                    updatePaginate(response);
				});
			}
			
            $scope.initAccounts = function() {
                $scope.hasInfo = false;
                $scope.hasNoInfo = true;
                $scope.ActivePage = 1;
                $scope.NextPage = null;
                $scope.PrevPage = null;
                $scope.DataLoading = false;
                getAccounts({ page: $scope.ActivePage });
            };
            $scope.initAccounts();
            $scope.navigatePage = function(page) {
                $scope.ActivePage = page;
                getAccounts({ page: $scope.ActivePage });
            };
            //Open account information
            $scope.openAccountInfo = function(account) {
                $scope.Account = account;
                $scope.hasInfo = true;
                $scope.hasNoInfo = false;
				getFees(account.id);
				getSched(account.id);
				getHist(account.id);
            };
            //Remove account information
            $scope.removeAccountInfo = function() {
                $scope.hasInfo = false;
                $scope.hasNoInfo = true;
                $scope.Account = null;
            };
            //Filter account
            $scope.filterAccount = function(account) {
                var searchBox = $scope.searchAccount;
                var keyword = new RegExp(searchBox, 'i');
                var test = keyword.test(account.account_name);
                return !searchBox || account.account_no == searchBox || test;
            };
            $scope.confirmSearch = function() {
                    getAccounts({ page: $scope.ActivePage, keyword: $scope.searchAccount, fields: ['account_name'] });
                }
                //Clear searchbox
            $scope.clearSearch = function() {
                $scope.searchAccount = null;
            };
            $scope.deleteAccounts = function(id) {
                var data = { id: id };
                api.DELETE('accounts', data, function(response) {
                    $scope.removeAccountInfo();
                    getAccounts({ page: $scope.ActivePage });
                });
            };
            $scope.openModal = function(back_log) {
                var modalInstance = $uibModal.open({
                    animation: true,
                    templateUrl: 'myModalContent.html',
                    controller: 'ModalInstanceController',
                    resolve: {
                        back_log: function() {
                            return back_log;
                        }
                    }
                });
                $rootScope.__MODAL_OPEN = true;
                modalInstance.result.then(function(back_log) {
                    $scope.Account.back_log = back_log;
                    $rootScope.__MODAL_OPEN = false;
                }, function(source) {
                    $rootScope.__MODAL_OPEN = false;
                });
            };
			$scope.openAccountModal = function(back_log) {
				$rootScope.$broadcast('OpenAccountModal');
				return;
                var modalInstance = $uibModal.open({
                    animation: true,
                    templateUrl: 'AccountModal.html',
                    controller: 'AccountModalController',
                    resolve: {
                        back_log: function() {
                            return back_log;
                        }
                    }
                });
                $rootScope.__MODAL_OPEN = true;
                modalInstance.result.then(function(back_log) {
                    $scope.Account.back_log = back_log;
                    $rootScope.__MODAL_OPEN = false;
                }, function(source) {
                    $rootScope.__MODAL_OPEN = false;
                });
            };
			
        };
    }]);
    app.register.controller('ModalInstanceController', ['$scope', '$uibModalInstance', 'api', 'back_log', function($scope, $uibModalInstance, api, back_log) {

        $scope.$evalAsync(function() {
            $scope.BackLog = angular.copy(back_log);
        });

        $scope.newEntry = function() {
			var data = {
				transaction_no: $scope.transaction_no,
				ref_no: $scope.ref_no,
				amount: $scope.amount
			}
		
		$scope.BackLog.push(data);
		};

		$scope.removeEntry = function(index) {
			$scope.BackLog.splice(index, 1);
		};

		$scope.$watch('',function() {	
			$scope.totalAmount();
		});

		$scope.totalAmount = function() {
			var totalAmount = 0;
			angular.forEach($scope.BackLog, function(data) {
				totalAmount += data.amount;
			});	
			return totalAmount;
		};

        //Close modal
        //$scope.BackLog = back_log;
        $scope.cancelChangeScheme = function() {
            $uibModalInstance.dismiss('cancel');
        };

		
		$scope.confirmSaveEntry = function() {
			api.POST('accounts', data, function success(response) {
                $uibModalInstance.dismiss('confirm');
            });
        };
		
		 $scope.deleteEntry = function(id) {
                var data = { id: id };
                //api.DELETE('accounts', data, function(response) {        
           // });
        };
		
        $scope.confirmAction = function() {
            $uibModalInstance.close($scope.BackLog);	
        }
    }]);
	
	app.register.controller('AccountModalController', ['$scope','$rootScope','$filter','$timeout','api','aModal','Atomic',
	function($scope,$rootScope,$filter,$timeout,api,aModal,atomic){
		const $selfScope =  $scope;
		$scope = this;

		
		$scope.init = function(){
			atomic.ready(function(){
				$scope.Sections = atomic.Sections;
			});

			$scope.SchedHeaders = ['Bill Month',{label:'Due Date',class:'col-md-4'}, {label:'Due Amount',class:'col-md-4'}];
			$scope.SchedProps = ['label','due_date','due_amount'];
			$scope.SchedInputs = [{field:'label'},{field:'due_date', type:'date'},{field:'due_amount',type:'number'}];

			$scope.LedgerHeaders = ['Fee','Amount'];
			$scope.LedgerProps = ['fee_id','amount'];
			$scope.LedgerFees = fee.items;
			$scope.LedgerInputs = [{field:'fee_id',options:$scope.LedgerFees},{field:'amount',type:'number'}];

			$scope.Account = {};
			$scope.Account.date_enrolled = new Date();
			$scope.Account.last_billing = new Date('2025-04-07'); // TODOS: Add in system defaults
		}


		$scope.updateSched = function(sched){

		}

		$selfScope.$on('OpenAccountModal',function(){
			aModal.open('AccountModal');
			$scope.init();
		});

		$scope.closeModal = function() {
            aModal.close('AccountModal');
        };

		
		$scope.confirmAction = function() {
			$uibModalInstance.dismiss('confirm');
			return;
			api.POST('accounts', data, function success(response) {
                
            });
        };
        $scope.computeSched = function(){
        	let enrollDate = $scope.Account.date_enrolled;
        	let lastBillDate = $scope.Account.last_billing;
        	let schedule = computePaysched(enrollDate, lastBillDate);
        	$scope.SchedData = schedule;
        }

        function computePaysched(enrollDate, lastBillDate) {
		    let schedule = [];

		    // 1. Upon Enrollment
		    schedule.push({
		        due_date: enrollDate,
		        label: "Upon Enrollment",
		        due_amount: 0
		    });

		    // 2. Generate subsequent months until lastBillDate
		    let current = new Date(enrollDate);
		    current.setMonth(current.getMonth() + 1);
		    const dueDay = lastBillDate.getDate();

		    while (current.getFullYear() < lastBillDate.getFullYear() ||
		           (current.getFullYear() === lastBillDate.getFullYear() && current.getMonth() <= lastBillDate.getMonth())) {

		        const daysInMonth = new Date(current.getFullYear(), current.getMonth() + 1, 0).getDate();
		        current.setDate(Math.min(dueDay, daysInMonth));

		        schedule.push({
		            due_date: current,
		            label: formatLabel(current),
		            due_amount: 0
		        });

		        current = new Date(current);
		        current.setMonth(current.getMonth() + 1);
		    }

		    return schedule;
		}

		function formatLabel(d) {
		    const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
		    return `${months[d.getMonth()]} ${d.getFullYear()}`;
		}
	
	}]);
});