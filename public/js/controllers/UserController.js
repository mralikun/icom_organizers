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
    function date_formater(d){
        var day = d.getDate();
        var month = d.getMonth();
        var year = d.getFullYear();
        return year + "-" + ((month < 10) ? "0"+month : month) + "-" + ((day < 10) ? "0"+day : day);
    }
    scope.grades = {
        availability: 0,
        dress: 0,
        commitment: 0,
        performance: 0,
        hospitality: 0,
        attendance: 0,
        apperance: 0,
        multi_task: 0
    }
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
    scope.grading_conferences = function(){
        if(scope.start instanceof Date && scope.end instanceof Date){
            if(scope.end < scope.start){
                scope.wrong_dates = true;
                return false;
            }else{
                scope.wrong_dates = false;
                scope.view_data.processing_request = true;
                var params = "from="+date_formater(scope.start)+"&to="+date_formater(scope.end);
                request.set("url" , "/conferences?"+params).set("verb" , "get").send().then(function(resp){
                    scope.confs = resp.data;
                    scope.view_data.processing_request = false;
                } , function(err){
                    alert("Something went wrong while retrieving conferences data, Please refresh and try again!");
                    scope.view_data.processing_request = false;
                });
            }
        }
    }
    
    request.set("url" , "/workingfields").set("verb" , "get").send().then(function(resp){
        scope.fields = resp.data;
    } , function(err){
        // MODAL
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
        for(var i = 0; i< f.length; i++){
            $("input[type='checkbox'][data-f='"+f[i]+"']").attr("checked" , true);
        }
        choosen_fields = f;
    }
    
    scope.appendField = function(event){
        var id = parseInt(event.target.getAttribute("data-f") , 10);
        if(choosen_fields.indexOf(id) !== -1){
            choosen_fields.splice(choosen_fields.indexOf(id) , 1);
        }else {
            choosen_fields.push(id);
        }
    }
    
    scope.showGradingSheet = function(event){
        var tar = event.target;
        var id = parseInt(tar.getAttribute("data-id") , 10);
        var name = tar.getAttribute("data-name");
        scope.grade_org_id = id;
        scope.grade_org_name = name;
        request.set("verb" , "GET").set("url" , "/check/grade/" + id + "/" + params.conf_id).send().then(function(resp){
            console.log(resp.data.length);
        } , function(){
            scope.view_data.success_msg = "Connection Error!, Couldn't check if the organizer has previous grades!";
            $("#notify").modal("show");
        });
        $(".cover").show();
    }
    
    scope.setGrades = function(){
        request.set("url" , "/organizer_grade").set("verb" , "POST").set("data" , {
            organizer_id: scope.grade_org_id,
            conference_id: params.conf_id,
            grades: scope.grades
        }).send().then(function(resp){
            scope.view_data.success_msg = "Grades has been saved!";
            $("#notify").modal("show");
        } , function(){
            scope.view_data.success_msg = "Error! , Couldn't save your grade selection.";
            $("#notify").modal("show");
        });
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
        } , function(err){
            // MODAL
        });
    }else if(rt.indexOf("grade") !== -1 || rt.indexOf("post_grading") !== -1){
        root.$emit("changeTitle" , "Grade Organizers");
        if(params.conf_id){
            request.set("url" , "/conferance/organizers/" + params.conf_id).set("verb" , "get").send().then(function(resp){
               scope.grade_organizers = resp.data;
            } , function(err){
                scope.view_data.success_msg = "Connection Error! , Couldn't retrive organizers data.";
                $("#notify").modal("show");
            });
        }

    }else if(rt === "/"){
        request.set("url" , "/auth/onlineUser").set("verb" , "get").send().then(function(resp){
            scope.role = resp.data.role;
        } , function(){
            // MODAL
        });
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
                scope.view_data.errors = response;
                scope.view_data.success_msg = "We encountered some errors, Please review the list of errors given below the form.";
            }else {
                scope.view_data.encoding_status = "It might take a few seconds to encode the agreement image after you choose it, Please wait for the encoding to finish before submitting!";
                scope.view_data.success_msg = "Created Successfully!";
            }
            $("#notify").modal("show");
        } , function(err){
            // MODAL
            scope.view_data.success_msg = "The connection didn't complete successfully, Please try again.";
            $("#notify").modal("show");
        });
    }
    
    scope.editUser = function(){
        request.set("url" , "/organizer/update/" + scope.newUser.id).set("verb" , "post").set("data" , scope.newUser).send().then(function(response){
            scope.view_data.processing_request = false;
            var response = response.data;
            if(response instanceof Object){
                scope.view_data.errors = response;
                scope.view_data.success_msg = "We encountered some errors, Please review the list of errors given below the form.";
                $("#notify").modal("show");
            }else {
                scope.view_data.success_msg = "Edited Successfully!";
                $("#notify").modal("show");
            }
        } , function(err){
            scope.view_data.success_msg = "The connection didn't complete successfully, Please try again.";
            $("#notify").modal("show");
        });
    }
    
    scope.delete = function(ev){
        var btn = ev.target;
        var email = btn.getAttribute("data-uni");
        request.set("url" , "/organizers/"+email).set("verb" , "delete").send().then(function(resp){
            // MODAL
            scope.view_data.success_msg = "The Account has been deleted!";
            $("#notify").modal("show");
            var acc = $(scope.view_data.orgs).filter(function(ind , el){
                return el.email == email;
            })[0];
            scope.which = "";
            scope.view_data.orgs.splice(scope.view_data.orgs.indexOf(acc) , 1);
        } , function(err){
            scope.view_data.success_msg = "Couldn't delete the account, Something has went wrong with the connection!";
            $("#notify").modal("show");
        });
    }
            
}]);