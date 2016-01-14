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
        var start = (scope.start) ? $.datepicker.parseDate("dd-mm-yy" , scope.start) : "";
        var end = (scope.end) ? $.datepicker.parseDate("dd-mm-yy" , scope.end) : "";
        if(start instanceof Date && end instanceof Date){
            if(end < start){
                scope.wrong_dates = true;
                return false;
            }else{
                scope.wrong_dates = false;
                var params = "from="+date_formater(start)+"&to="+date_formater(end);
                request.set("url" , "/conferences?"+params).set("verb" , "get").send().then(function(resp){
                    
                    scope.confs = resp.data;
                } , function(err){
                });
            }
        }else {
            console.log("NO");
        }
    }
    
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
    
    scope.check_in = function(ev){
        if(!scope.personToCheck){
            scope.view_data.success_msg = "Please choose an organizer first";
            $("#notify").modal("show");
            return false;
        }
        request.set("url" , "/checkin?organizer_id="+scope.personToCheck).set("verb" , "get").send().then(function(resp){
            scope.att_org_status.checked_in = "true";
            if(resp.data == "error"){
                scope.view_data.success_msg = "Check in failed! , No tasks for this organizer at the moment.";
            }else {
                scope.view_data.success_msg = "Checked in successfully!";
            }
            $("#notify").modal("show");
        } , function(){
            scope.view_data.success_msg = "There was an error checking this organizer in, Please make sure that this organizers is assigned and accepted a task in this conference and that task is due time is now.";
            $("#notify").modal("show");
        });
    }
    
    scope.check_out = function(){
        request.set("url" , "/checkout?organizer_id="+scope.personToCheck).set("verb" , "get").send().then(function(resp){
            scope.att_org_status.checked_out = "true";
            scope.view_data.success_msg = "Checked out successfully!";
            $("#notify").modal("show");
        } , function(){
            scope.view_data.success_msg = "Connection Error! , Something went wrong trying to check this organizer out.";
            $("#notify").modal("show");
        });
    }
    
    scope.showGradingSheet = function(event){
        var tar = event.target;
        var id = parseInt(tar.getAttribute("data-id") , 10);
        var name = tar.getAttribute("data-name");
        scope.grade_org_id = id;
        scope.grade_org_name = name;
        request.set("verb" , "get").set("url" , "/tasks/"+scope.grade_org_id+"/"+params.conf_id).send().then(function(resp){
            scope.tasks = resp.data;
        
        } , function(){});
        $(".cover").show();
    }
    
    scope.checkPreviousGrading = function(){
        request.set("verb" , "GET").set("url" , "/check/grade/" + scope.grade_org_id + "/" + scope.task_to_grade).send().then(function(resp){
            //checking the grades of the organizer .... 
            if(resp.data.length){
                scope.has_prev_grades = true;
                for(var i = 0; i < resp.data.length; i++){
                    scope.grades[resp.data[i].criteria] = resp.data[i].grade;
                }
            }else {
                scope.has_prev_grades = false;
                for(var k in scope.grades){
                    scope.grades[k] = 0;
                }
            }

        
        } , function(){
            scope.view_data.success_msg = "Connection Error!, Couldn't check if the organizer has previous grades!";
            $("#notify").modal("show");
        });
    }
    
    scope.setGrades = function(){
        
        var url = (scope.has_prev_grades) ? "/update_grade" : "/organizer_grade";
        var the_grades = [];
        if(!scope.task_to_grade){
            scope.view_data.success_msg = "Please choose a task to grade!";
            $("#notify").modal("show");
            return false;
        }
        
        
        for(var k in scope.grades){
            the_grades.push({
                criteria: k,
                grade: scope.grades[k]
            });
        }
        
        scope.view_data.processing_request = true;
        request.set("url" , url).set("verb" , "POST").set("data" , {
            organizer_id: scope.grade_org_id,
            task_id: parseInt(scope.task_to_grade , 10),
            grades: the_grades
        }).send().then(function(resp){
            scope.view_data.processing_request = false;
            scope.has_prev_grades = true;
            scope.view_data.success_msg = "Grades has been saved!";
            $("#notify").modal("show");
        } , function(){
            scope.view_data.processing_request = false;
            scope.view_data.success_msg = "Error! , Couldn't save your grade selection.";
            $("#notify").modal("show");
        });
    }
    
    scope.hideGradingSheet = function(){
        for(var k in scope.grades){
            scope.grades[k] = 0;
        }
        delete scope.task_to_grade;
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
        scope.newUser = {
            gender: 0
        }
        organizer.reset();
        request.reset();
        $("input[type='checkbox']").attr("checked" , false);
        choosen_fields = [];
    }
    
    scope.create_new_user = function(){
        scope.view_data.processing_request = true;
        request.set("verb" , "POST").set("url" , "/users").set("data" , scope.the_user).send().then(function(){
            scope.view_data.processing_request = false;
            scope.view_data.success_msg = "User created successfully!";
            $("#notify").modal("show");
            delete scope.the_user;
        } , function(){
            scope.view_data.processing_request = false;
            scope.view_data.success_msg = "Connection Error! , Couldn't create the new user!";
            $("#notify").modal("show");
        });
    }
    
    scope.delete_user = function(ev){
        var id = parseInt(ev.target.getAttribute("data-id") ,10);
        request.set("verb" , "delete").set("url" , "/users/"+id).send().then(function(resp){
            scope.view_data.success_msg = "User deleted successfully!";
            $("#notify").modal("show");
            $(ev.target).parents("tr").remove();
        } , function(){
            scope.view_data.success_msg = "Connection Error! ,User couldn't be deleted!";
            $("#notify").modal("show");
        });
    }
    
    scope.checkStatus = function(){
        request.set("verb" , "get").set("url" , "/status?organizer_id="+scope.personToCheck).send().then(function(resp){
            scope.att_org_status.checked_in = resp.data.checkin;
            scope.att_org_status.checked_out = resp.data.checkout;
        } , function(){
            scope.view_data.success_msg = "Connection Error! , Couldn't check the organizer's status";
            $("#notify").modal("show");
        });
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
            request.set("url" , "/conferences/"+params.conf_id).set("verb" , "GET").send().then(function(resp){
                scope.grading_conf = resp.data[0];
                scope.grading_conf.from = scope.grading_conf.from.split("-").reverse().join("-");
                scope.grading_conf.to = scope.grading_conf.to.split("-").reverse().join("-");
            } , function(){
                scope.view_data.success_msg = "Connection Error, Couldn't retrive conference data.";
                $("#notify").modal("show");
            });
            request.set("url" , "/conferance/organizers/" + params.conf_id).set("verb" , "get").send().then(function(resp){
               scope.grade_organizers = resp.data;
            } , function(err){
                scope.view_data.success_msg = "Connection Error! , Couldn't retrive organizers data.";
                $("#notify").modal("show");
            });
        }

    }else if(rt.indexOf("attendance") !== -1){
        root.$emit("changeTitle" , "Organizers Attendance.");
        scope.att_org_status = {
            checked_in: "true",
            checked_out: "true"
        };
        if(params.att_conf_id){
            request.set("url" , "/conferance/organizers/" + params.att_conf_id).set("verb" , "get").send().then(function(resp){
                scope.att_organizers = resp.data;
            } , function(){
                
            });
        }
        
    }else if(rt.indexOf("create_organizer") !== -1){
        request.set("url" , "/workingfields").set("verb" , "get").send().then(function(resp){
            scope.fields = resp.data;
        } , function(err){
            scope.view_data.success_msg = "Error! , Something went wrong trying to retrieve working fields data.";
            $("#notify").modal("show");
        });
    }else if(rt.indexOf("add_user") !== -1) {
        root.$emit("changeTitle" , "Create New Users");
    }else if(rt.indexOf("manage_users") !== -1){
        root.$emit("changeTitle" , "Mange Users");
        request.set("url" , "/users").set("verb" , "get").send().then(function(resp){
            scope.all_users = $(resp.data).filter(function(ind , e){
                return e.role != "admin";
            });
        } , function(){
            scope.view_data.success_msg = "Connection Error! , Couldn't retrieve users data.";
            $("#notify").modal("show");
        });
    }else if(rt.indexOf("edit_user") !== -1){
        root.$emit("changeTitle" , "Edit User");
        request.set("verb" , "get").set("url" , "/users/"+params.user_ID).send().then(function(resp){
            scope.the_user = resp.data;
            delete scope.the_user.password;
        } , function(){
            scope.view_data.success_msg = "Connection Error , Couldn't retrieve user data";
            $("#notify").modal("show");
        });
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
    
    scope.update_user = function(){
        scope.view_data.processing_request = true;
        request.set("verb" , "POST").set("url" , "/update_user/"+scope.the_user.id).set("data" , scope.the_user).send().then(function(resp){
            scope.view_data.success_msg = "User has been updated successfully!";
            $("#notify").modal("show");
            scope.view_data.processing_request = false;
        } , function(){
            scope.view_data.success_msg = "Connection Error! , Couldn't update the user information.";
            $("#notify").modal("show");
            scope.view_data.processing_request = false;
        });
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
        if("dob" in scope.newUser){
            scope.newUser.dob = date_formater($.datepicker.parseDate("dd-mm-yy" , scope.newUser.dob));
        }
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
    
    scope.initPicker = function(ev){
        $(ev.target).datepicker({
            dateFormat: "dd-mm-yy",
            showOn: "focus"
        });
    }
            
}]);