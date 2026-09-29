<?php
/**
 * Theme-SI-Note（si-note.com の子テーマ）の機能
 *
 * 親テーマ（Theme-SI-Original）の functions.php より先に読み込まれる。
 * 親テーマの関数を置き換える場合は、同じ名前の関数をここで定義する。
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
 * 表示回数（トップページの「人気記事」の並びに使う）
 *
 * 投稿のカスタムフィールド post_views_count に数える。名前を変えると、これまでの数が引き継がれない。
 */
if (!function_exists('set_post_views')) {
    function set_post_views(int $postId): void
    {
        $count = get_post_meta($postId, 'post_views_count', true);

        if ($count === '') {
            delete_post_meta($postId, 'post_views_count');
            add_post_meta($postId, 'post_views_count', '0');
        } else {
            update_post_meta($postId, 'post_views_count', (int) $count + 1);
        }
    }
}

if (!function_exists('si_note_count_post_views')) {
    /**
     * 投稿を表示したときに数える（テンプレートではなく、ここで数える）
     */
    function si_note_count_post_views(): void
    {
        // 記事のプレビュー・テーマのライブプレビュー（カスタマイザー）・BlogOSのプレビューの表示は数えない
        $blogos_preview = function_exists('blogos_is_preview') && blogos_is_preview();
        if (is_single() && !is_preview() && !is_customize_preview() && !$blogos_preview) {
            set_post_views(get_queried_object_id());
        }
    }
}
add_action('template_redirect', 'si_note_count_post_views');

if (!function_exists('si_note_enqueue_prism')) {
    /**
     * コードの色付け・コピーのボタン（Prism）
     */
    function si_note_enqueue_prism(): void
    {
        $base = 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0';

        wp_enqueue_style('prism-css', "{$base}/themes/prism-tomorrow.min.css", [], null);
        wp_enqueue_style('prism-toolbar-css', "{$base}/plugins/toolbar/prism-toolbar.min.css", ['prism-css'], null);

        wp_enqueue_script('prism-js', "{$base}/prism.min.js", [], null, true);
        wp_enqueue_script('prism-toolbar-js', "{$base}/plugins/toolbar/prism-toolbar.min.js", ['prism-js'], null, true);
        wp_enqueue_script('prism-copy-js', "{$base}/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js", ['prism-toolbar-js'], null, true);
        // prism.min.js にない言語（Java・Python・SQL など）を、使っているものだけ自動で読み込む（html-rules.md 3-7）
        wp_enqueue_script('prism-autoloader-js', "{$base}/plugins/autoloader/prism-autoloader.min.js", ['prism-js'], null, true);
    }
}
add_action('wp_enqueue_scripts', 'si_note_enqueue_prism');

/*
 * 本文の自動整形（段落・改行の変換）を、ショートコードの処理の後にする
 * （ショートコードの枠の中に、余分な <p> や <br> が入らないようにするため）
 */
remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop', 99);
add_filter('the_content', 'shortcode_unautop', 100);

/*
 * ショートコード：本文に差し込む紹介の枠（parts/ のファイル）
 *
 * [ショートコード名] => parts/ のファイル名
 */
const SI_NOTE_BOX_SHORTCODES = [
    'excel_book_box_beginner'  => 'common-excel-book-box-beginner',  // Excel記事の書籍（基礎）
    'excel_book_box_function'  => 'common-excel-book-box-function',  // Excel記事の書籍（関数）
    'js_book_box'              => 'common-js-book-box',              // JavaScript記事の書籍
    'aws_book_box_beginner'    => 'common-aws-book-box-beginner',    // AWS記事の書籍（初心者向け）
    'excel_udemy_box_beginner' => 'common-excel-udemy-box-beginner', // Excel記事のUdemy（初心者向け）
    'aws_udemy_box_beginner'   => 'common-aws-udemy-box-beginner',   // AWS記事のUdemy（初心者向け）
    'js_school_box'            => 'common-js-school-box',            // JavaScript記事のスクール
    'aws_school_box'           => 'common-aws-school-box',           // AWS記事のスクール
    'aws_cert_box_clf'         => 'common-aws-cert-box-clf',         // AWS記事の資格（CLF）
    'aws_cert_box_saa'         => 'common-aws-cert-box-saa',         // AWS記事の資格（SAA）
];

if (!function_exists('si_note_register_box_shortcodes')) {
    function si_note_register_box_shortcodes(): void
    {
        foreach (SI_NOTE_BOX_SHORTCODES as $tag => $part) {
            add_shortcode($tag, function () use ($part): string {
                ob_start();
                get_template_part('parts/' . $part);

                return (string) ob_get_clean();
            });
        }
    }
}
add_action('init', 'si_note_register_box_shortcodes');

