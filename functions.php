<?php
// 直接アクセスを禁止
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !function_exists( 'st_after_setup_theme' ) ) {
	/**
	 * テーマの初期設定
	 */
	function st_after_setup_theme() {
		add_theme_support( 'title-tag' );
	}
}
add_action( 'after_setup_theme', 'st_after_setup_theme' );

if ( !function_exists( 'st_enqueue_scripts' ) ) {
	/**
	 * スクリプトをキューへ追加
	 */
	function st_enqueue_scripts() {

		wp_deregister_script( 'jquery' );

		wp_enqueue_script(
			'jquery',
			'//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js',
			array(),
			'1.11.3',
			false
		);

		wp_enqueue_script( 'base', get_template_directory_uri() . '/js/base.js', array( 'jquery' ), false, true );

		// PCのみ追尾広告のjs読み込み
		wp_enqueue_script(
			'scroll',
			get_template_directory_uri() . '/js/scroll.js',
			array( 'jquery' ),
			false,
			true
		);

	}
}
add_action( 'wp_enqueue_scripts', 'st_enqueue_scripts' );

if ( !function_exists( 'st_enqueue_styles' ) ) {
	/**
	 * CSSをキューへ追加
	 */
	function st_enqueue_styles() {
		wp_register_style(
			'normalize',
			get_template_directory_uri() . '/css/normalize.css',
			array(),
			'1.5.9',
			'all'
		);

		wp_register_style(
			'font-awesome',
			get_template_directory_uri() . '/css/fontawesome/css/font-awesome.min.css',
			array('normalize'),
			'4.5.0',
			'all'
		);

		wp_register_style(
			'style',
			get_stylesheet_uri(),
			array('normalize', 'font-awesome'),
			false,
			'all'
		);

		wp_enqueue_style( 'style' );
	}
}
add_action( 'wp_enqueue_scripts', 'st_enqueue_styles' );

// カスタム背景
$custom_bgcolor_defaults = array(
	'default-color' => '#f2f2f2',
);
add_theme_support( 'custom-background', $custom_bgcolor_defaults );

// カスタムヘッダー
$custom_header = array(
	'random-default' => false,
	'width' => 1060,
	'height' => 300,
	'flex-height' => true,
	'flex-width' => false,
	'default-text-color' => '',
	'header-text' => false,
	'uploads' => true,
	'default-image' => get_stylesheet_directory_uri() . '/images/af.png',
);
add_theme_support( 'custom-header', $custom_header );

if (!function_exists('st_custom_excerpt_length')) {
	/**
	 * 抜粋の長さを変更する
	 */
    function st_custom_excerpt_length($length) {
	$excerptcount = 100;
	return $excerptcount;
    }
}
add_filter( 'excerpt_length', 'st_custom_excerpt_length', 999 );

// 文末文字を変更する
if ( !function_exists( 'st_custom_excerpt_more' ) ) {
	function st_custom_excerpt_more( $more ) {
		return ' ... ';
	}
}
add_filter( 'excerpt_more', 'st_custom_excerpt_more' );

if ( !function_exists( 'st_is_mobile' ) ) {
	/**
	 * スマホ表示分岐
	 */
	function st_is_mobile() {
		$useragents = array(
			'iPhone', // iPhone
			'iPod', // iPod touch
			'Android.*Mobile', // 1.5+ Android *** Only mobile
			'Windows.*Phone', // *** Windows Phone
			'dream', // Pre 1.5 Android
			'CUPCAKE', // 1.5+ Android
			'blackberry9500', // Storm
			'blackberry9530', // Storm
			'blackberry9520', // Storm v2
			'blackberry9550', // Storm v2
			'blackberry9800', // Torch
			'webOS', // Palm Pre Experimental
			'incognito', // Other iPhone browser
			'webmate' // Other iPhone browser

		);
		$pattern = '/' . implode( '|', $useragents ) . '/i';

		return preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] );
	}
}

if ( !function_exists( 'st_trim_excerpt' ) ) {
	/**
	 * 抜粋を取得/生成
	 *
	 * 本文からの抜粋生成時には全ショートコードを処理
	 *
	 * @param string $text 抜粋
	 *
	 * @return string 抜粋
	 */
	function st_trim_excerpt( $text = '' ) {
		if ( $text !== '' ) {
			return $text;
		}

		$text = get_the_content( '' );
		$text = apply_filters( 'the_content', $text );
		$text = str_replace( ']]>', ']]&gt;', $text );

		$excerpt_length = apply_filters( 'excerpt_length', 55 );

		$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
		$text         = wp_trim_words( $text, $excerpt_length, $excerpt_more );

		return $text;
	}
}
add_filter( 'get_the_excerpt', 'st_trim_excerpt', 9 );

