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
    })
    // check with ali if this to assign organizers to conference or the tasks to organizers.
    .when("/assign_tasks" , {
        templateUrl: "/pages/assign_tasks.html",
        controller: "TaskController"
    })
    
    .when("/grade" , {
        templateUrl: "/pages/grades.html",
        controller: "UserController"
    })
    
    .when("/request_organizers" , {
        templateUrl: "/pages/request_form.html",
        controller: "RequestController"
    });
    
}]);