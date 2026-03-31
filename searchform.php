<?php
/**
 * Template for displaying search forms
 *
 * @package WordPress
 * @subpackage pittspress
 * @since 1.0
 * @version 1.0
 */

?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search News' ); ?>"
         value="<?php echo get_search_query(); ?>"/>
  <button type="submit" class="submit button primary-button" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search' ); ?>"><i
      class="fas fa-search"></i></button>
</form>
