<?php
/*
Template Name: HTMLサイトマップ
*/
get_header(); ?>

<div id="content">
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
					<h1 class="entry-title">サイトマップ</h1>
		  </div>
	</div>
</div>
          <div class="mainbox">

            <div class="entry-content">
              <div class="html-sitemap">
<p>このページは、当サイト「現役システムエンジニアの備忘録」の全ページを一覧で確認できるサイトマップです。JavaScript・AWS などの学習カテゴリや、固定ページへのリンクをまとめています。</p>
                <h2>固定ページ</h2>
                <ul>
	<?php
		wp_list_pages(array(
			'title_li' => '',
			'exclude'  => get_the_ID()
		));
	?>
</ul>

<h2>カテゴリ & 記事一覧</h2>
<ul>
<?php
$shown_posts = array();

// 親カテゴリ（記事0でも表示）
$parent_categories = get_categories(array(
	'orderby'    => 'name',
	'order'      => 'ASC',
	'parent'     => 0,
	'hide_empty' => false,
));

foreach ($parent_categories as $parent) :

	// ★ 親カテゴリ配下の子カテゴリを取得
	$child_categories = get_categories(array(
		'orderby'    => 'name',
		'order'      => 'ASC',
		'parent'     => $parent->term_id,
		'hide_empty' => false,
	));

	// ★ 親カテゴリの総記事数を計算（子カテゴリの投稿数を合計）
	$parent_post_count = 0;
	foreach ($child_categories as $child) {
		$posts_in_child = get_posts(array(
			'category'    => $child->term_id,
			'numberposts' => -1,
		));
		$parent_post_count += count($posts_in_child);
	}
	// ★ 子カテゴリの総記事数が0なら親カテゴリを表示しない
if ($parent_post_count === 0) {
    continue;
}
?>
	<li class="sitemap-parent">

	<!-- ★ 親カテゴリ名 + 記事数 -->
	<span class="parent-title">
		<?php echo esc_html($parent->name); ?>
		<span class="post-count">（記事数：<?php echo $parent_post_count; ?>）</span>
	</span>

	<?php if ($child_categories) : ?>
		<ul class="child-category-list">
			<?php foreach ($child_categories as $child) : ?>

			<?php
			// 子カテゴリの記事一覧
			$posts = get_posts(array(
				'category'    => $child->term_id,
				'numberposts' => -1,
				'orderby'     => 'date',
				'order'       => 'DESC'
			));
			// ★ 子カテゴリに記事が0なら表示しない
if (count($posts) === 0) {
    continue;
}
			?>

			<li class="sitemap-category">
				<a href="#" class="sitemap-toggle">
					<?php echo esc_html($child->name); ?>
					<span class="post-count">（記事数：<?php echo count($posts); ?>）</span>
				</a>

				<?php if ($posts) : ?>
				<ul class="sitemap-posts">
				<?php foreach ($posts as $post) : ?>
					<?php
						if (in_array($post->ID, $shown_posts, true)) {
						continue;
						}
						$shown_posts[] = $post->ID;
					?>
					<li>
						<a href="<?php echo get_permalink($post->ID); ?>">
							<?php echo esc_html($post->post_title); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

			</li>

			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	</li>
<?php endforeach; ?>
</ul>

<h2>アーカイブ</h2>
<ul>
	<?php wp_get_archives('type=monthly'); ?>
</ul>

              </div>
            </div>

          </div><!-- /mainbox -->
				
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

        </article>
      </div><!-- /#st-page -->

    </div><!-- /st-main -->

  </div><!-- /#contentInner -->

  <?php get_sidebar(); ?>

</div><!-- /#content -->

<?php get_footer(); ?>
