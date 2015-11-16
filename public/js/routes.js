app.config(["$routeProvider" , function(route){
    
    route.when("/" , {
        templateUrl: "/pages/addOrganizer.html",
        controller: "UserController"
    })
    .when("/create_organizer" , {
        templateUrl: "/pages/addOrganizer.html",
        controller: "UserController"
    });
    
}]);