// アイキャッチサムネイル
add_theme_support( 'post-thumbnails' );
add_image_size( 'st_thumb100', 100, 100, true );
add_image_size( 'st_thumb150', 150, 150, true );

// カスタムメニュー
add_action( 'init', 'my_custom_menus' );
function my_custom_menus() {
    register_nav_menus(
	   array(
		  'primary-menu' => __( 'ヘッダー用メニュー', 'default' ),
		  'secondary-menu' => __( 'フッター用メニュー', 'default' ),
		  'smartphone-menu' => __( 'スマートフォン用メニュー', 'default' )
	   )
    );
}

// RSS
add_theme_support( 'automatic-feed-links' );

// 管理画面にオリジナルのスタイルを適用
//add_editor_style( 'style.css' );    // メインのCSS
add_editor_style( 'editor-style.css' );

if ( !isset( $content_width ) ) {
	$content_width = 700;
}

if ( !function_exists( 'st_custom_editor_settings' ) ) {
	function st_custom_editor_settings( $initArray ) {
		$initArray['body_id'] = 'primary';    // id の場合はこれ
		$initArray['body_class'] = 'post';    // class の場合はこれ

		return $initArray;
	}
}
add_filter( 'tiny_mce_before_init', 'st_custom_editor_settings' );

// ヘッダーを綺麗に
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_generator' );

if ( !function_exists( 'st_custom_content_more_link' ) ) {
	/**
	 * moreリンク
	 */
	function st_custom_content_more_link( $output ) {
		$output = preg_replace( '/#more-[\d]+/i', '', $output );

		return $output;
	}
}
add_filter( 'the_content_more_link', 'st_custom_content_more_link' );

if ( !function_exists( 'st_no_self_pingst' ) ) {
	/**
	 * セルフピンバック禁止
	 */
	function st_no_self_pingst( $pung ) {
		$pung[] = home_url();

		return array_unique( $pung );
	}

	apply_filters( 'get_pung', 'st_no_self_pingst' );
}


if ( !function_exists( 'st_wrap_iframe_in_div' ) ) {
	/**
	 * iframeのレスポンシブ対応
	 */
	function st_wrap_iframe_in_div( $the_content ) {
		//YouTube
		$the_content =
		    preg_replace( '/<iframe[^>]+?youtube\.com[^<]+?<\/iframe>/is',
			   '<div
		class="youtube-container">${0}</div>',
			   $the_content );

		return $the_content;
	}
}

if ( !function_exists( 'st_singular_wrap_iframe_in_div' ) ) {
	/**
	 * iframeのレスポンシブ対応 (単一投稿)
	 */
	function st_singular_wrap_iframe_in_div( $the_content ) {
		if ( is_singular() ) {
			$the_content = st_wrap_iframe_in_div( $the_content );
		}

		return $the_content;
	}
}
add_filter('the_content','st_singular_wrap_iframe_in_div');

if ( !function_exists( 'st_register_sidebars' ) ) {
	/**
	 * ウイジェット追加
	 */
	function st_register_sidebars() {

		register_sidebar( array(
				  'id' => 'sidebar-10',
				  'name' => 'サイドバートップ',
			'description' => 'サイドバーの一番上に表示されるコンテンツエリアです。（タイトルは表示されません）',
				  'before_widget' => '<div class="ad">',
				  'after_widget' => '</div>',
				  'before_title' => '<p style="display:none">',
				  'after_title' => '</p>',
			   ) );


		register_sidebar( array(
			'id' => 'sidebar-1',
			'name' => 'サイドバーウイジェット',
			'description' => 'サイドバーに表示されるコンテンツです',
			'before_widget' => '<div class="ad">',
			'after_widget' => '</div>',
			'before_title' => '<p class="menu_underh2">',
			'after_title' => '</p>',
		) );

		register_sidebar( array(
			'id' => 'sidebar-2',
			'name' => 'スクロール広告用',
			'description' => 'サイドバーの下でコンテンツに追尾するボックスエリアです。「テキスト」をここにドロップして内容を入力して下さい。アドセンスは禁止です。※PC以外では非表示部分',
			'before_widget' => '<div class="ad">',
			'after_widget' => '</div>',
			'before_title' => '<p class="menu_underh2" style="text-align:left;">',
			'after_title' => '</p>',
		) );

		register_sidebar( array(
			'id' => 'sidebar-3',
			'name' => '広告・Googleアドセンス用336px',
			'description' => 'Googleアドセンス336pxに適したボックスで記事下に2つ連続で表示されます。「テキスト」をここにドロップしてコードを入力して下さい。※タイトルは反映されません',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<p style="display:none">',
			'after_title' => '</p>',
		) );

		register_sidebar( array(
			'id' => 'sidebar-4',
			'name' => '広告・Googleアドセンスのスマホ用300px',
			'description' => 'Googleアドセンス300pxに適したボックスで記事下に1つサイドバーの上に１つショートコードを利用した時のアドセンス時にも挿入されます。「テキスト」をここにドロップしてコードを入力して下さい。タイトルは反映されません。',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<p style="display:none">',
			'after_title' => '</p>',
		) );

		register_sidebar( array(
				  'id' => 'sidebar-9',
				  'name' => '広告・スマホ用記事下のみ',
			'description' => 'スマホのみ記事下に表示されるボックスエリアです。',
				  'before_widget' => '<div class="headbox">',
				  'after_widget' => '</div>',
				  'before_title' => '<p style="display:none">',
				  'after_title' => '</p>',
			   ) );
	}
}
add_action( 'widgets_init', 'st_register_sidebars' );

