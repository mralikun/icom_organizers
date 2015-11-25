app.config(["$routeProvider" , function(route){
    
    route.when("/" , {
        templateUrl: "/pages/home.html",
        controller: "UserController"
    })
    .when("/create_organizer" , {
        templateUrl: "/pages/addOrganizer.html",
        controller: "UserController"
    })
    
    .when("/search_organizer" , {
        templateUrl: "/pages/searchOrganizer.html",
        controller: "UserController"
    })
    
    .when("/edit_organizer/:email" , {
        templateUrl: "/pages/addOrganizer.html",
        controller: "UserController"
    });
    
}]);