/**
 * Controller que maneja la sincronizacion de carteleras
 * @param $scope
 * @constructor
 */

Citicinemas.controller("SyncCtrl", function($scope,$timeout){
	$scope.SyncStatus = SyncStatus;
	$scope.Locations = Locations;
	$scope.refreshing = false;
	$scope.message = "test";

	$scope.sync = function(location_id ){
		//console.log("rochin");
		$("#Loading" ).show("fade");
		//$scope.SyncStatus.running =  true;
		$.ajax({
			url: '/admin/shows/sync/'+location_id,
			method:'GET',
			type:'json',
			success: function(data){
				$scope.SyncStatus =  data;
			}
		});
		//$scope.refresh();
	};

	$scope.refresh = function(){
		$timeout(function(){
			if(!$scope.refreshing){
				$scope.refreshing = true;
				$.ajax({
					url:"/admin/shows/syncstatus/",
					method:'GET',
					type:'json',
					success:function(data){
						//console.log(data);
						$scope.refreshing = false;
						$scope.SyncStatus =  data;
						$("#Loading" ).hide("fade");
					}
				});
			}
			$scope.refresh();
		},500);
	}



	$timeout($scope.refresh,500);

});