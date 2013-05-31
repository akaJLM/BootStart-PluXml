<?php

/**
 * Listing des commentaires en attente de validation
 *
 * @package PLX
 * @author	Stephane F
 * modification 30/03/2013
 * @author akaJLM
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminCommentsPrepend'));

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN, PROFIL_MANAGER, PROFIL_MODERATOR);

# Interdire de l'accès à la page si les commentaires sont désactivés
if(!$plxAdmin->aConf['allow_com']) {
    header('Location: index.php');
    exit;
}

# validation de l'id de l'article si passé en parametre
if(isset($_GET['a']) AND !preg_match('/^_?[0-9]{4}$/',$_GET['a'])) {
	plxMsg::Error(L_ERR_UNKNOWN_ARTICLE); # Article inexistant
	header('Location: index.php');
	exit;
}

# Suppression des commentaires selectionnes
if(isset($_POST['selection']) AND ($_POST['selection'][0] == 'delete' OR $_POST['selection'][1] == 'delete') AND isset($_POST['idCom'])) {
	foreach ($_POST['idCom'] as $k => $v) $plxAdmin->delCommentaire($v);
	header('Location: comments.php'.(!empty($_GET['a'])?'?a='.$_GET['a']:''));
	exit;
}
# Validation des commentaires selectionnes
elseif(isset($_POST['selection']) AND ($_POST['selection'][0] == 'online' OR $_POST['selection'][1] == 'online') AND isset($_POST['idCom'])) {
	foreach ($_POST['idCom'] as $k => $v) $plxAdmin->modCommentaire($v, 'online');
	header('Location: comments.php'.(!empty($_GET['a'])?'?a='.$_GET['a']:''));
	exit;
}
# Mise hors-ligne des commentaires selectionnes
elseif (isset($_POST['selection']) AND ($_POST['selection'][0] == 'offline' OR $_POST['selection'][1] == 'offline') AND isset($_POST['idCom'])) {
	foreach ($_POST['idCom'] as $k => $v) $plxAdmin->modCommentaire($v, 'offline');
	header('Location: comments.php'.(!empty($_GET['a'])?'?a='.$_GET['a']:''));
	exit;
}

# Récuperation des infos sur l'article attaché au commentaire si passé en paramètre
if(!empty($_GET['a'])) {
	# Infos sur notre article
	if(!$globArt = $plxAdmin->plxGlob_arts->query('/^'.$_GET['a'].'.(.*).xml$/','','sort',0,1)) {
		plxMsg::Error(L_ERR_UNKNOWN_ARTICLE); # Article inexistant
		header('Location: index.php');
		exit;
	}
	# Infos sur l'article
	$aArt = $plxAdmin->parseArticle(PLX_ROOT.$plxAdmin->aConf['racine_articles'].$globArt['0']);
	$portee = L_COMMENTS_ARTICLE_SCOPE.' &laquo;'.$aArt['title'].'&raquo;';
} else { # Commentaires globaux
	$portee = '';
}

# On inclut le header
include(dirname(__FILE__).'/top.php');

?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-comments icon-grey icon-shadowed"></i></span>
      <?php
            # Récuperation du type de commentaire à afficher
            $_GET['sel'] = !empty($_GET['sel']) ? $_GET['sel'] : '';
            if(in_array($_GET['sel'], array('online', 'offline', 'all')))
                $comSel = plxUtils::nullbyteRemove($_GET['sel']);
            else
                $comSel = ((isset($_SESSION['selCom']) AND !empty($_SESSION['selCom'])) ? $_SESSION['selCom'] : 'all');
            
            if(!empty($_GET['a'])) {
                $comSelMotif = '/^[[:punct:]]?'.str_replace('_','',$_GET['a']).'.(.*).xml$/';
                $_SESSION['selCom'] = 'all';
                $nbComPagination=$plxAdmin->nbComments($comSelMotif);
                echo '<h5>'.L_COMMENTS_ALL_LIST.'</h5>';
            }
            elseif($comSel=='online') {
                $comSelMotif = '/^[0-9]{4}.(.*).xml$/';
                $_SESSION['selCom'] = 'online';
                $nbComPagination=$plxAdmin->nbComments('online');
                echo '<h5>'.L_COMMENTS_ONLINE_LIST.'</h5>';
            }
            elseif($comSel=='offline') {
                $comSelMotif = '/^_[0-9]{4}.(.*).xml$/';
                $_SESSION['selCom'] = 'offline';
                $nbComPagination=$plxAdmin->nbComments('offline');
                echo '<h5>'.L_COMMENTS_OFFLINE_LIST.'</h5>';
            }
            elseif($comSel=='all') { // all
                $comSelMotif = '/^[[:punct:]]?[0-9]{4}.(.*).xml$/';
                $_SESSION['selCom'] = 'all';
                $nbComPagination=$plxAdmin->nbComments('all');
                echo '<h5>'.L_COMMENTS_ALL_LIST.'</h5>';
            }
			
			# On va récupérer les commentaires
			$plxAdmin->getPage();
			$start = $plxAdmin->aConf['bypage_admin_coms']*($plxAdmin->page-1);
			$coms = $plxAdmin->getCommentaires($comSelMotif,'rsort',$start,$plxAdmin->aConf['bypage_admin_coms'],'all');
			
			if($coms) { # Si on a des commentaires (hors page)
				# Calcul des pages
				$last_page = ceil($nbComPagination/$plxAdmin->aConf['bypage_admin_coms']);
				if($plxAdmin->page > $last_page) $plxAdmin->page = $last_page;
			}
			printf('<span class="label label-info pull-right" style="margin-right:17px">'.L_PAGINATION.'</span>', $plxAdmin->page, $last_page);
            ?>
    </div>
    <div class="widget-content">
      <?php

if($portee!='') {
	echo '<span class="label pull-right" style="margin-right:12px">'.$portee.'</span>';
}

$breadcrumbs = array();
$breadcrumbs[] = '<div class="btn-group"><a class="btn btn-responsive" '.($_SESSION['selCom']=='all'?'class="selected" ':'').'href="comments.php?sel=all&amp;page=1">'.L_ALL.' <span class="label">'.$plxAdmin->nbComments('all').'</span></a>';
$breadcrumbs[] = '<a class="btn btn-responsive" '.($_SESSION['selCom']=='online'?'class="selected" ':'').'href="comments.php?sel=online&amp;page=1">'.L_COMMENT_ONLINE.' <span class="label">'.$plxAdmin->nbComments('online').'</span></a>';
$breadcrumbs[] = '<a class="btn btn-responsive" '.($_SESSION['selCom']=='offline'?'class="selected" ':'').'href="comments.php?sel=offline&amp;page=1">'.L_COMMENT_OFFLINE.' <span class="label label-info">'.$plxAdmin->nbComments('offline').'</span></a></div>';
if(!empty($_GET['a'])) {
	$breadcrumbs[] = '<a class="btn btn-responsive" href="comment_new.php?a='.$_GET['a'].'" title="'.L_COMMENT_NEW_COMMENT_TITLE.'">'.L_COMMENT_NEW_COMMENT.'</a>';
}

ob_start();
if($comSel=='online')
	plxUtils::printSelect('selection[]', array(''=> L_FOR_SELECTION, 'offline' => L_COMMENT_SET_OFFLINE, '-'=>'-----', 'delete' => L_COMMENT_DELETE), '', false,'',false);
elseif($comSel=='offline')
	plxUtils::printSelect('selection[]', array(''=> L_FOR_SELECTION, 'online' => L_COMMENT_SET_ONLINE, '-'=>'-----', 'delete' => L_COMMENT_DELETE), '', false,'',false);
elseif($comSel=='all')
	plxUtils::printSelect('selection[]', array(''=> L_FOR_SELECTION, 'online' => L_COMMENT_SET_ONLINE, 'offline' => L_COMMENT_SET_OFFLINE,  '-'=>'-----','delete' => L_COMMENT_DELETE), '', false,'',false);
$selector=ob_get_clean();

?>
      <?php eval($plxAdmin->plxPlugins->callHook('AdminCommentsTop')) # Hook Plugins ?>
      <form action="comments.php<?php echo !empty($_GET['a'])?'?a='.$_GET['a']:'' ?>" method="post" id="form_comments">
        <?php echo implode('', $breadcrumbs); ?>
        <div class="control-group">
          <div class="controls pull-left" style="margin-left:0;margin-top:15px;margin-bottom:10px">
            <div class="input-append"> <?php echo $selector ?>
              <input class="btn submit" type="submit" name="submit" value="<?php echo L_OK ?>" />
            </div>
          </div>
        </div>
        <div class="pagination pagination-mini pull-right" id="pagination" style="margin:0">
          <ul>
            <?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminCommentsPagination'));
# Affichage de la pagination
if($coms) { # Si on a des commentaires (hors page)
	# Calcul des pages
	$last_page = ceil($nbComPagination/$plxAdmin->aConf['bypage_admin_coms']);
	if($plxAdmin->page > $last_page) $plxAdmin->page = $last_page;
	$prev_page = $plxAdmin->page - 1;
	$next_page = $plxAdmin->page + 1;
	# Generation des URLs
	$p_url = 'comments.php?page='.$prev_page.'&amp;sel='.$_SESSION['selCom'].(!empty($_GET['a'])?'&amp;a='.$_GET['a']:''); # Page precedente
	$n_url = 'comments.php?page='.$next_page.'&amp;sel='.$_SESSION['selCom'].(!empty($_GET['a'])?'&amp;a='.$_GET['a']:''); # Page suivante
	$l_url = 'comments.php?page='.$last_page.'&amp;sel='.$_SESSION['selCom'].(!empty($_GET['a'])?'&amp;a='.$_GET['a']:''); # Derniere page
	$f_url = 'comments.php?page=1'.'&amp;sel='.$_SESSION['selCom'].(!empty($_GET['a'])?'&amp;a='.$_GET['a']:''); # Premiere page
	# On effectue l'affichage
	if($plxAdmin->page > 2) # Si la page active > 2 on affiche un lien 1ere page
		echo '<li><a href="'.$f_url.'" title="'.L_PAGINATION_FIRST_TITLE.'">'.L_PAGINATION_FIRST.'</a></li>';
	else
		echo '<li class="disabled"><a href="#" title="'.L_PAGINATION_FIRST_TITLE.'">'.L_PAGINATION_FIRST.'</a></li>';
	if($plxAdmin->page > 1) # Si la page active > 1 on affiche un lien page precedente
		echo '<li><a href="'.$p_url.'" title="'.L_PAGINATION_PREVIOUS_TITLE.'">'.L_PAGINATION_PREVIOUS.'</a></li>';
	else
		echo '<li class="disabled"><a href="#" title="'.L_PAGINATION_PREVIOUS_TITLE.'">'.L_PAGINATION_PREVIOUS.'</a></li>';
	if($plxAdmin->page < $last_page) # Si la page active < derniere page on affiche un lien page suivante
		echo '<li><a href="'.$n_url.'" title="'.L_PAGINATION_NEXT_TITLE.'">'.L_PAGINATION_NEXT.'</a></li>';
	else
		echo '<li class="disabled"><a href="#" title="'.L_PAGINATION_NEXT_TITLE.'">'.L_PAGINATION_NEXT.'</a></li>';
	if(($plxAdmin->page + 1) < $last_page) # Si la page active++ < derniere page on affiche un lien derniere page
		echo '<li><a href="'.$l_url.'" title="'.L_PAGINATION_LAST_TITLE.'">'.L_PAGINATION_LAST.'</a></li>';
	else
		echo '<li class="disabled"><a href="#" title="'.L_PAGINATION_LAST_TITLE.'">'.L_PAGINATION_LAST.'</a></li>';
}
?>
          </ul>
        </div>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" onclick="checkAll(this.form, 'idCom[]')" /></th>
              <th class="hidden-phone"><?php echo L_COMMENTS_LIST_DATE ?></th>
              <th><?php echo L_COMMENTS_LIST_MESSAGE ?></th>
              <th><?php echo L_COMMENTS_LIST_AUTHOR ?></th>
              <th><?php echo L_COMMENTS_LIST_ACTION ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
# On affiche les commentaires
if($coms) {
	$num=0;
	while($plxAdmin->plxRecord_coms->loop()) { # On boucle
		$artId = $plxAdmin->plxRecord_coms->f('article');
		$status = $plxAdmin->plxRecord_coms->f('status');
		$id = $status.$artId.'.'.$plxAdmin->plxRecord_coms->f('numero');
		$content = nl2br($plxAdmin->plxRecord_coms->f('content'));
		if($_SESSION['selCom']=='all') {
			$content = $content.'<span class="label pull-right'.($status==''? '">'.L_COMMENT_ONLINE:' label-info">'.L_COMMENT_OFFLINE).'</span>';
		}
		# On génère notre ligne
		echo '<tr class="line-'.(++$num%2).' top type-'.$plxAdmin->plxRecord_coms->f('type').'">';
		echo '<td><input type="checkbox" name="idCom[]" value="'.$id.'" /></td>';
		echo '<td class="datetime hidden-phone">'.plxDate::formatDate($plxAdmin->plxRecord_coms->f('date')).'&nbsp;</td>';
		echo '<td>'.$content.'&nbsp;</td>';
		echo '<td>'.plxUtils::strCut($plxAdmin->plxRecord_coms->f('author'),30).'&nbsp;</td>';
		echo '<td class="action"><div class="btn-group" style="margin:0;">';
		echo '<a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="'.L_COMMENT_ANSWER.'" href="comment_new.php?c='.$id.(!empty($_GET['a'])?'&amp;a='.$_GET['a']:'').'">'.L_COMMENT_ANSWER.'</a> | ';
		echo '<a class="btn btn-mini hidden-phone" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="'.L_COMMENT_EDIT_TITLE.'" href="comment.php?c='.$id.(!empty($_GET['a'])?'&amp;a='.$_GET['a']:'').'">'.L_COMMENT_EDIT.'</a> | ';
		echo '<a class="btn btn-mini hidden-phone" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="'.L_COMMENT_ARTICLE_LINKED_TITLE.'" href="article.php?a='.$artId.'">'.L_COMMENT_ARTICLE_LINKED.'</a>';
		echo '</div></td></tr>';
	}
} else { # Pas de commentaires
	echo '<tr><td colspan="5" class="center">'.L_NO_COMMENT.'</td></tr>';
}
?>
          </tbody>
        </table>
        <div class="control-group">
          <div class="controls" style="margin-left:0;margin-top:15px;">
            <div class="input-append"> <?php echo $selector ?>
              <input class="btn submit" type="submit" name="submit" value="<?php echo L_OK ?>" />
            </div>
          </div>
        </div>
        <?php echo plxToken::getTokenPostMethod() ?>
      </form>
      <div class="pagination text-center" id="pagination">
        <ul>
          <?php
# Affichage de la pagination
if($coms) { # Si on a des articles (hors page)
	# On effectue l'affichage
	if($plxAdmin->page > 2) # Si la page active > 2 on affiche un lien 1ere page
		echo '<li><a href="'.$f_url.'" title="'.L_PAGINATION_FIRST_TITLE.'">'.L_PAGINATION_FIRST.'</a></li>';
	else
		echo '<li class="disabled"><a href="#" title="'.L_PAGINATION_FIRST_TITLE.'">'.L_PAGINATION_FIRST.'</a></li>';
	if($plxAdmin->page > 1) # Si la page active > 1 on affiche un lien page precedente
		echo '<li><a href="'.$p_url.'" title="'.L_PAGINATION_PREVIOUS_TITLE.'">'.L_PAGINATION_PREVIOUS.'</a></li>';
	else
		echo '<li class="disabled"><a href="#" title="'.L_PAGINATION_PREVIOUS_TITLE.'">'.L_PAGINATION_PREVIOUS.'</a></li>';
	if($plxAdmin->page < $last_page) # Si la page active < derniere page on affiche un lien page suivante
		echo '<li><a href="'.$n_url.'" title="'.L_PAGINATION_NEXT_TITLE.'">'.L_PAGINATION_NEXT.'</a></li>';
	else
		echo '<li class="disabled"><a href="#" title="'.L_PAGINATION_NEXT_TITLE.'">'.L_PAGINATION_NEXT.'</a></li>';
	if(($plxAdmin->page + 1) < $last_page) # Si la page active++ < derniere page on affiche un lien derniere page
		echo '<li><a href="'.$l_url.'" title="'.L_PAGINATION_LAST_TITLE.'">'.L_PAGINATION_LAST.'</a></li>';
	else
		echo '<li class="disabled"><a href="#" title="'.L_PAGINATION_LAST_TITLE.'">'.L_PAGINATION_LAST.'</a></li>';
}
?>
        </ul>
        <?php
# Affichage de la page courante
	printf('<br><span class="label label-info">'.L_PAGINATION.'</span>',$plxAdmin->page,$last_page);
?>
      </div>
      <?php if(!empty($plxAdmin->aConf['clef'])) : ?>
      <?php echo L_COMMENTS_PRIVATE_FEEDS ?>
      <?php $urlp_hl = $plxAdmin->racine.'feed.php?admin'.$plxAdmin->aConf['clef'].'/commentaires/hors-ligne'; ?>
      <div class="btn-group"> <a class="btn btn-mini" href="<?php echo $urlp_hl ?>" title="<?php echo L_COMMENT_OFFLINE_FEEDS_TITLE ?>"><?php echo L_COMMENT_OFFLINE_FEEDS ?></a>
        <?php $urlp_el = $plxAdmin->racine.'feed.php?admin'.$plxAdmin->aConf['clef'].'/commentaires/en-ligne'; ?>
        <a class="btn btn-mini" href="<?php echo $urlp_el ?>" title="<?php echo L_COMMENT_ONLINE_FEEDS_TITLE ?>"><?php echo L_COMMENT_ONLINE_FEEDS ?></a> </div>
      <?php endif; ?>
    </div>
    <!-- widget-content --> 
    
  </div>
  <!-- span12 widget --> 
  
</div>
<!-- row-fluid -->
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminCommentsFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
