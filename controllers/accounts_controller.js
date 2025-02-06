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
			$scope.SchedData = [];
			$scope.FeeDistHdr = null;
			$scope.FeeDistProps = null;
			$scope.FeeDistInput = null;
			$scope.FeeDistData = [];
			let ComputeOptions = [
				{id:'UPREG', name:'Upon  Registration'},
				{id:'UPNF', name:'Full Upon Enrollment'},
				{id:'DIST', name:'Distribute Monthly'}

			];
			$scope.LedgerHeaders = ['Fee','Amount', 'Computation'];
			$scope.LedgerProps = ['fee_id','amount','computation'];
			$scope.LedgerFees = fee.items;
			$scope.LedgerInputs = [{field:'fee_id',options:$scope.LedgerFees},{field:'amount',type:'number'},{field:'computation',options:ComputeOptions}];
			$scope.LedgerData = [];
			$scope.Account = {};
			$scope.Account.date_enrolled = new Date();
			$scope.Account.last_billing = new Date('2025-04-07'); // TODOS: Add in system defaults
			$scope.isAccountObjValid = true;
			// Setup Default Fees
			fee.items.map(function(obj){
				let item = {fee_id:obj.id,
							amount:obj.amount,
							computation:obj.computation};
				$scope.LedgerData.push(item);
			});
				
		}

		$scope.updateLedger = function(fees){
			$scope.LedgerData = fees;
		}
		$scope.updateSched = function(sched){

		}
		$scope.updateFeeDist = function(dist){

		}

		$selfScope.$on('OpenAccountModal',function(){
			aModal.open('AccountModal');
			$scope.init();
		});

		$scope.closeModal = function() {
            aModal.close('AccountModal');
        };

		
		$scope.confirmAction = function(form) {
			console.log(form);
			form.$setSubmitted();

			let account = $scope.Account;
			account.paysched = $scope.SchedData;
			account.ledger = $scope.Ledger
			api.POST('accounts/new_student_account', account, function success(response) {
            	console.log(response);    
            });
        };
        $scope.computeSched = function(){
        	let enrollDate = $scope.Account.date_enrolled;
        	let lastBillDate = $scope.Account.last_billing;
        	let fees = $scope.LedgerData;
        	let schedObj = computePaysched(enrollDate, lastBillDate,fees);
        	$scope.SchedData = schedObj.schedule;
        	$scope.FeeDistHdrs = schedObj.headers;
        	$scope.FeeDistProps = schedObj.props;
        	$scope.FeeDistInputs = schedObj.inputs;
        	$scope.FeeDistData = schedObj.data;
        }

       function computePaysched(enrollDate, lastBillDate, fees) {
		    let schedule = [];
		    let data = [];

		    // 1. Upon Registration (Separate Line for UPREG)
		    let registrationCharge = {};
		    fees.forEach(fee => {
		        if (fee.computation === 'UPREG') {
		            registrationCharge[fee.fee_id] = fee.amount;
		        }
		    });
		    if (Object.keys(registrationCharge).length > 0) {
		        registrationCharge['Total'] = Object.values(registrationCharge).reduce((a, b) => a + b, 0);
		        schedule.push({
		            due_date: enrollDate,
		            label: "Upon Registration",
		            due_amount: registrationCharge['Total']
		        });
		        data.push(registrationCharge);
		    }

		    // 2. Upon Enrollment (Separate Line for UPNF)
		    let enrollmentCharge = {};
		    fees.forEach(fee => {
		        if (fee.computation === 'UPNF') {
		            enrollmentCharge[fee.fee_id] = fee.amount;
		        }
		    });


		    // 3. Generate subsequent months until lastBillDate
		    let current = new Date(enrollDate);
		    current.setMonth(current.getMonth() + 1);
		    const dueDay = lastBillDate.getDate();

		    let billingMonths = [];
		    while (current.getFullYear() < lastBillDate.getFullYear() ||
		           (current.getFullYear() === lastBillDate.getFullYear() && current.getMonth() <= lastBillDate.getMonth())) {
		        const daysInMonth = new Date(current.getFullYear(), current.getMonth() + 1, 0).getDate();
		        current.setDate(Math.min(dueDay, daysInMonth));
		        billingMonths.push({
		            due_date: new Date(current),
		            label: formatLabel(current)
		        });
		        current = new Date(current);
		        current.setMonth(current.getMonth() + 1);
		    }
		    
		    // Compute distributed fees (monthly tuition & energy fee)
		    let billMonthCount = billingMonths.length + 1;
		    let monthlyCharge = {};
		    fees.forEach(fee => {
		        if (fee.computation === 'DIST') {
		            monthlyCharge[fee.fee_id] = fee.amount / billMonthCount;
		            enrollmentCharge[fee.fee_id] = (enrollmentCharge[fee.fee_id] || 0) + monthlyCharge[fee.fee_id];
		        }
		    });
		    
		    if (Object.keys(enrollmentCharge).length > 0) {
		        enrollmentCharge['Total'] = Object.values(enrollmentCharge).reduce((a, b) => a + b, 0);
		        schedule.push({
		            due_date: enrollDate,
		            label: "Upon Enrollment",
		            due_amount: enrollmentCharge['Total']
		        });
		        data.push(enrollmentCharge);
		    }

		    

		    // 4. Distribute fees across billing months
		    billingMonths.forEach(month => {
		        let monthlyRow = { ...monthlyCharge };
		        monthlyRow['Total'] = Object.values(monthlyRow).reduce((a, b) => a + b, 0);
		        schedule.push({
		            due_date: month.due_date,
		            label: month.label,
		            due_amount: monthlyRow['Total']
		        });
		        data.push(monthlyRow);
		    });

		    // Compute total row for all columns
		    let totalRow = {};
		    fees.forEach(fee => {
		        totalRow[fee.fee_id] = data.reduce((sum, row) => sum + (row[fee.fee_id] || 0), 0);
		    });
		    totalRow['Total'] = Object.values(totalRow).reduce((a, b) => a + b, 0);
		    data.push(totalRow);

		    // Convert data values to currency format before returning
		    data = data.map(row => {
		        let formattedRow = {};
		        Object.keys(row).forEach(key => {
		            formattedRow[key] = $filter('currency')(row[key]);
		        });
		        return formattedRow;
		    });
		    // Fee Distribution Table
		    const headers = [...fees.map(fee => ({ label: fee.fee_id, class: 'text-right' })), { label: 'Total', class: 'text-right' }];
    
		    const props = [...fees.map(fee => fee.fee_id), 'Total'];
		    const inputs = [...fees.map(fee => ({ field: fee.fee_id, type: 'number' })), { field: 'Total', type: 'number' }];
		    data.map(function(item){
		    	console.log(item)
		    	
		    });
		    return { schedule, headers, props, inputs, data };
		}





		function formatLabel(d) {
		    const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
		    return `${months[d.getMonth()]} ${d.getFullYear()}`;
		}
	
	}]);
});