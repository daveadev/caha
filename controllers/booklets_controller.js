"use strict";
define(['app','api'], function (app) {
    app.register.controller('BookletController',['$scope','$rootScope','$uibModal','api', function ($scope,$rootScope,$uibModal,api) {
		console.log($uibModal);
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Booklets';
			//Initialize components
			$scope.initBooklets=function(){
				$scope.Booklets=[];
				api.GET('booklets',function success(response){
					console.log(response.data);
					$scope.Booklets=response.data;	
				});
			};
			$scope.initBooklets();
			//Set for ng-show
			$scope.hasInfo = false;
			$scope.hasNoInfo = true;
			//Open Booklet Information
			$scope.openBooklet=function(booklet){
				$scope.Booklet = booklet;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			//Remove Booklet Information
			$scope.removeBookletInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Booklet = null;
			};
			//Filter Booklet
			$scope.filterBooklet=function(booklet){
				var searchBox = $scope.searchBooklet;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(booklet.series_start) || keyword.test(booklet.series_end);
				return !searchBox || test;
			};
			//Filter search box
			$scope.clearSearch = function(){
				$scope.searchBooklet = null;
			};
			//Opening the modal
			$scope.openModal=function(){
				var modalInstance = $uibModal.open({
					animation: true,
					templateUrl: 'myModalContent.html',
					controller: 'ModalInstanceController',
				});
				modalInstance.result.then(function (selectedItem) {
				  $scope.selected = selectedItem;
				}, function (source) {
					//Re-initialize booklets when confirmed
					if(source==='confirm')
						$scope.initBooklets();
				});
			};
		};
    }]);
	app.register.controller('ModalInstanceController',['$scope','$uibModalInstance','api', function ($scope, $uibModalInstance, api){
		//Get the data entered and push it to booklets.js
		$scope.confirmBooklet = function(){
			 $scope.Booklets={
						  series_start: $scope.seriesStart,
						  series_end: $scope.seriesEnd,
						  series_counter: $scope.seriesCounter,
						  status: "active",
						  cashier: $scope.cashier
						 };
			api.POST('booklets',$scope.Booklets,function success(response){
				$uibModalInstance.dismiss('confirm');
			});
		};
		//This is for ng-blur
		$scope.setSeriesCounter=function(seriesStart){
			$scope.seriesCounter=$scope.seriesStart;
		};
		//Close modal
		$scope.cancelBooklet = function(){
			$uibModalInstance.dismiss('cancel');
		};
	}]);
	
});


