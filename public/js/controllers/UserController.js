app.controller("UserController" , ["$scope" , "$timeout" , "$location" , "$routeParams" , "_TOKEN" , "Patcher" , "Organizer", function(scope , timeout , loc , params , token , request , organizer){
    scope.view_data = {
        processing_request: false,
        success_msg: undefined,
        page_title: "Home Page",
        encoding_status: "It might take a few seconds to encode the agreement image after you choose it, Please wait for the encoding to finish before submitting!",
        errors: [],
        orgs: []
    };
    
    function changeTitle(title){
        scope.view_data.page_title = title;
    }
    
    scope.$on("$routeChangeSuccess" , function(ev , next , prev){
        
        var to = next.originalPath;
        
        if(to.indexOf("search_organizer") !== -1)
            changeTitle("Search Organizer");
        else if(to.indexOf("edit_organizer") !== -1)
            changeTitle("Edit Organizer");
        else if(to.indexOf("create_organizer") !== -1)
            changeTitle("Add Organizer");
        else
            changeTitle("Home Page");
    });
    
    scope.$on("$routeChangeStart" , function(ev , next , current){
        if(!current && next.originalPath.indexOf("edit") !== -1){
            loc.path("/search_organizer");
        }
    });
    
    scope.clear = function(){
        // clears the data and the view and resets everything to 
        // its default state;
        scope.newUser = {
            gender: 0
        }
        organizer.reset();
        request.reset();
    }
    
    var edit_mode = false;
    var rt = loc.path();
    if(rt.indexOf("edit") !== -1 && params.hasOwnProperty("email")){
        organizer.import(params.email);
        scope.newUser = organizer;
        scope.view_data.page_title = "Edit Organizer";
        edit_mode = true;
    }else if(rt.indexOf("search") !== -1 && !scope.view_data.orgs.length){
        request.set("url" , "/organizers").set("verb" , "get").send().then(function(resp){
            scope.view_data.orgs = resp.data;
        } , function(err){});
    }
    
    var reader = new FileReader();
    reader.onload = function(res){
        scope.$apply(function(){
            scope.newUser.agreement = res.target.result;
            scope.view_data.encoding_status = "Finished Encoding!";
        });
    }
    
    $("#agreement").on("change" , function(){
        var file = this.files[0];
        scope.$apply(function(){
            scope.view_data.encoding_status = "encoding image... ";
        });
        reader.readAsDataURL(file);
    });
    
    if(!edit_mode) {
        scope.clear();   
    }
    
    scope.createOrganizer = function(){
        scope.view_data.errors = [];
        //check to see if the agreement image changed and the
        //encoding didn't finish yet.
        if(document.getElementById("agreement").files.length){
            if(!scope.newUser.agreement || scope.newUser.agreement.indexOf("base64") === -1){
                return false;
            }
        }else {
            delete scope.newUser.agreement;
        }
        // we are sending a request depending on the mode! ..... Hold On!
        scope.view_data.processing_request = true;
        if(edit_mode){
            scope.editUser();
            return false;
        }
        request.set("url" , "/organizers").set("verb" , "post").set("data" , scope.newUser).send().then(function(resp){
            scope.view_data.processing_request = false;
            var response = resp.data;
            if(response instanceof Object){
                alert("Some error occurred, Please review the list of errors below!");
                scope.view_data.errors = response;
            }else {
                scope.view_data.encoding_status = "It might take a few seconds to encode the agreement image after you choose it, Please wait for the encoding to finish before submitting!";
                scope.view_data.success_msg = "Created Successfully!";
                // cycle ?!
                timeout(function(){
                    scope.view_data.success_msg = undefined;
                    scope.clear();
                } , 2000);
            }
        } , function(){
            
        });
    }
    
    scope.editUser = function(){
        console.log(scope.newUser);
        request.set("url" , "/organizer/update/" + scope.newUser.id).set("verb" , "post").set("data" , scope.newUser).send().then(function(response){
            scope.view_data.processing_request = false;
            var response = response.data;
            if(response instanceof Object){
                alert("Some error occurred, Please review the list of errors below!");
                scope.view_data.errors = response;
            }else {
                scope.view_data.success_msg = "Edited Successfully!";
                // reseting the user to null ?!
                timeout(function(){
                    scope.view_data.success_msg = undefined;
                } , 2000);
            }
        } , function(err){
            console.log(err);
        });
    }
    
    scope.delete = function(ev){
        var btn = ev.target;
        var email = btn.getAttribute("data-uni");
        request.set("url" , "/organizers/"+email).set("verb" , "delete").send().then(function(resp){
            $(".del-msg").show();
            setTimeout(function(){
                $(".del-msg").hide();
            } , 2000);
            var acc = $(scope.view_data.orgs).filter(function(ind , el){
                return el.email == email;
            })[0];
            scope.which = "";
            scope.view_data.orgs.splice(scope.view_data.orgs.indexOf(acc) , 1);
        } , function(err){});
    }
            
}]);