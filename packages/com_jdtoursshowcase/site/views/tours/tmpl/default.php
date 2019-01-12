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
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jdtoursshowcase/assets/css/jdtoursshowcase.css');
$document->addStyleSheet(JUri::root() . 'media/com_jdtoursshowcase/css/list.css');
$document->addStyleSheet(JUri::root() . 'media/com_jdtoursshowcase/css/jdgrid.css');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_jdtoursshowcase') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'tourform.xml');
$canEdit    = $user->authorise('core.edit', 'com_jdtoursshowcase') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'tourform.xml');
$canCheckin = $user->authorise('core.manage', 'com_jdtoursshowcase');
$canChange  = $user->authorise('core.edit.state', 'com_jdtoursshowcase');
$canDelete  = $user->authorise('core.delete', 'com_jdtoursshowcase');
$db = JFactory::getDbo();


?>

<div class="row">
	<?php foreach ($this->items as $i => $item) :  ?>
		<div class="col-lg-4 d-md-flex mb-4 jd-tour-item <?php echo ($i==0) ? 'firstItem': ''; ?><?php echo ($i==array_key_last($this->items))? 'LastItem': '';  ?>">
			<div class="tour-wrap">
				<div class="tour-view-img">
						<?php if($item->tour_image){ ?>
							<a href="<?php echo JRoute::_('index.php?option=com_jdtoursshowcase&view=tour&id='.(int) $item->id); ?>">	<img src="<?php echo $item->tour_image; ?>" alt="<?php echo $item->title;?>" class="card-img-top img-fluid">
							</a>
						<?php } ?>
					<?php if($item->show_discount){ ?>
						<div class="tour-discount">
							<?php
								if($item->show_discount){
									if($item->discount_type=="percentage"){	
										echo '<div class="off_percentage"><span> '.$item->price_currency.abs($item->percentage). '%' .'</span> </br>Off' . '</div>';	
										
									}elseif($item->discount_type=="fixed_amount"){
										echo '<div class="off_fixed_amount"> <span>Flat '.$item->price_currency .abs($item->fixed_amount).' </span></br>Off' .'</div>';
									}
								}
							?>
						</div>
					<?php  } ?> 
				</div>
				<div class="tour-body text-center">
					<div class="tour-title">
						<a href="<?php echo JRoute::_('index.php?option=com_jdtoursshowcase&view=tour&id='.(int) $item->id); ?>" target="1">
							<h5><?php echo $this->escape($item->title); ?></h5>
						</a>
					</div>
					<div class="tour-sub-title">
						<span class="text-muted"><?php echo $item->tour_type; ?></span>
					</div>
						<div class="tour-show-discount">
							<strong>
								<?php
									if($item->show_discount){
										if($item->discount_type=="percentage"){
											$percentage =  (($item->price*abs($item->percentage))/100);
											echo $item->price_currency.$price =  ($item->price - $percentage);
											
										}elseif($item->discount_type=="fixed_amount"){
											$fixed_amount = $item->fixed_amount;
											echo $item->price_currency.$price = ($item->price - abs($item->fixed_amount));
										}
									}
								?>
							</strong> 
							<span><?php  if($item->show_discount){ ?>   <del>$<?php echo  $item->price; ?></del> <?php }  ?>
							<?php  if(!$item->show_discount){ ?><?php echo  $item->price_currency; ?> <?php echo  $item->price; ?><br> <?php }  ?></span>
							<p class="tour-person">
							<?php if(!empty($item->price_postfix)) { ?>
									<span class="text-muted"><?php echo $item->price_postfix; ?></span>
								<?php } ?>
							</p>
						</div>
						<?php $features = json_decode($item->feature);
							if(!empty($features)){
							 	foreach($features as $feature){ ?>
									<div class="tour-showcase-icon">
										<?php  if($feature->show_img_feature == 1){ ?>
											<i class="<?php echo $feature->icon_class; ?>"  data-toggle="tooltip" data-placement="top" title="<?php echo $feature->tool_tip; ?>" aria-hidden="true"></i>
										<?php } ?>
										<?php  if($feature->show_img_feature == 2){ ?>
											<img src="<?php echo $feature->icon_img; ?>"  data-toggle="tooltip" data-placement="top" title="<?php echo $feature->tool_tip; ?>" aria-hidden="true"/>
										<?php } ?>
									</div>
								<?php } ?>
							<?php } ?>
						<a href="<?php echo JRoute::_('index.php?option=com_jdtoursshowcase&view=tour&id='.(int) $item->id); ?>" class="tour-showcase-see-more">
							See More <i class="fa fa-angle-right pl-2" style="color: #ff2424;" aria-hidden="true"></i>
						</a>
				</div>
			</div>
		</div>
		
	
			<?php $canEdit = $user->authorise('core.edit', 'com_jdtoursshowcase'); ?>

			<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_jdtoursshowcase')): ?>
				<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
			<?php endif; ?>

	<?php endforeach; ?>
	<div class="col-12">
			<?php //echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?> <?php echo $this->pagination->getListFooter(); ?>
	</div>
