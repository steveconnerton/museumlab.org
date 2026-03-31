<?php

/**
 * Videos
 */
if (get_sub_field('videos')):
  wp_enqueue_script("fancybox-js", 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js', array("jquery"), false, true);
  wp_enqueue_style('fancybox-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css', array(), '3.3.5', 'all');
  ?>
  <section class="section section-videos">
    <div class="container">
      <?php get_template_part_args('templates/content-modules-text', array('v' => 'heading', 't' => 'h2', 'tc' => 'heading--2 mb-4')); ?>

      <div class="video-holder js-videos-grid">

        <?php $i = 0;
        while (has_sub_field('videos')): ?>
          <?php if (get_sub_field('video') && get_sub_field('image')): $i++; ?>
            <?php $video = get_sub_field('video'); ?>
            <a class="video fancybox" hidefocus="true"
               data-fancybox="gallery"
               href="<?php echo $video; ?>">
              <?php get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => 'video')); ?>
            </a>
          <?php endif; ?>
        <?php endwhile; ?>

      </div>
      <?php if ($i > 3): ?>
        <div class="text-center mt-5">
          <a class="btn js-load-more-videos btn--more" href="#">Load more videos</a>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif ?>
