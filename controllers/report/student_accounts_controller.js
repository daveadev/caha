"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('StudentAccController',['$scope','$rootScope','api',
	function($scope,$rootScope,api){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Student Accounts Collection';
			$scope.Headers = ['Student','Fee Dues','IP Pay','IP Bal','SEP-20 Pay','SEP-20 Bal','OCT-20 Pay','OCT-20 Bal','NOV-20 Pay','NOV-20 Bal','DEC-20 Pay','DEC-20 Bal','JAN-21 Pay','JAN-21 Bal','FEB-21 Pay','FEB-21 Bal','MAR-21 Pay','MAR-21 Bal','APR-21 Pay','APR-21 Bal'];
			$scope.Props = [
				'student','fee','pay1','bal1','pay2','bal2',
				'pay3','bal3','pay4','bal4','pay5','bal5',
				'pay6','bal6','pay7','bal7','pay8','bal8',
				'pay9','bal9'
			];
			getStudentAccountsColl();
		}
		
		$selfScope.$watch("SA.Active",function(active){
			if(!active) return false;
			$scope.ActiveSY =  active.sy;
		});
		
		$scope.gotoPage = function(page){
			getStudentAccountsColl(page);
		}
		
		function getStudentAccountsColl(page){
			var data = {
				limit:10
			}
			if(page)
				data.page = page;
			api.GET('student_account_collections', data,function success(response){
				var collections = response.data[0].collections;
				var update_fields = []; 
				angular.forEach(collections, function(col){
					var row = {};
					row['student'] = col.name;
					row['fee'] = col.total_fees;
					
					if(col.payments.length>2){
						var ctr = 1;
						angular.forEach(col.payments, function(sched){
							row['pay'+ctr]=sched.payment;
							row['bal'+ctr]=sched.balance;
							ctr++;
						});
					}
					
					update_fields.push(row);
				});
				console.log(update_fields);
				$scope.Meta = response.meta;
				$scope.Data = update_fields;

			});
		}
	
	}]);

});