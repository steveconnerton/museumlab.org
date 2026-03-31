<?php
/**
 * Three Column Shaped Images with Link Layout
 */

?>
<section class="three-column-shaped-images-with-link">
  <div class="container">

      <div class="row justify-content-center align-items-center">
        <?php if (have_rows('items')): ?>
          <?php while (have_rows('items')): the_row(); ?>
            <div class="col-lg-4 col-md-4 col-sm-6">
              <?php
              $link = get_sub_field('link');
              if ($link):
              $link_url = $link['url'];
              $link_title = str_replace('&lt;','<',$link['title']);
              $link_title = str_replace('&gt;','>',$link_title);
              $link_target = $link['target'] ? $link['target'] : '_self';
              ?>
              <a class="tcsiwl-link" href="<?php echo esc_url($link_url); ?>"
                 target="<?php echo esc_attr($link_target); ?>">
                <?php endif; ?>
                <div class="tcsiwl-box-content">
                  <h4 class="tcsiwl-title"><?php echo $link_title ?></h4>
                </div>
                <?php if ($link): ?>
              </a>
            <?php endif; ?>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>

  </div>
</section>
