<?php
/**
 * 投稿に表示する広告
 */
?>
<div class="adbox">

	<?php if ( st_is_mobile() ) { //スマートフォンの時は300pxサイズを ?>
		<?php if ( is_active_sidebar( 4 ) && !is_404() ) { ?>
				<div style="text-align:center;">
				<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 4 ) ) : else : ?>
				<?php endif; ?>
				</div>
		<?php } ?>
	<?php } else {  //PCの時は336pxサイズを	?>
		<?php if ( is_active_sidebar( 3 ) && !is_404() ) { ?>
			<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 3 ) ) : else : ?>
			<?php endif; ?>
		<?php } ?>
	<?php } ?>

	<?php if ( st_is_mobile() ) { //スマホの場合 ?>

			<?php if ( is_active_sidebar( 9 ) ) { ?>
				<div class="adsbygoogle" style="padding-top:10px;">
					<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 9 ) ) : else : //スマホのみに表示する広告 ?>
					<?php endif; ?>
				</div>
			<?php } ?>

	<?php } else { //PCの場合 ?>

		<?php if ( st_is_mobile() ) { //スマートフォンの時は300pxサイズを ?>
			<?php if ( is_active_sidebar( 4 ) && !is_404() ) { ?>
				<div style="padding-top:10px;text-align:center;">
					<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 4 ) ) : else : ?>
					<?php endif; ?>
				</div>
			<?php } ?>
		<?php } else {  //PCの時は336pxサイズを	?>
			<?php if ( is_active_sidebar( 3 ) && !is_404() ) { ?>
				<div style="padding-top:10px;">
					<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 3 ) ) : else : ?>
					<?php endif; ?>
				</div>
			<?php } ?>
		<?php } ?>

	<?php } ?>
</div>
		