app.config(["$routeProvider" , function(route){
    
    route.when("/" , {
        templateUrl: "/pages/welcome.html",
        controller: "UserController"
    });
    
}]);