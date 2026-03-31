<?php if ($images = get_sub_field('listicle')): ?>
  <div class="listicle">
    <?php if (have_rows('listicle')): ?>
      <?php while (have_rows('listicle')): the_row(); ?>
        <div class="mb-3">
          <?php get_template_part_args('templates/content-modules-text', array('v' => 'title', 't' => 'h4')); ?>
          <?php get_template_part_args('templates/content-modules-text', array('v' => 'content', 't' => 'div')); ?>
          <?php get_template_part_args('templates/content-modules-cta-button', array('v' => 'cta_button', 'c' => 'btn', 'w' => '','wc'=>'')); ?>
        </div>
      <?php endwhile; ?>
    <?php endif ?>
  </div>
<?php endif; ?>
