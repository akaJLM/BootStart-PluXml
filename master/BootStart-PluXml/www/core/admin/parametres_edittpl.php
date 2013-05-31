<?php
/**
 * Edition des fichiers templates du thème en vigueur
 * @package PLX
 * @author	Stephane F
 * modification 30/03/2013
 * @author akaJLM
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

# Initialisation
$tpl = isset($_POST['tpl'])?$_POST['tpl']:'home.php';
if(!empty($_POST['load'])) $tpl = $_POST['template'];

$style = $plxAdmin->aConf['style'];
$filename = realpath(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$style.'/'.$tpl);
if(!preg_match('#^'.str_replace('\\', '/', realpath(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$style.'/').'#'), str_replace('\\', '/', $filename))) {
	$tpl='home.php';
}
$filename = realpath(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$style.'/'.$tpl);

# On teste l'existence du thème
if(empty($style) OR !is_dir(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$style)) {
	plxMsg::Error(L_CONFIG_EDITTPL_ERROR_NOTHEME);
	header('Location: parametres_affichage.php');
	exit;
}

# Traitement du formulaire: sauvegarde du template
if(isset($_POST['submit']) AND trim($_POST['content']) != '') {
	if(plxUtils::write($_POST['content'], $filename))
		plxMsg::Info(L_CONFIG_EDITTPL_FILE_SAVE_INFO);
	else
		plxMsg::Error(L_CONFIG_EDITTPL_FILE_SAVE_ERROR);
}

# On récupère les fichiers templates du thèmes
$aTemplates=array();
function listFolderFiles($dir, $include, $root=''){
		$content = array();
    $ffs = scandir($dir);
    foreach($ffs as $ff){
				if($ff!='.' && $ff!='..') {
						$ext = strtolower(strrchr($ff,'.'));
						if(!is_dir($dir.'/'.$ff) AND is_array($include) AND in_array($ext,$include)) {
								$f = str_replace($root, "", PLX_ROOT.ltrim($dir.'/'.$ff,'./'));
								$content[$f] = $f;
						}
						if(is_dir($dir.'/'.$ff))
								$content = array_merge($content, listFolderFiles($dir.'/'.$ff,$include,$root));
				}
    }
    return $content;
}
$root = PLX_ROOT.$plxAdmin->aConf['racine_themes'].$style;
$aTemplates=listFolderFiles($root, array('.php','.css','.htm','.html','.txt','.js'), $root);

# On récupère le contenu du fichier template
$content = '';
if(file_exists($filename) AND filesize($filename) > 0) {
	if($f = fopen($filename, 'r')) {
		$content = fread($f, filesize($filename));
		fclose($f);
	}
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-desktop icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_EDITTPL_TITLE ?> &laquo;<?php echo plxUtils::strCheck($style) ?>&raquo;</h5>
    </div>
    <div class="widget-content">
      <?php eval($plxAdmin->plxPlugins->callHook('AdminSettingsEdittplTop')) # Hook Plugins ?>
      <form class="form form-horizontal" action="parametres_edittpl.php" method="post" id="form_select">
        <div class="control-group">
          <label class="control-label" for="id_template"><?php echo L_CONFIG_EDITTPL_SELECT_FILE ?></label>
          <div class="controls">
            <div class="input-append">
              <?php plxUtils::printSelect('template', $aTemplates, $tpl); ?>
              <input class="btn" name="load" type="submit" value="<?php echo L_CONFIG_EDITTPL_LOAD ?>" />
            </div>
          </div>
        </div>
        <?php echo plxToken::getTokenPostMethod() ?>
      </form>
      <form class="form form-horizontal" action="parametres_edittpl.php" method="post" id="form_file">
        <?php plxUtils::printInput('tpl',plxUtils::strCheck($tpl),'hidden'); ?>
        <div class="control-group">
          <label class="control-label" for="id_content"><?php echo L_CONTENT_FIELD ?></label>
          <div class="controls">
            <?php plxUtils::printArea('content',plxUtils::strCheck($content),60,20, false, 'span12'); ?>
          </div>
        </div>
        <?php eval($plxAdmin->plxPlugins->callHook('AdminSettingsEdittpl')) # Hook Plugins ?>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group">
          <label class="control-label">&nbsp;</label>
          <div class="controls">
            <input  class="btn btn-responsive update" name="submit" type="submit" value="<?php echo L_CONFIG_EDITTPL_SAVE ?>" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminSettingsEdittplFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
