<?php
/**
 
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright  Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jdtoursshowcase/assets/css/jdtoursshowcase.css');
$document->addStyleSheet(JUri::root() . 'media/com_jdtoursshowcase/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_jdtoursshowcase');
$saveOrder = $listOrder == 'a.`ordering`';
$maxTours = 50;
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_jdtoursshowcase&task=tours.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'tourList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
$JdtoursshowcaseViewTours = new JdtoursshowcaseViewTours();
?>

<form action="<?php echo JRoute::_('index.php?option=com_jdtoursshowcase&view=tours'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

            <?php  $total = $JdtoursshowcaseViewTours->get_total();  ?>
				<?php if($total >= $maxTours) {
					 echo JFactory::getApplication() -> enqueueMessage(JText::sprintf('COM_JDTOURSSHOWCAS_TOUR_LIMIT_ERROR',
					$maxTours), 'error');	
			
				} ?>

				<?php if( ( ( new JVersion() )::MAJOR_VERSION ) < 4 ) { ?>
					<div class="alert jd-upgrade-pro">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<div class="content">
							<div class="content__inner">
								<?php echo JText::_('COM_JDTOURSSHOWCAS_TOUR_PRO_LINE'); ?>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<joomla-alert type="success" class="alert jd-upgrade-pro" close-text="Close" dismiss="true">
						<div class="content">
							<div class="content__inner">
								<?php echo JText::_('COM_JDTOURSSHOWCAS_TOUR_PRO_LINE'); ?>
							</div>
						</div>
					</joomla-alert>
				<?php } ?>

            <?php  echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

			<div class="clearfix"></div>
			<table class="table table-striped" id="tourList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
					<?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>
					<?php if (isset($this->items[0]->state)): ?>
						<th width="1%" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th>
					<?php endif; ?>

				
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_JDTOURSSHOWCASE_TOURS_TITLE', 'a.`title`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_JDTOURSSHOWCASE_TOURS_TOUR_TYPE', 'a.`tour_type`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_JDTOURSSHOWCASE_TOURS_CHECKED_OUT_TIME', 'a.`checked_out_time`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_JDTOURSSHOWCASE_TOURS_HITS', 'a.`hits`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_JDTOURSSHOWCASE_TOURS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
					
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_jdtoursshowcase');
					$canEdit    = $user->authorise('core.edit', 'com_jdtoursshowcase');
					$canCheckin = $user->authorise('core.manage', 'com_jdtoursshowcase');
					$canChange  = $user->authorise('core.edit.state', 'com_jdtoursshowcase');
					?>
					<tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = JText::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif; ?>
									<span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
										  title="<?php echo $disabledLabel ?>">
							<i class="icon-menu"></i>
						</span>
									<input type="text" style="display:none" name="order[]" size="5"
										   value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
								<?php else : ?>
									<span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<td class="hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (isset($this->items[0]->state)): ?>
							<td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'tours.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>

												<td>
				
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_jdtoursshowcase&task=tour.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->title); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->title); ?>
				<?php endif; ?>

				</td>				<td>

					<?php echo $item->tour_type; ?>
				</td>		
				<td>
					<?php  $strtotime_date=strtotime($item->created_on);  echo date("d-M-y", $strtotime_date); ?>
				</td>		
				<td>
					
					<span class="badge badge-info">
						<?php echo (!empty($item->hits)) ? $item->hits  :  0  ?>
					</span>					
					
				</td>
				<td>

				<?php echo $item->id; ?>
				</td>		
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
<script>
    window.toggleField = function (id, task, field) {

        var f = document.adminForm, i = 0, cbx, cb = f[ id ];

        if (!cb) return false;

        while (true) {
            cbx = f[ 'cb' + i ];

            if (!cbx) break;

            cbx.checked = false;
            i++;
        }

        var inputField   = document.createElement('input');

        inputField.type  = 'hidden';
        inputField.name  = 'field';
        inputField.value = field;
        f.appendChild(inputField);

        cb.checked = true;
        f.boxchecked.value = 1;
        window.submitform(task);

        return false;
    };
</script>