var app = angular.module("organizers" , ["ngRoute"])
    .factory("UserToEdit" , function(){
        return {
            data: null,
            setData: function(obj){
                this.data = obj;
            },
            reset: function(){
                this.data = null;
            }
        };
    });

var icom = {
    fileBase: ""
};

function filter(arr , fun){
    var f = [];
    for(var i = 0 ; i < arr.length; i++){
        if(fun(arr[i])){
            f.push(arr[i]);
        }
    }
    
    return f;
}