<aside id="sidebar" role="toolbar complementary" aria-label="sidebar-tool" class="span1 well well-small sidebar-mini">
		
    <a class="hidden-phone" style="display:block;margin-top:15px" href="<?php echo PLX_CORE ?>admin/profil.php" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="<?php
        echo ' ' . plxUtils::strCheck($plxAdmin->aUsers[$_SESSION['user']]['name']) . ': ';
            if($_SESSION['profil']==PROFIL_ADMIN) echo L_PROFIL_ADMIN;
        elseif($_SESSION['profil']==PROFIL_MANAGER) echo L_PROFIL_MANAGER;
        elseif($_SESSION['profil']==PROFIL_MODERATOR) echo L_PROFIL_MODERATOR;
        elseif($_SESSION['profil']==PROFIL_EDITOR) echo L_PROFIL_EDITOR;
        else echo L_PROFIL_WRITER;
        ?>"><i class="icon-user icon-blue icon-shadowed-white"></i>
    </a>
        
  	<hr class="hidden-phone">
    
  	<ul role="menubar" class="nav nav-tabs nav-stacked">
    
    <?php
	
		$menus = array();
		$userId = ($_SESSION['profil'] < PROFIL_WRITER ? '[0-9]{3}' : $_SESSION['user']);
		$nbartsmod = $plxAdmin->nbArticles('all', $userId, '_');
		$arts_mod = $nbartsmod>0 ? '<span class="label label-info hidden-phone" style="position: absolute;margin: -10px auto auto 10px;">' . $nbartsmod . '</span>':'';
		
		$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_ARTICLES, 'index.php?page=1', false, 'role="menuitem" aria-labelledby="index" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_ARTICLES_TITLE.'"', 'copy', false, false,$arts_mod);

		if(isset($_GET['a'])) # edition article
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_NEW_ARTICLES_TITLE, 'article.php', false, 'role="menuitem" aria-labelledby="article" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_NEW_ARTICLES.'"', 'edit', false, false, '');
		else # nouvel article
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_NEW_ARTICLES_TITLE, 'article.php', false, 'role="menuitem" aria-labelledby="article" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_NEW_ARTICLES.'"', 'edit', false, false, '');

		$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_MEDIAS, 'medias.php', false, 'role="menuitem" aria-labelledby="medias" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_MEDIAS_TITLE.'"', 'cloud-upload', false, false, '');

		if($_SESSION['profil'] <= PROFIL_MANAGER) {
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_STATICS, 'statiques.php', false, 'role="menuitem" aria-labelledby="statiques" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_STATICS_TITLE.'"', 'list-alt', false, false, '');
		}
		
		if($_SESSION['profil'] <= PROFIL_MODERATOR) {
			$nbcoms = $plxAdmin->nbComments('offline');
			$coms_offline = $nbcoms>0 ? '<span class="label label-info hidden-phone" style="position: absolute;margin: -10px auto auto 10px;">' . $plxAdmin->nbComments('offline') . '</span>':'';
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_COMMENTS, 'comments.php?page=1', false, 'role="menuitem" aria-labelledby="comments" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_COMMENTS_TITLE.'"', 'comments', false, false, $coms_offline);
		}
		
		if($_SESSION['profil'] <= PROFIL_EDITOR) {
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CATEGORIES, 'categories.php', false, 'role="menuitem" aria-labelledby="categories" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CATEGORIES_TITLE.'"', 'magnet', false, false, '');
		}
		
		if($_SESSION['profil'] == PROFIL_ADMIN) {
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG, 'parametres_base.php', false, 'role="menuitem" aria-labelledby="parametres_base" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_TITLE.'"', 'cogs', false, false, '');
		
		if (preg_match('/parametres/',basename($_SERVER['SCRIPT_NAME']))) {
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_BASE, 'parametres_base.php', false, 'role="menuitem" aria-labelledby="parametres_base" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_BASE_TITLE.'"', 'cog', 'sub', false, '');
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_VIEW, 'parametres_affichage.php', false, 'role="menuitem" aria-labelledby="parametres_affichage" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_VIEW_TITLE.'"', 'desktop', 'sub', false, '');
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_USERS, 'parametres_users.php', false, 'role="menuitem" aria-labelledby="parametres_users" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_USERS_TITLE.'"', 'group', 'sub', false, '');
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_ADVANCED, 'parametres_avances.php', false, 'role="menuitem" aria-labelledby="parametres_avances" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_ADVANCED_TITLE.'"', 'cogs', 'sub', false, '');
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_PLUGINS, 'parametres_plugins.php', false, 'role="menuitem" aria-labelledby="parametres_plugins" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_PLUGINS_TITLE.'"', 'wrench', 'sub', false, '');
			$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_INFOS, 'parametres_infos.php', false, 'role="menuitem" aria-labelledby="parametres_infos" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_INFOS_TITLE.'"', 'book', 'sub', false, '');
			}
		}
		
		$menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_PROFIL, 'profil.php', false, 'role="menuitem" aria-labelledby="profil" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_PROFIL_TITLE.'"', 'user', false, false, '');
		
		# récuperation des menus pour les plugins
		foreach($plxAdmin->plxPlugins->aPlugins as $plugName => $plugin) {
			if(isset($plugin['activate']) AND $plugin['activate'] AND !empty($plugin['title'])) {
				if(isset($plugin['instance']) AND is_file(PLX_PLUGINS.$plugName.'/admin.php')) {
					if($plxAdmin->checkProfil($plugin['instance']->getAdminProfil(),false)) {
						if($plugin['instance']->adminMenu) {
							$menu = plxUtilsExt::formatMenuBootstrap(plxUtils::strCheck($plugin['instance']->adminMenu['title']), 'plugin.php?p='.$plugName, plxUtils::strCheck($plugin['instance']->adminMenu['caption']));
							if($plugin['instance']->adminMenu['position'])
								array_splice($menus, ($plugin['instance']->adminMenu['position']-1), 0, $menu);
							else
								$menus[]=$menu;
						}
						else
							$menus[] = plxUtilsExt::formatMenuBootstrap(plxUtils::strCheck($plugin['title']), 'plugin.php?p='.$plugName, plxUtils::strCheck($plugin['title']));
					}
				}
			}
		}
		
		# Hook Plugins
		eval($plxAdmin->plxPlugins->callHook('AdminTopMenus'));
		
		echo implode('', $menus);
    ?>
  </ul>
  
  <hr class="visible-desktop">
  
  <a class="visible-desktop" title="PluXml" href="http://www.pluxml.org">Pluxml <?php echo $plxAdmin->aConf['version'] ?></a>
  
</aside>
<!--.sidebar-mini -->