if ( !function_exists( 'st_get_mtime' ) ) {
	/**
	 * 更新日の追加
	 */
	function st_get_mtime( $format ) {
		$mtime = (int) get_the_modified_time( 'Ymd' );
		$ptime = (int) get_the_time( 'Ymd' );

		if ( $ptime > $mtime ) {
			return get_the_time( $format );
		} elseif ( $ptime === $mtime ) {
			return null;
		} else {
			return get_the_modified_time( $format );
		}
	}
}

if ( !function_exists( 'st_rss_feed_copyright' ) ) {
	/**
	 * RSSに著作権
	 */
	function st_rss_feed_copyright( $content ) {
		$content = $content . '<p>Copyright &copy; ' . esc_html( date( 'Y' ) ) .
				 ' <a href="' . esc_url( home_url() ) . '">' .
				 apply_filters( 'bloginfo', get_bloginfo( 'name' ), 'name' ) .
				 '</a> All Rights Reserved.</p>';

		return $content;
	}
}
add_filter( 'the_excerpt_rss', 'st_rss_feed_copyright' );
add_filter( 'the_content_feed', 'st_rss_feed_copyright' );

if ( !function_exists( 'st_showads' ) ) {
	/**
	 * アドセンス
	 */
	function st_showads() {
		ob_start();
		get_template_part( 'st-ad' );
		$ads = ob_get_clean();
		return $ads;
	}
	add_shortcode( 'adsense', 'st_showads' );
}

if ( !function_exists( 'st_shortcode' ) ) {
	/**
	 * オリジナルショートコード
	 */
	function st_shortcode() {
		ob_start();
		get_template_part( 'st-shortcode' );
		$osc = ob_get_clean();
		return $osc;
	}
	add_shortcode( 'originalsc', 'st_shortcode' );
}

