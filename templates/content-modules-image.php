<!-- content-modules-image -->
<?php extract( $template_args ) ?>
<?php //var_dump( $template_args ) ?>
<?php 
	$val = isset( $v ) && !empty( $v ) ? $v : 'image'; 
	if( isset( $v ) && !empty( $v ) ) {
		if ( false === $v2x ) {
			$val2x = false;
		}
		else{
			$val2x = isset( $v2x ) && !empty( $v2x ) ? $v2x : $val; 
		}
	} 
	else{
		$val2x = 'image_2x'; 
	}

	$image_size = isset( $is ) && !empty( $is ) ? $is : false; 
	$image_size_2x = isset( $is_2x ) && !empty( $is_2x ) ? $is_2x : $image_size.'-2x'; 

	$class = isset( $c ) && !empty( $c ) ? 'class="'.$c.'"' : ''; 

	// var_dump_pre( $val );
	// var_dump_pre( $val2x );
	// var_dump_pre( $image_size );
	// var_dump_pre( $image_size_2x );
?>
<?php $option = isset( $o ) && !empty( $o ) ? $o : false; ?>
<?php $ww = isset( $w ) && !empty( $w ) ? $w : false; ?>
<?php $wclass = isset( $wc ) && !empty( $wc ) ? $wc : ''; ?>
<?php 
	if( 'o' == $option ){
		$image = get_field( $val, 'option' );
		$image_2x = get_field( $val2x, 'option' );
	} 
	elseif( 'f' == $option ){
		$image = get_field( $val );
		$image_2x = get_field( $val2x );
	}
	else{
		$image = get_sub_field( $val );
		$image_2x = get_sub_field( $val2x );
	}
?>
<?php if( false == $is && false == $is_2x ): ?>
	<?php if( !empty( $image ) ): ?>
		<?php if( $ww ): ?>
			<<?php echo $ww; ?> <?php if( $wclass ){ echo 'class="'.$wclass.'"'; } ?>>
		<?php endif ?>
			<?php $bg_url = $image['url']; ?>
			<?php $bg_url_2x = $image['url']; ?>
			<?php if ( false === $val2x ): ?>
				<img <?php echo $class; ?> src="<?php echo $bg_url; ?>" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title'] ?>">
			<?php else: ?>	
				<img <?php echo $class; ?> src="<?php echo $bg_url; ?>" srcset="<?php echo $bg_url_2x; ?> 2x" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title'] ?>">
			<?php endif ?>
		<?php if( $ww ): ?>
			</<?php echo $ww; ?>>
		<?php endif; ?>
	<?php endif; ?>
<?php else: ?>	
	<?php if( !empty( $image ) ): ?>
		<?php if( $ww ): ?>
			<<?php echo $ww; ?> <?php if( $wclass ){ echo 'class="'.$wclass.'"'; } ?>>
		<?php endif ?>
			<?php $bg_url = $image['sizes'][$image_size]; ?>
			<?php $bg_url_2x = $image['sizes'][$image_size_2x]; ?>
			<img <?php echo $class; ?> src="<?php echo $bg_url; ?>" srcset="<?php echo $bg_url_2x; ?> 2x" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title'] ?>">
		<?php if( $ww ): ?>
			</<?php echo $ww; ?>>
		<?php endif; ?>
	<?php endif; ?>
<?php endif ?>