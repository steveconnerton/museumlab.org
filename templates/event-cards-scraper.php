<?php
$class = $args['class'];
$manual_events = get_sub_field('events');

// Check if we should use manual ACF events or fallback to Dynamic API events
if ($manual_events):
    // 1. ORIGINAL LOGIC: Display manually selected cards
    $results = new WP_Query(array(
      'post_type' => 'tribe_events',
      'posts_per_page' => 4,
      'post__in' => $manual_events,
      'order'          => 'ASC',
      'orderby'        => 'meta_value',
      'meta_key'  => '_EventStartDate',
      'meta_type' => 'DATETIME',
      'ignore_custom_sort' => true,
    ));

    if ($results->have_posts()):
      while ($results->have_posts()) : $results->the_post();  ?>
        <div class="<?php echo $class ?>">
          <div class="event">
            <a href="<?php echo get_permalink(); ?>" class="event__image">
              <?php the_post_thumbnail(); ?>
              <div class="event__detail">
                <div class="event__dates">
                  <span><?php echo tribe_get_start_date(null,false,'M'); ?> <?php echo tribe_get_start_date(null,false,'j'); ?></span>
                </div>
                <div class="event__title"><?php the_title(); ?></div>
              </div>
            </a>
          </div>
        </div>
      <?php endwhile;
      wp_reset_postdata();
    endif;

else: 
    // 2. DYNAMIC LOGIC: Pull from Tessitura API if nothing is selected manually
    // This uses your existing shortcode function to fetch the data
    echo do_shortcode('[ml_upcoming_events]'); 

endif;
?>