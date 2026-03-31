<?php if ($author = get_sub_field('author')): ?>
  <div class="quote">
    <?php get_template_part_args('templates/content-modules-text', array('v' => 'quote', 't' => 'blockquote')); ?>
    <p class="quote__author">&mdash;<?php echo $author; ?></p>
  </div>
<?php endif ?>
