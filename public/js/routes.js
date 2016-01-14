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
    
    .when("/post_grading/:conf_id" , {
        templateUrl: "/pages/grades.html",
        controller: "UserController"
    })
    
    .when("/grade" , {
        templateUrl: "/pages/get_organizers_in_conference.html",
        controller: "UserController"
    })
    
    .when("/request_organizers" , {
        templateUrl: "/pages/request_form.html",
        controller: "RequestController"
    })
    
    .when("/attendance" , {
        templateUrl: "/pages/attendance.html",
        controller: "UserController"
    })
    
    .when("/manage_attendance/:att_conf_id" , {
        templateUrl: "/pages/manage_attendance.html",
        controller: "UserController"
    })
    
    .when("/requests" , {
        templateUrl: "/pages/all_requests.html",
        controller: "RequestController"
    })
    
    .when("/view_request/:file" , {
        templateUrl: "/pages/view_request.html",
        controller: "RequestController"
    })
    
    .when("/add_user" , {
        templateUrl: "/pages/add_user.html",
        controller: "UserController"
    })
    
    .when("/manage_users" , {
        templateUrl: "/pages/manage_users.html",
        controller: "UserController"
    })
    
    .when("/edit_user/:user_ID" ,{
        templateUrl: "/pages/edit_user.html",
        controller: "UserController"
    })
    
    .when("/exports" , {
        templateUrl: "/pages/exports.html",
        controller: "ExportController"
    });
    
}]);