"use strict";
define(['app','api','atomic/bomb'],function(app){
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
			$scope.BillingMonths =[
					//{id:'AUG2024', name:'AUG 2024'},
					{id:'SEP2024', name:'SEP 2024'},
					{id:'OCT2024', name:'OCT 2024'},
					{id:'NOV2024', name:'NOV 2024'},
					{id:'DEC2024', name:'DEC 2024'},
					{id:'JAN2025', name:'JAN 2025'},
					{id:'FEB2025', name:'FEB 2025'}
				];
			$scope.BillMonth = $scope.BillingMonths[4].id;
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