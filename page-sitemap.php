<?php
/*
Template Name: HTMLサイトマップ
*/

/**
 * サイトマップ（固定ページ「サイトマップ」に割り当てる。ファイル名を変えると、割り当てが外れる）
 *
 * 固定ページ・カテゴリごとの記事・月別アーカイブの一覧。本文（the_content）は表示しない。
 * 子カテゴリの記事一覧の開閉は、functions.php（si_note_sitemap_toggle_script）の JavaScript で行う。
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
      itemtype="http://schema.org/ListItem"><a href="<?php echo esc_url( home_url() ); ?>" itemprop="item"><span itemprop="name">HOME</span></a> > <meta itemprop="position" content="1" /></li>
					<?php
					$i = 2;
					foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $parid ) { ?>

						<li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo esc_url( get_page_link( $parid ) ); ?>" title="<?php echo esc_attr( get_the_title( $parid ) ); ?>" itemprop="item"> <span itemprop="name"><?php echo esc_html( get_the_title( $parid ) ); ?></span></a> > <meta itemprop="position" content="<?php echo (int) $i; ?>" /></li>
					<?php $i++; } ?>
				</ol>
				</section>
				<!--/ ぱんくず -->
			<?php endif; ?>

      <div id="st-page" <?php post_class('post'); ?>>
        <article>
          <div class="article-header">
            <div class="header-top">
              <?php get_template_part( 'template-parts/article-header-icon' ); ?>
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
                  wp_list_pages( array(
                    'title_li' => '',
                    'exclude'  => get_queried_object_id(),
                  ) );
                  ?>
                </ul>

                <h2>カテゴリ & 記事一覧</h2>
                <ul>
                <?php
                // 同じ記事を、複数のカテゴリで重ねて表示しない
                $shown_posts = array();

                // 親カテゴリ（子カテゴリに記事がなければ表示しない）
                $parent_categories = get_categories( array(
                  'orderby'    => 'name',
                  'order'      => 'ASC',
                  'parent'     => 0,
                  'hide_empty' => false,
                ) );

                foreach ( $parent_categories as $parent ) :

                  $child_categories = get_categories( array(
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'parent'     => $parent->term_id,
                    'hide_empty' => false,
                  ) );

                  // 子カテゴリごとの記事（新しい順）と、親カテゴリの記事数（子カテゴリの記事数の合計）
                  $child_posts = array();
                  $parent_post_count = 0;
                  foreach ( $child_categories as $child ) {
                    $child_posts[ $child->term_id ] = get_posts( array(
                      'category'    => $child->term_id,
                      'numberposts' => -1,
                      'orderby'     => 'date',
                      'order'       => 'DESC',
                    ) );
                    $parent_post_count += count( $child_posts[ $child->term_id ] );
                  }

                  if ( $parent_post_count === 0 ) {
                    continue;
                  }
                ?>
                  <li class="sitemap-parent">

                    <span class="parent-title">
                      <?php echo esc_html( $parent->name ); ?>
                      <span class="post-count">（記事数：<?php echo (int) $parent_post_count; ?>）</span>
                    </span>

                    <ul class="child-category-list">
                      <?php foreach ( $child_categories as $child ) : ?>
                        <?php
                        $category_posts = $child_posts[ $child->term_id ];
                        if ( count( $category_posts ) === 0 ) {
                          continue;
                        }
                        ?>
                      <li class="sitemap-category">
                        <a href="#" class="sitemap-toggle">
                          <?php echo esc_html( $child->name ); ?>
                          <span class="post-count">（記事数：<?php echo count( $category_posts ); ?>）</span>
                        </a>

                        <ul class="sitemap-posts">
                          <?php foreach ( $category_posts as $category_post ) : ?>
                            <?php
                            if ( in_array( $category_post->ID, $shown_posts, true ) ) {
                              continue;
                            }
                            $shown_posts[] = $category_post->ID;
                            ?>
                          <li>
                            <a href="<?php echo esc_url( get_permalink( $category_post ) ); ?>">
                              <?php echo esc_html( get_the_title( $category_post ) ); ?>
                            </a>
                          </li>
                          <?php endforeach; ?>
                        </ul>
                      </li>
                      <?php endforeach; ?>
                    </ul>

                  </li>
                <?php endforeach; ?>
                </ul>

                <h2>アーカイブ</h2>
                <ul>
                  <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                </ul>

              </div>
            </div>

          </div><!-- /mainbox -->

					<?php if( is_front_page() ):
						get_template_part( 'sns-top' ); // トップ用ソーシャルボタン読み込み
					else:
						get_template_part( 'sns' ); // ページ用ソーシャルボタン読み込み
					endif; ?>

				<div class="blogbox">
					<p><span class="kdate">
						<?php $sitemap_page = get_queried_object(); ?>
						<?php if ( get_the_date( '', $sitemap_page ) != get_the_modified_date( '', $sitemap_page ) ) : // 更新がある場合 ?>
							投稿日：<?php echo esc_html( get_the_date( '', $sitemap_page ) ); ?>
							更新日：<time class="updated" datetime="<?php echo esc_attr( get_the_modified_date( DATE_ISO8601, $sitemap_page ) ); ?>"><?php echo esc_html( get_the_modified_date( '', $sitemap_page ) ); ?></time>
						<?php else: // 更新がない場合 ?>
							投稿日：<time class="updated" datetime="<?php echo esc_attr( get_the_date( DATE_ISO8601, $sitemap_page ) ); ?>"><?php echo esc_html( get_the_date( '', $sitemap_page ) ); ?></time>
						<?php endif; ?>
					</span></p>
				</div>

				<?php $sitemap_author = (int) ( $sitemap_page->post_author ?? 0 ); ?>
				<p>執筆者：<a href="<?php echo esc_url( get_author_posts_url( $sitemap_author ) ); ?>" rel="author"><?php echo esc_html( get_the_author_meta( 'display_name', $sitemap_author ) ); ?></a></p>

        </article>
      </div><!-- /#st-page -->

    </div><!-- /st-main -->

  </div><!-- /#contentInner -->

  <?php get_sidebar(); ?>

</div><!-- /#content -->

<?php get_footer(); ?>
