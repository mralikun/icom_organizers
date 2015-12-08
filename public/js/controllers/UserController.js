app.controller("UserController" , ["$scope" , "$rootScope" , "$timeout" , "$location" , "$routeParams" , "_TOKEN" , "Patcher" , "Organizer", function(scope , root , timeout , loc , params , token , request , organizer){
    scope.view_data = {
        processing_request: false,
        success_msg: undefined,
        page_title: "Home Page",
        encoding_status: "It might take a few seconds to encode the agreement image after you choose it, Please wait for the encoding to finish before submitting!",
        errors: [],
        orgs: [],
        crits: []
    };
    var myMap = function(arr , cb){
        var a = [];
        for(var i = 0; i < arr.length; i++){
            a.push(cb(arr[i]));
        }
        return a;
    }
    var intID = 0;
    var choosen_fields = [];
    // Grading an Organizer
    var Grades = {
        data : {},
        assign: function(crit , grade){
            this.data[crit] = grade;
        },
        userID: 0,
        patch: function(){
            // the data to a link // the data object {each key is the id of the criteria => the value is the grade}.
            // we might also need the conference ID
            request.set("url" , "/organizer/setGrades").set("verb" , "POST").set("data" , this.data).send().then(function(resp){
                
            } , function(err){
                alert("Something wrong happened when connecting to server, Please refresh and try again!");
            });
        },
        init: function(){
            if(scope.view_data.crits.length){
                for(var i = 0; i < scope.view_data.crits.length ; i++){
                    this.data[scope.view_data.crits[i].name.toLowerCase()] = 0;
                }
            }
        }
    };
    ///
    
    request.set("url" , "/workingfields").set("verb" , "get").send().then(function(resp){
        scope.fields = resp.data;
    } , function(err){
        alert("Something went wrong, Please refresh the page and try again!");
    });
    
    root.$on("changeTitle" , function(events , title){
        changeTitle(title);
    });
    
    function changeTitle(title){
        scope.view_data.page_title = title;
    }
    
    function setFields(arr){
        window.clearInterval(intID);
        var f = myMap(arr , function(e){
            return e.id;
        });
        console.log(f instanceof Array);
        for(var i = 0; i< f.length; i++){
            $("input[type='checkbox'][data-f='"+f[i]+"']").attr("checked" , true);
        }
        choosen_fields = f;
    }
    
    scope.appendField = function(event){
        var id = parseInt(event.target.getAttribute("data-f") , 10);
        console.log(typeof choosen_fields);
        if(choosen_fields.indexOf(id) !== -1){
            choosen_fields.splice(choosen_fields.indexOf(id) , 1);
        }else {
            choosen_fields.push(id);
        }
        console.log(choosen_fields);
    }
    
    scope.showGradingSheet = function(event){
        $(".cover").show();
        var tar = event.target;
        var id = parseInt(tar.getAttribute("data-id") , 10);
        console.log(id);
    }
    
    scope.hideGradingSheet = function(){
        $(".cover").hide();
    }
    
    scope.$on("$routeChangeSuccess" , function(ev , next , prev){
        
        var to = "";
        if(next.originalPath)
            to = next.originalPath;
        if(to.indexOf("search_organizer") !== -1)
            changeTitle("Search Organizer");
        else if(to.indexOf("edit_organizer") !== -1)
            changeTitle("Edit Organizer");
        else if(to.indexOf("create_organizer") !== -1)
            changeTitle("Add Organizer");
        else
            changeTitle("Home Page");
        $("li.active-link").removeClass("active-link");
        $("a:not(.home)[href='#"+to+"']").parent().addClass("active-link");
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
        $("input[type='checkbox']").attr("checked" , false);
        choosen_fields = [];
    }
    
    var edit_mode = false;
    var rt = loc.path();
    if(rt.indexOf("edit") !== -1 && params.hasOwnProperty("email")){
        organizer.import(params.email);
        scope.newUser = organizer;
        scope.view_data.page_title = "Edit Organizer";
        edit_mode = true;
        intID = setInterval(function(){
            if(scope.newUser.workingfields){
                setFields(scope.newUser.workingfields);
            }
        } , 1000);
    }else if(rt.indexOf("search") !== -1 && !scope.view_data.orgs.length){
        request.set("url" , "/organizers").set("verb" , "get").send().then(function(resp){
            scope.view_data.orgs = resp.data;
        } , function(err){});
    }else if(rt.indexOf("grade") !== -1){
        root.$emit("changeTitle" , "Grade Organizers");
    }else if(rt === "/"){
        request.set("url" , "/auth/onlineUser").set("verb" , "get").send().then(function(resp){
            scope.role = resp.data.role;
        } , function(){});
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
        scope.newUser.working_fields = choosen_fields;
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