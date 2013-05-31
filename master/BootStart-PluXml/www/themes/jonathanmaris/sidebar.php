<?php if(!defined('PLX_ROOT')) exit; ?>

	<div role="toolbar" aria-label="contact-tool">
    
		<div role="menu" aria-labelledby="hire-me" id="contact-me" class="btn-group main pull-right"><span id="hire-me" style="display:none">Contacter akaJLM - Web développeur - Belgique</span>
            <a class="btn btn-mini" href="https://twitter.com/JonathanMaris1" target="_new" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="M' envoyer un Tweet privé"><i class="icon icon-twitter icon-grey icon-shadowed-white"></i></a>
            <a class="btn btn-mini" href="https://github.com/jonathanmaris" target="_new" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Me contacter sur Github"><i class="icon icon-github-alt icon-grey icon-shadowed-white"></i></a>
            <a class="btn btn-mini" href="https://plus.google.com/115751515758432574367/posts?hl=fr" target="_new" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Me contacter depuis mon profil Google Plus"><i class="icon icon-google-plus icon-grey icon-shadowed-white"></i></a>
            <a class="btn btn-mini" href="mailto:webmaster@jonathanmaris.net" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="M' envoyer un mail"><i class="icon icon-envelope icon-grey icon-shadowed-white"></i></a>
        </div>
        
    </div>

    <aside role="toolbar complementary" aria-label="sidebar-tool" class="span3 pull-right">
    
        <ul role="menubar" class="unstyled">
     
            <li id="categories" class="well well-small sidebar-nav" role="menuitem" aria-labelledby="asideTitleCat">
                <ul role="menu" class="nav nav-list">
                  <li id="asideTitleCat" class="nav-header"><?php $plxShow->lang('CATEGORIES'); ?></li>
                  <?php $plxShow->catList('','<li role="menuitem" id="#cat_id" class="#cat_status"><a role="link" class="#cat_status" href="#cat_url" title="#cat_name"><i class="icon-mini #icon_type #icon_color"></i> #cat_name <span class="label label-info pull-right">#art_nb</span></a></li><hr>', '', '', 'cog|fire|play|dashboard|screenshot|beaker|edit|globe|cogs|adjust|book'); ?>
                </ul>
            </li>
            
            <li id="favoriteCategories" role="menuitem" aria-labelledby="userFavoriteCats" class="well well-small sidebar-nav">
                <ul role="list" class="nav nav-list">
                    <li id="userFavoriteCats" class="nav-header" style="display:none">Personal <?php $plxShow->lang('CATEGORIES'); ?></li>
                    <li role="listitem"><em style="color:#999">&lsquo;&lsquo;Drag & drop your favorite categories.&rsquo;&rsquo;</em></li>
                </ul>
            </li>
            
            <li role="menuitem" class="well well-small sidebar-nav" aria-labelledby="asideTitleLastArticles">
                <ul role="list" class="nav nav-list">
                  <li id="asideTitleLastArticles" class="nav-header"><?php $plxShow->lang('LAST_ARTICLES') ?></li>
                  <?php $plxShow->lastArtList('<li role="listitem" class="#art_status"><a role="link" class="#art_status" href="#art_url" title="#art_title">#art_title</a></li><hr>'); ?>
                </ul>
            </li>
            
            <li id="favoriteTags" role="menuitem" aria-labelledby="userFavoriteTags" class="well well-small sidebar-nav">
                <ul role="list" class="nav nav-list">
                    <li id="userFavoriteTags" class="nav-header" style="display:none">Personal <?php $plxShow->lang('TAGS'); ?></li>
                    <li role="listitem"><em style="color:#999">&lsquo;&lsquo;Drag & drop your favorite keywords.&rsquo;&rsquo;</em></li>
                </ul>
            </li>
            
            <li role="menuitem" aria-labelledby="asideTitleTags" class="well well-small sidebar-nav">
                <ul role="menu" class="nav nav-list">
                	<li id="asideTitleTags" class="nav-header"><?php $plxShow->lang('TAGS') ?></li>
                 	<?php $plxShow->tagList('<li role="menuitem" class="tag #tag_size #tag_status"><a role="link" class="#tag_status" href="#tag_url" title="#tag_name">#tag_name</a></li><hr>', 20); ?>
                </ul>
            </li>
            
            <li class="well well-small">
                <em style="color:#999">&lsquo;&lsquo;This is my tool to share, nothing more&rsquo;&rsquo;</em> <a class="btn btn-mini pull-right" href="http://stackoverflow.com/" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Center d'aide entre développeurs (en anglais uniquement)" target="_new"><i class="icon icon-bullhorn icon-grey icon-shadowed-white"></i></a>
            </li>
            
            <li role="menuitem" aria-labelledby="asideTitleLastComments" class="well well-small sidebar-nav">
                <ul role="menu" class="nav nav-list">
                  <li id="asideTitleLastComments" class="nav-header"><?php $plxShow->lang('LAST_COMMENTS') ?></li>
                 <?php $plxShow->lastComList('<li role="listitem"><a href="#com_url">#com_author '.$plxShow->getLang('SAID').' : #com_content(50)</a></li><hr>'); ?>
                </ul>
            </li>
            
            <li id="relax" class="well well-small">
                <em style="color:#999">&lsquo;&lsquo;Comment is not a stress relief. Relax&rsquo;&rsquo;</em> <a class="btn btn-mini pull-right" href="http://procatinator.com/?cat=<?php $input = array("87", "52", "54"); shuffle($input); echo $input[0] . "\n"; ?>" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Center de relaxation d' urgence &nbsp;(en vision de chat uniquement)" target="_new"><i class="icon icon-bullhorn icon-grey icon-shadowed-white"></i></a>
            </li>
            
            <li role="menuitem" aria-labelledby="asideTitleArchives" class="well well-small sidebar-nav">
                <ul role="menu" class="nav nav-list">
                 <li id="asideTitleArchives" class="nav-header"><?php $plxShow->lang('ARCHIVES') ?></li>
                 <?php $plxShow->archList('<li role="menuitem" id="#archives_id" class="#archives_status"><a role="link" class="#archives_status" href="#archives_url" title="#archives_name">#archives_name <span class="label label-info pull-right">#archives_nbart</span></a></li><hr>'); ?>
                </ul>
            </li>
            
            <li role="listitem" aria-labelledby="asideTitleExtraLinks" class="well well-small sidebar-nav">
                <ul role="menu" class="nav nav-list">
                    <li id="asideTitleExtraLinks" class="nav-header">RSS XML</li>
                    <li role="menuitem"><a href="<?php $plxShow->urlRewrite('feed.php?rss') ?>" title="<?php $plxShow->lang('ARTICLES_RSS_FEEDS') ?>">
                        <?php $plxShow->lang('ARTICLES') ?></a>
                    </li>
                    <hr>
                    <li role="menuitem"><a href="<?php $plxShow->urlRewrite('feed.php?rss/commentaires') ?>" title="<?php $plxShow->lang('COMMENTS_RSS_FEEDS') ?>">
                        <?php $plxShow->lang('COMMENTS') ?></a>
                    </li>
                    <hr>
                </ul>
            </li>
        </ul>
    </aside>
