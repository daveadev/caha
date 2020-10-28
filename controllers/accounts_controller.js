"use strict";
define(['app', 'api', 'simple-sheet'], function(app) {
    app.register.controller('AccountController', ['$scope', '$rootScope', '$uibModal', 'api', function($scope, $rootScope, $uibModal, api) {
        $scope.list = function() {
            $rootScope.__MODULE_NAME = 'Accounts';

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
});