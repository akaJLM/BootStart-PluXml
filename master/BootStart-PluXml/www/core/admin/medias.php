<?php

/**
 * Gestion des images et documents
 *
 * @package PLX
 * @author  Stephane F
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Sécurisation du chemin du dossier
if(isset($_POST['folder']) AND $_POST['folder']!='.' AND !plxUtils::checkSource($_POST['folder'])) {
	$_POST['folder']='.';
}

# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminMediasPrepend'));

# Recherche du type de medias à afficher via la session
if(empty($_SESSION['medias']) OR !empty($_POST['btn_images'])) {
	$_SESSION['medias'] = $plxAdmin->aConf['images'];
	$_SESSION['folder'] = '';
}
elseif(!empty($_POST['btn_documents'])) {
	$_SESSION['medias'] = $plxAdmin->aConf['documents'];
	$_SESSION['folder'] = '';
}
elseif(!empty($_POST['folder'])) {
	$_SESSION['currentfolder']= (isset($_SESSION['folder'])?$_SESSION['folder']:'');
	$_SESSION['folder'] = ($_POST['folder']=='.'?'':$_POST['folder']);
}
# Nouvel objet de type plxMedias
if($plxAdmin->aConf['userfolders'] AND $_SESSION['profil']==PROFIL_WRITER)
	$plxMedias = new plxMedias(PLX_ROOT.$_SESSION['medias'].$_SESSION['user'].'/',$_SESSION['folder']);
else
	$plxMedias = new plxMedias(PLX_ROOT.$_SESSION['medias'],$_SESSION['folder']);

#----

if(!empty($_POST['btn_newfolder']) AND !empty($_POST['newfolder'])) {
	$newdir = plxUtils::title2filename(trim($_POST['newfolder']));
	if($plxMedias->newDir($newdir)) {
		$_SESSION['folder'] = $_SESSION['folder'].$newdir.'/';
	}
	header('Location: medias.php');
	exit;
}
elseif(!empty($_POST['btn_delete']) AND !empty($_POST['folder']) AND $_POST['folder']!='.') {
	if($plxMedias->deleteDir($_POST['folder'])) {
		$_SESSION['folder'] = '';
	}
	header('Location: medias.php');
	exit;
}
elseif(!empty($_POST['btn_upload'])) {
	$plxMedias->uploadFiles($_FILES, $_POST);
	header('Location: medias.php');
	exit;
}
elseif(isset($_POST['selection']) AND ($_POST['selection'][0] == 'delete' OR $_POST['selection'][1] == 'delete') AND isset($_POST['idFile'])) {
	$plxMedias->deleteFiles($_POST['idFile']);
	header('Location: medias.php');
	exit;
}
elseif(isset($_POST['selection']) AND ($_POST['selection'][0] == 'move' OR $_POST['selection'][1] == 'move') AND isset($_POST['idFile'])) {
	$plxMedias->moveFiles($_POST['idFile'], $_SESSION['currentfolder'], $_POST['folder']);
	header('Location: medias.php');
	exit;
}
elseif(isset($_POST['selection']) AND ($_POST['selection'][0] == 'thumbs' OR $_POST['selection'][1] == 'thumbs') AND isset($_POST['idFile'])) {
	$plxMedias->makeThumbs($_POST['idFile'], $plxAdmin->aConf['miniatures_l'], $plxAdmin->aConf['miniatures_h']);
	header('Location: medias.php');
	exit;
}

# Tri de l'affichage des fichiers
if(isset($_POST['sort']) AND !empty($_POST['sort'])) {
	$sort = $_POST['sort'];
} else {
	$sort = isset($_SESSION['sort_medias']) ? $_SESSION['sort_medias'] : 'title_asc';
}

$sort_title = 'title_desc';
$sort_date = 'date_desc';
switch ($sort) {
	case 'title_asc':
		$sort_title = 'title_desc';
		usort($plxMedias->aFiles, create_function('$b, $a', 'return strcmp($a["name"], $b["name"]);'));
		break;
	case 'title_desc':
		$sort_title = 'title_asc';
		usort($plxMedias->aFiles, create_function('$a, $b', 'return strcmp($a["name"], $b["name"]);'));
		break;
	case 'date_asc':
		$sort_date = 'date_desc';
		usort($plxMedias->aFiles, create_function('$b, $a', 'return strcmp($a["date"], $b["date"]);'));
		break;
	case 'date_desc':
		$sort_date = 'date_asc';
		usort($plxMedias->aFiles, create_function('$a, $b', 'return strcmp($a["date"], $b["date"]);'));
		break;
}
$_SESSION['sort_medias']=$sort;

# Contenu des 2 listes déroulantes
$selectionList = array('' =>L_FOR_SELECTION, 'move'=>L_PLXMEDIAS_MOVE_FOLDER, 'thumbs'=>L_MEDIAS_RECREATE_THUMB, '-'=>'-----', 'delete' =>L_DELETE);

# On inclut le header
include(dirname(__FILE__).'/top.php');

?>
<script type="text/javascript" src="<?php echo PLX_CORE ?>lib/multifiles.js"></script>
<script type="text/javascript">
function toggle_divs(){
	var medias_back = document.getElementById('medias_back');
	var uploader = document.getElementById('files_uploader');
	var manager = document.getElementById('files_manager');
	if(uploader.style.display == 'none') {
		medias_back.style.display = 'block';
		uploader.style.display = 'block';
		manager.style.display = 'none';
	} else {
		medias_back.style.display = 'none';
		uploader.style.display = 'none';
		manager.style.display = 'block';
	}
}
</script>

	<div class="row-fluid">
    
		<div class="span12 widget">
        
        <div class="widget-title"><span class="icon"><i class="icon-cloud-upload icon-grey icon-shadowed"></i></span>
                <h5><?php echo L_MEDIAS_TITLE ?></h5><a id="medias_back" href="javascript:void(0)" onclick="toggle_divs();return false" class="btn btn-mini pull-right" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_MEDIAS_BACK ?>" style="display:none;margin-right:10px"><i class="icon-undo icon-grey icon-shadowed-white"></i></a>
              </div>
             
             <div class="widget-content">

<?php eval($plxAdmin->plxPlugins->callHook('AdminMediasTop')) # Hook Plugins ?>


      <ul class="btn-align breadcrumb nav-breadcrumb pull-left" style="margin-top:0;">
        <li> <?php echo L_MEDIAS_DIRECTORY; ?> <span class="divider">></span></li>
        <li class="active">/<?php echo plxUtils::strCheck(basename($_SESSION['medias']).'/'.$_SESSION['folder']); ?></li>
      </ul>

	<div id="files_uploader" style="display:none">
	
	<span class="label label-info pull-right"><?php echo L_MEDIAS_MAX_UPOLAD_FILE ?> <?php echo $plxMedias->maxUpload['display']; ?></span>
	
    <form class="form-horizontal form_uploader" action="medias.php" method="post" id="form_uploader" enctype="multipart/form-data">
    		<div class="clearfix"></div>
			<input id="selector" type="file" name="selector" />
            <hr>
			<div class="files_list" id="files_list"></div>
            <hr>
			<?php if($_SESSION['medias']==$plxAdmin->aConf['images']) : ?>
			<div class="control-group">
				<?php plxUtils::printInput('artId',$artId,'hidden'); ?>
                <label class="control-label"><?php echo L_MEDIAS_RESIZE ?></label>
                <div class="controls">

                        <label class="radio"><input type="radio" name="resize" value="" />&nbsp;<?php echo L_MEDIAS_RESIZE_NO ?></label>
                        <?php
                            foreach($img_redim as $redim) {
                                echo '<label class="radio"><input type="radio" name="resize" value="'.$redim.'" />'.$redim.'</label>';
                            }
                        ?>
                        <label class="radio"><input type="radio" checked="checked" name="resize" value="<?php echo intval($plxAdmin->aConf['images_l' ]).'x'.intval($plxAdmin->aConf['images_h' ]) ?>" /><?php echo intval($plxAdmin->aConf['images_l' ]).'x'.intval($plxAdmin->aConf['images_h' ]) ?> <a class="btn btn-mini" href="<?php echo PLX_CORE ?>admin/parametres_affichage.php"><?php echo L_MEDIAS_MODIFY ?></a></label>
                        <label class="radio"><input type="radio" name="resize" value="user" />
                        
                                    <input class="input-mini" type="text" size="2" maxlength="4" name="user_w" />X<input class="input-mini" type="text" size="2" maxlength="4" name="user_h" />
                        
                        </label>
                    </div>
                </div>
				
			<div class="control-group">
					<label class="control-label"><?php echo L_MEDIAS_THUMBS ?></label>
                    <div class="controls">
				
						<?php $sel = (!$plxAdmin->aConf['thumbs'] ? ' checked="checked"' : '') ?>
						<label class="radio"><input<?php echo $sel ?> type="radio" name="thumb" value="" /><?php echo L_MEDIAS_THUMBS_NONE ?></label>

						<?php
                            foreach($img_thumb as $thumb) {
                                echo '<label class="radio"><input type="radio" name="thumb" value="'.$thumb.'" />'.$thumb.'</label>';
                            }
                        ?>
					
						<?php $sel = ($plxAdmin->aConf['thumbs'] ? ' checked="checked"' : '') ?>
						<label class="radio"><input<?php echo $sel ?> type="radio" name="thumb" value="<?php echo intval($plxAdmin->aConf['miniatures_l' ]).'x'.intval($plxAdmin->aConf['miniatures_h' ]) ?>" /><?php echo intval($plxAdmin->aConf['miniatures_l' ]).'x'.intval($plxAdmin->aConf['miniatures_h' ]) ?> <a class="btn btn-mini" href="<?php echo PLX_CORE ?>admin/parametres_affichage.php"><?php echo L_MEDIAS_MODIFY ?></a></label>

						<label class="radio"><input type="radio" name="thumb" value="user" /><input class="input-mini" type="text" size="2" maxlength="4" name="thumb_w" />X<input class="input-mini" type="text" size="2" maxlength="4" name="thumb_h" /></label>
                        
                        </div>
                </div>

			<?php endif; ?>
            
            <?php eval($plxAdmin->plxPlugins->callHook('AdminMediasUpload')) # Hook Plugins ?>
            
			<div class="control-group">
                <label class="control-label">&nbsp;</label>
                <div class="controls">
                    <input class="btn submit" type="submit" name="btn_upload" id="btn_upload" value="<?php echo L_MEDIAS_SUBMIT_FILE ?>" />
                </div>
            </div>
			
			<?php echo plxToken::getTokenPostMethod() ?>
	</form>
    
	<script type="text/javascript">
		var multi_selector = new MultiSelector(document.getElementById('files_list'), -1, '<?php echo $plxAdmin->aConf['racine'] ?>');
		multi_selector.addElement(document.getElementById('selector'));
	</script>
</div>

<div id="files_manager">
	
    <form class="form form-horizontal" action="medias.php" method="post" id="form_medias">
		
		<div class="control-group pull-right">
            <div class="controls" style="margin-left:0;">
                <div class="input-append">
                	<input class="newfolder" placeholder="<?php echo L_MEDIAS_NEW_FOLDER ?>" id="id_newfolder" type="text" name="newfolder" value="" maxlength="50" size="10" />
                    <input class="btn new" type="submit" name="btn_newfolder" value="<?php echo L_MEDIAS_CREATE_FOLDER ?>" />
                </div>
        	</div>
        </div>
        
		<div class="clearfix"></div>
        
            <div class="btn-group">
                <input class="btn btn-responsive submit<?php echo basename($_SESSION['medias'])=='images'?' select':'' ?>" type="submit" name="btn_images" value="<?php echo L_MEDIAS_IMAGES ?>" />
                <input class="btn btn-responsive submit<?php echo basename($_SESSION['medias'])=='documents'?' select':'' ?>" type="submit" name="btn_documents" value="<?php echo L_MEDIAS_DOCUMENTS ?>" />
                <input class="btn btn-responsive submit" type="submit" onclick="toggle_divs();return false" value="<?php echo L_MEDIAS_ADD_FILE ?>" />
            </div>
            
			<?php echo plxToken::getTokenPostMethod() ?>

		<div class="control-group pull-right">
            <div class="controls" style="margin-left:0;">
                <div class="input-append input-prepend">
                  <span class="add-on hidden-phone"><?php echo L_MEDIAS_FOLDER ?></span>
                	<?php echo $plxMedias->contentFolder() ?>
                    <input class="btn submit" type="submit" name="btn_ok" value="<?php echo L_MEDIAS_GOTO_FOLDER ?>" />
                    <?php if(!empty($_SESSION['folder'])) : ?>
                    <input class="btn delete" type="submit" name="btn_delete" onclick="Check=confirm('<?php echo L_MEDIAS_DELETE_FOLDER_CONFIRM ?>');if(Check==false) return false;" value="<?php echo L_MEDIAS_DELETE_FOLDER ?>" />
                    <?php endif; ?>
                </div>
        	</div>
        </div>

        <div class="control-group">
            	<div class="controls" style="margin-left:0; margin-top: 15px">
                	<div class="input-append">
					<?php plxUtils::printSelect('selection[]', $selectionList , '', false, '', false) ?>
                    <input class="btn submit" type="submit" name="btn_action" value="<?php echo L_OK ?>" />
					</div>
            	</div>
            </div>
        
			<table class="table table-bordered table-hover">
			<thead>
			<tr>
				<th><input type="checkbox" onclick="checkAll(this.form, 'idFile[]')" /></th>
				<th>&nbsp;</th>
				<th><a href="javascript:void(0)" class="hcolumn" onclick="document.forms[1].sort.value='<?php echo $sort_title; ?>';document.forms[1].submit();return true;"><?php echo L_MEDIAS_FILENAME ?></a></th>
				<th class="hidden-phone"><?php echo L_MEDIAS_EXTENSION ?></th>
				<th class="hidden-phone"><?php echo L_MEDIAS_FILESIZE ?></th>
				<th class="hidden-phone"><?php echo L_MEDIAS_DIMENSIONS ?></th>
				<th><a href="javascript:void(0)" class="hcolumn" onclick="document.forms[1].sort.value='<?php echo $sort_date; ?>';document.forms[1].submit();return true;"><?php echo L_MEDIAS_DATE ?></a></th>
			</tr>
			</thead>
			<tbody>
			<?php
			# Initialisation de l'ordre
			$num = 0;
			# Si on a des fichiers
			if($plxMedias->aFiles) {
				foreach($plxMedias->aFiles as $v) { # Pour chaque fichier
					$ordre = ++$num;
					echo '<tr class="line-'.($num%2).'">';
					echo '<td><input type="checkbox" name="idFile[]" value="'.$v['name'].'" /></td>';
					echo '<td class="icon"><a onclick="this.target=\'_blank\';return true;" title="'.plxUtils::strCheck($v['name']).'" href="'.$v['path'].'"><img alt="" src="'.$v['.thumb'].'" class="thumb" /></a></td>';
					echo '<td>';
					echo '<a class="hidden-phone" onclick="this.target=\'_blank\';return true;" title="'.plxUtils::strCheck($v['name']).'" href="'.$v['path'].'">'.plxUtils::strCheck($v['name']).'</a><br>';
					if($v['thumb']) {
						echo '<a onclick="this.target=\'_blank\';return true;" title="'.L_MEDIAS_THUMB.' : '.plxUtils::strCheck($v['name']).'" href="'.plxUtils::thumbName($v['path']).'">'.L_MEDIAS_THUMB.'</a> : '.$v['thumb']['infos'][0].' x '.$v['thumb']['infos'][1]. ' ('.plxUtils::formatFilesize($v['thumb']['filesize']).')';
					}
					echo '</td>';
					echo '<td class="hidden-phone">'.strtoupper($v['extension']).'</td>';
					echo '<td class="hidden-phone">'.plxUtils::formatFilesize($v['filesize']).'</td>';
					$dimensions = '&nbsp;';
					if(isset($v['infos']) AND isset($v['infos'][0]) AND isset($v['infos'][1])) {
						$dimensions = $v['infos'][0].' x '.$v['infos'][1];
					}
					echo '<td class="hidden-phone">'.$dimensions.'</td>';
					echo '<td>'.plxDate::formatDate(plxDate::timestamp2Date($v['date'])).'</td>';
					echo '</tr>';
				}
			}
			else echo '<tr><td colspan="7" class="text-center">'.L_MEDIAS_NO_FILE.'</td></tr>';
			?>
			</tbody>
			</table>
			
            <div class="control-group">
            	<div class="controls" style="margin-left:0;">
                	<div class="input-append">
					<?php plxUtils::printSelect('selection[]', $selectionList , '', false, '', false) ?>
                    <input class="btn submit" type="submit" name="btn_action" value="<?php echo L_OK ?>" />
					</div>
            	</div>
            </div>
            <input type="hidden" name="sort" value="" />
	</form>
</div>
</div>
</div>

<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminMediasFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>

