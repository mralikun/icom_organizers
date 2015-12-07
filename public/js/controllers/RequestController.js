app.controller("RequestController" , ["$scope" , "Patcher" , "$rootScope" , "$location" , "$routeParams" , function(scope , request , root , loc , params){
    
    function date_formater(d){
        var day = d.getDate();
        var month = d.getMonth();
        var year = d.getFullYear();
        return year + "-" + ((month < 10) ? "0"+month : month) + "-" + ((day < 10) ? "0"+day : day);
    }
    
    if(loc.path().indexOf("requests") !== -1){
        root.$emit("changeTitle" , "All Requests");
        request.set("url" , "/getRequests").set("verb" , "get").send().then(function(resp){
            var mod = $(resp.data).map(function(index , ele){
                return ele.substring(ele.indexOf("/") + 1 , ele.length).replace(".json" , "");
            });
            scope.all_requests = mod;
        } , function(err){
            alert("Something went wrong, Please refresh and try again!");
        });
    }else if(loc.path().indexOf("view_request") !== -1){
        request.set("url" , "/getOrganizerrequest/" + params.file + ".json").set("verb" , "get").send().then(function(resp){
            
            resp.data.from = new Date(resp.data.from);
            resp.data.gathering_time = new Date(resp.data.gathering_time);
            resp.data.meeting_date = new Date(resp.data.meeting_date);
            resp.data.meeting_ending_time = new Date(resp.data.meeting_ending_time);
            resp.data.meeting_starting_time = new Date(resp.data.meeting_starting_time);
            resp.data.start_time = new Date(resp.data.start_time);
            resp.data.to = new Date(resp.data.to);
            
            request.set("url" , "/conferences/" + resp.data.conference_id).set("verb" , "get").send().then(function(resp){
                scope.confs = resp.data;
            } , function(err){
                alert("Something went wrong. Please refresh the page and try again!");
            });
            
            
            scope.request = resp.data;
            console.log(resp.data);
        } , function(err){
            alert("Something went wrong, Please refresh the page and try again!");
        })
    }
    // change this broadcast to change the page title!
    root.$emit("changeTitle" , "Request Organizers");
    scope.setUp = function(){
        var choosen_conf = $(scope.confs).filter(function(ind , el){
            return el.id == scope.request.conference_id;
        })[0];
        scope.request.from = new Date(choosen_conf.from);
        scope.request.to = new Date(choosen_conf.to);
        scope.request.venue = choosen_conf.venue;
    }
    
    scope.getConferences = function(){
        if(scope.start instanceof Date && scope.end instanceof Date){
            if(scope.end < scope.start){
                scope.wrong_dates = true;
                return false;
            }else{
                scope.wrong_dates = false;
                var params = "from="+date_formater(scope.start)+"&to="+date_formater(scope.end);
                request.set("url" , "/conferences?"+params).set("verb" , "get").send().then(function(resp){
                    scope.confs = resp.data;
                } , function(err){
                    alert("Something went wrong while retrieving conferences data, Please refresh and try again!");
                });
            }
        }
    }
    
    scope.sendRequest = function(){
        var data = angular.copy(scope.request);
        if("from" in data){
            data.from = date_formater(data.from);
        }else if("to" in data){
            data.to = date_formater(data.to);
        }else if("meeting_date" in data){
            data.meeting_date = date_formater(data.meeting_date);
        }
        request.set("url" , "/organizerrequest").set("verb" , "post").set("data" , data).send().then(function(resp){
            console.log(resp.data);
        } , function(err){
            alert("Something went wrong, Please refresh the page and try again!");
        });
    }
    
    scope.requestChanged = function(){
        var t = scope.request.type;
        scope.request = {
            type: t
        };
    }
    
}]);