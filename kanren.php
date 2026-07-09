<?php
/**
 * 関連記事の一覧
 */
?>
<p class="point"><span class="point-in">関連記事</span></p>
<div class="kanren">
	<?php
	$kanrenpost_no = 5; //表示したい記事数
	$categories = get_the_category( $post->ID );
	$category_ID = array();
	foreach ( $categories as $category ):
		array_push( $category_ID, $category->cat_ID );
	endforeach;
	$args = array(
		'post__not_in' => array( $post->ID ),
		'posts_per_page' => $kanrenpost_no,
		'category__in' => $category_ID,
		'orderby' => 'rand', //ランダム表示
	);
	$st_query = new WP_Query( $args ); ?>
	<?php
	if ( $st_query->have_posts() ): ?>
		<?php
		while ( $st_query->have_posts() ) : $st_query->the_post(); ?>
			<dl class="clearfix">
				<dt><a href="<?php the_permalink() ?>">
						<?php if ( has_post_thumbnail() ): // サムネイルを持っているときの処理 ?>
							<?php the_post_thumbnail( 'thumbnail' ); ?>
						<?php else: // サムネイルを持っていないときの処理 ?>
							<img src="<?php echo get_template_directory_uri(); ?>/images/no-img.png" alt="no image" title="no image" width="100" height="100" />
						<?php endif; ?>
					</a></dt>
				<dd>
					<p class="kanren-t"><a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a></p>
<div class="blog_info">
					<p><i class="fa fa-clock-o"></i>
						<?php the_time( 'Y/m/d' ); ?>
						&nbsp;<span class="pcone">
							<i class="fa fa-folder-open-o" aria-hidden="true"></i>-<?php the_category( ', ' ) ?><br/>
							<?php the_tags( '<i class="fa fa-tags"></i>&nbsp;', ', ' ); ?>
          </span></p>
				</div>
					<div class="smanone2">
						<?php the_excerpt(); //抜粋文 ?>
					</div>
				</dd>
			</dl>
		<?php endwhile; ?>
	<?php else: ?>
		<p>関連記事はありませんでした</p>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</div>