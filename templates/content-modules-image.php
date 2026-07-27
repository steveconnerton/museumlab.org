<!-- content-modules-image -->
<?php extract( $template_args ) ?>
<?php //var_dump( $template_args ) ?>
<?php 
	// Safely check $v2x first to prevent PHP undefined variable warnings
	$v2x_val = isset( $v2x ) && !empty( $v2x ) ? $v2x : false;

	$val = isset( $v ) && !empty( $v ) ? $v : 'image'; 
	if ( isset( $v ) && !empty( $v ) ) {
		$val2x = $v2x_val ? $v2x_val : $val; 
	} else {
		$val2x = 'image_2x'; 
	}

	$image_size = isset( $is ) && !empty( $is ) ? $is : false; 
	$image_size_2x = isset( $is_2x ) && !empty( $is_2x ) ? $is_2x : $image_size.'-2x'; 

	$class = isset( $c ) && !empty( $c ) ? 'class="'.$c.'"' : ''; 
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
<?php if( false == $image_size && false == $image_size_2x ): ?>
	<?php if( !empty( $image ) ): ?>
		<?php if( $ww ): ?>
			<<?php echo $ww; ?> <?php if( $wclass ){ echo 'class="'.$wclass.'"'; } ?>>
		<?php endif ?>
			<?php $bg_url = $image['url']; ?>
			<?php $bg_url_2x = !empty($image_2x['url']) ? $image_2x['url'] : $image['url']; ?>
			<?php if ( false === $val2x ): ?>
				<img <?php echo $class; ?> src="<?php echo esc_url( $bg_url ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" title="<?php echo esc_attr( $image['title'] ); ?>">
			<?php else: ?>	
				<img <?php echo $class; ?> src="<?php echo esc_url( $bg_url ); ?>" srcset="<?php echo esc_url( $bg_url_2x ); ?> 2x" alt="<?php echo esc_attr( $image['alt'] ); ?>" title="<?php echo esc_attr( $image['title'] ); ?>">
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
			<?php $bg_url = isset($image['sizes'][$image_size]) ? $image['sizes'][$image_size] : $image['url']; ?>
			<?php $bg_url_2x = isset($image['sizes'][$image_size_2x]) ? $image['sizes'][$image_size_2x] : $bg_url; ?>
			<img <?php echo $class; ?> src="<?php echo esc_url( $bg_url ); ?>" srcset="<?php echo esc_url( $bg_url_2x ); ?> 2x" alt="<?php echo esc_attr( $image['alt'] ); ?>" title="<?php echo esc_attr( $image['title'] ); ?>">
		<?php if( $ww ): ?>
			</<?php echo $ww; ?>>
		<?php endif; ?>
	<?php endif; ?>
<?php endif ?>