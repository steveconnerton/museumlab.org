<section class="section intro-content">
  <div class="container">
    <?php get_template_part_args('templates/content-modules-text', array('v' => 'heading', 't' => 'h1', 'tc' => 's-headline-title')); ?>
    <?php get_template_part_args('templates/content-modules-text', array('v' => 'intro_content', 't' => 'h2', 'w' => '', 'wc' => '')); ?>
    <?php get_template_part_args('templates/content-modules-text', array('v' => 'content', 'w' => 'div', 'wc' => 's-content _mid')); ?>
    <?php get_template_part_args('templates/content-modules-cta-button', array('v' => 'cta_button', 'c' => 'btn', 'w' => 'div', 'wc' => '')); ?>
    <?php get_template_part_args('templates/content-modules-cta-button', array('v' => 'cta_link', 'c' => 'more-link', 'w' => 'div', 'wc' => '')); ?>
    <?php if ($fact_sheet = get_sub_field('pdf')): ?>
      <div class="pdf-list">
        <?php get_template_part_args('templates/content-modules-text', array('v' => 'pdf_heading', 't' => 'p')); ?>
        <a href="<?php echo $fact_sheet['url']; ?>" download>
          <?php echo get_pdf_icon(); ?>
          <?php if ($name = $fact_sheet['title']): ?>
            <?php echo $name; ?>
          <?php else: ?>
            Fact Sheet
          <?php endif; ?>
        </a>
      </div>
    <?php endif; ?>

  </div>
</section>
