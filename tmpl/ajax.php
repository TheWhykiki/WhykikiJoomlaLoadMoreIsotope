<?php
define( '_JEXEC', 1 );
define('JPATH_BASE', '../../../');

require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );

$app = JFactory::getApplication('site');
$db = JFactory::getDbo();



$ordering = $app->input->post->get('ordering');
$ordering = str_replace("a.", "", $ordering);

$directionState = $app->input->post->get('direction');
if($directionState == 0){
	$direction = 'ASC';
}
if($directionState == 1){
	$direction = 'DESC';
}
$orderingDirection = $ordering." ".$direction;

$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
$categories = $_POST["catsString"];
$spotlight = $app->input->post->get('spotlight');
$baseLink = $_POST['baseLink'];
$linkTitles = $app->input->post->get('linkTitles');
$titleFlag = $app->input->post->get('titleFlag');
$imageFlag = $app->input->post->get('imageFlag');
$textLength = $app->input->post->get('textLength');
$readMoreStylePost = $_POST['readMoreStylePost'];
$readMoreText  = $app->input->post->get('readMoreText');
$textTrigger  = $app->input->post->get('textTrigger');
$dateTrigger  = $app->input->post->get('dateTrigger');
$dateFormat  = $app->input->post->get('dateFormat');

//throw HTTP error if page number is not valid
if(!is_numeric($page_number)){
	header('HTTP/1.1 500 Invalid page number!');
	exit;
}
$item_per_page = $app->input->post->get('count');

//get current starting point of records
$position = (($page_number-1) * $item_per_page);

//fetch records using page position and item per page.
$query = $db->getQuery(true);

$categories = 'catid IN ('.$categories.')';

// When loading with fields
//$query->select('a.*, b.*');
$query->select('a.*');
$query->from($db->quoteName('#__content', 'a'));
// When loading with fields
//$query->join('LEFT', $db->quoteName('#__fields_values', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.item_id') . ')');

$query->where($categories);
//$query->andWhere($db->quoteName('catid')." = ".$db->quote(2));
$query->order($orderingDirection);
$query->setLimit($item_per_page);
$db->setQuery($query,$position,$item_per_page);

$row = $db->loadAssocList();

?>



		<?php foreach ($row as $item): ?>
			<?php
			// Vars
			$images = json_decode($item['images']);
			$introImage = $images->image_intro;
			$introText = $item['introtext'];
			$date = $item['publish_up'];
			$date = date($dateFormat,strtotime($date));
			$slug = $item['id'] . '-' . $item['alias'];
			$link = $baseLink."/".$slug;

			?>



            <div class=" element-item  element-item<?php echo $item['id']; ?> filterValue<?php echo $item['catid']; ?> ">
	            <?php if($imageFlag == 1): ?>
		            <?php if($linkTitles == 1): ?>
                        <a class="" href="<?php echo $link; ?>">
		            <?php endif; ?>
                    <img class="blogImage blogImageLarger" src="/<?php echo $introImage; ?>" />
		            <?php if($linkTitles == 1): ?>
                        </a>
		            <?php endif; ?>
	            <?php endif; ?>
                <div class="blogInner">


                    <?php if($dateTrigger == 1): ?>
                    <p class="newsDate"><?php echo $date; ?></p>
                    <?php endif; ?>
                    <div class="blogItemText">

						<?php if($titleFlag == 1): ?>
							<?php if($linkTitles == 1): ?>
                                <h3 class="blogHeadlinePost">
                                    <a class="<?php echo $item['value']; ?>" href="<?php echo $link; ?>">
                                            <?php echo $item['title']; ?>
                                    </a>
                                </h3>
							<?php else: ?>
                                <h3 class="blogHeadlinePost">
									<?php echo $item['title'];?>
                                </h3>
							<?php endif; ?>
						<?php endif; ?>

						<?php if($textTrigger == 1): ?>
							<?php
							// Ausgabe Introtext gekürzt
							echo substr($introText,0, $textLength);
							?> ...
							<?php if($linkTitles == 1): ?>
                                <div class="postWeiter">
                                    <a href="<?php echo $link; ?>">
										<?php echo $readMoreText; ?> <?php echo $readMoreStylePost; ?>
                                    </a>
                                </div>
							<?php endif; ?>
						<?php endif; ?>
                    </div>
                </div>
            </div>

		<?php endforeach; ?>



