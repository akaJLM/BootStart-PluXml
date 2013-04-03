<?php if(!defined('PLX_ROOT')) exit; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $plxAdmin->aConf['default_lang'] ?>" lang="<?php echo $plxAdmin->aConf['default_lang'] ?>">
<head>
<meta name="robots" content="noindex, nofollow" />
<title><?php echo plxUtils::strCheck($plxAdmin->aConf['title']) ?><?php echo L_ADMIN ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo strtolower(PLX_CHARSET) ?>" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--Bootstrap css-->
<link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap.min.css" rel="stylesheet" media="screen">

<!--Bootstrap extension - general-->
<link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap.min.ext.css" rel="stylesheet">

<!--Bootstrap extension - FontAwesome with ie7 support-->
<link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap.font-awesome.min.css" rel="stylesheet">
<!--[if IE 7]>
    <link rel="stylesheet" href="css/bootstrap.font-awesome-ie7.min.css">
    <![endif]-->
<!--Bootstrap extension - FontAwesome new color icons-->
<link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap.font-awesome.min.ext.css" rel="stylesheet">

<!--Hack precognized for #issue-->
<style type="text/css">
body {
	padding-top: 48px;
	padding-bottom: 40px;
}
 @media (max-width: 979px) {
body {
	padding-top: 0;
}
}
</style>

<!--Bootstrap responsive-->
<link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap-responsive.min.css" rel="stylesheet">

<!--Bootstrap responsive - extension-->
<link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap-responsive.min.ext.css" rel="stylesheet">

<!--Theme css-->
<link href="<?php echo PLX_CORE ?>admin/theme/css/theme.style.min.css" rel="stylesheet">

<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<script type="text/javascript" src="<?php echo PLX_CORE ?>lib/functions.js"></script>
<script type="text/javascript" src="<?php echo PLX_CORE ?>lib/visual.js"></script>
<?php eval($plxAdmin->plxPlugins->callHook('AdminTopEndHead')) ?>
</head>

<body>

<!--Menu top-->
<div id="navbar-user" class="navbar navbar-fixed-top">
  
  <div class="navbar-inner" style="padding:0">
    
    <div class="container-fluid more-padding">
    
    <div id="issue">
      
      <a class="brand" href="./" style="margin-left:0;padding-left:0;"><?php echo plxUtils::strCheck($plxAdmin->aConf['title']) ?></a></li>
      
      <div class="pull-right">
        
        <div class="btn-toolbar">
          
          <div class="btn-group"> <a href="<?php echo PLX_ROOT ?>" class="btn btn-mini" title="<?php echo L_BACK_TO_SITE_TITLE ?>"><i class="icon-undo icon-white"></i><?php echo L_BACK_TO_SITE;?></a>
            <?php if(isset($plxAdmin->aConf['homestatic']) AND !empty($plxAdmin->aConf['homestatic'])) : ?>
            <a href="<?php echo $plxAdmin->urlRewrite('?blog'); ?>" class="btn btn-mini" title="<?php echo L_BACK_TO_BLOG_TITLE ?>"><i class="icon-undo icon-white"></i><?php echo L_BACK_TO_BLOG;?></a>
            <?php endif; ?>
          </div>
          
          <div class="btn-group"> <a href="<?php echo PLX_CORE ?>admin/profil.php" class="btn btn-mini" title="<?php echo L_PROFIL_EDIT_TITLE ?>"> <i class="icon-user icon-white"></i><?php echo L_PROFIL ?></a> <a href="auth.php?d=1" class="btn btn-mini" title="<?php echo L_ADMIN_LOGOUT_TITLE ?>" id="logout"><i class="icon-off icon-white"></i><?php echo L_ADMIN_LOGOUT ?></a> </div>
        	</div>
      
       	</div><!--btn-toolbar-->
    
    </div><!--pull-right-->
    
    </div><!--issue-->
    
  </div><!--container-fluid-->

</div><!--navbar-inner-->

</div><!--navbar-user-->

<!--Header-->
<header class="subhead clearfix">
    
    <div class="container-fluid more-padding">
    
    	<div style="padding:0 20px;">
      
      <!--Breadcrumb-->
      <ul class="btn-align breadcrumb pull-left hidden-phone nav-breadcrumb" id="adminBreadcrumb">
        <li> <a href="<?php echo PLX_CORE ?>admin/index.php"><?php echo L_ADMIN_DASHBOARD_TEXT ?></a> <span class="divider">/</span></li>
        <li class="active"></li>
      </ul>
      
      <!-- Button Toolbar right-->
      <div class="btn-toolbar pull-right">
        
        <div class="btn-group"> 
        	<a href="<?php echo PLX_CORE ?>admin/article.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_ADD_POST ?>"> <i class="icon-edit"></i> </a> <a href="<?php echo PLX_CORE ?>admin/statiques.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_ADD_PAGE ?>"> <i class="icon-list-alt"></i> </a>
        </div>
        
        <div class="btn-group">
        	<a href="<?php echo PLX_CORE ?>admin/medias.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_MANAGE_FILES ?>"> <i class="icon-cloud-upload"></i> </a> <a href="<?php echo PLX_CORE ?>admin/parametres_users.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_MANAGE_USERS ?>"> <i class="icon-group"></i> </a> <a href="<?php echo PLX_CORE ?>admin/comments.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_MANAGE_COMMENTS ?>"> <i class="icon-comments"></i>
          <?php $comments = $plxAdmin->nbComments('offline'); echo ($comments > 0) ? '<span class="label label-info">' . $comments . '</span>' : ''; ?>
          </a> <a href="<?php echo PLX_CORE ?>admin/index.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_MANAGE_POSTS ?>"> <i class="icon-copy"></i>
          <?php $userId = ($_SESSION['profil'] < PROFIL_WRITER ? '[0-9]{3}' : $_SESSION['user']); $posts = $plxAdmin->nbArticles('all', $userId, '_'); echo ($posts > 0) ? '<span class="label label-info">' . $posts . '</span>' : ''; ?>
          </a>
       </div>
       
 	 </div><!--btn-toolbar pull-right-->
     
     </div><!--content-->
  
  </div><!--container-fluid-->
  
