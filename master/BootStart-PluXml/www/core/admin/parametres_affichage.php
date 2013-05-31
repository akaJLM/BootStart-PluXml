<?php

/**
 * Edition des paramètres d'affichage
 *
 * @package PLX
 * @author	Stephane F et Florent MONTHEL
 * modification 30/03/2013
 * @author akaJLM
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

# On édite la configuration
if(!empty($_POST)) {
	$_POST['feed_footer']=$_POST['content'];
	$_POST['images_l']=plxUtils::getValue($_POST['images_l'],800);
	$_POST['images_h']=plxUtils::getValue($_POST['images_h'],600);
	$_POST['miniatures_l']=plxUtils::getValue($_POST['miniatures_l'],200);
	$_POST['miniatures_h']=plxUtils::getValue($_POST['miniatures_h'],100);
	unset($_POST['content']);
	$plxAdmin->editConfiguration($plxAdmin->aConf,$_POST);
	header('Location: parametres_affichage.php');
	exit;
}

# On récupère les templates
$aStyles[''] = L_NONE1;
$files = plxGlob::getInstance(PLX_ROOT.$plxAdmin->aConf['racine_themes'], true);
if($styles = $files->query("/[a-z0-9-_\.\(\)]+/i")) {
	foreach($styles as $k=>$v) {
		if(substr($v,0,7) != 'mobile.')	$aStyles[$v] = $v;
	}
}

# Tableau du tri
$aTriArts = array('desc'=>L_SORT_DESCENDING_DATE, 'asc'=>L_SORT_ASCENDING_DATE, 'alpha'=>L_SORT_ALPHABETICAL);
$aTriComs = array('desc'=>L_SORT_DESCENDING_DATE, 'asc'=>L_SORT_ASCENDING_DATE);

# On va tester les variables pour les images et miniatures
if(!is_numeric($plxAdmin->aConf['images_l'])) $plxAdmin->aConf['images_l'] = 800;
if(!is_numeric($plxAdmin->aConf['images_h'])) $plxAdmin->aConf['images_h'] = 600;
if(!is_numeric($plxAdmin->aConf['miniatures_l'])) $plxAdmin->aConf['miniatures_l'] = 200;
if(!is_numeric($plxAdmin->aConf['miniatures_h'])) $plxAdmin->aConf['miniatures_h'] = 100;

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-desktop icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_VIEW_FIELD ?></h5>
    </div>
    <div class="widget-content">
      <?php eval($plxAdmin->plxPlugins->callHook('AdminSettingsDisplayTop')) # Hook Plugins ?>
      <form class="form-horizontal" action="parametres_affichage.php" method="post" id="form_settings">
        <div class="control-group">
          <label class="control-label" for="id_style"><?php echo L_CONFIG_VIEW_SKIN_SELECT ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('style', $aStyles, $plxAdmin->aConf['style']); ?>
            <div class="input-append" style="margin-left:-7px;">
              <?php if(!empty($plxAdmin->aConf['style']) AND is_dir(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$plxAdmin->aConf['style'])) : ?>
              <a href="parametres_edittpl.php" class="btn"rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_CONFIG_VIEW_FILES_EDIT ?> &quot;<?php echo $plxAdmin->aConf['style'] ?>&quot;"><i class="icon icon-edit"></i></a>
              <?php endif; ?>
            </div>
            <a href="http://ressources.pluxml.org" style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo preg_replace('#<a href="http://(.+)">(.+)</a>#', '$1', L_CONFIG_VIEW_PLUXML_RESSOURCES) ?>"><i class="icon icon-link"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_tri"><?php echo L_CONFIG_VIEW_SORT ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('tri', $aTriArts, $plxAdmin->aConf['tri']); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_bypage"><?php echo L_CONFIG_VIEW_BYPAGE ?></label>
          <div class="controls">
            <?php plxUtils::printInput('bypage', $plxAdmin->aConf['bypage'], 'text', '2-2',false,'fieldnum'); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_bypage_archives"><?php echo L_CONFIG_VIEW_BYPAGE_ARCHIVES ?></label>
          <div class="controls">
            <?php plxUtils::printInput('bypage_archives', $plxAdmin->aConf['bypage_archives'], 'text', '2-2',false,'fieldnum'); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_bypage_admin"><?php echo L_CONFIG_VIEW_BYPAGE_ADMIN ?></label>
          <div class="controls">
            <?php plxUtils::printInput('bypage_admin', $plxAdmin->aConf['bypage_admin'], 'text', '2-2',false,'fieldnum'); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_tri_coms"><?php echo L_CONFIG_VIEW_SORT_COMS ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('tri_coms', $aTriComs, $plxAdmin->aConf['tri_coms']); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_bypage_admin_coms"><?php echo L_CONFIG_VIEW_BYPAGE_ADMIN_COMS ?></label>
          <div class="controls">
            <?php plxUtils::printInput('bypage_admin_coms', $plxAdmin->aConf['bypage_admin_coms'], 'text', '2-2',false,'fieldnum'); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_display_empty_cat"><?php echo L_CONFIG_VIEW_DISPLAY_EMPTY_CAT ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('display_empty_cat',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['display_empty_cat']);?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label"><?php echo L_CONFIG_VIEW_IMAGES ?></label>
          <div class="controls"> <span>
            <?php plxUtils::printInput('images_l', $plxAdmin->aConf['images_l'], 'text', '4-4',false,'fieldnum'); ?>
            X
            <?php plxUtils::printInput('images_h', $plxAdmin->aConf['images_h'], 'text', '4-4',false,'fieldnum'); ?>
            </span> </div>
        </div>
        <div class="control-group">
          <label class="control-label"><?php echo L_CONFIG_VIEW_THUMBS ?></label>
          <div class="controls"> <span>
            <?php plxUtils::printInput('miniatures_l', $plxAdmin->aConf['miniatures_l'], 'text', '4-4',false,'fieldnum'); ?>
            X
            <?php plxUtils::printInput('miniatures_h', $plxAdmin->aConf['miniatures_h'], 'text', '4-4',false,'fieldnum'); ?>
            </span> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_thumbs"><?php echo L_MEDIAS_THUMBS ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('thumbs',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['thumbs']);?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_bypage_feed"><?php echo L_CONFIG_VIEW_BYPAGE_FEEDS ?></label>
          <div class="controls">
            <?php plxUtils::printInput('bypage_feed', $plxAdmin->aConf['bypage_feed'], 'text', '2-2',false,'fieldnum'); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_feed_chapo"><?php echo L_CONFIG_VIEW_FEEDS_HEADLINE ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('feed_chapo',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['feed_chapo']);?>
            <a class="btn btn-mini" style="vertical-align:top" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_CONFIG_VIEW_FEEDS_HEADLINE_HELP ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_content"><?php echo L_CONFIG_VIEW_FEEDS_FOOTER ?></label>
          <div class="controls">
            <?php plxUtils::printArea('content',plxUtils::strCheck($plxAdmin->aConf['feed_footer']),140,5); ?>
          </div>
        </div>
        <?php eval($plxAdmin->plxPlugins->callHook('AdminSettingsDisplay')) # Hook Plugins ?>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group">
          <label class="control-label" for="id_content">&nbsp;</label>
          <div class="controls">
            <input class="btn update" type="submit" value="<?php echo L_CONFIG_VIEW_UPDATE ?>" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminSettingsDisplayFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
