<?php
$class = $args['class'];
$events = get_sub_field('events');
$results = new WP_Query(array(
  'post_type' => 'tribe_events',
  'posts_per_page' => 4,
  'post__in' => $events,
  'order'          => 'ASC',
  'orderby'        => 'meta_value',
  'meta_key'  => '_EventStartDate',
  'meta_type' => 'DATETIME',
  'ignore_custom_sort' => true,
));
$wp_query = $results;
?>
<?php if ($results->have_posts()):
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
    <?php $i++; endwhile;
  wp_reset_postdata();
endif;
wp_reset_query(); ?>