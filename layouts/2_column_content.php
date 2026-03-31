<?php $custom_class = get_sub_field('custom_css_class'); ?>
<section class="section two-columns <?php echo $custom_class ?>">
  <div class="container">
    <div class="two-columns__wrapper">
      <div class="two-columns__content">
        <?php if (have_rows('left_column')): ?>
          <?php while (have_rows('left_column')): ?>
            <?php the_row(); ?>
            <div class="two-columns__heading">
              <?php get_template_part_args('templates/content-modules-text', array('v' => 'heading', 'tc' => 'heading--1', 't' => 'h2', 'w' => 'div', 'wc' => 'title-wrapper')); ?>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
        <?php if (have_rows('right_column')): ?>
          <?php while (have_rows('right_column')): ?>
            <?php the_row(); ?>
            <div class="text-description">
              <?php get_template_part_args('templates/content-modules-text', array('v' => 'title', 't' => 'h3')); ?>
              <?php get_template_part_args('templates/content-modules-text', array('v' => 'content', 't' => 'div')); ?>
              <?php get_template_part_args('templates/content-modules-cta-button', array('v' => 'cta_button', 'c' => 'btn', 'w' => 'div', 'wc' => 'btn-holder')); ?>
              <?php get_template_part_args('templates/content-modules-cta-button', array('v' => 'cta_link', 'c' => 'more-link', 'w' => 'div', 'wc' => 'link-holder')); ?>

              <?php get_template_part('templates/quote'); ?>
              <?php get_template_part('templates/details'); ?>
              <?php get_template_part('templates/images'); ?>
              <?php get_template_part('templates/listicle'); ?>

            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
