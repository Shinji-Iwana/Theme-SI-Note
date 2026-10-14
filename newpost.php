<?php
/**
 * サイドバーの新着一覧（si-note：見出し「新着記事」を表示）
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
	<?php // 見出しの順番（本文の h2・h3 の後に h4 が来ないよう、h2 にする。見た目は h4 のまま。PageSpeed Insights の heading-order） ?>
	<h2 class="side-heading">新着記事</h2>
	<?php if ( $st_query->have_posts() ): ?>
		<?php while ( $st_query->have_posts() ) : $st_query->the_post(); ?>
			<dl class="clearfix">
				<dt><a href="<?php the_permalink() ?>">
						<?php if ( has_post_thumbnail() ): // サムネイルを持っているときの処理 ?>
							<?php the_post_thumbnail( 'thumbnail', array( 'alt' => wp_strip_all_tags( get_the_title() ) ) ); // 画像だけのリンクに名前を付ける（PageSpeed Insights の link-name） ?>
						<?php else: // サムネイルを持っていないときの処理 ?>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/images/no-img.png' ); ?>" alt="no image" title="no image" width="100" height="100" />
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