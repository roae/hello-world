$(document).ready(function() {
	$.ajaxSetup({ cache: true });
	$.getScript('//connect.facebook.net/en_US/sdk.js', function(){
		FB.init({
			appId: '966533043359649',
			version: 'v2.3' // or v2.0, v2.1, v2.0
		});
		$('#loginbutton,#feedbutton').removeAttr('disabled');
		FB.getLoginStatus(function(){
			console.dir(arguments);

		});
	});

});

function fbConnect(){
	FB.login(function(response) {
		if (response.authResponse) {
			console.log('Welcome!  Fetching your information.... ');
			FB.api('/me', function(response) {
				window.location = window.location.pathname+"facebook";
				console.dir(response);
				console.log('Good to see you, ' + response.name + '.');
			});
		} else {
			console.log('User cancelled login or did not fully authorize.');
		}
	},{
		//scope: "public_profile,email",
		return_scopes: true
	});
	return false;
}