</header>

<!--Content-->
<section class="content">
	<div class="container-fluid more-padding">
		<div class="row-fluid">
        	<!-- Sidebar main menu left-->
            <div class="span1 well well-small sidebar-mini" id="sidebar"> <a class="hidden-phone" style="display:block;margin-top:15px" href="<?php echo PLX_CORE ?>admin/profil.php" rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="<?php
                    echo ' ' . plxUtils::strCheck($plxAdmin->aUsers[$_SESSION['user']]['name']) . ': ';
                        if($_SESSION['profil']==PROFIL_ADMIN) echo L_PROFIL_ADMIN;
                    elseif($_SESSION['profil']==PROFIL_MANAGER) echo L_PROFIL_MANAGER;
                    elseif($_SESSION['profil']==PROFIL_MODERATOR) echo L_PROFIL_MODERATOR;
                    elseif($_SESSION['profil']==PROFIL_EDITOR) echo L_PROFIL_EDITOR;
                    else echo L_PROFIL_WRITER;
                    ?>"> <i class="icon-user icon-blue icon-shadowed-white"></i> </a>
              <hr class="hidden-phone">
              <ul class="nav nav-tabs nav-stacked">
                <?php
                        $menus = array();
                        $userId = ($_SESSION['profil'] < PROFIL_WRITER ? '[0-9]{3}' : $_SESSION['user']);
                        $nbartsmod = $plxAdmin->nbArticles('all', $userId, '_');
                        $arts_mod = $nbartsmod>0 ? '<span class="label label-info hidden-phone" style="position: absolute;margin: -10px auto auto 10px;">' . $nbartsmod . '</span>':'';
                        $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_ARTICLES, 'index.php?page=1', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_ARTICLES_TITLE.'"', 'copy', false, false,$arts_mod);
            
                        if(isset($_GET['a'])) # edition article
                    $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_NEW_ARTICLES_TITLE, 'article.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_NEW_ARTICLES.'"', 'edit', false, false, '');
                        else # nouvel article
                    $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_NEW_ARTICLES_TITLE, 'article.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_NEW_ARTICLES.'"', 'edit', false, false, '');
            
                        $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_MEDIAS, 'medias.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_MEDIAS_TITLE.'"', 'cloud-upload', false, false, '');
            
                        if($_SESSION['profil'] <= PROFIL_MANAGER) {
                    $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_STATICS, 'statiques.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_STATICS_TITLE.'"', 'list-alt', false, false, '');
                        }
                        if($_SESSION['profil'] <= PROFIL_MODERATOR) {
                    $nbcoms = $plxAdmin->nbComments('offline');
                    $coms_offline = $nbcoms>0 ? '<span class="label label-info hidden-phone" style="position: absolute;margin: -10px auto auto 10px;">' . $plxAdmin->nbComments('offline') . '</span>':'';
                    $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_COMMENTS, 'comments.php?page=1', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_COMMENTS_TITLE.'"', 'comments', false, false, $coms_offline);
                        }
                        if($_SESSION['profil'] <= PROFIL_EDITOR) {
                    $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CATEGORIES, 'categories.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CATEGORIES_TITLE.'"', 'magnet', false, false, '');
                        }
                        if($_SESSION['profil'] == PROFIL_ADMIN) {
                    $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG, 'parametres_base.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_TITLE.'"', 'cogs', false, false, '');
                            if (preg_match('/parametres/',basename($_SERVER['SCRIPT_NAME']))) {
                                $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_BASE, 'parametres_base.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_BASE_TITLE.'"', 'cog', 'sub', false, '');
                                $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_VIEW, 'parametres_affichage.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_VIEW_TITLE.'"', 'desktop', 'sub', false, '');
                                $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_USERS, 'parametres_users.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_USERS_TITLE.'"', 'group', 'sub', false, '');
                                $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_ADVANCED, 'parametres_avances.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_ADVANCED_TITLE.'"', 'cogs', 'sub', false, '');
                                $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_PLUGINS, 'parametres_plugins.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_PLUGINS_TITLE.'"', 'wrench', 'sub', false, '');
                                $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_CONFIG_INFOS, 'parametres_infos.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_CONFIG_INFOS_TITLE.'"', 'book', 'sub', false, '');
                            }
                        }
                        $menus[] = plxUtilsExt::formatMenuBootstrap(L_MENU_PROFIL, 'profil.php', false, 'rel="tooltip" data-toggle="tooltip" data-placement="right" data-original-title="'.L_MENU_PROFIL_TITLE.'"', 'user', false, false, '');
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
              <a class="visible-desktop" title="PluXml" href="http://www.pluxml.org">Pluxml <?php echo $plxAdmin->aConf['version'] ?></a> </div>
            <!-- sidebar -->
            
			<?php eval($plxAdmin->plxPlugins->callHook('AdminTopBottom')) ?>
            
            <div class="span11 main pull-right" id="content">
             
			<?php
			if(is_file(PLX_ROOT.'install.php')) ;
					$msg = new plxMsg;
					$msg->Error(L_WARNING_INSTALLATION_FILE);
					$msg->Display();
			?>
