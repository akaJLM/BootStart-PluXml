<?php if (!defined('PLX_ROOT')) exit; ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="msvalidate.01" content="783659123DF028F83A21D975C2074E7D" />
<title><?php $plxShow->pageTitle(); ?></title>
<?php $plxShow->meta('description'); ?>
<?php $plxShow->meta('keywords'); ?>
<?php $plxShow->meta('author'); ?>
<?php $plxShow->templateCss(); ?>

<link rel="icon" href="<?php $plxShow->template(); ?>/img/favicon.png" />

<link rel="alternate" type="application/rss+xml" title="<?php $plxShow->lang('ARTICLES_RSS_FEEDS') ?>" href="<?php $plxShow->urlRewrite('feed.php?rss') ?> " />
<link rel="alternate" type="application/rss+xml" title="<?php $plxShow->lang('COMMENTS_RSS_FEEDS') ?>" href="<?php $plxShow->urlRewrite('feed.php?rss/commentaires') ?> " />

<!--Bootstrap css-->
<link href="<?php $plxShow->template(); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">

<!--Bootstrap extension - general-->
<link href="<?php $plxShow->template(); ?>/css/bootstrap.min.ext.css" rel="stylesheet" media="screen">

<!--Bootstrap extension - FontAwesome with ie7 support-->
<link href="<?php $plxShow->template(); ?>/css/bootstrap.font-awesome.min.css" rel="stylesheet" media="screen">
<!--[if IE 7]>
    <link rel="stylesheet" href="css/bootstrap.font-awesome-ie7.min.css" media="screen">
    <![endif]-->
<!--Bootstrap extension - FontAwesome new color icons-->
<link href="<?php $plxShow->template(); ?>/css/bootstrap.font-awesome.min.ext.css" rel="stylesheet" media="screen">

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
<link href="<?php $plxShow->template(); ?>/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">

<!--Theme css-->
<link href="<?php $plxShow->template(); ?>/css/theme.style.min.css" rel="stylesheet" media="screen">

<!--Printer css-->
<link href="<?php $plxShow->template(); ?>/css/print.css" rel="stylesheet" media="print">

<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-7594261-41']);
  _gaq.push(['_setDomainName', 'jonathanmaris.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body role="document" aria-labelledby="documentName"><span id="documentName" style="display:none"><?php $plxShow->pageTitle(); ?></span>
    <!--Menu top-->
<nav id="navbar-user" class="navbar navbar-fixed-top">
  
  <div class="navbar-inner" style="padding:0">
    
    <div class="container-fluid more-padding">
    
    <div id="issue">
      
      <?php 
	  if($plxShow->mode() != 'article'){
		  $tagArray = array(0 => '<h1>', 1 => '</h1>');
	  }
	  else {
		  $tagArray = array(0 => '<h2>', 1 => '</h2>');
	  }
	  ?>
      <?php echo $tagArray[0]; ?><a class="brand" href="<?php echo $plxShow->urlRewrite(); ?>" title="Jonathan Maris: développeur web et développeur php, développeur html5 et css3, freelance Belgique"><?php $plxShow->mainTitle(); ?></a><?php echo $tagArray[1]; ?>
      
      <div class="pull-right">
        
        <div class="btn-toolbar">
          
          <div class="btn-group">
			<?php $plxShow->artFeed('rss',$plxShow->catId()); ?>
    		<a class="btn btn-mini" href="<?php echo $plxShow->urlRewrite('#top') ?>" title="<?php $plxShow->lang('GOTO_TOP'); ?>"><i class="icon-arrow-up icon-white"></i> <?php $plxShow->lang('TOP'); ?></a>
          </div>
      
       	</div><!--btn-toolbar-->
    
    </div><!--pull-right-->
    
    </div><!--issue-->
    
  </div><!--container-fluid-->

</div><!--navbar-inner-->

</nav><!--navbar-user-->


<header role="banner" class="subhead clearfix">
    
    <div class="container-fluid more-padding">
    
    	<div role="toolbar" aria-label="static-links" class="subfix">
        
            <!--Breadcrumb-->
           <aside id="breadcrumb" class="hidden-phone">
              <ul role="tree" class="breadcrumb btn-align pull-left nav-breadcrumb">
                <?php eval($plxShow->callHook('MyBreadcrumb')); ?>
              </ul>
           </aside>
           
           <aside role="menubar" class="pull-right">
            <ul role="menu" class="btn-group btn-align btn-group-horizontal">
                <?php $plxShow->staticList($plxShow->getLang('HOME'),'<li role="menuitem" id="#static_id"><a role="link" href="#static_url" class="btn btn-mini #static_status" title="#static_name">#static_name</a></li>'); ?>
                <?php $plxShow->pageBlog('<li role="menuitem" id="#page_id"><a role="link" class="btn btn-mini #page_status" href="#page_url" title="#page_name">#page_name</a></li>'); ?>
            </ul>
          </aside>
      
      </div>
  
  </div><!--container-fluid-->
  
</header>
            
<!--Content-->
<section role="main" class="content">

	<div class="container-fluid more-padding">
    
		<div class="row-fluid">
            
            <div class="span9 main" id="content">