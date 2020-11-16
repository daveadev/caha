define(['app','api'],function(app){
	app.register.controller('AccountInfoController',['$rootScope','$scope','api','$uibModal',function($rootScope,$scope,api,$uibModal){
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Account Info';
		};
		$scope.Save = function(){
			if(!$scope.OldPassword)
				return alert("Input old password");
			else if(!$scope.NewPassword)
				return alert("Input new password");
			else if(!$scope.ConfirmPassword)
				return alert("Input confirm password");

			if($scope.NewPassword!==$scope.ConfirmPassword)
				return  alert("Confirm password must match new password");
			
			var data = {old_password:$scope.OldPassword,new_password:$scope.NewPassword};
			var success = function(response){
				var message = response.meta.message;
					message += " Log in again.";
					alert(message);
					window.location.href="#/logout";
				

			}
			var error  =function(response){
				if(response.code==401)
					message = "Invalid password";
				else if(response.code==402)
					message = "New password too similar";
				alert(message);

			}
			api.POST('users/change_pass',data,success, error);
		}
	}]);
});