"use strict";
define(['app', 'api', 'atomic/bomb'], function(app) {
    app.register.controller('LedgerController', ['$scope', '$rootScope', '$uibModal', 'api', 
	function($scope, $rootScope, $uibModal, api) {
		const $selfscope = $scope;
		$scope = this;
        $scope.list = function() {
            $rootScope.__MODULE_NAME = 'Student Ledgers';
            //Initialize ledger and get ledgers.js
			$rootScope.$watch('_APP',function(app){
                if(app){
					$scope.SchoolYears = app.SCHOOL_YEARS;
					$scope.initLedgers();
				}
            });
            function getLedgers(data) {
				
                $scope.DataLoading = true;
                api.GET('ledgers', data, function success(response) {
                    console.log(response.data);
                    $scope.Ledgers = response.data;
                    $scope.NextPage = response.meta.next;
                    $scope.PrevPage = response.meta.prev;
                    $scope.TotalItems = response.meta.count;
                    $scope.LastItem = response.meta.page * response.meta.limit;
                    $scope.FirstItem = $scope.LastItem - (response.meta.limit - 1);
                    if ($scope.LastItem > $scope.TotalItems) {
                        $scope.LastItem = $scope.TotalItems;
                    };
                    $scope.DataLoading = false;
                });
            }
            $scope.initLedgers = function() {
				$scope.ActiveSY  = $rootScope._APP.ACTIVE_SY;
                $scope.hasInfo = false;
                $scope.hasNoInfo = true;
                $scope.ActivePage = 1;
                $scope.NextPage = null;
                $scope.PrevPage = null;
                $scope.DataLoading = false;
                getLedgers({ page: $scope.ActivePage,esp:$scope.ActiveSY });
            };
            
            $scope.navigatePage = function(page) {
                $scope.ActivePage = page;
                getLedgers({ page: $scope.ActivePage });
            };
            //Open ledger Information
            $scope.openLedgerInfo = function(ledger) {
				console.log(ledger);
                $scope.Ledger = ledger;
                $scope.hasInfo = true;
                $scope.hasNoInfo = false;
            };
            //Remove Transaction Info
            $scope.removeLedgerInfo = function() {
                $scope.hasInfo = false;
                $scope.hasNoInfo = true;
                $scope.Ledger = null;
            };
            //Filter ledger
            $scope.filterLedger = function(ledger) {
                var searchBox = $scope.searchLedger;
                var keyword = new RegExp(searchBox, 'i');
                var test = keyword.test(ledger.details) || keyword.test(ledger.account.account_name);
                return !searchBox || test;
            };
            $scope.confirmSearch = function() {
                    getLedgers({ page: $scope.ActivePage, keyword: $scope.searchLedger, fields: ['account.account_name'] });
                }
                //Clear search box
            $scope.clearSearch = function() {
                $scope.searchLedger = null;
            };
            $scope.deleteLedger = function(id) {
                var data = { id: id };
                api.DELETE('ledgers', data, function(response) {
                    $scope.removeLedgerInfo();
                    getLedgers({ page: $scope.ActivePage });
                });
            };


            //Open modal
            $scope.openModal = function() {
				var active = {
					sy:$scope.ActiveSY,
					SYs:$scope.SchoolYears,
				}
                var modalInstance = $uibModal.open({
                    animation: true,
                    templateUrl: 'myModalContent.html',
                    controller: 'ModalInstanceController',
					resolve: {
						active: function(){
							return active;
						}
					}
                });
                $rootScope.__MODAL_OPEN = true;
                modalInstance.result.then(function(selectedItem) {
                    $scope.selected = selectedItem;
                    $rootScope.__MODAL_OPEN = false;
                }, function(source) {
                    $rootScope.__MODAL_OPEN = false;
                    //Re initialize ledger when confirmed
                    if (source === 'confirm')
                        $scope.initLedgers();
                });
            };
        };
    }]);

    app.register.controller('ModalInstanceController', ['$scope', '$uibModalInstance', 'api','active','$filter',
	function($scope, $uibModalInstance, api, active,$filter) {
        //Gets the data entered in modal and push it to ledgers.js
		$scope.Saving = 0;
		/* api.GET('transaction_types', {is_ledger:true,limit:'less'}, function success(response){
			$scope.Details = response.data;
		}); */
		$scope.Details = [
			{id:'ADVTP',name:'Advance Payment'},
			{id:'RSRVE',name:'Reservation Fee'},
			{id:'RRVRS',name:'Reservation Reversal'},
			{id:'BFRWD',name:'Balance Forwarded'},
			{id:'OLDAC',name:'Old Account'},
			{id:'FULLP',name:'Full Payment'},
			{id:'INIPY',name:'Initial Payment'},
			{id:'SBQPY',name:'Subsequent Payment'},
		]
		api.GET('booklets',{status:'ACTIV'}, function success(response){
			$scope.Ref_no = 'OR '+ response.data[0].series_counter;
		});
		$scope.SchoolYears = active.SYs;
		$scope.SchoolYear = active.sy;
        //$scope.type = 'credit';
        $scope.confirmLedger = function() {
			$scope.Saving = 1;
			var ledgerItem = {
				account_id:$scope.Account.id,
				transac_date:$scope.date,
				ref_no:$scope.Ref_no,
				transaction_type_id:$scope.Detail.id,
				details:$scope.Detail.name,
				esp:$scope.SchoolYear,
				amount:$scope.Amount,
			}
			if($scope.type=='credit')
				ledgerItem.type = '+';
			else
				ledgerItem.type = '-';
			ledgerItem.transac_date = $filter('date')(new Date(ledgerItem.transac_date),'yyyy-MM-dd');
			
            //console.log(tDate.getFullYear());

            api.POST('ledgers', ledgerItem, function success(response) {
				$scope.Saving = 0;
                $uibModalInstance.dismiss('confirm');
            });
        };

        //Close modal
        $scope.cancelLedger = function() {
            $uibModalInstance.dismiss('cancel');
        };
        //Change if it is debit or credit
        $scope.setType = function(value) {
            $scope.type = value;
        };
    }]);
});