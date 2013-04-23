<?php include(dirname(__FILE__).'/header.php'); ?>

    <div role="region" aria-labelledby="sectionType"><span id="sectionType" style="display:none"><?php $plxShow->catName(); ?> <?php $plxShow->catDescription(' : #cat_description'); ?></span>
    
        <div id="navUtils" class="clearfix">
            <div role="navigation" id="paginationLeftHanded" class="pagination pagination-mini pull-right">
               <?php $plxShow->paginationBootstrap('mini', 'none'); ?>&nbsp;
             </div>
                  
             <div role="navigation" id="paginationRightHanded" class="pagination pagination-mini pull-left">
               &nbsp;<?php $plxShow->paginationBootstrap('mini', 'none'); ?>&nbsp;
             </div>
        </div>

        <?php while($plxShow->plxMotor->plxRecord_arts->loop()): ?>
            
            <article role="article" class="well">
            
                <div class="clearfix">
                    <div class="pull-right">
                        <span class="date label label-info"><?php $plxShow->artDate('#num_day #month #num_year(4)'); ?></span>
                    </div>
                    <h2 class="pull-left"><?php $plxShow->artTitle('link'); ?></h2>
                </div>
                
                <div class="media well well-small">
                  <div class="media-body">
                    <div class="media-heading">Intro</div>
                        <em><?php $plxShow->artChapo('resume', false); ?></em>
                  </div>
                </div>
                
                <hr>
        
                <div class="resume article-content">
                     <?php $plxShow->artChapo('', true); ?>
                </div>
        
                <div><hr></div>
    
                <div class="author article-info pull-right">
                    <p>
                        <?php $plxShow->lang('WRITTEN_BY'); ?> <?php $plxShow->artAuthor(); ?>
                    </p>
                </div>
        
                <div class="category article-info pull-left">
                    <p>
                        <?php $plxShow->lang('CLASSIFIED_IN') ?> <?php $plxShow->artCat(); ?> avec, pour <?php $plxShow->lang('TAGS') ?> <?php $plxShow->artTags(); ?>
                    </p>
                </div>
                
                <br class="clearfix">&nbsp;
                
                <div class="number-of-comments article-info">
                    <?php echo $plxShow->artNbCom(); ?>
                </div>
        
            </article>
        
            <?php endwhile; ?>
    
        <div role="navigation" id="paginationBottom" class="pagination pagination-mini text-center" style="margin-top:10px;">
                <?php $plxShow->paginationBootstrap(); ?>
        </div>
    
        
        
	</div>
    
<?php include(dirname(__FILE__).'/footer.php'); ?>
