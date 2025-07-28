window.addEventListener("load", function() {
	// Menu lateral
	const url_License = window.location.search
	const link_cartas = document.getElementById('menu-posts-tarokkina_pro')

	if (url_License !== '?post_type=tarokkina_pro&page=tarokina_pro_license') {
		const menu_tarot_sub = document.querySelector('#menu-posts-tarokkina_pro .wp-submenu')
		if(menu_tarot_sub !== null){menu_tarot_sub.style.display='none';}
	}

	if(link_cartas !== null){link_cartas.classList.remove("wp-has-submenu");}



});
// window