<?php
/**
 * Include custom post types
 */
foreach (glob(dirname(__FILE__) . "/cpt/*.php") as $filename) {
  include $filename;
}

/**
 * Include custom taxonomies
 */
foreach (glob(dirname(__FILE__) . "/ct/*.php") as $filename) {
  include $filename;
}


