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
        if (is_single() && !is_preview()) {
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
