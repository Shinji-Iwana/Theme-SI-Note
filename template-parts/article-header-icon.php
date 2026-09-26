<?php
/**
 * 記事・固定ページの見出しの左のアイキャッチ（アイキャッチがなければ、親テーマの no-img.png）
 */
?>
	<div class="header-icon">
		<?php if ( has_post_thumbnail() ): ?>
			<?php the_post_thumbnail( 'thumbnail' ); ?>
		<?php else: ?>
			<img src="<?php echo esc_url( get_template_directory_uri() . '/images/no-img.png' ); ?>" alt="no image" title="no image" width="300" height="300" />
		<?php endif; ?>
	</div>
