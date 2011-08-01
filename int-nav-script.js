function highlightLink(curlink, curpost) {
	if (Get_Cookie( 'int_nav_ids' ) != null) {
		var ids = Get_Cookie( 'int_nav_ids' );
		var ids_split = ids.split(',');
		var f = null;
		for (counter=0; counter < ids_split.length; counter++) {
			if (curpost == ids_split[counter]) {
				var aElems = document.getElementsByTagName("a");
				for (var i=0, n=aElems.length; i<n; ++i) {
					var elem = aElems[i];
					var text = elem.innerHTML;
					if ( (elem.href.indexOf(curlink) != -1) && (curlink != '') ) {
						elem.innerHTML = '<strong class="int_nav_strong">' + text + '</strong>';
					}
				}
				f = 1;
				
			}
		}	
		if ( f != 1 ) {
			Delete_Cookie( 'int_nav_term_id', '/', '' );
			Delete_Cookie( 'int_nav_term_taxonomy', '/', '' );
			Delete_Cookie( 'int_nav_term_url', '/', '' );
			Delete_Cookie( 'int_nav_ids', '/', '' );
		}
	}
}

function resizeFrame(f) {
	var my_frame = document.getElementById(f);
	if (my_frame != null) {
		my_frame.style.height = my_frame.contentWindow.document.body.scrollHeight + "px";
	}
}
	
function Set_Cookie( name, value, expires, path, domain, secure ) {	
	var today = new Date();
	today.setTime( today.getTime() );
	if ( expires ) {
		expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name + "=" +escape( value ) +
	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
	( ( path ) ? ";path=" + path : "" ) +
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );
}

function Get_Cookie( check_name ) {	
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';	
	var cookie_name = '';	
	var cookie_value = '';	
	var b_cookie_found = false; 	
	for ( i = 0; i < a_all_cookies.length; i++ ) {
		a_temp_cookie = a_all_cookies[i].split( '=' );
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');
		if ( cookie_name == check_name ) {
			b_cookie_found = true;
			if ( a_temp_cookie.length > 1 ) {
				cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
			}			
			return cookie_value;
			break;
		}		
		a_temp_cookie = null;
		cookie_name = '';	
	}	
	if ( !b_cookie_found ) {
		return null;	
	}
}
				
function Delete_Cookie( name, path, domain ) {
	if ( Get_Cookie( name ) ) document.cookie = name + "=" +
	( ( path ) ? ";path=" + path : "") +
	( ( domain ) ? ";domain=" + domain : "" ) +
	";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}