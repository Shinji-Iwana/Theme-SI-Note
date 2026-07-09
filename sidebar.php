<div id="side">
	<div class="st-aside">

		<?php if ( is_active_sidebar( 10 ) ) { ?>
			<div class="side-topad">
				<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 10 ) ) : else : //サイドバートップのみのウィジェット ?>
				<?php endif; ?>
			</div>
		<?php } ?>

		<?php get_template_part( 'newpost' ); //最近のエントリ ?>

		<?php if ( is_active_sidebar( 1 ) ) { ?>
			<div id="mybox">
				<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 1 ) ) : else : //サイドウイジェット読み込み ?>
				<?php endif; ?>
			</div>
		<?php } ?>
		<!-- 追尾広告エリア -->
		<div id="scrollad">
			<?php if ( is_active_sidebar( 2 ) ) { ?>
				<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 2 ) ) : else : ?>
				<?php endif; ?>
			<?php } ?>
		</div>
		<!-- 追尾広告エリアここまで -->
	</div>
</div>
<!-- /#side -->
