<?php
/**
 * Include custom taxonomies
 */
foreach (glob(dirname(__FILE__) . "/widgets/*.php") as $filename) {
  include $filename;
}
?>
