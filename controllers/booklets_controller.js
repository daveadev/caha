"use strict";
define(['app','api'], function (app) {
    app.register.controller('BookletController',['$scope','$rootScope','$uibModal','api', function ($scope,$rootScope,$uibModal,api) {
		console.log($uibModal);
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Booklets';
			$scope.initBooklets=function(){
				$scope.Booklets=[];
				api.GET('booklets',function success(response){
					console.log(response.data);
					$scope.Booklets=response.data;	
				});
			};
			$scope.initBooklets();
			$scope.hasInfo = false;
			$scope.hasNoInfo = true;
			$scope.openBooklet=function(booklet){
				$scope.Booklet = booklet;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			$scope.removeBookletInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Booklet = null;
			};
			$scope.filterBooklet=function(booklet){
				var searchBox = $scope.searchBooklet;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(booklet.series_start) || keyword.test(booklet.series_end);
				return !searchBox || test;
			};
			$scope.clearSearch = function(){
				$scope.searchBooklet = null;
			};
			$scope.openModal=function(){
				var modalInstance = $uibModal.open({
					animation: true,
					templateUrl: 'myModalContent.html',
					controller: 'ModalInstanceController',
				});
				modalInstance.result.then(function (selectedItem) {
				  $scope.selected = selectedItem;
				}, function (source) {
					console.log(source);
				});
			};
		};
    }]);
	app.register.controller('ModalInstanceController',['$scope','$uibModalInstance','api', function ($scope, $uibModalInstance, api){
		$scope.confirmBooklet = function(){
			var Booklets={
						  series_start: $scope.seriesStart,
						  series_end: $scope.seriesEnd,
						  series_counter: $scope.seriesCounter,
						  cashier: $scope.cashier
						 };
			console.log(Booklets);
		};
		$scope.cancelBooklet = function(){
			$uibModalInstance.dismiss('cancel');
		};
	}]);
	
});


