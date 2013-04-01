<?php

/**
 * Edition du code source d'une page statique
 *
 * @package PLX
 * @author	Stephane F. et Florent MONTHEL
 **/

include(dirname(__FILE__).'/prepend.php');
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminStaticPrepend'));

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN, PROFIL_MANAGER);

# On édite la page statique
if(!empty($_POST) AND isset($plxAdmin->aStats[$_POST['id']])) {
	$plxAdmin->editStatique($_POST);
	header('Location: statique.php?p='.$_POST['id']);
	exit;
} elseif(!empty($_GET['p'])) { # On affiche le contenu de la page
	$id = plxUtils::strCheck(plxUtils::nullbyteRemove($_GET['p']));
	if(!isset($plxAdmin->aStats[ $id ])) {
		plxMsg::Error(L_STATIC_UNKNOWN_PAGE);
		header('Location: statiques.php');
		exit;
	}
	# On récupère le contenu
	$content = trim($plxAdmin->getFileStatique($id));
	$title = $plxAdmin->aStats[$id]['name'];
	$url = $plxAdmin->aStats[$id]['url'];
	$active = $plxAdmin->aStats[$id]['active'];
	$title_htmltag = $plxAdmin->aStats[$id]['title_htmltag'];
	$meta_description = $plxAdmin->aStats[$id]['meta_description'];
	$meta_keywords = $plxAdmin->aStats[$id]['meta_keywords'];
	$template = $plxAdmin->aStats[$id]['template'];
} else { # Sinon, on redirige
	header('Location: statiques.php');
	exit;
}

# On récupère les templates des pages statiques
$files = plxGlob::getInstance(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$plxAdmin->aConf['style']);
if ($array = $files->query('/^static(-[a-z0-9-_]+)?.php$/')) {
	foreach($array as $k=>$v)
		$aTemplates[$v] = $v;
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>

	<div class="row-fluid">
    
		<div class="span12 widget">
        
        <div class="widget-title"><span class="icon"><i class="icon-list-alt icon-grey icon-shadowed"></i></span>
                <h5><?php echo L_STATIC_TITLE ?> "<?php echo plxUtils::strCheck($title); ?>"</h5><a href="statiques.php" class="btn btn-mini pull-right" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_STATIC_BACK_TO_PAGE ?>" style="margin-right:10px"><i class="icon-undo icon-grey icon-shadowed-white"></i></a>
         </div>
             
             <div class="widget-content">

<?php eval($plxAdmin->plxPlugins->callHook('AdminStaticTop')) # Hook Plugins ?>

<form class="form form-horizontal" action="statique.php" method="post" id="form_static">
		<?php plxUtils::printInput('id', $id, 'hidden');?>
        
        <div class="control-group">
			<label class="control-label" for="id_content"><?php echo L_CONTENT_FIELD ?></label>
            <div class="controls">
                <?php plxUtils::printArea('content', plxUtils::strCheck($content),140,18) ?>
            </div>
        </div>
        
        <div class="control-group">
			<label class="control-label" for="id_template"><?php echo L_STATICS_TEMPLATE_FIELD ?></label>
            <div class="controls">
               <?php plxUtils::printSelect('template', $aTemplates, $template) ?>
            </div>
        </div>
        
        <div class="control-group">
			<label class="control-label" for="id_title_htmltag"><?php echo L_STATIC_TITLE_HTMLTAG ?></label>
            <div class="controls">
               <?php plxUtils::printInput('title_htmltag',plxUtils::strCheck($title_htmltag),'text','50-255'); ?>
            </div>
        </div>
        
        <div class="control-group">
			<label class="control-label" for="id_meta_description"><?php echo L_STATIC_META_DESCRIPTION ?></label>
            <div class="controls">
               <?php plxUtils::printInput('meta_description',plxUtils::strCheck($meta_description),'text','50-255'); ?>
            </div>
        </div>
        
        <div class="control-group">
			<label class="control-label" for="id_meta_keywords"><?php echo L_STATIC_META_KEYWORDS ?></label>
            <div class="controls">
              <?php plxUtils::printInput('meta_keywords',plxUtils::strCheck($meta_keywords),'text','50-255'); ?>
            </div>
        </div>
		
	<?php eval($plxAdmin->plxPlugins->callHook('AdminStatic')) # Hook Plugins ?>
   	
    <?php echo plxToken::getTokenPostMethod() ?>
    
    <div class="control-group">
    	<label class="control-label">&nbsp;</label>
        <div class="controls">
			<input class="btn btn-responsive" type="submit" value="<?php echo L_STATIC_UPDATE ?>"/>
            <?php if($active) : ?>
            <a class="btn btn-responsive" href="<?php echo PLX_ROOT; ?>?static<?php echo intval($id); ?>/<?php echo $url; ?>"><?php echo L_STATIC_VIEW_PAGE ?> <?php echo plxUtils::strCheck($title); ?> <?php echo L_STATIC_ON_SITE ?></a>
            <?php endif; ?>
        </div>
    </div>
    
</form>
</div>
</div>
</div>

<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminStaticFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>