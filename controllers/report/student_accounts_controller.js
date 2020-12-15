"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('StudentAccController',['$scope','$rootScope','api','$filter',
	function($scope,$rootScope,api,$filter){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Student Accounts Collection';
			$scope.Headers = ['Student','Year Level','Section','Fee Dues','Subsidy','IP Pay','IP Bal','SEP-20 Pay','SEP-20 Bal','OCT-20 Pay','OCT-20 Bal','NOV-20 Pay','NOV-20 Bal','DEC-20 Pay','DEC-20 Bal','JAN-21 Pay','JAN-21 Bal','FEB-21 Pay','FEB-21 Bal','MAR-21 Pay','MAR-21 Bal','APR-21 Pay','APR-21 Bal'];
			$scope.Props = [
				'student','year_level','section','fee','subsidy','pay1','bal1','pay2','bal2',
				'pay3','bal3','pay4','bal4','pay5','bal5',
				'pay6','bal6','pay7','bal7','pay8','bal8',
				'pay9','bal9'
			];
			$scope.HHeaders = ['Student','Year Level','Section','Fee Dues','Subsidy','IP Pay','SEP-20 Pay','OCT-20 Pay','NOV-20 Pay','DEC-20 Pay','JAN-21 Pay','FEB-21 Pay','MAR-21 Pay','APR-21 Pay',];
			$scope.HProps = [
				'student','year_level','section','fee','subsidy','pay1','pay2',
				'pay3','pay4','pay5','pay6','pay7','pay8',
				'pay9',
			];
			$scope.HiddenBal = false;
			getStudentAccountsColl();
		}
		
		$selfScope.$watch("SA.Active",function(active){
			if(!active) return false;
			$scope.ActiveSY =  active.sy;
		});
		
		$scope.gotoPage = function(page){
			getStudentAccountsColl(page);
		}
		
		$scope.Print = function(){
			if(print.data[0].hidden!=$scope.HiddenBal){
				var final_collections = buildData(coll);
				print = {data:[{'collections':final_collections,'columns':$scope.Headers}]};
				print.data[0].collections = final_collections;
				print.data[0].columns = $scope.Headers;
				if($scope.HiddenBal){
					print.data[0].columns = $scope.HHeaders;
					print.data[0].hidden = true;
				}else
					print.data[0].hidden = false;
				$scope.forPrinting = print;
			}
			document.getElementById('PrintStudentAccount').submit();
		}
		
		$scope.ToggleBalance = function(){
			$scope.HiddenBal = !$scope.HiddenBal;
			if(print.data){
				var final_collections = buildData(coll);
				print = {data:[{'collections':final_collections,'columns':$scope.Headers}]};
				print.data[0].collections = final_collections;
				print.data[0].columns = $scope.Headers;
				if($scope.HiddenBal){
					print.data[0].columns = $scope.HHeaders;
					print.data[0].hidden = true;
				}else
					print.data[0].hidden = false;
				$scope.forPrinting = print;
			}
		}
		
		var coll = [];
		var print = {};
		function getForPrint(ctr){
			var data = {
				limit:50,
				page:ctr
			}
			api.GET('student_account_collections', data, function success(response){
				coll = coll.concat(response.data[0].collections);
				if(response.meta.next){
					ctr++;
					getForPrint(ctr);
				}else{
					console.log(coll);
					var final_collections = buildData(coll);
					print = {data:[{'total_collected':response.data[0].total_collected,'collections':final_collections,'columns':$scope.Headers}]};
					if($scope.HiddenBal)
						print.data[0].columns = $scope.HHeaders;
					$scope.forPrinting = print;
					$scope.LoadingPrint = 0;
				}
			});
		}
		
		function getStudentAccountsColl(page){
			var data = {
				limit:10
			}
			if(page)
				data.page = page;
			api.GET('student_account_collections', data,function success(response){
				if(response.meta.page==1){
					$scope.LoadingPrint = 1;
					getForPrint(1);
				}
				var collections = response.data[0].collections;
				var final_data = buildData(collections);
				$scope.Meta = response.meta;
				$scope.Data = final_data;

			});
		}
		
		function buildData(data){
			var update_fields = []; 
			angular.forEach(data, function(col){
				var row = {};
				row['student'] = col.name;
				row['year_level']=col.year_level;
				row['section']=col.section;
				row['fee'] = $filter('currency')(col.total_fees);
				row['subsidy'] = $filter('currency')(col.subsidy);
				
				if(col.payments.length>2){
					var ctr = 1;
					angular.forEach(col.payments, function(sched){
						row['pay'+ctr]=$filter('currency')(sched.payment);
						if(!$scope.HiddenBal)
							row['bal'+ctr]=$filter('currency')(sched.balance);
						
						ctr++;
					});
				}else{
					var ctr = 1;
					var currbal = 0;
					for(var i=1;i<=9;i++){
						if(i==1||i==5){
							row['pay'+i]=$filter('currency')(col.payments[ctr-1].payment);
							if(!$scope.HiddenBal)
								row['bal'+i]=$filter('currency')(col.payments[ctr-1].balance);
							//currbal = $filter('currency')(col.payments[ctr-1].balance);
							ctr++;
						}else{
							row['pay'+i]=0;
							if(!$scope.HiddenBal)
								row['bal'+i]=$filter('currency')(currbal);
						}
					}
				}
				
				update_fields.push(row);
			});
			return update_fields;
		}
		
	
	}]);

});