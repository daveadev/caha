"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('StudentAccController',['$scope','$rootScope','api','$filter',
	function($scope,$rootScope,api,$filter){
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
			getForPrint();
			$scope.LoadingPrint = 1;
		}
		
		$selfScope.$watch("SA.Active",function(active){
			if(!active) return false;
			$scope.ActiveSY =  active.sy;
		});
		
		$scope.gotoPage = function(page){
			getStudentAccountsColl(page);
		}
		
		$scope.Print = function(){
			console.log($scope.forPrinting);
			document.getElementById('PrintStudentAccount').submit();
		}
		
		function getForPrint(){
			var data = {
				limit:'less'
			}
			api.GET('student_account_collections', data, function success(response){
				var print = {data:response.data};
				$scope.forPrinting = print;
				$scope.LoadingPrint = 0;
			});
		}
		
		function getStudentAccountsColl(page){
			var data = {
				//account_id:'LSS97238',
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
					row['fee'] = $filter('currency')(col.total_fees);
					
					if(col.payments.length>2){
						var ctr = 1;
						angular.forEach(col.payments, function(sched){
							row['pay'+ctr]=$filter('currency')(sched.payment);
							row['bal'+ctr]=$filter('currency')(sched.balance);
							ctr++;
						});
					}else{
						var ctr = 1;
						var currbal = 0;
						console.log(col.payments);
						for(var i=1;i<=9;i++){
							if(i==1||i==5){
								row['pay'+i]=$filter('currency')(col.payments[ctr-1].payment);
								row['bal'+i]=$filter('currency')(col.payments[ctr-1].balance);
								currbal = $filter('currency')(col.payments[ctr-1].balance);
								ctr++;
							}else{
								row['pay'+i]=0;
								row['bal'+i]=$filter('currency')(currbal);
							}
						}
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