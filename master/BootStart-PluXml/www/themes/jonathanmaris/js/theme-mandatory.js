jQuery(document).ready(function ($) {


/* 
	@tooltip
*/
    $("[rel='tooltip']").tooltip({
        delay: {
            show: 100,
            hide: 100
        },
		html: true
    });


/* 
	@Any files - Theme responsive
*/
	$(window).on('load resize', function () {
		themeResponsive();
    });
	
	function themeResponsive() {
		
		var width = $(window).width();
		
		$("#issue").removeAttr('style');
		
		if (width < 980)
		{
			/* Buttons responsive */
			$(".btn-responsive").each(function ()
			{
				if ($(this).hasClass('btn-large'))
				{
					$(this).removeClass('btn-large').addClass('id-btn-large');
				} 
				else
				{
					if (!$(this).hasClass('id-btn-large'))
					{
						$(this).addClass('btn-mini');
					}
				}
			});
			
			if (width < 768)
			{
				/* Sidebar */
				$(".sidebar-mini > .nav-tabs.nav-stacked > li > a").each(function () {
					
					$(this).removeAttr('data-placement data-toggle rel data-original-title');
					
				});
				$("#issue").css('padding', '0 20px 0 20px');
			}
		} 
		else
		{
			/* Buttons responsive */
			$(".btn-responsive").each(function () {
				
				$(this).removeClass('btn-mini');
	
				if ($(this).hasClass('id-btn-large'))
				{
					$(this).addClass('btn-large').removeClass('id-btn-large');
				}
			});
		}
	}	


/*
	Comments dock
*/
	
	if($('#comments-box'))
	{
		if($(location).attr('href').match(/(#c[0-9]{1,10}\-1)$/))
		{
			ref = $(location).attr('href').match(/(c[0-9]{1,10}\-1)$/);
			
			$('#quote-'+ref+' > a').css('color', '#08c');
			
			$('#comments-box').animate({"height": "97.5%", "width": "97.5%"},"fast");
			
			$('#comments-box').bind('mouseleave', function() {
				$('#comments-box').animate({"height": "37px", "width": "43px"},"fast");
			});
			
			$('#comments-box').bind('mouseenter', function() {
				$('#comments-box').animate({"height": "97.5%", "width": "97.5%"},"fast");
			});
		}
		else
		{
			$('#comments-box').hide().css('visibility', 'visible').fadeIn("fast");
		 
			$('#comments-box').bind('mouseenter', function() {
				$('#comments-box').animate({"height": "97.5%", "width": "97.5%"},"fast");
			});
			 
			$('#comments-box').bind('mouseleave', function() {
				$('#comments-box').animate({"height": "37px", "width": "43px"},"fast");
			});
		}
	}
});


/* 
	msgs alert/info
*/
	function setOpacity(obj, opacity) {
		obj.style.minHeight = obj.style.minHeight; 
		// hack IE
		opacity = (opacity == 100)?99.999:opacity;
		obj.style.filter = "alpha(opacity="+opacity+")"; 
		// IE/Win
		obj.style.KHTMLOpacity = opacity/100; 
		//Safari<1.2, Konqueror
		obj.style.MozOpacity = opacity/100; 
		//Older Mozilla and Firefox
		obj.style.opacity = opacity/100; 
		//Safari 1.2, newer Firefox and Mozilla, CSS3
	}
	function fadeOut(objId,opacity) {
		var obj = document.getElementById(objId);
		if(obj) {
			if(opacity==undefined) {
				window.setTimeout("fadeOut('"+objId+"',"+100+")", 3000);
			} else {
				if (opacity >=0) {
					setOpacity(obj, opacity);
					opacity -= 10;
					window.setTimeout("fadeOut('"+objId+"',"+opacity+")", 100);
				} else {
					obj.style.display = 'none';
				}
			}
		}
	}
	function setMsg() {
		if(document.getElementById('msg')) {
	
			objDiv = document.getElementById('msg');
			objSidebar = document.getElementById('sidebar')
			if (typeof window.innerWidth != 'undefined') {
				wndWidth = window.innerWidth;
			}
			else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth !='undefined' && document.documentElement.clientWidth != 0) {
				wndWidth = document.documentElement.clientWidth;
			}
			else {
				wndWidth = document.getElementsByTagName('body')[0].clientWidth;
			}
			xpos = Math.round((wndWidth-objDiv.offsetWidth)/2);
			objDiv.style.left=xpos+'px';
			fadeOut('msg');
		}
	}


/*
	Respond comments
*/
	function answerCom(where,id,author) {
		var element = document.getElementById("id_" + where);
		addText(where, '@'+author+' :\n');
		window.location.href = '#discuss';
	}
			
	function addText(where, open, close) {
		close = close==undefined ? '' : close;
		var formfield = document.getElementsByName(where)['0'];
		// IE support
		if (document.selection && document.selection.createRange) {
			formfield.focus();
			sel = document.selection.createRange();
			sel.text = open + sel.text + close;
			formfield.focus();
		}
		// Moz support
		else if (formfield.selectionStart || formfield.selectionStart == '0') {
			var startPos = formfield.selectionStart;
			var endPos = formfield.selectionEnd;
			var restoreTop = formfield.scrollTop;
			formfield.value = formfield.value.substring(0, startPos) + open + formfield.value.substring(startPos, endPos) + close + formfield.value.substring(endPos, formfield.value.length);
			formfield.selectionStart = formfield.selectionEnd = endPos + open.length + close.length;
			if (restoreTop > 0) formfield.scrollTop = restoreTop;
			formfield.focus();
		}
		// Fallback support for other browsers
		else {
			formfield.value += open + close;
			formfield.focus();
		}
		return;
	}