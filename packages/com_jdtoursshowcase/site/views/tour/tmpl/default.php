<?php
/**
 
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright  Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jdtoursshowcase/assets/css/jdtoursshowcase.css');
$document->addStyleSheet(JUri::root() . 'media/com_jdtoursshowcase/css/list.css');
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_jdtoursshowcase');

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_jdtoursshowcase'))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}

$hits =	(!empty($this->item->hits)) ? $this->item->hits : 0 ;
$hits_one = $hits+1;
$JdtoursshowcaseHelpersJdtoursshowcase = new JdtoursshowcaseHelpersJdtoursshowcase();
$JdtoursshowcaseHelpersJdtoursshowcase->hits($hits_one,$this->item->id);
 
jimport('joomla.application.module.helper');
?>
<!-- Title -->
<div class="row">
	<div class="col-12 tour-title text-center py-5">
		<h3 class=""><?php echo  $this->item->title; ?></h3>
	</div>
</div>
<!-- End -->
<!-- Tour Type -->
<div class="row tour-details pb-5 text-center">
		<div class="col-sm-12 col-md-3 col-lg-3">
			<p class="m-0"><?php echo JText::_( 'Tour Type' ) ?></p>
			<p class="m-0"><strong><?php echo $this->item->tour_type; ?></strong></p>
		</div>
		<div class="col-sm-12 col-md-3 col-lg-3">
			<p class="m-0"><?php echo JText::_( 'Duration' ) ?></p>
			<p class="m-0"><strong><?php echo  $this->item->duration; ?></strong></p>
		</div>
		<div class="col-sm-12 col-md-3 col-lg-3">
			<p class="m-0"><?php echo JText::_( 'Price' ) ?> 
			
			<?php if($this->item->show_discount){ ?>
			/  <?php  if($this->item->discount_type=="percentage"){	
					echo '<span>'.$item->price_currency.$this->item->percentage. '%' .'</span>&nbsp;'.JTEXT::_('JOFF');													
				}elseif($this->item->discount_type=="fixed_amount"){
					echo '<span>Flat '.$item->price_currency.$this->item->fixed_amount.' </span>&nbsp;'.JTEXT::_('JOFF');
				} ?>
			<?php } ?>
			</p>
		<p class="m-0"><strong>
				<?php if($this->item->show_discount){
					if($this->item->discount_type=="percentage"){
						$percentage =  (($this->item->price*$this->item->percentage)/100);
						echo $item->price_currency.$price =  ($this->item->price - $percentage).'&nbsp;';

						echo '<strike>'.$item->price_currency.$this->item->price.'</strike> &nbsp;';

					}elseif($this->item->discount_type=="fixed_amount"){
						$fixed_amount = $this->item->fixed_amount;
						echo $item->price_currency.$price = ($this->item->price - $fixed_amount);

						echo '<strike>'.$item->price_currency.$this->item->price.'</strike>';

					}
				}else { echo '$'.$this->item->price; } ?></strong>
			</p>
		</div>
		<div class="col-sm-12 col-md-3 col-lg-3">
			<p class="m-0"><?php echo JText::_( 'Destination' ) ?></p>
			<p class="m-0"><strong><?php echo  $this->item->destination; ?></strong></p>
		</div>
</div>
<!-- End -->
<!-- Slider -->
<div class="row">
		<div class="col-12">
			<?php $galleis = json_decode($this->item->gallery); ?>
			<?php if(!empty($galleis)) { ?>
				<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
						<?php $i=1; foreach($galleis as $gallery) {?>
							<div class="carousel-item <?php echo ($i==1) ? 'active' : '' ?>">
								<img class="d-block w-100" src="<?php  echo $gallery->gallery_img; ?>" alt="First slide">
							</div>
						<?php $i++; } ?>
				<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<!-- End -->
<div class="row">
	<!-- Left Part -->
	<div class="col-sm-12 col-md-12 col-lg-<?php if($this->item->enable_sidebar){ echo '8';  }else{ echo "12"; } ?>">
		<?php if(!empty($this->item->tour_description)) { ?>
		<div class="tour-description pt-5">
			<?php echo  $this->item->tour_description; ?>
		</div>
		<?php }?>
	
		<?php if(!empty($this->item->facilities_description)) { ?>
		<div class="facilities-description pt-5">
			<?php echo  $this->item->facilities_description; ?>
		</div>
		<?php }?> 
	
		<?php if(!empty($this->item->schedule_description)) { ?>
		<div class="schedule-description pt-5">
			<?php echo  $this->item->schedule_description; ?>
		</div>
		<?php }?> 
		<div class="row facilities-features py-5">
			<?php $features = json_decode($this->item->facilities_features); ?>
			<?php  if(!empty($features)) { ?>
				<?php  foreach($features as $feature) {?>
					<?php  if($feature->show_img_feature == 1){ ?>
						<div class="col-sm-12 col-md-3 col-lg-3 mb-3">
						<i class="mr-2 <?php echo $feature->icon_class; ?>"></i><span><?php echo $feature->facilitiestooltip; ?></span>
						</div>
					<?php } ?>

					<?php  if($feature->show_img_feature == 2){ ?>
					<div class="col-sm-12 col-md-3 col-lg-3 mb-3">
						<img class="mr-2" src="<?php echo $feature->icon_img; ?>"/><span><?php echo $feature->facilitiestooltip; ?></span>
					</div>
					<?php } ?>

				<?php } ?>
			<?php } ?>
		</div>
		<div class="tour-schedule">
		<?php $items_accordions = json_decode($this->item->tour_schedule); ?>
		<?php if(!empty($items_accordions)) { ?>
			<div class="accordion" id="touraccordion">
				<?php $i=1; foreach($items_accordions as $items_accordion) { ?>
				<div class="accordion">
					<div class="accordion-header" id="headingOne">
						<ul class="accordion-list">
							<li class="<?php echo ($i != 1) ? 'collapsed' : ''?>" data-toggle="collapse" data-target="#One<?php echo "accorddin".$i;?>" aria-expanded="true" aria-controls="collapseOne">
								<?php echo  $items_accordion->schedule_title;?>
							</li>
						</ul>
					</div>

					<?php if(!empty($items_accordion->schedule_desc)) {?>	
						<div id="One<?php echo "accorddin".$i;?>" class="collapse <?php echo ($i==1) ? 'show' : ''?>" aria-labelledby="headingOne" data-parent="#touraccordion">
					<?php if(!empty($items_accordion->schedule_desc)) {?> 
						<div class="card-body">
							<?php echo  $items_accordion->schedule_desc;?>
						</div>
					<?php } ?>  
						</div>
					<?php } ?>  
				</div>
					
				<?php $i++; } ?>
			</div>
		<?php } ?>
		</div>
	</div>
	<!-- Left Part End -->
	<?php if($this->item->enable_sidebar){ ?>		
		<!-- Right Part -->
			<div class="col-sm-12 col-md-12 col-lg-4">
				<div class="module-position">
					<?php if(!empty($this->item->module_position)) { ?>
						<?php
							$outputModules = '{loadposition '.$this->item->module_position.'}';

							echo JHtml::_('content.prepare', $outputModules);
						?>
					<?php }else{ echo "Module is not here";} ?>	
				</div>
			</div>
		<!-- Right Part End -->
	<?php } ?>
</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_jdtoursshowcase&task=tour.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_JDTOURSSHOWCASE_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_jdtoursshowcase.tour.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_JDTOURSSHOWCASE_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_JDTOURSSHOWCASE_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_JDTOURSSHOWCASE_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_jdtoursshowcase&task=tour.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_JDTOURSSHOWCASE_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>