if (!function_exists('st_tiny_mce_before_init')) {
	/**
	 * オリジナルタグ登録
	 */
	function st_tiny_mce_before_init( $init_array ) {
	//書式プルダウンメニューのカスタマイズ
	$init_array['block_formats'] = '段落=p;見出し2=h2;見出し3=h3;見出し4=h4;見出し5=h5;見出し6=h6';
	$init_array['fontsize_formats'] = '70% 80% 90% 120% 130% 150% 200% 250% 300%';
	//自作クラスをプルダウンメニューで追加
	$style_formats = array (
		array( 'title' => '太字', 'inline' => 'span', 'classes' => 'huto' ),
		array( 'title' => '太字（赤）', 'inline' => 'span', 'classes' => 'hutoaka' ),
		array( 'title' => '大文字', 'inline' => 'span', 'classes' => 'oomozi' ),
		array( 'title' => '小文字', 'inline' => 'span', 'classes' => 'komozi' ),
		array( 'title' => 'ドット線', 'inline' => 'span', 'classes' => 'dotline' ),
		array( 'title' => '黄マーカー', 'inline' => 'span', 'classes' => 'ymarker' ),
		array( 'title' => '赤マーカー', 'inline' => 'span', 'classes' => 'rmarker' ),
		array( 'title' => '参考', 'inline' => 'span', 'classes' => 'sankou' ),
		array( 'title' => '写真に枠線', 'inline' => 'span', 'classes' => 'photoline' ),
		array( 'title' => '記事タイトルデザイン', 'block' => 'p', 'classes' => 'entry-title' ),
		array( 'title' => 'code', 'inline' => 'code' ),
		array( 'title' => '吹き出し', 'block' => 'p', 'classes' => 'h2fuu' ),
		array( 'title' => '回り込み解除', 'block' => 'div', 'classes' => 'clearfix' , 'wrapper' => true ),
		array( 'title' => 'センター寄せ', 'block' => 'div', 'classes' => 'center' , 'wrapper' => true ),
		array( 'title' => '黄色ボックス', 'block' => 'div', 'classes' => 'yellowbox' , 'wrapper' => true ),
		array( 'title' => '薄赤ボックス', 'block' => 'div', 'classes' => 'redbox' , 'wrapper' => true ),
		array( 'title' => 'グレーボックス', 'block' => 'div', 'classes' => 'graybox' , 'wrapper' => true ),
		array( 'title' => '引用風ボックス', 'block' => 'div', 'classes' => 'inyoumodoki' , 'wrapper' => true ),
		array( 'title' => 'olタグを囲む数字ボックス', 'block' => 'div', 'classes' => 'maruno' , 'wrapper' => true ),
		array( 'title' => 'ulタグを囲む数字ボックス', 'block' => 'div', 'classes' => 'maruck' , 'wrapper' => true ),
		array( 'title' => 'table横スクロールボックス', 'block' => 'div', 'classes' => 'scroll-box' , 'wrapper' => true ),
		array( 'title' => 'imgインラインボックス', 'block' => 'span', 'classes' => 'inline-img' , 'wrapper' => true ),
		array( 'title' => 'width100%リセット', 'block' => 'span', 'classes' => 'resetwidth' , 'wrapper' => true ),
		array( 'title' => '装飾なしテーブル', 'block' => 'div', 'classes' => 'notab' , 'wrapper' => true ),
		);
	$init_array['style_formats'] = json_encode( $style_formats );
	$init['style_formats_merge'] = false;
	return $init_array;
	}
}
add_filter( 'tiny_mce_before_init', 'st_tiny_mce_before_init' );

if (!function_exists('st_add_orignal_quicktags')) {
	/**
	 * オリジナルクイックタグ登録
	 */
	function st_add_orignal_quicktags() {
		if ( wp_script_is( 'quicktags' ) ) { ?>
			<script type="text/javascript">
				QTags.addButton('ed_p', 'P', '<p>', '</p>');
				QTags.addButton('ed_huto', '太字', '<span class="huto">', '</span>');
				QTags.addButton('ed_hutoaka', '太字（赤）', '<span class="hutoaka">', '</span>');
				QTags.addButton('ed_oomozi', '大文字', '<span class="oomozi">', '</span>');
				QTags.addButton('ed_komozi', '小文字', '<span class="komozi">', '</span>');
				QTags.addButton('ed_dotline', 'ドット線', '<span class="dotline">', '</span>');
				QTags.addButton('ed_ymarker', '黄マーカー', '<span class="ymarker">', '</span>');
				QTags.addButton('ed_rmarker', '赤マーカー', '<span class="rmarker">', '</span>');
				QTags.addButton('ed_sankou', '参考', '<span class="sankou">', '</span>');
				QTags.addButton('ed_photoline', '写真に枠線', '<span class="photoline">', '</span>');
				QTags.addButton('ed_entry', '記事タイトルデザイン', '<p class="entry-title">', '</p>');
				QTags.addButton('ed_code', 'code', '<code>', '</code>');
				QTags.addButton('ed_ads', 'アドセンス', '[adsense]', '');
				QTags.addButton('ed_clearfix', '回り込み解除', '<div class="clearfix">', '</div>');
				QTags.addButton('ed_center', 'センター寄せ', '<div class="center">', '</div>');
				QTags.addButton('ed_yellowbox', '黄色ボックス', '<div class="yellowbox">', '</div>');
				QTags.addButton('ed_redbox', '薄赤ボックス', '<div class="redbox">', '</div>');
				QTags.addButton('ed_graybox', 'グレーボックス', '<div class="graybox">', '</div>');
				QTags.addButton('ed_inyoumodoki', '引用風', '<div class="inyoumodoki">', '</div>');
				QTags.addButton('ed_maruno', 'olタグを囲む数字ボックス', '<div class="maruno">', '</div>');
				QTags.addButton('ed_maruck', 'ulタグを囲むチェックボックス', '<div class="maruck">', '</div>');
				QTags.addButton('ed_scroll_box', 'table横スクロール要素', '<div class="scroll-box">', '</div>');
				QTags.addButton('ed_resetwidth', 'width100%リセット', '<span class="resetwidth">', '</span>');
				QTags.addButton('ed_notab', '装飾なしテーブル', '<div class="notab">', '</div>');
				QTags.addButton('ed_responbox', 'PCのみ左右%ボックス', '<div class="clearfix responbox"><div class="lbox"><p>左側のコンテンツ40%</p></div><div class="rbox"><p>右側のコンテンツ60%</p></div></div>', '');
				QTags.addButton('ed_responbox50s', '全サイズ左右50%ボックス', '<div class="clearfix responbox50 smart50"><div class="lbox"><p>左側のコンテンツ50%</p></div><div class="rbox"><p>右側のコンテンツ50%</p></div></div>', '');
				QTags.addButton( 'ed_ive', 'イベント', "onclick=\"ga('send', 'event', 'linkclick', 'click', 'hoge');\"", '' );
				QTags.addButton( 'ed_nofollow', 'nofollow', " rel=\"nofollow\"", '' );
			</script>
			<?php
		}
	}
}
add_action('admin_print_footer_scripts', 'st_add_orignal_quicktags');

