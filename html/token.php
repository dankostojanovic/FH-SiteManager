<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
	if (!window.jQuery) {
		document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');
	}
</script>
<script src="js/component_js/global.js"></script>
<script>
	$(document).ready(function() {
		let url = new URL(window.location.href);
		if(/error=access_denied/.test(url)) {
			// Logout and clear all parts of oauth.
			window.sessionStorage.removeItem("accessToken");
	        window.sessionStorage.removeItem("tokenType");
	        window.sessionStorage.removeItem("expiresIn");
	        window.sessionStorage.removeItem("expiresDate");
	        window.sessionStorage.removeItem("state");
	        window.sessionStorage.removeItem("IDToken");

	        var isInIframe = (window.location != window.parent.location) ? true : false;

			if(isInIframe) {
				parent.closeIFrame(true);
			} else {
				window.location.href = "http://localhost:8080/logout.php?access_denied=1";
			}
		} else {
			var hash = url.hash.substr(1);

			var urlVariables = hash.split('&');
			urlVariables.forEach(function(variable) {
				variable = variable.split('=');
				if(variable[0] == "access_token") {
					window.sessionStorage.accessToken = variable[1];
				} else if(variable[0] == "token_type") {
					window.sessionStorage.tokenType = variable[1];
				} else if(variable[0] == "expires_in") {
					window.sessionStorage.expiresIn = variable[1];
					window.sessionStorage.expiresDate = Number(variable[1]) + Math.floor(new Date().getTime() / 1000);
				} else if(variable[0] == "state") {
					window.sessionStorage.state = variable[1];
				} else if(variable[0] == "id_token"){
					window.sessionStorage.IDToken = variable[1];
				}
			});

			var decodeTokenIDToken = decodeToken(window.sessionStorage.IDToken);
			var decodeTokenAccess = decodeToken(window.sessionStorage.accessToken);
			window.sessionStorage.username = decodeTokenIDToken['username'];

			createSiteManagerConfigCookie(decodeTokenAccess);
			// Check if window is an iframe.
			var isInIframe = (window.location != window.parent.location) ? true : false;

			if(isInIframe) {
				parent.closeIFrame();
			} else {
				window.location.href = "http://localhost:8080/#app/communities.php";
			}
		}
	});

	function createSiteManagerConfigCookie(token) {
		// If cookie exists check if scopes are the same.
		if(document.cookie.indexOf('permissions=') !== -1) {
			var cookiePerms = getCookie('permissions');

			if(!isEqual(cookiePerms, token['scopes'])){
				setScopesCookie(token['scopes']);
			}
		} else {
			// Create the cookie.
			setScopesCookie(token['scopes']);
		}
	}
</script>