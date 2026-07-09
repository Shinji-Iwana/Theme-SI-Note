<?php
/**
 * サイドバーの新着一覧
 */
?>
<div class="kanren">
	<?php
	$newentrypost_no = 5; //表示したい記事数
	$args = array(
		'posts_per_page' => $newentrypost_no,
	);
	$st_query = new WP_Query( $args );
	?>
	<h4>新着記事</h4>
	<?php if ( $st_query->have_posts() ): ?>
		<?php while ( $st_query->have_posts() ) : $st_query->the_post(); ?>
			<dl class="clearfix">
				<dt><a href="<?php the_permalink() ?>">
						<?php if ( has_post_thumbnail() ): // サムネイルを持っているときの処理 ?>
							<?php the_post_thumbnail( 'thumbnail' ); ?>
						<?php else: // サムネイルを持っていないときの処理 ?>
							<img src="<?php echo get_template_directory_uri(); ?>/images/no-img.png" alt="no image" title="no image" width="100" height="100" />
						<?php endif; ?>
					</a></dt>
				<dd>
					<div class="blog_info">
						<p><?php the_time( 'Y/m/d' ); ?></p>
					</div>
					<p class="kanren-t"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>

					<div class="smanone2">
						<?php //the_excerpt(); //抜粋文 ?>
					</div>
				</dd>
			</dl>
		<?php endwhile; ?>
	<?php else: ?>
		<p>新しい記事はありません</p>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</div>