"use strict";
define(['app','billings','api','atomic/bomb'],function(app,billings){
	const BILL_MONTHS = billings.generateBillingMonths('SOA');
	const LAST_BILL_MO = BILL_MONTHS.length-1;
	app.register.controller('SoaController',['$scope','$rootScope','api',
	function($scope,$rootScope,api){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.$watch('_APP',function($app){
				if(!$app)
					return;
				else{
					$rootScope.__MODULE_NAME = 'Ledger printing';
					$scope.Printed = 0;
					$scope.Options = ['Single','Batch'];
					$scope.ActiveOpt = 'Single';
					$scope.ActiveSem = $app.DEFAULT_.SEMESTER.id;
					getDepartments();
					getYl();
					getSections();
					console.log($app);
				}
			});

			$scope.BillingMonths =BILL_MONTHS;
			$scope.BillMonth =  BILL_MONTHS[LAST_BILL_MO].id;
		}
		$selfScope.$watch("SOA.Active",function(active){
			if(!active) return false;
			$scope.ActiveSy = active.sy;
			$scope.ActiveDept = active.ACTIVE_DEPT;
			console.log($scope.ActiveSy);
		});
		
		$scope.setActOption = function(opt){
			$scope.ActiveOpt = opt;
		}
		
		$scope.printSoa = function(){
			console.log($scope.Student); 
			$scope.Printed = 1;
			document.getElementById('PrintSoa').submit();
		}
		
		$scope.BatchPrint = function(){
			$scope.Printed = 1;
			document.getElementById('PrintSoaBatch').submit();
		}
		
		$scope.setActiveDept = function(dept){
			$scope.ActiveSection = '';
			$scope.ActiveDept = dept;
			getYl()
		}
		
		$scope.setActiveYearLevel = function(yl){
			$scope.ActiveYearLevel = yl;
		}
		
		$scope.SetActiveSection = function(sec){
			$scope.ActiveSection = sec;
			$scope.OpenFilter = false;
		}
		
		function getDepartments(){
			api.GET('departments', function success(response){
				$scope.departments = response.data;
			});
		}
		
		function getYl(){
			var data={department_id:$scope.ActiveDept,limit:99};
			api.GET('year_levels',data,function success(response){
				$scope.YearLevels = response.data;
			});
		}
		
		function getSections(){
			var data = {limit:'less'};
			api.GET('sections',data, function success(response){
				$scope.Sections = response.data;
			});
		}
		
	}]);

});