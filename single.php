<?php
/**
 * 投稿（si-note）
 *
 * 親テーマの single.php との違い：
 * - タイトルの左にアイキャッチを置いた見出し（.article-header）
 * - 関連記事を、本文の下（SNSボタンの前）に移し、エックスサーバーの紹介の枠を加えた
 * 表示回数は functions.php（si_note_count_post_views）で数える。
 */
get_header(); ?>

<div id="content" class="clearfix">
	<div id="contentInner">

		<div class="st-main">

			<!-- ぱんくず -->
			<section id="breadcrumb">
			<ol itemscope itemtype="http://schema.org/BreadcrumbList">
					 <li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo esc_url( home_url() ); ?>" itemprop="item"><span itemprop="name">HOME</span></a> > <meta itemprop="position" content="1" /></li>
				<?php
				$i = 2;
				foreach ( st_breadcrumb_categories( st_first_category_id() ) as $catid ): ?>
					<li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo esc_url( get_category_link( $catid ) ); ?>" itemprop="item">
					<span itemprop="name"><?php echo esc_html( get_cat_name( $catid ) ); ?></span> </a> &gt;<meta itemprop="position" content="<?php echo (int) $i; ?>" /></li>
				<?php
				$i++;
				endforeach; ?>
			</ol>
			</section>
			<!--/ ぱんくず -->

			<div id="post-<?php the_ID(); ?>" <?php post_class('st-post'); ?>>
				<article>
					<!--ループ開始 -->
					<?php if (have_posts()) : while (have_posts()) :
					the_post(); ?>

					<?php // カテゴリ表示
					$categories = get_the_category();
					$separator = ' ';
					$output = ''; ?>
					<p class="st-catgroup">
					<?php
						if ( $categories ) {
							foreach( $categories as $category ) {
								$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="'
								. esc_attr( sprintf( "View all posts in %s", $category->name ) )
								. '" rel="category tag"><span class="catname st-catid' . (int) $category->cat_ID . '">' . esc_html( $category->cat_name ) . '</span></a>' . $separator;
							}
							echo trim( $output, $separator );
						} ?>
					</p>

					<div class="article-header">
						<div class="header-top">
							<?php get_template_part( 'template-parts/article-header-icon' ); ?>
							<div class="header-title">
								<h1 class="entry-title"><?php the_title(); // タイトル ?></h1>
							</div>
						</div>
						<div class="header-date">
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
						</div>
					</div>

					<div class="mainbox">
							<div class="entry-content">
								<?php the_content(); // 本文 ?>
							</div>
						<?php get_template_part( 'st-ad-on' ); // 広告 ?>

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

					</div><!-- .mainboxここまで -->

						<!--関連記事-->
						<?php get_template_part( 'kanren' ); ?>

					<?php get_template_part( 'parts/xserver-box' ); // エックスサーバーの紹介 ?>

						<?php get_template_part( 'sns' ); // ソーシャルボタン読み込み ?>

						<p class="tagst">
							<i class="fa fa-folder-open-o" aria-hidden="true"></i>-<?php the_category( ', ' ) ?><br/>
							<?php the_tags( '<i class="fa fa-tags"></i>-', ', ' ); ?>
						</p>

					<p>執筆者：<?php the_author_posts_link(); ?></p>


					<?php endwhile; else: ?>
						<p>記事がありません</p>
					<?php endif; ?>
					<!--ループ終了-->
			</article>

					<div class="st-aside">

						<?php if ( comments_open() || get_comments_number() ) {
							comments_template(); // コメント（si-note では comments.php で表示しない）
						} ?>

						<!--ページナビ-->
						<div class="p-navi clearfix">
							<dl>
								<?php
								$prev_post = get_previous_post();
								if ( !empty( $prev_post ) ): ?>
									<dt>PREV</dt>
									<dd>
										<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"><?php echo esc_html( get_the_title( $prev_post ) ); ?></a>
									</dd>
								<?php endif; ?>
								<?php
								$next_post = get_next_post();
								if ( !empty( $next_post ) ): ?>
									<dt>NEXT</dt>
									<dd>
										<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"><?php echo esc_html( get_the_title( $next_post ) ); ?></a>
									</dd>
								<?php endif; ?>
							</dl>
						</div>
					</div><!-- /st-aside -->

			</div>
			<!--/post-->

		</div><!-- /st-main -->
	</div>
	<!-- /#contentInner -->
	<?php get_sidebar(); ?>
</div>
<!--/#content -->
<?php get_footer(); ?>
