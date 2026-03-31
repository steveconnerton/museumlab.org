<?php

/*
 * custom breadcrumb walker
 */
function get_breadcrumbs()
{
  global $post;
  echo '
    <div class="container">
        <ul class="breadcrumbs list-linked list-inline">';
  if (is_page()) {
    if ($post->post_parent) {
      $anc = get_post_ancestors($post->ID);

      //reverse order so top level page is first
      $anc = array_reverse($anc);
      $title = get_the_title();
      $output = '';
      foreach ($anc as $ancestor) {
        $output .= '<li><a href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li> <li class="separator"> <i class="zmdi zmdi-chevron-right"></i> </li>';
      }
      echo $output;
      echo '<li>' . $title . '</li>';
    } else {
      echo '<li>' . get_the_title() . '</li>';
    }
  } elseif (is_singular('product')) {
    $product_category_id = get_field('product_category', $post->ID);
    $post_parent = get_field('landing_page_id', 'product_category_' . $product_category_id);
    if ($post_parent->post_parent) {
      $anc = get_post_ancestors($post_parent->ID);

      //reverse order so top level page is first
      $anc = array_reverse($anc);
      $title = get_the_title();
      $output = '';
      foreach ($anc as $ancestor) {
        $output .= '<li><a href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li> <li class="separator"> <i class="zmdi zmdi-chevron-right"></i> </li>';
      }
      $output .= '<li><a href="' . get_permalink($post_parent) . '" title="' . get_the_title($post_parent) . '">' . get_the_title($post_parent) . '</a></li> <li class="separator"> <i class="zmdi zmdi-chevron-right"></i> </li>';
      echo $output;
      echo '<li>' . $title . '</li>';
    } else {
      echo '<li>' . get_the_title() . '</li>';
    }
  }
  echo '</ul>
    </div>
    ';
}
