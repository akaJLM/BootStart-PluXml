<?php include(dirname(__FILE__) . '/header.php'); ?>


    <div role="region" aria-labelledby="sectionType"><span id="sectionType" style="display:none"><?php $plxShow->lang('ERROR') ?></span>
    
        
        <article role="article" class="well">
            
            <h1>
				<?php $plxShow->lang('ERROR') ?>
			</h1>

			<p>
				<?php $plxShow->erreurMessage(); ?>
			</p>
            
        </article>
        
    </div>

<?php include(dirname(__FILE__).'/footer.php'); ?>