<?php get_header(); ?>

<div id="content" class="clearfix">
	<div id="contentInner">

		<div class="st-main">

			<!-- ぱんくず -->
			<section id="breadcrumb">
			<ol itemscope itemtype="http://schema.org/BreadcrumbList">
					 <li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo home_url(); ?>" itemprop="item"><span itemprop="name">HOME</span></a> > <meta itemprop="position" content="1" /></li>
				<?php 
					$postcat = get_the_category();
					$catid = $postcat[0]->cat_ID;
					$allcats = array( $catid );
						
				while ( !$catid == 0 ) {
					$mycat = get_category( $catid );
					$catid = $mycat->parent;
					array_push( $allcats, $catid );
				}
				array_pop( $allcats );
				$allcats = array_reverse( $allcats );
				$i = 2;
				foreach ( $allcats as $catid ): ?>
					<li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo get_category_link( $catid ); ?>" itemprop="item">
					<span itemprop="name"><?php echo esc_html( get_cat_name( $catid ) ); ?></span> </a> &gt;<meta itemprop="position" content="<?php echo $i; ?>" /></li> 
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
    the_post();

    // ▼ PV カウント（人気記事用）
    if (is_single()) {
        set_post_views(get_the_ID());
    }
?>
					
					<?php //カテゴリ表示
					if ( isset($GLOBALS['stdata60']) && $GLOBALS['stdata60'] === 'yes' ) {

					} else {

						$categories = get_the_category();
						$separator = ' ';
						$output = ''; ?>
					<p class="st-catgroup">
					<?php
							if ( $categories ) {
								foreach( $categories as $category ) {
									$output .= '<a href="' . get_category_link( $category->term_id ) . '" title="' 
									. esc_attr( sprintf( "View all posts in %s", $category->name ) ) 
									. '" rel="category tag"><span class="catname st-catid' . $category->cat_ID . '">' . $category->cat_name . '</span></a>' . $separator;
									}
								echo trim( $output, $separator );
							} ?>
					</p>
					<?php
					} //カテゴリ表示ここまで
					?>				
<div class="article-header">
	  <div class="header-top">
	<div class="header-icon">
          <?php if ( has_post_thumbnail() ): // サムネイルを持っているときの処理 ?>
            <?php the_post_thumbnail( 'thumbnail' ); ?>
          <?php else: // サムネイルを持っていないときの処理 ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/no-img.png" alt="no image" title="no image" width="300" height="300" />
          <?php endif; ?>
  </div>
	<div class="header-title">
					<h1 class="entry-title"><?php the_title(); //タイトル ?></h1>
		  </div>
	</div>
	<div class="header-date">
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
	</div>
</div>
					<div class="mainbox">
							<div class="entry-content">
								<?php the_content(); //本文 ?>
							</div>
						<?php get_template_part( 'st-ad-on' ); //広告 ?>

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

					</div><!-- .mainboxここまで -->
					
						<!--関連記事-->
						<?php get_template_part( 'kanren' ); ?>

					<div class="xserver-box">
						<p>
							このブログは Xserver 上の WordPress で運用しています。<br/>
							表示速度が速く、安定しているのでブログ初心者にもおすすめです。
						</p>
						<p>
							→ <a href="https://px.a8.net/svt/ejp?a8mat=4AZL0J+A5K67M+CO4+61JSI" rel="nofollow">レンタルサーバー エックスサーバー</a>
							<img border="0" width="1" height="1" src="https://www18.a8.net/0.gif?a8mat=4AZL0J+A5K67M+CO4+61JSI" alt="">
						</p>
					</div>
						<?php get_template_part( 'sns' ); //ソーシャルボタン読み込み ?>

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
							comments_template(); //コメント
						} ?>

						<!--ページナビ-->
						<div class="p-navi clearfix">
							<dl>
								<?php
								$prev_post = get_previous_post();
								if ( !empty( $prev_post ) ): ?>
									<dt>PREV</dt>
									<dd>
										<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"><?php echo $prev_post->post_title; ?></a>
									</dd>
								<?php endif; ?>
								<?php
								$next_post = get_next_post();
								if ( !empty( $next_post ) ): ?>
									<dt>NEXT</dt>
									<dd>
										<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"><?php echo $next_post->post_title; ?></a>
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
