jQuery(document).ready(function ($) {

/* 
	@tooltip
*/
    $("[rel='tooltip']").tooltip({
        delay: {
            show: 100,
            hide: 100
        }
    });


/*
	@top.php - Catch the menu title in its language from sidebar left and display it on the top-left-breadcrumb
*/
	var breadcrumbContent = $('#sidebar > ul > li.active > a span.visible-desktop').html();
	
	if(typeof breadcrumbContent == 'undefined')
	{
		breadcrumbContent = "Edit";	
	}
	$("#adminBreadcrumb > li.active").html(breadcrumbContent);

	
/*
	@any wysiwyg editor - wysiwyg editor is active ? maximize width for the textarea
*/
	if(typeof CKEDITOR != 'undefined' && $("#id_content").length > 0)
	{
		$("div#form-article").addClass("form-vertical").removeClass('form-horizontal');
	}


/*
	@any files = main menu sidebar left - change icon color for active link
*/	
	$('#sidebar > ul > li.menu.active > a > i').removeClass('icon-blue').addClass('icon-grey');


/*
	@article.php - sidebar right - tags system
*/

	/* init toggle position - input vs tags system */
	if($('#toggle_tag_chose > a').length == 0)
	{
		$('#toggle_tag_chose').css('display', 'none');
		$('#toggler_tag_chose').html('+');
		$('#toggle_tag_add').css('display', 'block');
		$('#toggler_tag_add').html('-');
	}
	
	// func - insert tag
	function insertTag(where, tag) {
		var formfield = document.getElementsByName(where)['0'];
		if(formfield.value=='')
			formfield.value=tag;
		else
			formfield.value = formfield.value+', '+tag;
	}
	
	// func - remove tag
	function removeTag(where, tag) {
		var formfield = document.getElementsByName(where)['0'];
		if(formfield.value=='')
			formfield.value=formfield.value;
		else
			var regex = new RegExp(", ?"+tag+"|^"+tag+", |^"+tag+"$");
			formfield.value = formfield.value.replace(regex, "");
	}
		
	/* if exist - disabled statement for each tag on page load */
	if($('#id_tags').length > 0)
	{
		var tags = $('#id_tags').val();
		var tagsArray = tags.split(',');
		
		// foreach tag button
		$('#toggle_tag_chose > a').each(function()
		{
			// Catch tag
			var tag = $(this).text().slice(0, -2);
			
			// remove original pluXML function
			$(this).removeAttr('onclick');
			
			// init tags buttons statements
			for(i in tagsArray)
			{
				var str = tagsArray[i];
				var test = str.indexOf(tag);
				
				if(test != -1)
				{
					$(this).addClass('disabled');
				}
			}
			
			/* event: each tag button - disabled-enabled statement */
			/* event: input#id_tags - remove or insert tag in the str-tags-input#id_tags */
			$(this).click(function(e)
			{
				e.preventDefault();
				
				if($(this).hasClass('disabled'))
				{
					$(this).removeClass('disabled');
					removeTag('tags', tag);
				}
				else{
					$(this).addClass('disabled');
					insertTag('tags', tag);
				}
			});
		});
	}

	/* event: open/close - toogler button for str-new-cat-input#id_new_catname - autofocus & highlight this input  */
	$('#toggler_cat_add').click(function(e)
	{
		e.preventDefault();
		
		if($(this).html() == '-')
		{
			$('#id_new_catname').focus();
		}
	});
	
	/* event: open/close - toogler button for str-tags-input#id_tags - autofocus & highlight this input */
	$('#toggler_tag_add').click(function(e)
	{
		e.preventDefault();
		
		if($(this).html() == '-')
		{
			$('#id_tags').focus();
		}
	});
	
	/* event: open/close - toogler button for chapô textarea#id_chapo - autofocus & highlight this textarea */
	$('#toggler_chapo').click(function(e)
	{
		e.preventDefault();
		
		if($(this).html() == '-')
		{
			$('#id_chapo').focus();
		}
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
});