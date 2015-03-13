/**
 * Created by IErshov on 05.11.2014.
 */
(function(){
var app = angular.module('app', []);

app.controller('GemController', ['$http', function($http){
    //this.rows = gems;
    var ptr = this;

    var connector='/get-events/connector.php';
    var key='';
    if(typeof(docState.campaign) != undefined) {key='/?key='+docState.campaign;}
    var url = connector+key;
    console.log('url: '+url);


    $http.get(url).success(function(data){
        ptr.rows=data.data;
        //console.log(gems);
    });
}]);


//var gems=[{id:'1', name:'test'},{id:'2', name:'test2'}];
})();