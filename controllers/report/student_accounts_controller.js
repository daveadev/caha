"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('StudentAccController',['$scope','$rootScope','$timeout','api','$filter',
	function($scope,$rootScope,$timeout,api,$filter){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Student Accounts Collection';
			$scope.Headers = ['Total Fees','Subsidy','Fee Dues','Reservation','IP Pay','IP Bal','SEP-21 Pay','SEP-21 Bal','OCT-21 Pay','OCT-21 Bal','NOV-21 Pay','NOV-21 Bal','DEC-21 Pay','DEC-21 Bal','JAN-22 Pay','JAN-22 Bal','FEB-22 Pay','FEB-22 Bal','MAR-22 Pay','MAR-22 Bal','APR-22 Pay','APR-22 Bal','MAY-22 Pay','MAY-22 Bal',' '];
			$scope.Props = [
				'reservation','pay1','bal1','pay2','bal2',
				'pay3','bal3','pay4','bal4','pay5','bal5',
				'pay6','bal6','pay7','bal7','pay8','bal8',
				'pay9','bal9','pay10','bal10'
			];
			$scope.HHeaders = ['Total Fees','Subsidy','Fee Dues','Reservation','IP Pay','SEP-20 Pay','OCT-20 Pay','NOV-20 Pay','DEC-20 Pay','JAN-21 Pay','FEB-21 Pay','MAR-21 Pay','APR-21 Pay','MAY-22 Pay',' '];
			$scope.HProps = [
				'student','year_level','section','fee','subsidy','fee_dues','reservation','pay1','pay2',
				'pay3','pay4','pay5','pay6','pay7','pay8',
				'pay9','pay10'
			];
			$scope.HiddenBal = false;
			$scope.Loading = 1;
			getStudentAccountsColl();
		}
		
		$selfScope.$watch("SA.Active",function(active){
			if(!active) return false;
			$scope.ActiveSY =  active.sy;
		});
		
		$scope.Search = function(){
			$scope.PageOne = angular.copy($scope.Data);
			$scope.OrigMeta = angular.copy($scope.Meta);
			$scope.Data = '';
			$scope.Loading = 1;
			var filter = {
				keyword:$scope.SearchWord,
				fields:['first_name','middle_name','last_name','id'],
				limit:'less'
			}
			api.GET('student_account_collections', filter, function success(response){
				var collections = response.data[0].collections;
				var final_data = buildData(collections);
				$scope.Meta = response.meta;
				$scope.Data = final_data;
				$scope.Loading = 0;
			});
		}
		
		$scope.clearSearch = function(){
			$scope.Data = '';
			$scope.Loading = 1;
			$scope.SearchWord = '';
			$scope.Data = $scope.PageOne;
			$scope.Meta = $scope.OrigMeta;
			$scope.Loading = 0;
		}
		
		$scope.gotoPage = function(page){
			$scope.Loading = 1;
			$scope.Data = '';
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
		
		$scope.printSoa = function(id){
			console.log(id); 
			$scope.AccountId = id;
			$scope.Printed = 1;
			//document.getElementById('PrintSoa').submit();
			$timeout(function(){
				document.getElementById('PrintSoa').submit();
			},1000);
			//$scope.ShowDemo = true;
		}
		
		var coll = [];
		var print = {};
		function getForPrint(ctr){
			var data = {
				limit:100,
				page:ctr
			}
			api.GET('student_account_collections', data, function success(response){
				coll = coll.concat(response.data[0].collections);
				$scope.Perc = response.meta;
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
				$scope.Loading = 0;
			});
		}
		
		function buildData(data){
			var update_fields = []; 
			angular.forEach(data, function(col){
				var row = {};
				row['account_id']=col.account_id;
				row['student'] = col.name;
				row['year_level']=col.year_level;
				row['section']=col.section;
				row['fee'] = $filter('currency')(col.total_fees);
				row['subsidy'] = $filter('currency')(col.subsidy);
				row['fee_dues'] = $filter('currency')(col.total_fees+col.subsidy);
				var runningBal = 0;
				var totalpayment = 0;
				if(col.hasRes){
					row['reservation']=1000;
					runningBal-=1000;
					totalpayment+=1000;
				}else
					row['reservation']=0;
				if(row.subsidy!=0){
					runningBal+=row.subsidy;
					totalpayment += Math.abs(col.subsidy);
				}
				
				if(col.payments.length>2){
					var ctr = 1;
					angular.forEach(col.payments, function(sched){
						row['pay'+ctr]=$filter('currency')(sched.payment);
						totalpayment += sched.payment;
						runningBal = col.total_fees - totalpayment;
						//console.log(totalpayment);
						//console.log(runningBal);
						if(!$scope.HiddenBal)
							row['bal'+ctr]=$filter('currency')(runningBal);
						//console.log(col);
						ctr++;
					});
				}else{
					var ctr = 1;
					var currbal = 0;
					for(var i=1;i<=col.payments.length;i++){
						if(i==1||i==5){
							if(!col.payments[ctr-1])
								continue;
							
							console.log(col);
							row['pay'+i]=$filter('currency')(col.payments[ctr-1].payment);
							if(!$scope.HiddenBal)
								row['bal'+i]=$filter('currency')(col.payments[ctr-1].balance);
							currbal = $filter('currency')(col.payments[ctr-1].balance);
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