if (!function_exists('si_note_sitemap_toggle_script')) {
    /**
     * サイトマップのページ（page-sitemap.php）：子カテゴリの記事一覧の開閉
     */
    function si_note_sitemap_toggle_script(): void
    {
        if (!is_page_template('page-sitemap.php')) {
            return;
        }
        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".sitemap-toggle").forEach(function (toggle) {
                toggle.addEventListener("click", function (e) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                });
            });
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'si_note_sitemap_toggle_script');

/*
 * REST API の投稿・固定ページに、AIOSEO のメタディスクリプション（_aioseo_description）を加える。
 * 以前から出力している項目。BlogOS は AIOSEO が加える aioseo_meta_data を使っており、この項目は使っていない。
 */
add_action('rest_api_init', function (): void {
    register_rest_field(['post', 'page'], 'aioseo_meta_description', [
        'get_callback' => fn (array $object) => get_post_meta($object['id'], '_aioseo_description', true),
        'schema'       => null,
    ]);
});

/*
 * パンくずリスト（親テーマの st_breadcrumb_items）：カテゴリをロードマップのページに置き換える
 *
 * ロードマップのページは、BlogOS の「カテゴリの立ち上げ」と同じ決まりで探す。
 * 親カテゴリ：スラッグが親カテゴリのスラッグと同じ固定ページ（親のページなし。例：/js.html）
 * 子カテゴリ：親ロードマップの子のページで、スラッグが子カテゴリのスラッグと同じもの（例：/js/js-basic.html）
 * ロードマップのページがないカテゴリは、カテゴリの一覧のまま。ロードマップのページは、長いタイトルではなくカテゴリの名前を出す。
 */
if (!function_exists('si_note_roadmap_page')) {
    function si_note_roadmap_page(int $categoryId): ?WP_Post
    {
        static $cache = [];
        if (array_key_exists($categoryId, $cache)) {
            return $cache[$categoryId];
        }

        $category = get_category($categoryId);
        $page = null;
        if ($category instanceof WP_Term) {
            $parent = (int) $category->parent !== 0 ? si_note_roadmap_page((int) $category->parent) : null;
            $path = (int) $category->parent === 0 ? $category->slug : ($parent !== null ? get_page_uri($parent) . '/' . $category->slug : null);
            $found = $path !== null ? get_page_by_path($path) : null;
            $page = $found instanceof WP_Post && $found->post_status === 'publish' ? $found : null;
        }

        return $cache[$categoryId] = $page;
    }
}

if (!function_exists('si_note_roadmap_category')) {
    /**
     * ロードマップのページに当たるカテゴリ（ロードマップのページでなければ null）
     */
    function si_note_roadmap_category(int $pageId): ?WP_Term
    {
        $page = get_post($pageId);
        $category = $page instanceof WP_Post ? get_category_by_slug($page->post_name) : false;
        if (!$category instanceof WP_Term) {
            return null;
        }
        $roadmap = si_note_roadmap_page($category->term_id);

        return $roadmap !== null && $roadmap->ID === $page->ID ? $category : null;
    }
}

add_filter('st_breadcrumb_items', function (array $items): array {
    foreach ($items as $index => $item) {
        if (isset($item['category_id']) && ($page = si_note_roadmap_page((int) $item['category_id'])) !== null) {
            $items[$index]['url'] = get_permalink($page);
        } elseif (isset($item['page_id']) && ($category = si_note_roadmap_category((int) $item['page_id'])) !== null) {
            $items[$index]['name'] = $category->name;
        }
    }

    return $items;
});

/*
 * 関連記事（kanren.php）：本文に BlogOS が管理する関連記事（related-box、または「関連記事」の見出し）がある記事では出さない。
 * すべての記事の本文に関連記事が入ったら、kanren.php とこの判定を削除する。
 */
if (!function_exists('si_note_has_related_in_content')) {
    function si_note_has_related_in_content(?int $postId = null): bool
    {
        $content = (string) get_post_field('post_content', $postId ?? get_the_ID());

        return str_contains($content, 'related-box') || preg_match('#<h[2-4][^>]*>\s*関連記事#u', $content) === 1;
    }
}

/*
 * 固定ページの URL の末尾に .html を付ける（プラグイン「.html on PAGES」から移した。例：/js.html、/js/js-basic.html）
 *
 * プラグインを無効にしても URL が変わらないよう、テーマで行う。プラグインと同時に有効でも、二重には付かない。
 * プラグインを無効にした後は、「設定 → パーマリンク」で「変更を保存」を押す（プラグインが .html なしで書き換えのルールを保存し直すため）。
 */
add_action('init', function (): void {
    global $wp_rewrite;
    if (strpos($wp_rewrite->get_page_permastruct(), '.html') === false) {
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
    }
}, -1);

add_filter('user_trailingslashit', function (string $string, string $type): string {
    global $wp_rewrite;

    return $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes && $type === 'page' ? untrailingslashit($string) : $string;
}, 66, 2);
