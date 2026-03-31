</div> <!-- content-wrapper -->

<footer class="footer">

  <div class="container">
    <div class="footer__top">
      <img
        src="<?php bloginfo('template_url'); ?>/images/logo.png" class="navbar__logo"
        alt="<?php bloginfo('name') ?>" width="182" height="39">
    </div>
    <div class="row">

      <div class="col-md-6  mb-5 mb-md-0">
        <?php
        $args = array(
          'theme_location' => 'footer_nav',
          'menu' => 'top_nav',
          'container' => '',
          'menu_class' => 'navbar-nav footer-nav',
          'echo' => true,
          'items_wrap' => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
        );
        wp_nav_menu($args);
        ?>
      </div>

      <div class="col-md-3 mb-2 mb-md-0 ">
        <?php get_template_part_args('templates/content-modules-text', array('v' => 'location_address', 'o' => 'o', 't' => '')); ?>
        <ul class="footer__social col-md-3 d-flex">
          <?php
          $social_icons = get_field('social_icons', 'options');

          foreach ($social_icons as $social_icon) :
            echo '<li><a href="' . $social_icon['social_media_link'] . '" ><i class="fab fa-' . $social_icon['social_media_type'] . '"></i></a></li>';
          endforeach;
          ?>
        </ul>


      </div>
      <div class="col-md-3 footer__logo mb-2 mb-md-0 ">
        <?php get_template_part_args('templates/content-modules-text', array('v' => 'working_hours', 'o' => 'o', 't' => '')); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 footer__logos">
        <?php if (have_rows('members_logo', 'options')): ?>
          <?php while (have_rows('members_logo', 'options')): the_row(); ?>

            <?php if (have_rows('members', 'options')): ?>

              <?php while (have_rows('members', 'options')): the_row(); ?>

                <?php
                $link = get_sub_field('link');
                if ($link):
                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                  <a class="button"
                  href="<?php echo esc_url($link_url); ?>"
                  target="<?php echo esc_attr($link_target); ?>">
                <?php endif; ?>
                <?php
                $image = get_sub_field('choose_logo');
                if (!empty($image)): ?>
                  <img src="<?php echo esc_url($image['url']); ?>"
                       alt="<?php echo esc_attr($image['alt']); ?>"/>
                <?php endif; ?>
                <?php if ($link): ?>
                  </a>
                <?php endif; ?>
              <?php endwhile; ?>
            <?php endif; ?>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 footer__copyright mb-2 mb-md-0">
        <p>
          &copy; <?php echo date('Y'); ?><?php get_template_part_args('templates/content-modules-text', array('v' => 'copyright_content', 'o' => 'o', 't' => '')); ?>
          <a href="/privacy-policy" target="_blank">Privacy Policy</a>
        </p>
      </div>
	<!--	
      <div class="col-md-6 footer__copyright text-center mb-2 mb-md-0 text-md-right">
        <p>
          Another Creative Technology By <a href="https://creatotech.com/" target="_blank" style="margin-left: 10px;"><img src="https://creatotech.com/wp-content/uploads/2023/03/Creatotech-Color-White-02.png" width="120" ></a>
        </p>
      </div>-->
    </div>
  </div>
  <div class='back-to-top'>
    <div class="back-to-top__inner">
      <i class="fas fa-arrow-up"></i></div>
  </div>
</footer>
</div> <!-- site-wrapper -->

<?php wp_footer(); ?>
<?php if ($_SERVER['SERVER_NAME'] == 'museumlab.org'): ?>
<!--accessibi code-->
  <script> (function () {
      var s = document.createElement('script');
      var h = document.querySelector('head') || document.body;
      s.src = 'https://acsbapp.com/apps/app/dist/js/app.js';
      s.async = true;
      s.onload = function () {
        acsbJS.init({
          statementLink: '',
          footerHtml: '',
          hideMobile: false,
          hideTrigger: false,
          disableBgProcess: false,
          language: 'en',
          position: 'right',
          leadColor: '#146FF8',
          triggerColor: '#146FF8',
          triggerRadius: '50%',
          triggerPositionX: 'right',
          triggerPositionY: 'bottom',
          triggerIcon: 'people',
          triggerSize: 'bottom',
          triggerOffsetX: 20,
          triggerOffsetY: 20,
          mobile: {
            triggerSize: 'small',
            triggerPositionX: 'right',
            triggerPositionY: 'bottom',
            triggerOffsetX: 20,
            triggerOffsetY: 20,
            triggerRadius: '20'
          }
        });
      };
      h.appendChild(s);
    })(); </script>
<?php endif; ?>
</body>
</html>
