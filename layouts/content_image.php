<!-- content_image -->
<section class="section content-image content-image__<?php echo get_sub_field('image_position') ?>">
  <div class="container">
    <div class="item">
      <?php get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => 'ci', 'w' => 'div', 'wc' => 'item__image')); ?>
      <div class="item__content">
        <?php get_template_part_args('templates/content-modules-text', array('v' => 'heading', 't' => 'h2')); ?>
        <?php get_template_part_args('templates/content-modules-text', array('v' => 'content', 't' => 'p')); ?>
        <?php get_template_part('templates/details'); ?>
      </div>
    </div>
  </div>
</section>
