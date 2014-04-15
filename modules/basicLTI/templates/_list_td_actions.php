<?php use_javascript('/sfEfBasicLTIPlugin/js/basiclti.js') ?>

<span>
	<?php echo $helper->linkToShow($basiclti, array(  'params' =>   array(  ),  'class_suffix' => 'show',  'label' => 'Show',)) ?>    |
</span>

<span class="edit">
	<?php echo $helper->linkToEdit($basiclti, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>    |
</span>

<span class="launch">
	<?php echo link_to(__('Launch', array(), 'global'), 'basicLTI/launch?id='.$basiclti->getId(), array('class' => 'basiclti_launch')) ?>     |
</span>

<span>
	<?php echo $helper->linkToDelete($basiclti, array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
</span>
