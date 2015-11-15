var app = angular.module("organizers" , ["ngRoute"]);

var ICOM = {
    processable: function(_var) {
        return !!_var;
    }
};