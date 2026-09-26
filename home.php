<?php
/**
 * トップページ（si-note）：サイト紹介・学習カテゴリ・人気記事・記事一覧
 */

/*
 * 学習カテゴリのカード。show を true にしたものだけ表示する
 * （記事がそろったカテゴリから表示する）
 */
$si_note_cards = array(
	array( 'show' => true,  'title' => 'AWS',          'text' => 'クラウドの基本から実践までを学ぶ',                 'url' => '/category/aws/',        'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_195015.jpg' ),
	array( 'show' => false, 'title' => 'Linux',        'text' => '基礎からコマンド操作・サーバー管理まで解説',       'url' => '/category/linux/',      'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_194620.jpg' ),
	array( 'show' => false, 'title' => 'Salesforce',   'text' => '基礎からCRM活用・業務効率化まで解説',               'url' => '/category/salesforce/', 'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_194151.jpg' ),
	array( 'show' => false, 'title' => 'Apex',         'text' => '基礎からSalesforce開発・自動処理まで解説',          'url' => '/category/apex/',       'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_194151.jpg' ),
	array( 'show' => false, 'title' => 'Java',         'text' => '基礎からオブジェクト指向・実践的な開発まで解説',    'url' => '/category/java/',       'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_193537.jpg' ),
	array( 'show' => false, 'title' => 'Phyton',       'text' => '基礎からデータ分析・自動化まで解説',                'url' => '/category/phyton/',     'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_193703.jpg' ),
	array( 'show' => true,  'title' => 'JavaScript',   'text' => '基礎からDOM操作・非同期処理まで解説',               'url' => '/js.html',              'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_194927.jpg' ),
	array( 'show' => false, 'title' => 'HTML',         'text' => '基礎からタグ構造・ページ作成まで解説',              'url' => '/category/html/',       'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_194739.jpg' ),
	array( 'show' => false, 'title' => 'PHP',          'text' => '基礎からフォーム処理・Web開発まで解説',             'url' => '/category/php/',        'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_194819.jpg' ),
	array( 'show' => false, 'title' => 'Excel',        'text' => '表計算の基本から関数・データ分析まで解説',          'url' => '/category/excel/',      'icon' => '/wp-content/uploads/2026/03/Copilot_20260325_091054.jpg' ),
	array( 'show' => false, 'title' => 'VBA',          'text' => '基礎からExcel自動化・業務効率化まで解説',           'url' => '/category/vba/',        'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_193840.jpg' ),
	array( 'show' => false, 'title' => 'ネットワーク', 'text' => '基礎から通信の仕組み・接続技術まで解説',            'url' => '/category/network/',    'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_194447.jpg', 'alt' => 'Network' ),
	array( 'show' => false, 'title' => 'データベース', 'text' => '基礎からSQL操作・データ管理まで解説',               'url' => '/category/db/',         'icon' => '/wp-content/uploads/2026/03/Copilot_20260322_194401.jpg', 'alt' => 'Database' ),
);

get_header(); ?>

<div id="content" class="clearfix">
  <div id="contentInner">
    <div class="st-main">
      <article>
        <div class="st-aside">

          <!-- サイト紹介 -->
          <section class="top-intro">
            <h2>現役システムエンジニアの備忘録</h2>
            <p>技術スキルや用語を初心者向けに解説する学習サイトです。</p>
          </section>

          <!-- カテゴリカード -->
          <section class="top-cards">
            <h2>学習カテゴリ</h2>

            <div class="card-row">
              <?php foreach ( $si_note_cards as $card ) : ?>
                <?php if ( ! $card['show'] ) { continue; } ?>
              <div class="card category-card">
                <div class="card-inner">
                  <div class="card-icon">
                    <img src="<?php echo esc_url( $card['icon'] ); ?>" alt="<?php echo esc_attr( $card['alt'] ?? $card['title'] ); ?>">
                  </div>
                  <div class="card-content">
                    <h3><?php echo esc_html( $card['title'] ); ?></h3>
                    <p><?php echo esc_html( $card['text'] ); ?></p>
                    <a href="<?php echo esc_url( $card['url'] ); ?>" class="card-button">詳しく見る</a>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>

          <!-- 人気記事（表示回数 post_views_count の多い順） -->
          <section class="top-popular">
            <h2>人気記事</h2>

            <div class="kanren">
              <?php
              $popular_posts = get_posts( array(
                'meta_key'    => 'post_views_count',
                'orderby'     => 'meta_value_num',
                'order'       => 'DESC',
                'numberposts' => 5,
              ) );

              // テンプレートの $post は、WordPress の現在の投稿（the_title 等が使う）
              foreach ( $popular_posts as $post ) :
                setup_postdata( $post );
              ?>
                <dl class="clearfix">
                  <dt>
                    <a href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                      <?php the_post_thumbnail( 'thumbnail' ); ?>
                    <?php else : ?>
                      <img src="<?php echo esc_url( get_template_directory_uri() . '/images/no-img.png' ); ?>" alt="no image" width="100" height="100">
                    <?php endif; ?>
                    </a>
                  </dt>

                  <dd>
                    <p class="kanren-t">
                      <a href="<?php the_permalink(); ?>">
                      <?php the_title(); ?>
                      </a>
                    </p>

                    <div class="blog_info">
                      <p>
                        <i class="fa fa-clock-o"></i>
                        <?php the_time( 'Y/m/d' ); ?>
                        &nbsp;
                        <span class="pcone">
                          <i class="fa fa-folder-open-o"></i>-
                          <?php the_category( ', ' ); ?><br>
                          <?php the_tags( '<i class="fa fa-tags"></i>&nbsp;', ', ' ); ?>
                        </span>
                      </p>
                    </div>

                    <div class="smanone2">
                      <?php echo esc_html( wp_trim_words( get_the_excerpt(), 100, '...' ) ); ?>
                    </div>
                  </dd>
                </dl>

              <?php endforeach;
              wp_reset_postdata(); ?>
            </div>
          </section>

          <!-- 記事一覧（親テーマの itiran.php） -->
          <section class="top-new">
            <h2>記事一覧</h2>
            <?php get_template_part( 'itiran' ); ?>
          </section>

          <?php get_template_part( 'st-pagenavi' ); ?>
        </div>

        <?php get_template_part( 'sns-top' ); ?>
      </article>
    </div>
  </div>
  <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
