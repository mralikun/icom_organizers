app.controller("RequestController" , ["$scope" , "Patcher" , "$rootScope" , function(scope , request , root){
    
    function date_formater(d){
        var day = d.getDate();
        var month = d.getMonth();
        var year = d.getFullYear();
        return year + "-" + ((month < 10) ? "0"+month : month) + "-" + ((day < 10) ? "0"+day : day);
    }
    
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
        data.from = date_formater(data.from);
        data.to = date_formater(data.to);
        data.meeting_date = date_formater(data.meeting_date);
        console.log(scope.request);
    }
    
    scope.requestChanged = function(){
        var t = scope.request.type;
        scope.request = {
            type: t
        };
    }
    
}]);