<?php /* SVN: $Id: admin_add.ctp 75833 2012-02-15 05:22:09Z boopathi_026ac09 $ */ ?>
<?php
    if(!empty($page)):
        ?>
        <div class="js-tabs">
        <ul>
            <li><span><?php echo $this->Html->link(__l('Preview'), '#preview'); ?></span></li>
            <li><span><?php echo $this->Html->link(__l('Change'), '#add'); ?></span></li>
        </ul>
        <div id="preview">
            <div class="page">
                <h2><?php echo $page['Page']['title']; ?></h2>
                <div class="entry">
                   <?php echo $page['Page']['content']; ?>
                </div>
            </div>
        </div>
        <?php
    endif;
?>
<div id="add">
    <div class="pages form">
        <?php echo $this->Form->create('Page', array('class' => 'normal'));?>
        <fieldset>
            <?php
                echo $this->Form->input('title', array('between' => '', 'label' =>__l('Page title')));
                echo $this->Form->input('content', array('type' => 'textarea', 'class' => 'js-editor', 'label' => __l('Body'), 'info' => __l('Available Variables: ##SITE_NAME##, ##SITE_URL##, ##ABOUT_US_URL##, ##CONTACT_US_URL##, ##FAQ_URL##')));                
                echo $this->Form->input('description_meta_tag',array('label' => __l('Description Meta Tag')));
                echo $this->Form->input('slug',array('label' => __l('Slug'),'info' => __l('When you create link for this page, url should be page/value of this field.')));
			?>
            <div class="submit-block clearfix">
            	<?php
					echo $this->Form->submit(__l('Add'), array('name' => 'data[Page][Add]'));
					echo $this->Form->submit(__l('Preview'), array('name' => 'data[Page][Preview]'));
				?>
            </div>
        </fieldset>
            <?php echo $this->Form->end();  ?>
    </div>
</div>
<?php
    if(!empty($page)):
    ?>
    </div> <!-- js-tabs end !>
    <?php
endif;
?>
