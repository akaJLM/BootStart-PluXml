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

<body role="document" aria-labelledby="documentName"><span id="documentName" style="display:none"><?php echo plxUtils::strCheck($plxAdmin->aConf['title']) ?></span>

<!--Menu top-->
<nav id="navbar-user" class="navbar navbar-fixed-top">
  
  <div class="navbar-inner" style="padding:0">
    
    <div class="container-fluid more-padding">
    
    <div id="issue">
      
      <a class="brand" href="<?php echo PLX_ROOT ?>" style="margin-left:0;padding-left:0;"><?php echo plxUtils::strCheck($plxAdmin->aConf['title']) ?></a></li>
      
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

</nav><!--navbar-user-->

<!--Header-->
<header role="banner" class="subhead clearfix">
    
    <div class="container-fluid more-padding">
    
    <div role="toolbar" aria-label="static-links" class="subfix">
      
      <!--Breadcrumb-->
      <aside id="breadcrumb" class="hidden-phone">
          <ul role="tree" class="btn-align breadcrumb pull-left hidden-phone nav-breadcrumb" id="adminBreadcrumb">
            <li> <a role="link" href="<?php echo PLX_CORE ?>admin/index.php"><?php echo L_ADMIN_DASHBOARD_TEXT ?></a> <span class="divider">/</span></li>
            <li class="active"></li>
          </ul>
      </aside>
      
      <!-- Button Toolbar right-->
       <aside role="menubar" class="btn-toolbar pull-right">
        
        <div role="menu" class="btn-group"> 
        	<a role="link" href="<?php echo PLX_CORE ?>admin/article.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_ADD_POST ?>"> <i class="icon-edit"></i> </a> <a role="link" href="<?php echo PLX_CORE ?>admin/statiques.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_ADD_PAGE ?>"> <i class="icon-list-alt"></i> </a>
        </div>
        
        <div role="menu" class="btn-group">
        	<a role="link" href="<?php echo PLX_CORE ?>admin/medias.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_MANAGE_FILES ?>"> <i class="icon-cloud-upload"></i> </a> <a role="link" href="<?php echo PLX_CORE ?>admin/parametres_users.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_MANAGE_USERS ?>"> <i class="icon-group"></i> </a> <a role="link" href="<?php echo PLX_CORE ?>admin/comments.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_MANAGE_COMMENTS ?>"> <i class="icon-comments"></i>
          <?php $comments = $plxAdmin->nbComments('offline'); echo ($comments > 0) ? '<span class="label label-info">' . $comments . '</span>' : ''; ?>
          </a> <a role="link" href="<?php echo PLX_CORE ?>admin/index.php" class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BUTTON_MANAGE_POSTS ?>"> <i class="icon-copy"></i>
          <?php $userId = ($_SESSION['profil'] < PROFIL_WRITER ? '[0-9]{3}' : $_SESSION['user']); $posts = $plxAdmin->nbArticles('all', $userId, '_'); echo ($posts > 0) ? '<span class="label label-info">' . $posts . '</span>' : ''; ?>
          </a>
       </div>
       
 	 </aside><!--.btn-toolbar pull-right-->
     
     </div><!--.subfix-->
  
  </div><!--.container-fluid-->
  
</header>

<!--Content-->
<section role="main" class="content">

	<div class="container-fluid more-padding">
    
		<div class="row-fluid">
        
        	<?php include(PLX_CORE . 'admin/side.php'); ?>
            
			<?php eval($plxAdmin->plxPlugins->callHook('AdminTopBottom')) ?>
            
            <div class="span11 main pull-right" id="content">
             
			<?php
			if(is_file(PLX_ROOT.'install.php'))
					plxMsg::Error(L_WARNING_INSTALLATION_FILE);
					plxMsg::Display();
			?>
