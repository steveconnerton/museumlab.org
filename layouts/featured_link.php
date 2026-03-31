<section class="featured-link">
  <div class="featured-link__wrapper">
    <?php get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => 'hero-bg')); ?>
    <div class="featured-link__content">
      <div class="featured-link__content-wrapper">
        <div class="container">
          <div class="featured-link__title <?php echo get_sub_field('text_alignment') ?>">
            <?php get_template_part_args('templates/content-modules-cta-button', array('v' => 'link', 'c' => '', 'w' => 'h2', 'wc' => '')); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
