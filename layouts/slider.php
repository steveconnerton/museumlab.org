<?php if (get_sub_field('slider')): ?>
  <section class="section section-slider">
    <div class="container">
      <div class="slider">
        <?php  while (has_sub_field('slider')): ?>
            <?php get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => 'slider', 'w' => 'div', 'wc' => 'slide')); ?>
        <?php endwhile; ?>
      </div>
    </div>
  </section>
<?php endif ?>
