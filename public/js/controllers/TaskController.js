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
        request.set("url" , "/workingfields/organizers"+scope.task.working_field).set("verb" , "get").send().then(function(resp){
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
        if(scope.start instanceof Date){
            if(scope.end < scope.start){
                scope.wrong_dates = true;
                return false;
            }else{
                scope.wrong_dates = false;
                var d = {
                    from: date_formater(scope.start),
                    to: date_formater(scope.end)
                }
                console.log(d);
                request.set("url" , "/conferences").set("verb" , "get").set("data" , d).send().then(function(resp){
                    scope.confs = resp.data;
                } , function(err){
                    alert("Something went wrong while retrieving conferences data, Please refresh and try again!");
                });
            }
        }
    }
    
    scope.create = function(){
        if(!("type" in scope.task)){
            alert("Please choose a task type!");
            return false;
        }
        var data = angular.copy(scope.task);
        data.type = types[data.type - 1];
        console.log(data);
//            request.set("url" , "/task").set("verb" , "post").set("data" , {}).send().then(function(resp){} , function(err){});
    }
    
}]);