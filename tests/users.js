"use strict";
define(["model"],function($model){
	var user =  new $model(
			{
			  "meta": {
			    "title": "Users"
			  },
			  "data": [
			    {
			      "id": 0,
				 "username":"admin",
				 "password":"password",
				 "user_type":"admin"
			    },
			    {
			      "id": 1,
			     "username":"user",
				 "password":"password",
				 "user_type":"user"
			    }
			  ]
			}
		);
		user.POST = function(data){
			switch(data.action){
				case 'login':
					var __MSG = 'Invalid username/password';
					var __USER = {user:null};
					var users =  user.data;
					for(var index in users){
						var u = users[index];
						if(u.username==data.username && u.password==data.password){
							__USER.user = angular.copy(u);
							__MSG = 'Login successful!';
						}
					}
					return {success:{data:__USER,message:__MSG}};
				break;
				case 'register':
					return {success:user.save(data)};
				break;
			}
			
		}
	return user;
});