function set_post_views($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count == ''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function add_prism_assets() {
    wp_enqueue_style('prism-css', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css');
    wp_enqueue_style('prism-toolbar-css', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/toolbar/prism-toolbar.min.css');

    wp_enqueue_script('prism-js', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js', array(), null, true);
    wp_enqueue_script('prism-toolbar-js', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/toolbar/prism-toolbar.min.js', array('prism-js'), null, true);
    wp_enqueue_script('prism-copy-js', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js', array('prism-js'), null, true);
}
add_action('wp_enqueue_scripts', 'add_prism_assets');

// ショートコードの自動整形を無効化
remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop', 99);
add_filter('the_content', 'shortcode_unautop', 100);

// ショートコード：Excel記事の書籍(基礎)ボックス用
function load_common_excel_book_box_beginner() {
    ob_start();
    get_template_part('parts/common-excel-book-box-beginner');
    return ob_get_clean();
}
add_shortcode('excel_book_box_beginner', 'load_common_excel_book_box_beginner');

// ショートコード：Excel記事の書籍(関数)ボックス用
function load_common_excel_book_box_function() {
    ob_start();
    get_template_part('parts/common-excel-book-box-function');
    return ob_get_clean();
}
add_shortcode('excel_book_box_function', 'load_common_excel_book_box_function');

// ショートコード：JavaScript記事の書籍ボックス用
function load_common_js_book_box() {
    ob_start();
    get_template_part('parts/common-js-book-box');
    return ob_get_clean();
}
add_shortcode('js_book_box', 'load_common_js_book_box');

// ショートコード：AWS記事の書籍(初心者向け)ボックス用
function load_common_aws_book_box_beginner() {
    ob_start();
    get_template_part('parts/common-aws-book-box-beginner');
    return ob_get_clean();
}
add_shortcode('aws_book_box_beginner', 'load_common_aws_book_box_beginner');

// ショートコード：Excel記事のUdemy(初心者向け)ボックス用
function load_common_excel_udemy_box_beginner() {
    ob_start();
    get_template_part('parts/common-excel-udemy-box-beginner');
    return ob_get_clean();
}
add_shortcode('excel_udemy_box_beginner', 'load_common_excel_udemy_box_beginner');

// ショートコード：AWS記事のUdemy(初心者向け)ボックス用
function load_common_aws_udemy_box_beginner() {
    ob_start();
    get_template_part('parts/common-aws-udemy-box-beginner');
    return ob_get_clean();
}
add_shortcode('aws_udemy_box_beginner', 'load_common_aws_udemy_box_beginner');

// ショートコード：JavaScript記事のスクールボックス用
function load_common_js_school_box() {
    ob_start();
    get_template_part('parts/common-js-school-box');
    return ob_get_clean();
}
add_shortcode('js_school_box', 'load_common_js_school_box');

// ショートコード：AWS記事のスクールボックス用
function load_common_aws_school_box() {
    ob_start();
    get_template_part('parts/common-aws-school-box');
    return ob_get_clean();
}
add_shortcode('aws_school_box', 'load_common_aws_school_box');

// ショートコード：AWS記事の資格(CLF)ボックス用
function load_common_aws_cert_box_clf() {
    ob_start();
    get_template_part('parts/common-aws-cert-box-clf');
    return ob_get_clean();
}
add_shortcode('aws_cert_box_clf', 'load_common_aws_cert_box_clf');

// ショートコード：AWS記事の資格(SAA)ボックス用
function load_common_aws_cert_box_saa() {
    ob_start();
    get_template_part('parts/common-aws-cert-box-saa');
    return ob_get_clean();
}
add_shortcode('aws_cert_box_saa', 'load_common_aws_cert_box_saa');
