app.config(["$routeProvider" , function(route){
    
    route.when("/" , {
        templateUrl: "/pages/home.html",
        controller: "UserController"
    })
    .when("/create_organizer" , {
        templateUrl: "/pages/addOrganizer.html",
        controller: "UserController"
    })
    
    .when("/edit" , {
        templateUrl: "/pages/addOrganizer.html",
        controller: "UserController"
    })
    
    .when("/edit_organizer/:email?" , {
        templateUrl: "/pages/searchOrganizer.html",
        controller: ["$http" , "$scope" , "$routeParams", "UserToEdit", "$location" ,function(request , scope , params , user , loc){
            scope.requesting_edit = false;
            scope.do_request = function(){
                scope.requesting_edit = true;
            }
            if(params.hasOwnProperty("email")){
                request.get("/organizers/"+params.email+"/edit")
                    .then(function(resp){
                    user.setData(resp.data);
                    loc.path("/edit");
                } , function(err){
                    alert("Something went wrong, Please refresh and try again!");
                });
            }
            if(!scope.orgs){
                request.get("/organizers")
                        .then(function(resp){
                    scope.orgs = resp.data;
                } , function(err){});
            }

        }]
    });
    
}]);