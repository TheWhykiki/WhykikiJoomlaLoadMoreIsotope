<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//var_dump($module);
?>

<?php if($filtering == 1): ?>
<div class="filterPosition">
    <div class="button-group button-group<?php echo $module->id; ?> filters-button-group<?php echo $module->id; ?>">
        <button class="button is-checked" data-filter="*"><?php echo JText::_("MOD_WHY_LOADMORE_MASONRY_SHOW_ALL"); ?></button>
		<?php foreach($categoriesList as $category): ?>
            <button class="button" data-filter=".filterValue<?php echo $category->id; ?>"><?php echo $category->title; ?></button>
		<?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<div class="grid<?php echo $module->id; ?> grid">

</div>




<?php // Article Spotlight Ende ?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        var $grid = jQuery('.grid<?php echo $module->id; ?>').isotope({
            itemSelector: '.element-item',
            layoutMode: 'fitRows'
        });

        // init Isotope

// filter functions
        var filterFns = {
// show if number is greater than 50
            numberGreaterThan50: function() {
                var number = jQuery(this).find('.number').text();
                return parseInt( number, 10 ) > 50;
            },
// show if name ends with -ium
            ium: function() {
                var name = jQuery(this).find('.name').text();
                return name.match( /ium$/ );
            }
        };
// bind filter button click
        jQuery('.filters-button-group<?php echo $module->id; ?>').on( 'click', 'button', function() {
            var filterValue = jQuery( this ).attr('data-filter');
// use filterFn if matches value
            filterValue = filterFns[ filterValue ] || filterValue;
            $grid.isotope({ filter: filterValue });

        });
// change is-checked class on buttons
        jQuery('.button-group<?php echo $module->id; ?>').each( function( i, buttonGroup ) {
            var $buttonGroup = jQuery( buttonGroup );
            $buttonGroup.on( 'click', 'button', function() {
                $buttonGroup.find('.is-checked').removeClass('is-checked');
                jQuery( this ).addClass('is-checked');
            });
        });

        (function(jQuery){
            jQuery.fn.loaddata = function(options) {// Settings
                var settings = jQuery.extend({
                    loading_gif_url	: '/modules/mod_articles_news_load_more/images/ajax-loader.gif', //url to loading gif
                    //end_record_text	: 'No more records found!', //no more records to load
                    data_url 		: '/modules/mod_articles_news_load_more_masonry/tmpl/ajax.php', //url to PHP page
                    moduleID: '<?php echo $moduleID; ?>',
                    menuItem: '<?php echo $menuItem; ?>',
                    start_page 		: 1 //initial page
                }, options);

                var el = this;
                loading  = false;
                end_record = false;
                contents(el, settings); //initial data load

				<?php if($loadingType == 1): ?>
                jQuery( ".loadMoreButton<?php echo $module->id; ?>" ).click(function() {
                    contents(el, settings); //load content chunk
                });
				<?php else: ?>


                jQuery(window).scroll(function() { //detact scroll
                    var scrollerTrigger = jQuery( ".scrollerTrigger<?php echo $module->id; ?>" );
                    var position = scrollerTrigger.offset();
                    var offsetTop = position.top;
                    var scrollerHeight = jQuery( '.blogInner' ).height();
                    console.log(scrollerHeight);
                    console.log('Trig ' + offsetTop);
                    var offsetter = parseInt(jQuery(window).scrollTop()) + parseInt(scrollerHeight*5);

                    console.log('Scroll ' + offsetter);

                    if( offsetter >= offsetTop){ //scrolled to bottom of the page
                        contents(el, settings); //load content chunk

                    }
                });
				<?php endif; ?>

            };
            //Ajax load function
            function contents(el, settings){
                var load_img = jQuery('<img/>').attr('src',settings.loading_gif_url).addClass('loading-image'); //create load image


                if(loading == false && end_record == false){
                    loading = true; //set loading flag on
                    $grid.append(load_img); //append loading image
                    jQuery.post( settings.data_url,
                        {
                            'page': settings.start_page,
                            'moduleID': settings.moduleID,
                            'menuItem': settings.menuItem
                        },

                        function(data){ //jQuery Ajax post
                            //console.log(data);
                            if(data.trim().length == 0){ //no more records


                                jQuery('.loadMoreButtonRow<?php echo $module->id; ?>').css('display', 'none');
                                jQuery('.endRecordRow<?php echo $module->id; ?>').css('display', 'block');
                                load_img.remove(); //remove loading img

                                end_record = true; //set end record flag on
                                if(end_record = true){

                                }
                                return; //exit
                            }
                            loading = false;  //set loading flag off
                            load_img.remove(); //remove loading img

                            var $content = jQuery(data)
                            $grid.append( $content )
                            // add and lay out newly appended items
                                .isotope( 'appended', $content );
                            var elems = $grid.isotope('getItemElements');
                            //console.log(elems);

                            settings.start_page ++; //page increment
                        })
                }
            }

        })(jQuery);

        jQuery(".grid<?php echo $module->id; ?>").loaddata();

    });
    
</script>

<?php if($loadingType == 1): ?>
    <div class="row row-fluid loadMoreButtonRow loadMoreButtonRow<?php echo $module->id; ?>">
        <div class="col-md-12 span12">
            <button class="loadMoreButton loadMoreButton<?php echo $module->id; ?>"><span><?php echo JText::_("MOD_WHY_LOADMORE_MASONRY_LOAD_RECORDS"); ?></span></button>
        </div>
    </div>
<?php else: ?>
    <div class="scrollerTrigger scrollerTrigger<?php echo $module->id; ?>" style="visibility: hidden;">Trigger</div>
<?php endif; ?>

<div class="row row-fluid endRecordRow endRecordRow<?php echo $module->id; ?>" style="display: none;">
    <div class="col-md-12">
		<?php echo JText::_("MOD_WHY_LOADMORE_MASONRY_NO_MORE_RECORS"); ?>
    </div>
</div>

