/* XMLHTTP REQUEST (AJAX CONNECTION) :: CROSS BROWSER */
var xmlHttp = null;
try {
	// Mozilla, Opera, Safari sowie Internet Explorer (ab v7)
	xmlHttp = new XMLHttpRequest();
} catch(e) {
	try {
		// MS Internet Explorer (ab v6)
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch(e) {
		try {
			// MS Internet Explorer (ab v5)
			xmlHttp = new ActiveXObject("Msxml12.XMLHTTP");
		} catch(e) {
			xmlHttp = null;
		}
	}
}