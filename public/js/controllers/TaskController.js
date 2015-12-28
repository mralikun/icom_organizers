app.controller("TaskController" , ["$scope" , "$rootScope" , "Patcher" , function(scope , root , request){
    
    root.$emit("changeTitle" , "Assign Tasks");
    var ids = [];
    var types = ["conference" , "office hours" , "one time"];
    
    function date_formater(d){
        var day = d.getDate();
        var month = d.getMonth();
        var year = d.getFullYear();
        return year + "-" + ((month < 10) ? "0"+month : month) + "-" + ((day < 10) ? "0"+day : day);
    }
    scope.loading = false;
    scope.msg = undefined;
    
    request.set("url" , "/workingfields").set("verb" , "get").send().then(function(resp){
        scope.fs = resp.data;
    } , function(err){});
    
    scope.append = function(_ev){
        var tar = _ev.target;
        var id = parseInt(tar.getAttribute("data-id") , 10);
        if(ids.indexOf(id) === -1)
            ids.push(id);
        else
            ids.splice(ids.indexOf(id) , 1);
    }
    
    scope.load_organizer = function(event){
        scope.loaded_organizers = false;
        scope.no_organizers = false;
        request.set("url" , "/workingfields/organizers/"+scope.task.working_fields_id).set("verb" , "get").send().then(function(resp){
            scope.organizers = resp.data;
            scope.loaded_organizers = true;
            if(!resp.data.length){
                scope.no_organizers = true;
            }
        } , function(err){
            alert("Something wrong happend, Please refresh and try again!");
        });
    }
    
    scope.getConferences = function(){
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
    
    scope.setUp = function(){
        var choosen_conf = $(scope.confs).filter(function(ind , el){
            return el.id == scope.task.conference_id;
        })[0];
        scope.task.from = $.datepicker.formatDate("dd-mm-yy" , new Date(choosen_conf.from));
        scope.task.to = $.datepicker.formatDate("dd-mm-yy" , new Date(choosen_conf.to));
    }
    
    scope.reset = function(){
        if(scope.task.conference_id)
            delete scope.task.conference_id;
    }
    
    scope.create = function(){
        if(!("type" in scope.task)){
            alert("Please choose a task type!");
            return false;
        }
        scope.loading = true;
        var data = angular.copy(scope.task);
        data.type = types[data.type - 1];
        data.from = date_formater($.datepicker.parseDate("dd-mm-yy" , data.from));
        data.to = date_formater($.datepicker.parseDate("dd-mm-yy" , data.to));
        request.set("url" , "/task").set("verb" , "post").set("data" , data).send().then(function(resp){
            scope.loading = false;
            if(resp.data instanceof Object){
                scope.msg = "There're some errors, Please review the list below the form!";
                scope.errors = resp.data;
                $("#notify").modal("show");
            }else {
                scope.msg = "Task has been assigned successfully!";
                $("#notify").modal("show");
            }
        } , function(err){
            scope.msg = "Couldn't assign the task toorganizer due to connection error!";
            $("#notify").modal("show");
        });
    }
    
}]);