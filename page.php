<?php
/**
 * 固定ページ（si-note）
 *
 * 親テーマの page.php との違い：タイトルの左にアイキャッチを置いた見出し（.article-header）
 */
get_header(); ?>

<div id="content" class="clearfix">
	<div id="contentInner">
		<div class="st-main">

			<?php st_breadcrumb(); // ぱんくず（inc/template-functions.php） ?>

			<div id="st-page" <?php post_class('post'); ?>>
			<article>
					<!--ループ開始 -->
					<?php if (have_posts()) : while (have_posts()) :
					the_post(); ?>

						<?php if(!is_front_page()){ ?>
							<div class="article-header">
								<div class="header-top">
									<?php get_template_part( 'template-parts/article-header-icon' ); ?>
									<div class="header-title">
										<h1 class="entry-title"><?php the_title(); // タイトル ?></h1>
									</div>
								</div>
							</div>
						<?php } ?>

					<div class="mainbox">

							<div class="entry-content">
								<?php the_content(); // 本文 ?>
							</div>

							<?php // ページ分割
									wp_link_pages( array(
										'before'           => '<p class="tuzukicenter"><span class="tuzuki">',
										'after'            => '</span></p>',
										'link_before'      => '&gt;&ensp;',
										'link_after'       => '&ensp;',
										'next_or_number'   => 'next',
										'separator'        => ' ',
										'nextpagelink'     => '続きを読む',
										'previouspagelink' => '前のページへ',
										'pagelink'         => '%',
										'echo'             => 1,
									) );
							?>

					</div>

					<?php if( is_front_page() ):
						get_template_part( 'sns-top' ); // トップ用ソーシャルボタン読み込み
					else:
						get_template_part( 'sns' ); // ページ用ソーシャルボタン読み込み
					endif; ?>

				<div class="blogbox">
					<p><span class="kdate">
						<?php if ( get_the_date() != get_the_modified_date() ) : // 更新がある場合 ?>
							投稿日：<?php echo esc_html( get_the_date() ); ?>
							更新日：<time class="updated" datetime="<?php echo esc_attr( get_the_modified_date( DATE_ISO8601 ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time>
						<?php else: // 更新がない場合 ?>
							投稿日：<time class="updated" datetime="<?php echo esc_attr( get_the_date( DATE_ISO8601 ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						<?php endif; ?>
					</span></p>
				</div>

				<p>執筆者：<?php the_author_posts_link(); ?></p>

				<?php endwhile; else: ?>
					<p>記事がありません</p>
				<?php endif; ?>
				<!--ループ終了 -->

			</article>

				<?php if ( comments_open() || get_comments_number() ) {
					comments_template(); // コメント（si-note では comments.php で表示しない）
				} ?>

			</div>
			<!--/post-->

		</div><!-- /st-main -->
	</div>
	<!-- /#contentInner -->
	<?php get_sidebar(); ?>
</div>
<!--/#content -->
<?php get_footer(); ?>
