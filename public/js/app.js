var app = angular.module("organizers" , ["ngRoute"]);

app.factory("Patcher" , ["$http" , function(request){
    return {
        url: "",
        verb: "",
        data: undefined,
        get: function(key){ return this[key] },
        set: function(key , value){ this[key] = value; return this; },
        send: function(){
            var ready = this.url && this.verb;
            if(!ready){
                console.error("Unable to send request, missing arguments!");
            }
            var p_v = this.verb.toLowerCase();
            return request[p_v](this.url , this.data);
        },
        reset: function(){
            this.url = this.verb = "";
            this.data = undefined;
        }
    }
}])

.factory("User" , function(){
    return {
        name: "",
        email: "",
    }
})

.factory("Organizer" , ["User" , "Patcher" , function(user , request){
    user.dob = null;
    user.cell_phone = user.gender = user.id_number = user.activity = 0;
    user.address = user.working_fields = user.college = user.language = user.agreement = "";
    user.import = function(mail){
        request.set("url" , "/organizers/"+mail+"/edit").set("verb" , "get").send().then(function(response){
            console.log(response.data);
            for(var key in response.data){
                user[key] = response.data[key];
                if(key == "dob"){
                    user[key] = new Date(response.data[key]);
                }
            }
        } , function(error){
            alert("Couldn't retrieve the user's data, Please refresh and try again!");
        });
    }
    user.reset = function(){
        user.dob = null;
        user.cell_phone = user.gender = user.id_number = user.activity = 0;
        user.address = user.working_fields = user.college = user.language = user.agreement = "";
    }
    return user;
}]);

function filter(arr , fun){
    var f = [];
    for(var i = 0 ; i < arr.length; i++){
        if(fun(arr[i])){
            f.push(arr[i]);
        }
    }
    
    return f;
}