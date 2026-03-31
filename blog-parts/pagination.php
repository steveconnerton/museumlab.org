<div class="pagination">
  <?php
  global $wp_query;

  if ( $wp_query->max_num_pages > 1 ) {
    $big = 99999999;
    /*
    echo paginate_links(array(
      'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
      'format' => '/page/%#%',
      'total' => $wp_query->max_num_pages,
      'current' => max(1, get_query_var('paged')),
      'show_all' => false,
      'end_size' => 2,
      'mid_size' => 3,
      'prev_next' => true,
      'prev_text' => '<i class="fas fa-chevron-left"></i>',
      'next_text' => '<i class="fas fa-chevron-right"></i>',
      'type' => 'list'
    ));
    */
    oxo_pagination();
  }


  function oxo_pagination( $range = 5 ) {
    // $paged - number of the current page
    global $paged, $wp_query;
    // How much pages do we have?
    $max_page = $wp_query->max_num_pages;
    // We need the pagination only if there is more than 1 page
    if ( $max_page > 1 )
      if ( !$paged ) $paged = 1;

    echo "\n".'<ul>'."\n";
    // On the first page, don't put the First page link
    if ( $paged != 1 )
      echo '<li><a href='.get_pagenum_link(1).' class="extreme">'.__('&laquo').' </a></li>';

    // To the previous page
    echo '<li>';
    previous_posts_link('<i class="fas fa-chevron-left"></i> '); // «
    echo '</li>';

    // We need the sliding effect only if there are more pages than is the sliding range
    if ( $max_page > $range ) :
      // When closer to the beginning
      if ( $paged < $range ) :
        for ( $i = 1; $i <= ($range + 1); $i++ ) {
          $class = $i == $paged ? 'current' : '';
          echo '<li><a href="'.get_pagenum_link($i).'" class="paged-num '.$class.'"> '.$i.' </a></li>';
        }
      // When closer to the end
      elseif ( $paged >= ( $max_page - ceil($range/2)) ) :
        for ( $i = $max_page - $range; $i <= $max_page; $i++ ){
          $class = $i == $paged ? 'current' : '';
          echo '<li><a href="'.get_pagenum_link($i).'" class="paged-num '.$class.'"> '.$i.' </a></li>';
        }
      endif;
    // Somewhere in the middle
    elseif ( $paged >= $range && $paged < ( $max_page - ceil($range/2)) ) :
      for ( $i = ($paged - ceil($range/2)); $i <= ($paged + ceil($range/2)); $i++ ) {
        $class = $i == $paged ? 'current' : '';
        echo '<li><a href="'.get_pagenum_link($i).'" class="paged-num '.$class.'"> '.$i.' </a></li>';
      }
    // Less pages than the range, no sliding effect needed
    else :
      for ( $i = 1; $i <= $max_page; $i++ ) {
        $class = $i == $paged ? 'current' : '';
        echo '<li><a href="'.get_pagenum_link($i).'" class="paged-num '.$class.'"> '.$i.' </a></li>';
      }
    endif;

    // Next page
    echo '<li>';
    next_posts_link('<i class="fas fa-chevron-right"></i> '); // »
    echo '</li>';

    // On the last page, don't put the Last page link
    if ( $paged != $max_page )
      echo '<li><a href='.get_pagenum_link($max_page).' class="extreme"> '.__(' &raquo;').'</a></li>';

    echo "\n".'</ul>'."\n";
  }
  ?>

</div> <!-- post-pagination -->
