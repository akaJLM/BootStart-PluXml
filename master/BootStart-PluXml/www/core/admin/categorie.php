<?php

/**
 * Edition des options d'une catégorie
 *
 * @package PLX
 * @author	Stephane F.
 * modification 30/03/2013 
 * @author Jonathan Maris www.jonathanmaris.net for © littleRabbitLabs
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminCategoryPrepend'));

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN, PROFIL_MANAGER, PROFIL_MODERATOR, PROFIL_EDITOR);

# On édite la catégorie
if(!empty($_POST) AND isset($plxAdmin->aCats[ $_POST['id'] ])) {
	$plxAdmin->editCategorie($_POST);
	header('Location: categorie.php?p='.$_POST['id']);
	exit;
}
elseif(!empty($_GET['p'])) { # On vérifie l'existence de la catégorie
	$id = plxUtils::strCheck($_GET['p']);
	if(!isset($plxAdmin->aCats[ $id ])) {
		plxMsg::Error(L_CAT_UNKNOWN);
		header('Location: categorie.php');
		exit;
	}
} else { # Sinon, on redirige
	header('Location: categories.php');
	exit;
}

# On récupère les templates des categories
$files = plxGlob::getInstance(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$plxAdmin->aConf['style']);
if ($array = $files->query('/^categorie(-[a-z0-9-_]+)?.php$/')) {
	foreach($array as $k=>$v)
		$aTemplates[$v] = $v;
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-magnet icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_EDITCAT_PAGE_TITLE ?> "<?php echo plxUtils::strCheck($plxAdmin->aCats[$id]['name']); ?>"</h5>
      <a class="btn btn-mini pull-right" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_EDITCAT_BACK_TO_PAGE ?>" style="margin-right:10px" href="categorie.php"><i class="icon-undo icon-grey icon-shadowed-white"></i></a> </div>
    <div class="widget-content">
      <?php eval($plxAdmin->plxPlugins->callHook('AdminCategoryTop')) # Hook Plugins ?>
      <form class="form form-horizontal" action="categorie.php" method="post" id="form_category">
        <?php plxUtils::printInput('comId',$_GET['c'],'hidden'); ?>
        <?php plxUtils::printInput('id', $id, 'hidden');?>
        <div class="control-group">
          <label class="control-label" for="id_homepage"><?php echo L_EDITCAT_DISPLAY_HOMEPAGE ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('homepage',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aCats[$id]['homepage']);?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_content"><?php echo L_EDITCAT_DESCRIPTION ?></label>
          <div class="controls">
            <?php plxUtils::printArea('content',plxUtils::strCheck($plxAdmin->aCats[$id]['description']),95,8) ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_template"><?php echo L_EDITCAT_TEMPLATE ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('template', $aTemplates, $plxAdmin->aCats[$id]['template']) ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_title_htmltag"><?php echo L_EDITCAT_TITLE_HTMLTAG ?></label>
          <div class="controls">
            <?php plxUtils::printInput('title_htmltag',plxUtils::strCheck($plxAdmin->aCats[$id]['title_htmltag']),'text','50-255'); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_meta_description"><?php echo L_EDITCAT_META_DESCRIPTION ?></label>
          <div class="controls">
            <?php plxUtils::printInput('meta_description',plxUtils::strCheck($plxAdmin->aCats[$id]['meta_description']),'text','50-255') ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_meta_keywords"><?php echo L_EDITCAT_META_KEYWORDS ?></label>
          <div class="controls">
            <?php plxUtils::printInput('meta_keywords',plxUtils::strCheck($plxAdmin->aCats[$id]['meta_keywords']),'text','50-255') ?>
          </div>
        </div>
        <?php eval($plxAdmin->plxPlugins->callHook('AdminCategory')) # Hook Plugins ?>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group">
          <label class="control-label">&nbsp;</label>
          <div class="controls">
            <input class="btn btn-responsive update" type="submit" value="<?php echo L_EDITCAT_UPDATE ?>"/>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminCategoryFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
