<?php extract( $template_args ) ?>
<?php //var_dump( $template_args ) ?>
<?php $ww = isset( $w ) && !empty( $w ) ? $w : false; ?>
<?php $wclass = isset( $wc ) && !empty( $wc ) ? $wc : ''; ?>
<?php $val = isset( $v ) && !empty( $v ) ? $v : 'heading'; ?>
<?php $tag = isset( $t ) && !empty( $t ) ? $t : false; ?>
<?php $tclass = isset( $tc ) && !empty( $tc ) ? $tc : ''; ?>
<?php $option = isset( $o ) && !empty( $o ) ? $o : false; ?>

<?php 
	if( 'o' == $option ){
		$heading = get_field( $val, 'option' );
	} 
	elseif( 'f' == $option ){
		$heading = get_field( $val );
	}
	else{
		$heading = get_sub_field( $val );
	}
?> 
<?php if( $heading ): ?> 
	<?php if( $ww ): ?>
		<<?php echo $ww; ?> <?php if( $wclass ){ echo 'class="'.$wclass.'"'; } ?>>
	<?php endif ?>
		<?php if ( $tag ): ?>
			<<?php echo $tag; ?> <?php if( $tclass ){ echo 'class="'.$tclass.'"'; } ?>>
		<?php endif ?>
		<?php echo $heading; ?>
		<?php if ( $tag ): ?>
			</<?php echo $tag; ?>>
		<?php endif ?>
	<?php if( $ww ): ?>
		</<?php echo $ww; ?>>
	<?php endif; ?>
<?php endif; ?>

