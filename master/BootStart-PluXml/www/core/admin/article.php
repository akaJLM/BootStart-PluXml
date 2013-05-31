<?php

/**
 * Edition d'un article
 *
 * @package PLX
 * @author	Stephane F et Florent MONTHEL
 * modification 30/03/2013 
 * @author akaJLM
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
if(!isset($_POST['preview']))
	plxToken::validateFormToken($_POST);

# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminArticlePrepend'));

# validation de l'id de l'article si passé en parametre
if(isset($_GET['a']) AND !preg_match('/^_?[0-9]{4}$/',$_GET['a'])) {
	plxMsg::Error(L_ERR_UNKNOWN_ARTICLE); # Article inexistant
	header('Location: index.php');
	exit;
}

# Formulaire validé
if(!empty($_POST)) { # Création, mise à jour, suppression ou aperçu

	if(!isset($_POST['catId'])) $_POST['catId']=array();
	# Titre par défaut si titre vide
	if(trim($_POST['title'])=='') $_POST['title'] = L_DEFAULT_NEW_ARTICLE_TITLE;
	# Si demande d'enregistrement en brouillon on ajoute la categorie draft à la liste et on retire la demande de validation
	if(isset($_POST['draft']) AND !in_array('draft',$_POST['catId'])) array_unshift($_POST['catId'], 'draft');
	# si aucune catégorie sélectionnée on place l'article dans la catégorie "non classé"
	if(sizeof($_POST['catId'])==1 AND $_POST['catId'][0]=='draft') $_POST['catId'][]='000';
	else $_POST['catId'] = array_filter($_POST['catId'], create_function('$a', 'return $a!="000";'));
	# Si demande de publication ou demande de validation, on supprime la catégorie draft si elle existe
	if((isset($_POST['update']) OR isset($_POST['publish']) OR isset($_POST['moderate'])) AND isset($_POST['catId'])) $_POST['catId'] = array_filter($_POST['catId'], create_function('$a', 'return $a!="draft";'));
	# Si profil PROFIL_WRITER on vérifie l'id du rédacteur connecté et celui de l'article
	if($_SESSION['profil']==PROFIL_WRITER AND isset($_POST['author']) AND $_SESSION['user']!=$_POST['author']) $_POST['author']=$_SESSION['user'];
	# Si profil PROFIL_WRITER on vérifie que l'article n'est pas celui d'un autre utilisateur
	if($_SESSION['profil']==PROFIL_WRITER AND isset($_POST['artId']) AND $_POST['artId']!='0000') {
		# On valide l'article
		if(($aFile = $plxAdmin->plxGlob_arts->query('/^'.$_POST['artId'].'.([home[draft|0-9,]*).'.$_SESSION['user'].'.(.+).xml$/')) == false) { # Article inexistant
			plxMsg::Error(L_ERR_UNKNOWN_ARTICLE);
			header('Location: index.php');
			exit;
		}
	}
	# Previsualisation d'un article
	if(!empty($_POST['preview'])) {
		$art=array();
		$art['title'] = trim($_POST['title']);
		$art['allow_com'] = $_POST['allow_com'];
		$art['template'] = basename($_POST['template']);
		$art['chapo'] = trim($_POST['chapo']);
		$art['content'] =  trim($_POST['content']);
		$art['tags'] = trim($_POST['tags']);
		$art['meta_description'] = $_POST['meta_description'];
		$art['meta_keywords'] =  $_POST['meta_keywords'];
		$art['title_htmltag'] =  $_POST['title_htmltag'];
		$art['filename'] = '';
		$art['numero'] = $_POST['artId'];
		$art['author'] = $_POST['author'];
		$art['categorie'] = '';
		if(!empty($_POST['catId'])) {
			$array=array();
			foreach($_POST['catId'] as $k => $v) {
				if($v!='draft') $array[]=$v;
			}
			$art['categorie']=implode(',',$array);
		}
		$art['date'] = $_POST['year'].$_POST['month'].$_POST['day'].substr(str_replace(':','',$_POST['time']),0,4);
		$art['nb_com'] = 0;
		if(trim($_POST['url']) == '')
			$art['url'] = plxUtils::title2url($_POST['title']);
		else
			$art['url'] = plxUtils::title2url($_POST['url']);
		if($art['url'] == '') $art['url'] = L_DEFAULT_NEW_ARTICLE_URL;

		# Hook Plugins
		eval($plxAdmin->plxPlugins->callHook('AdminArticlePreview'));

		$article[0] = $art;
		$_SESSION['preview'] = $article;
		header('Location: '.PLX_ROOT.'index.php?preview');
		exit;
	}
	# Suppression d'un article
	if(isset($_POST['delete'])) {
		$plxAdmin->delArticle($_POST['artId']);
		header('Location: index.php');
		exit;
	}
	# Mode création ou maj
	if(isset($_POST['update']) OR isset($_POST['publish']) OR isset($_POST['moderate']) OR isset($_POST['draft'])) {
		# Vérification de la validité de la date de publication
		if(!plxDate::checkDate($_POST['day'],$_POST['month'],$_POST['year'],$_POST['time']))
			plxMsg::Error(L_ERR_INVALID_PUBLISHING_DATE);
		else {
			$plxAdmin->editArticle($_POST,$_POST['artId']);
			header('Location: article.php?a='.$_POST['artId']);
			exit;
		}
	}
	# Ajout d'une catégorie
	if(isset($_POST['new_category'])) {
		# Ajout de la nouvelle catégorie
		$plxAdmin->editCategories($_POST);
		# On recharge la nouvelle liste
		$plxAdmin->getCategories(path('XMLFILE_CATEGORIES'));
		$_GET['a']=$_POST['artId'];
	}
	# Alimentation des variables
	$artId = $_POST['artId'];
	$title = trim($_POST['title']);
	$author = $_POST['author'];
	$catId = isset($_POST['catId'])?$_POST['catId']:array();
	$date['day'] = $_POST['day'];
	$date['month'] = $_POST['month'];
	$date['year'] = $_POST['year'];
	$date['time'] = $_POST['time'];
	$chapo = trim($_POST['chapo']);
	$content =  trim($_POST['content']);
	$tags = trim($_POST['tags']);
	$url = $_POST['url'];
	$allow_com = $_POST['allow_com'];
	$template = $_POST['template'];
	$meta_description = $_POST['meta_description'];
	$meta_keywords = $_POST['meta_keywords'];
	$title_htmltag = $_POST['title_htmltag'];
	# Hook Plugins
	eval($plxAdmin->plxPlugins->callHook('AdminArticlePostData'));
} elseif(!empty($_GET['a'])) { # On n'a rien validé, c'est pour l'édition d'un article
	# On va rechercher notre article
	if(($aFile = $plxAdmin->plxGlob_arts->query('/^'.$_GET['a'].'.(.+).xml$/')) == false) { # Article inexistant
		plxMsg::Error(L_ERR_UNKNOWN_ARTICLE);
		header('Location: index.php');
		exit;
	}
	# On parse et alimente nos variables
	$result = $plxAdmin->parseArticle(PLX_ROOT.$plxAdmin->aConf['racine_articles'].$aFile['0']);
	$title = trim($result['title']);
	$chapo = trim($result['chapo']);
	$content =  trim($result['content']);
	$tags =  trim($result['tags']);
	$author = $result['author'];
	$url = $result['url'];
	$date = plxDate::date2Array($result['date']);
	$catId = explode(',', $result['categorie']);
	$artId = $result['numero'];
	$allow_com = $result['allow_com'];
	$template = $result['template'];
	$meta_description=$result['meta_description'];
	$meta_keywords=$result['meta_keywords'];
	$title_htmltag = $result['title_htmltag'];

	if($author!=$_SESSION['user'] AND $_SESSION['profil']==PROFIL_WRITER) {
		plxMsg::Error(L_ERR_FORBIDDEN_ARTICLE);
		header('Location: index.php');
		exit;
	}
	# Hook Plugins
	eval($plxAdmin->plxPlugins->callHook('AdminArticleParseData'));

} else { # On a rien validé, c'est pour la création d'un article
	$title = plxUtils::strRevCheck(L_DEFAULT_NEW_ARTICLE_TITLE);
	$chapo = $url = '';
	$content = '';
	$tags = '';
	$author = $_SESSION['user'];
	$date = array ('year' => date('Y'),'month' => date('m'),'day' => date('d'),'time' => date('H:i'));
	$catId = array('draft');
	$artId = '0000';
	$allow_com = $plxAdmin->aConf['allow_com'];
	$template = 'article.php';
	$meta_description=$meta_keywords=$title_htmltag='';
	# Hook Plugins
	eval($plxAdmin->plxPlugins->callHook('AdminArticleInitData'));
}

# On inclut le header
include(dirname(__FILE__).'/top.php');

# On construit la liste des utilisateurs
foreach($plxAdmin->aUsers as $_userid => $_user) {
	if($_user['active'] AND !$_user['delete'] ) {
		if($_user['profil']==PROFIL_ADMIN)
			$_users[L_PROFIL_ADMIN][$_userid] = plxUtils::strCheck($_user['name']);
		elseif($_user['profil']==PROFIL_MANAGER)
			$_users[L_PROFIL_MANAGER][$_userid] = plxUtils::strCheck($_user['name']);
		elseif($_user['profil']==PROFIL_MODERATOR)
			$_users[L_PROFIL_MODERATOR][$_userid] = plxUtils::strCheck($_user['name']);
		elseif($_user['profil']==PROFIL_EDITOR)
			$_users[L_PROFIL_EDITOR][$_userid] = plxUtils::strCheck($_user['name']);
		else
			$_users[L_PROFIL_WRITER][$_userid] = plxUtils::strCheck($_user['name']);
	}
}

# On récupère les templates des articles
$files = plxGlob::getInstance(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$plxAdmin->aConf['style']);
if ($array = $files->query('/^article(-[a-z0-9-_]+)?.php$/')) {
	foreach($array as $k=>$v)
		$aTemplates[$v] = $v;
}
$cat_id='000';
?>

<form class="form" action="article.php" method="post" id="form_article">
  <div class="row-fluid">
    <div class="span9 widget">
      <div class="widget-title"><span class="icon"><i class="icon-edit icon-grey icon-shadowed"></i></span>
        <h5><?php echo (empty($_GET['a']))?L_MENU_NEW_ARTICLES:L_ARTICLE_EDITING; ?></h5>
        <a class="btn btn-mini pull-right" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BACK_TO_ARTICLES ?>" style="margin-right:10px" href="index.php"><i class="icon-undo icon-grey icon-shadowed-white"></i></a> </div>
      <div class="widget-content">
        <div id="form-article" class="form-horizontal">
          <?php eval($plxAdmin->plxPlugins->callHook('AdminArticleTop')) # Hook Plugins ?>
          <div class="control-group">
            <?php plxUtils::printInput('artId',$artId,'hidden'); ?>
            <label class="control-label" for="id_title"><?php echo L_ARTICLE_TITLE ?></label>
            <div class="controls">
              <?php plxUtils::printInput('title',plxUtils::strCheck($title),'text','42-255'); ?>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="id_chapo"><a class="btn btn-mini" id="toggler_chapo" href="javascript:void(0)" onclick="toggleDiv('toggle_chapo', 'toggler_chapo', '+','-')"><?php echo $chapo==''? '+':'-'; ?></a> <?php echo L_HEADLINE_FIELD ?> </label>
            <div class="controls" id="toggle_chapo"<?php echo $chapo!=''?'':' style="display:none"' ?>>
              <?php plxUtils::printArea('chapo',plxUtils::strCheck($chapo),35,8); ?>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="id_content"><?php echo L_CONTENT_FIELD ?></label>
            <div class="controls">
              <?php plxUtils::printArea('content',plxUtils::strCheck($content),35,18); ?>
            </div>
          </div>
          <?php if($artId!='' AND $artId!='0000') : ?>
          <?php $link = $plxAdmin->urlRewrite('index.php?article'.intval($artId).'/'.$url) ?>
          <div class="control-group">
            <label class="control-label" for="id_link"><?php echo L_LINK_FIELD ?></label>
            <div class="controls">
              <div class="input-append">
                <?php 
							echo '<input class="visible-desktop" id="id_link" onclick="this.select()" class="readonly" readonly="readonly" type="text" value="' . $link . '" />';						
                            echo '<a class="btn" target="_blank" href="'.$link.'" title="'.L_LINK_ACCESS.'">'.L_LINK_VIEW.'</a>'; 
							?>
              </div>
            </div>
          </div>
          <?php endif; ?>
          <?php eval($plxAdmin->plxPlugins->callHook('AdminArticleContent')) ?>
          <div class="text-right" style="margin-right: 20px;">
            <div class="btn-group"> <?php echo plxToken::getTokenPostMethod() ?>
              <input class="btn btn-responsive preview" type="submit" name="preview" onclick="this.form.target='_blank';return true;" value="<?php echo L_ARTICLE_PREVIEW_BUTTON ?>"/>
              <?php
						if($_SESSION['profil']>PROFIL_MODERATOR AND $plxAdmin->aConf['mod_art']) {
							if(in_array('draft', $catId)) { # brouillon
								if($artId!='0000') # nouvel article
									echo '<input class="btn btn-responsive delete" type="submit" name="delete" value="'.L_DELETE.'" onclick="Check=confirm(\''.L_ARTICLE_DELETE_CONFIRM.'\');if(Check==false) {return false;} else {this.form.target=\'_self\';return true;}" />';
								echo '<input class="btn btn-responsive" onclick="this.form.target=\'_self\';return true;" type="submit" name="draft" value="'.L_ARTICLE_DRAFT_BUTTON.'"/>';
								echo '<input class="btn btn-responsive btn-info submit" onclick="this.form.target=\'_self\';return true;" type="submit" name="moderate" value="'.L_ARTICLE_MODERATE_BUTTON.'"/>';
							} else {
								if(preg_match('/^_[0-9]{4}$/',$_GET['a'])) { # en attente
									echo '<input class="btn btn-responsive delete" type="submit" name="delete" value="'.L_DELETE.'" onclick="Check=confirm(\''.L_ARTICLE_DELETE_CONFIRM.'\');if(Check==false) {return false;} else {this.form.target=\'_self\';return true;}" />';
									echo '<input class="btn btn-responsive" onclick="this.form.target=\'_self\';return true;" type="submit" name="draft" value="'.L_ARTICLE_DRAFT_BUTTON.'"/>';
									echo '<input class="btn btn-responsive btn-info update" onclick="this.form.target=\'_self\';return true;" type="submit" name="update" value="' . L_ARTICLE_UPDATE_BUTTON . '"/>';
								}
							}
						} else {
							if($artId!='0000')
								echo '<input class="btn btn-responsive delete" type="submit" name="delete" value="'.L_DELETE.'" onclick="Check=confirm(\''.L_ARTICLE_DELETE_CONFIRM.'\');if(Check==false) {return false;} else {this.form.target=\'_self\';return true;}" />';
							if(in_array('draft', $catId)) {
								echo '<input class="btn btn-responsive" onclick="this.form.target=\'_self\';return true;" type="submit" name="draft" value="' . L_ARTICLE_DRAFT_BUTTON . '"/>';
								echo '<input class="btn btn-responsive btn-info submit" onclick="this.form.target=\'_self\';return true;" type="submit" name="publish" value="' . L_ARTICLE_PUBLISHING_BUTTON . '"/>';
							} else {
								if(preg_match('/^_[0-9]{4}$/',$_GET['a']))
									echo '<input class="btn btn-responsive btn-info submit" onclick="this.form.target=\'_self\';return true;" type="submit" name="publish" value="' . L_ARTICLE_PUBLISHING_BUTTON . '"/>';
								else
									echo '<input class="btn btn-responsive" onclick="this.form.target=\'_self\';return true;" type="submit" name="draft" value="' . L_ARTICLE_OFFLINE_BUTTON . '"/>';
								echo '<input class="btn btn-responsive btn-info update" onclick="this.form.target=\'_self\';return true;" type="submit" name="update" value="' . L_ARTICLE_UPDATE_BUTTON . '"/>';
							}
						}
					?>
            </div>
          </div>
        </div>
      </div>
      <!-- widget-content --> 
      
    </div>
    <!-- span9 widget -->
    
    <div class="span3 widget pull-right" id="extra-sidebar">
      <div class="widget-title"><span class="icon"><i class="icon-cogs icon-grey icon-shadowed"></i></span>
        <h5><?php echo L_ARTICLE_SIDEBAR_TITLE ?></h5>
      </div>
      <div class="widget-content">
        <div class="text-left" style="margin-top:10px;"> <?php echo L_ARTICLE_STATUS ?> <span class="pull-right">
          <?php
                    if(isset($_GET['a']) AND preg_match('/^_[0-9]{4}$/',$_GET['a']))
                        echo '<span class="label label-important">' . L_AWAITING . '</span>';
                    elseif(in_array('draft', $catId)) {
                        echo '<span class="label label-info">' . L_DRAFT . '</span>';
                        echo '<input type="hidden" name="catId[]" value="draft" />';
                    }
                    else
                        echo '<span class="label label-success">' . L_PUBLISHED . '</span>';
                    ?>
          </span> </div>
        <hr>
        <div class="control-group">
          <label for="id_author"><?php echo L_ARTICLE_LIST_AUTHORS ?></label>
          <div class="controls">
            <?php
                        if($_SESSION['profil'] < PROFIL_WRITER)
                            plxUtils::printSelect('author', $_users, $author);
                        else {
                            echo '<input type="hidden" id="id_author" name="author" value="'.$author.'" />';
                            echo '<strong>'.plxUtils::strCheck($plxAdmin->aUsers[$author]['name']).'</strong>';
                        }
                        ?>
          </div>
        </div>
        <?php if($plxAdmin->aConf['allow_com']=='1') : ?>
        <div class="control-group">
          <label for="id_allow_com"><?php echo L_ALLOW_COMMENTS ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('allow_com',array('1'=>L_YES,'0'=>L_NO),$allow_com); ?>
          </div>
        </div>
        <?php else: ?>
        <?php plxUtils::printInput('allow_com','0','hidden'); ?>
        <?php endif; ?>
        <div class="control-group">
          <label><?php echo L_ARTICLE_DATE ?></label>
          <div class="controls">
            <?php plxUtils::printInput('day',$date['day'],'text','2-2',false,'fld1'); ?>
            <?php plxUtils::printInput('month',$date['month'],'text','2-2',false,'fld1'); ?>
            <?php plxUtils::printInput('year',$date['year'],'text','2-4',false,'fld2'); ?>
            <div class="input-append">
              <?php plxUtils::printInput('time',$date['time'],'text','2-5',false,'fld2'); ?>
              <a class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_NOW; ?>" href="javascript:void(0)" onclick="dateNow(<?php echo date('Z') ?>); return false;"><i class="icon-calendar"></i></a> </div>
          </div>
        </div>
        <label>
        <?php echo L_ARTICLE_CATEGORIES ?>
        <div class="btn-group pull-right"> <a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Ajouter une catégorie" href="javascript:void(0)" id="toggler_cat_add" onclick="toggleDiv('toggle_cat_add', 'toggler_cat_add', '+','-');">+</a> <a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_DEFAULT_CATEGORY_BUTTON_TOGGLE ?>" href="javascript:void(0)" id="toggler_cat_chose" onclick="toggleDiv('toggle_cat_chose', 'toggler_cat_chose', '+','-');">-</a> <a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_DEFAULT_NEW_CATEGORY_BUTTON_INFO ?>"><i class="icon-bullhorn"></i></a> </div>
        </label>
        <?php if($_SESSION['profil'] < PROFIL_WRITER) : ?>
        <div class="well well-small" id="toggle_cat_add" style="display:none">
          <label for="id_new_catname"><?php echo L_NEW_CATEGORY ?></label>
          <?php plxUtils::printInput('new_catname','','text','17-50')	?>
          <input class="btn btn-mini new" type="submit" name="new_category" value="<?php echo L_CATEGORY_ADD_BUTTON ?>" />
        </div>
        <?php endif; ?>
        <div class="well well-small" id="toggle_cat_chose"> 
          <!-- dropdown menu links -->
          <?php
                        $selected = (is_array($catId) AND in_array('000', $catId)) ? ' checked="checked"' : '';
                        echo '<label class="checkbox" for="cat_unclassified"><input readonly="readonly" disabled="disabled" type="checkbox" id="cat_unclassified" name="catId[]"'.$selected.' value="000" />'. L_UNCLASSIFIED .'</label>';
                        $selected = (is_array($catId) AND in_array('home', $catId)) ? ' checked="checked"' : '';
                        echo '<label class="checkbox" for="cat_home"><input type="checkbox" id="cat_home" name="catId[]"'.$selected.' value="home" />'. L_CATEGORY_HOME_PAGE .'</label>';
                        foreach($plxAdmin->aCats as $cat_id => $cat_name) {
                            $selected = (is_array($catId) AND in_array($cat_id, $catId)) ? ' checked="checked"' : '';
							if($plxAdmin->aCats[$cat_id]['active'])
                                echo '<label class="checkbox" for="cat_'.$cat_id.'">';
                            else
                                echo '<label class="checkbox" for="cat_'.$cat_id.'"><em>';
								
                            echo '<input type="checkbox" id="cat_'.$cat_id.'" name="catId[]"'.$selected.' value="'.$cat_id.'" />';
                            
							if($plxAdmin->aCats[$cat_id]['active'])
                                echo ''.plxUtils::strCheck($cat_name['name']).'</label>';
                            else
                                echo ''.plxUtils::strCheck($cat_name['name']).'</em></label>';
                        }
					?>
        </div>
        <label>
        <?php echo L_ARTICLE_TAGS_FIELD ?>
        <div class="btn-group pull-right"> <a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Ajouter des mots-clés" href="javascript:void(0)" id="toggler_tag_add" onclick="toggleDiv('toggle_tag_add', 'toggler_tag_add', '+','-');">+</a> <a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_ARTICLE_TOGGLER_TITLE ?>" href="javascript:void(0)" id="toggler_tag_chose" onclick="toggleDiv('toggle_tag_chose', 'toggler_tag_chose', '+','-');">-</a> <a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_ARTICLE_TAGS_FIELD_TITLE ?>"><i class="icon-bullhorn"></i></a> </div>
        </label>
        <div class="well well-small" id="toggle_tag_add" style="display:none">
          <?php plxUtils::printInput('tags',$tags,'text','25-255'); ?>
        </div>
        <div class="well well-small" id="toggle_tag_chose">
          <?php
				if($plxAdmin->aTags) {
					$array=array();
					foreach($plxAdmin->aTags as $tag) {
						if($tags = array_map('trim', explode(',', $tag['tags']))) {
							foreach($tags as $tag) {
								if($tag!='') {
									$t = plxUtils::title2url($tag);
									if(!isset($array[$tag]))
										$array[$tag]=array('url'=>$t,'count'=>1);
									else
										$array[$tag]['count']++;
								}
							}
						}
					}
					array_multisort($array);
					foreach($array as $tagname => $tag) {
						echo '<a class="btn btn-mini" href="javascript:void(0)" onclick="insTag(\'tags\',\''.$tagname.'\')">'.plxUtils::strCheck($tagname).' <span class="label label-info">'.$tag['count'].'</span></a>';
					}
				}
				else echo L_NO_TAG;
				?>
        </div>
        <div class="control-group">
          <label for="id_url"><?php echo L_ARTICLE_URL_FIELD ?><a class="btn btn-mini pull-right" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_ARTICLE_URL_FIELD_TITLE ?>"><i class="icon-bullhorn"></i></a></label>
          <div class="controls">
            <?php plxUtils::printInput('url',$url,'text','27-255'); ?>
          </div>
        </div>
        <div class="control-group">
          <label for="id_template"><?php echo L_ARTICLE_TEMPLATE_FIELD ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('template', $aTemplates, $template); ?>
          </div>
        </div>
        <div class="control-group">
          <label for="id_title_htmltag"><?php echo L_ARTICLE_TITLE_HTMLTAG ?></label>
          <div class="controls">
            <?php plxUtils::printInput('title_htmltag',plxUtils::strCheck($title_htmltag),'text','27-255'); ?>
          </div>
        </div>
        <div class="control-group">
          <label for="id_meta_description"><?php echo L_ARTICLE_META_DESCRIPTION ?></label>
          <div class="controls">
            <?php plxUtils::printInput('meta_description',plxUtils::strCheck($meta_description),'text','27-255'); ?>
          </div>
        </div>
        <div class="control-group">
          <label for="id_meta_keywords"><?php echo L_ARTICLE_META_KEYWORDS ?></label>
          <div class="controls">
            <?php plxUtils::printInput('meta_keywords',plxUtils::strCheck($meta_keywords),'text','27-255'); ?>
          </div>
        </div>
        <?php eval($plxAdmin->plxPlugins->callHook('AdminArticleSidebar')) # Hook Plugins ?>
        <div class="btn-group"> <a class="btn btn-mini" href="comments.php?a=<?php echo $artId ?>&amp;page=1" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_ARTICLE_MANAGE_COMMENTS_TITLE ?>"><i class="icon-comments"></i></a> <a class="btn btn-mini" href="comment_new.php?a=<?php echo $artId ?>" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_ARTICLE_NEW_COMMENT_TITLE ?>"><i class="icon-comment-alt"></i></a> </div>
        <div class="btn-group">
          <?php
						if($_SESSION['profil']>PROFIL_MODERATOR AND $plxAdmin->aConf['mod_art']) {
							if(in_array('draft', $catId)) { # brouillon
								if($artId!='0000') # nouvel article
								echo '<input class="btn btn-mini btn-info submit" onclick="this.form.target=\'_self\';return true;" type="submit" name="moderate" value="'.L_ARTICLE_MODERATE_BUTTON.'"/>';
							} else {
								if(preg_match('/^_[0-9]{4}$/',$_GET['a'])) { # en attente
									echo '<input class="btn btn-mini btn-info update" onclick="this.form.target=\'_self\';return true;" type="submit" name="update" value="' . L_ARTICLE_UPDATE_BUTTON . '"/>';
								}
							}
						} else {
							if(in_array('draft', $catId)) {
								echo '<input class="btn btn-mini btn-info submit" onclick="this.form.target=\'_self\';return true;" type="submit" name="publish" value="' . L_ARTICLE_PUBLISHING_BUTTON . '"/>';
							} else {
								if(preg_match('/^_[0-9]{4}$/',$_GET['a']))
									echo '<input class="btn btn-mini btn-info submit" onclick="this.form.target=\'_self\';return true;" type="submit" name="publish" value="' . L_ARTICLE_PUBLISHING_BUTTON . '"/>';
								else
									echo '<input class="btn btn-mini btn-info update" onclick="this.form.target=\'_self\';return true;" type="submit" name="update" value="' . L_ARTICLE_UPDATE_BUTTON . '"/>';
							}
						}
					?>
        </div>
      </div>
      <!-- widget-content --> 
      
    </div>
    <!-- span3 widget --> 
    
  </div>
  <!-- row-fluid -->
  
</form>
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminArticleFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
