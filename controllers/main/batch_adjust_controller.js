"use strict";
define(['app','api','atomic/bomb','caha/api'],function(app){
	const DATE_FORMAT = "yyyy-MM-dd";
	let NEXT_SY = false;
	app.register.controller('BatchAdjustController',['$scope','$sce','$rootScope','$filter','$timeout','api','aModal','Atomic','cahaApiService',
	function($scope,$sce,$rootScope,$filter,$timeout,api,aModal,atomic,cahaapi){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
			$rootScope.$watch('_APP',function(app){
				if(!app) return;
				$scope.ActiveSY = $rootScope._APP.ACTIVE_SY;	
				NEXT_SY = $rootScope._APP.MOD_ESP;
			});
			
			atomic.ready(function(){
				var sys = atomic.SchoolYears;
					sys = $filter('orderBy')(sys,'-id');
					sys = [sys[0]];
				var sy = atomic.ActiveSY;
				if(NEXT_SY){
					var asy = sy +1;
					var nsy ={};
						nsy.id =  asy;
						nsy.label = asy + '-'+ (asy+1);
						nsy.code =  (asy+''.substring(2));
					sys.push(nsy);
				}

				$scope.SchoolYears = $filter('orderBy')(sys,'-id');
				$scope.ActiveSY = sy;
				$scope.YearLevels = atomic.YearLevels;
				$scope.Sections = atomic.Sections;
				$scope.ActiveYearLevel = $scope.YearLevels[0].id;
			});
			$scope.TransactCodes = [
					{id:'ACECF', name:'AC/EC'},
				];
			$scope.TransactCode = $scope.TransactCodes[0].id;
			$scope.PrevHeaders = ['Student', 'Amount'];
			$scope.PrevProps = ['name', 'amount'];
			$scope.PrevData = [
					{name:'Juan Dela Cruz',amount:999}
				];
			$scope.AdjustData = [];
			$scope.PrevInputs = [{field:'name',disabled:true, enableIf:'OTHRS'},{field:'amount',type:'number'}];
			$scope.updateItems = function(data){
				$scope.PrevData = data;
			}
			$selfScope.$watchGroup(['BAC.Amount','BAC.Section','BAC.TransactCode'],function(values){
				$scope.isComplete = values[0] && values[1] && values[2];
			});
			$scope.ActiveAdjust = {};
		}

		$scope.previewList = function(){
			$scope.isLoading = true;
			$scope.PrevData = [];
			$scope.AdjustData = [];
			$scope.adjustMode = 'EDIT';
			let filter = {
				section_id:$scope.Section,
				esp:$scope.ActiveSY,
				limit:'less',
			}
			let success = function(response){
				let classlist = response.data;
				for(var i in classlist){
					classlist[i].amount = $scope.Amount;
				}
				$scope.PrevData = classlist;
				$scope.isPreview = true;
				$scope.isLoading = false;
			};
			let error = function(response){
				$scope.isLoading = false;
			};
			api.GET('classlist_blocks',filter,success,error);
		}
		$scope.revert =function(){
			$scope.isPreview = false;
			$scope.isLoading = false;
			$scope.PrevData = [];
			$scope.AdjustData = [];
			$scope.adjustMode = 'PREVIEW';
			if($scope.isAdjusted){
				let amount = angular.copy($scope.Amount);
				for(var i in $scope.Sections){
					let sectObj = $scope.Sections[i];
					let sectId = $scope.Section;
					if(sectObj.id==sectId){
						sectObj.name += ` - AC/EC ${amount}`;
						sectObj.disabled = true;
					}
				}
				$scope.isAdjusted = false;
				$scope.Section = null;
				$scope.Amount = null;

			}
		}
		$scope.applyChanges = function(){
			$scope.adjustMode = 'SAVE';
			$scope.AdjustData =  angular.copy($scope.PrevData);
			$scope.isSaving = true;
			saveAdjustment($scope.AdjustData,0);
		}
		function saveAdjustment(records,index){
			if(index>records.length-1){
				adjustmentCompleted();
				
			}
			let record = angular.copy(records[index]);
			if(record.amount==0)
				return saveAdjustment(records,index+1);


			let adjustment = {
				'account_id': record.student_id,
				'transact_code':$scope.TransactCode,
				'amount':record.amount,
				'esp':$scope.ActiveSY
			}

			adjustment.transac_date =  $filter('date')(new Date($scope.TransactDate),DATE_FORMAT);;
			let success = function(response){
				console.log(response);
				return saveAdjustment(records,index+1);
			}
			let error = function(response){
				console.log(response);
			}

			$scope.ActiveAdjust = record;
			api.POST('ledgers/adjust',adjustment,success,error);
		}

		function adjustmentCompleted(){
			$scope.isAdjusted = true;
			$scope.isSaving = false;
		}
	}]);

});