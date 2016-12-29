"use strict";
define(['app','api'], function (app) {
	app.register.directive('simpleTypeahead',function(api){
		var div,input;
		return{
			restrict: 'E',
			scope:{
				Filter:'=filter',
				Fields:'=fields',
				Display:'@display',
				Model:'=model',
				Endpoint:'@endpoint',
				Placeholder:'=placeholder',
				PrimaryKey:'@primaryKey',
			},
			templateUrl:'views/templates/simple_typeahead.html?'+Math.random(),
			link:function($scope, elem){
				console.log($scope.PrimaryKey);
				$scope.PrimaryKey = $scope.PrimaryKey||'id';
				$scope.ShowBtn=true;
				div = angular.element(elem[0]).find('div')[0];
				input = angular.element(elem[0]).find('input')[0];
			},
			controller:function($scope){
				$scope.$watch('Model.id',function(){
					if($scope.Model){
						if(!$scope.Model.id){
							$scope.ShowBtn=false;
						}
						$scope.ShowBtn=true;
					}
				});
				$scope.clearSearch  = function(){
					$scope.Model = {id:null,name:null};
					//$scope.ShowBtn=false;
				}
				$scope.getResults = function(value,filter){
					filter = filter || {}
					var fields =  $scope.Fields;
					var data = {keyword:value,fields:fields};
					for(var field in filter){
						data[field] = filter[field];
					}
					return api.GET($scope.Endpoint,data,function(response){
					}).then(function(response){
						input.nextSibling.style.width = div.offsetWidth+'px';
						var display = $scope.Display||{};
						var primaryKey = $scope.PrimaryKey||'id';
						var tokens = display.length?display.split(' '):display;
						var source = response.data.map(function(item){
							var pk =  item[primaryKey];
							var name = [];
							if(tokens.length){
								for(var i in tokens){
									var token = tokens[i];
									if(item.hasOwnProperty(token)){
										name.push(item[token]);
									}
								}
								name = name.join(' ');
							}else{
								name =  item.name;
							}
							var obj = {
								id:pk,
								name:name,
							};
								
							return obj;
						  });
						return source;
					});
				}
			}
		};
	});
	
});


