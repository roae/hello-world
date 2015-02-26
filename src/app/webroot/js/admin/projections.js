/**
 * Controller angular de peliculas
 * @param $scope
 * @constructor
 */

Citicinemas.controller("ProjectionsCtrl", function($scope){
	$scope.Projections = Projections;
	$scope.ValidationErrors = ValidationErrors;
	$scope.deletes = {
		'Projection': []
	};
	//console.dir($scope.ValidationErrors)

	$scope.formats = ["2D","3D","4D","48FMS","35MM"];

	$scope.langs = ["ESP","DOB","SUB"];

	 $scope.add = function(){
		$scope.Projections.push({
			'id':null,
			'vista_code':"",
			'lang':null,
			'format':null
		});
	 }

	 $scope.del = function(index){
		 if($scope.Projections[index].id){
			 $scope.deletes.Projection.push($scope.Projections[index].id);
		 }
		 /* Se elimina el elemento del arreglo de Projections */
		 $scope.Projections.splice(index,1);
	 }
});