<?php get_header(); ?>

<div id="content" class="clearfix">
	<div id="contentInner">
		<div class="st-main">

			<?php if( !is_front_page() ): ?>
				<!--ぱんくず -->
				<section id="breadcrumb">
				<ol itemscope itemtype="http://schema.org/BreadcrumbList">
					 <li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo home_url(); ?>" itemprop="item"><span itemprop="name">HOME</span></a> > <meta itemprop="position" content="1" /></li>
					<?php 
					$i = 2;
					foreach ( array_reverse( get_post_ancestors( $post->ID ) ) as $parid ) { ?>

						<li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo get_page_link( $parid ); ?>" title="<?php echo  get_the_title(); ?>" itemprop="item"> <span itemprop="name"><?php echo get_page( $parid )->post_title; ?></span></a> > <meta itemprop="position" content="<?php echo $i; ?>" /></li>
					<?php  $i++; } ?>
				</ol>
				</section>
				<!--/ ぱんくず -->
			<?php endif; ?>

			<div id="st-page" <?php post_class('post'); ?>>
			<article>
					<!--ループ開始 -->
					<?php if (have_posts()) : while (have_posts()) :
					the_post(); ?>
				<div class="article-header">
				<div class="header-top">
<div class="header-icon">
          <?php if ( has_post_thumbnail() ): // サムネイルを持っているときの処理 ?>
            <?php the_post_thumbnail( 'thumbnail' ); ?>
          <?php else: // サムネイルを持っていないときの処理 ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/no-img.png" alt="no image" title="no image" width="300" height="300" />
          <?php endif; ?>
  </div>
						<?php if(!is_front_page()){ ?>
				<div class="header-title">
					<h1 class="entry-title"><?php the_title(); //タイトル ?></h1>
		  </div>
					</div>
				</div>
						<?php } ?>

					<div class="mainbox">

							<div class="entry-content">
								<?php the_content(); //本文 ?>
							</div>

							<?php //ページ改
									$defaults = array(
									'before'           => '<p class="tuzukicenter"><span class="tuzuki">' . __( '', 'default' ),
									'after'            => '</span></p>',
									'link_before'      => '&gt;&ensp;',
									'link_after'       => '&ensp;',
									'next_or_number'   => 'next',
									'separator'        => ' ',
									'nextpagelink'     => __( '続きを読む', 'default' ),
									'previouspagelink' => __( '前のページへ', 'default' ),
									'pagelink'         => '%',
									'echo'             => 1
									);
									wp_link_pages( $defaults );
							?>

					</div>
				
					<?php if( is_front_page() ):
						get_template_part( 'sns-top' ); //トップ用ソーシャルボタン読み込み 
					else:
						get_template_part( 'sns' ); //ページ用ソーシャルボタン読み込み 
					endif; ?>

				<div class="blogbox">
					<p><span class="kdate">
						<?php if ( get_the_date() != get_the_modified_date() ) : //更新がある場合 ?>
							投稿日：<?php echo esc_html( get_the_date() ); ?>
							更新日：<time class="updated" datetime="<?php echo esc_attr( get_the_modified_date( DATE_ISO8601 ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time>
						<?php else: //更新がない場合 ?>
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
					comments_template(); //コメント
				} ?>

				<?php get_template_part( 'newpost-page' ); //最近のエントリ ?>

			</div>
			<!--/post-->

		</div><!-- /st-main -->
	</div>
	<!-- /#contentInner -->
	<?php get_sidebar(); ?>
</div>
<!--/#content -->
<?php get_footer(); ?>
