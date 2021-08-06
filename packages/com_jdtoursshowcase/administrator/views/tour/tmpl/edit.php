<?php
/**
 
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright  Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

if( ( ( new JVersion() )::MAJOR_VERSION ) < 4 ) {
	JHtml::_('behavior.formvalidation'); // J3
} else {
	JHtml::_('behavior.formvalidator'); // J4
}

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_jdtoursshowcase/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'tour.cancel') {
			Joomla.submitform(task, document.getElementById('tour-form'));
		}
		else {
			
			if (task != 'tour.cancel' && document.formvalidator.isValid(document.getElementById('tour-form'))) {
				
				Joomla.submitform(task, document.getElementById('tour-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_jdtoursshowcase&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="tour-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_JDTOURSSHOWCASE_TITLE_TOUR', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<?php echo $this->form->renderField('title'); ?>
				<?php echo $this->form->renderField('alias'); ?>
				<?php echo $this->form->renderField('tour_type'); ?>
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

				<?php echo $this->form->renderField('created_by'); ?>
			
				<?php echo $this->form->renderField('modified_by'); ?>				
				<?php echo $this->form->renderField('tour_image'); ?>
				<?php echo $this->form->renderField('price'); ?>
				<?php echo $this->form->renderField('price_currency'); ?>
				<?php echo $this->form->renderField('price_postfix'); ?>
				<?php echo $this->form->renderField('show_discount'); ?>
				<?php echo $this->form->renderField('discount_type'); ?>
				<?php echo $this->form->renderField('percentage'); ?>
				<?php echo $this->form->renderField('fixed_amount'); ?>
				<?php echo $this->form->renderField('myspacer'); ?>
				<?php echo $this->form->renderField('feature'); ?>
			
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'deatils', JText::_('COM_JDTOURSSHOWCASE_DETAILPAGE_TAB_TITLE', true)); ?>
		
				<?php echo $this->form->renderField('duration'); ?>
				<?php echo $this->form->renderField('destination'); ?>
				<?php echo $this->form->renderField('gallery'); ?>
				<?php echo $this->form->renderField('tour_description'); ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'facilities', JText::_('COM_JDTOURSSHOWCASE_FACILITIES_SCHEDULE_TAB_TITLE', true)); ?>
				<?php echo $this->form->renderField('facilities_spacer'); ?>
				<?php echo $this->form->renderField('facilities_description'); ?>
				<?php echo $this->form->renderField('facilities_features'); ?>

				<?php echo $this->form->renderField('schedule_spacer'); ?>
 
				<?php echo $this->form->renderField('schedule_description'); ?>
				<?php echo $this->form->renderField('tour_schedule'); ?>

				<?php echo $this->form->renderField('enable_sidebar'); ?>
				<?php echo $this->form->renderField('module_position'); ?>


				<?php if ($this->state->params->get('save_history', 1)) : ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
				</div>
				<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTab'); ?>

		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
