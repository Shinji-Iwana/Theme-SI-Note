# Theme-SI-Note

ブログ「現役システムエンジニアの備忘録」（https://si-note.com/ ）のテーマ。親テーマ **Theme-SI-Original** の子テーマ。

## 使い方

1. 親テーマ（`Theme-SI-Original`）と、このテーマ（`Theme-SI-Note`）の両方を、`wp-content/themes/` に置く。フォルダ名は変えない（`style.css` の `Template: Theme-SI-Original` で親テーマを指定しているため）。
2. WordPress の「外観 → テーマ」で、このテーマを有効にする（親テーマは有効にしない）。

## si-note 独自の部分

| ファイル | 内容 |
| --- | --- |
| `style.css` | テーマの情報と、si-note 独自のスタイル |
| `functions.php` | 表示回数の記録、コードの色付け（Prism。autoloader で Java・Python などにも色を付ける）、本文の自動整形の順序、紹介の枠のショートコード、サイトマップの開閉、REST API の項目、パンくずのロードマップへの置き換え、関連記事を出すかの判定、固定ページの URL の .html |
| `home.php` | トップページ（サイト紹介・学習カテゴリ・人気記事・記事一覧） |
| `single.php` | 投稿（アイキャッチ付きの見出し、本文の下の関連記事（本文に関連記事がない記事だけ）、エックスサーバーの紹介） |
| `page.php` | 固定ページ（アイキャッチ付きの見出し） |
| `page-sitemap.php` | 固定ページ「サイトマップ」のテンプレート（ファイル名を変えると、割り当てが外れる） |
| `kanren.php` | 関連記事（日付・カテゴリ・タグを表示） |
| `newpost.php` | サイドバーの新着記事（見出しを表示） |
| `comments.php` | コメントを表示しない |
| `template-parts/article-header-icon.php` | 見出しの左のアイキャッチ |
| `parts/` | 本文に差し込む紹介の枠（書籍・Udemy・スクール・資格）と、エックスサーバーの紹介 |

上の表にないテンプレート（`archive.php`・`search.php`・`itiran.php` など）は、親テーマのものを使う。

## 紹介の枠のショートコード

| ショートコード | 枠 |
| --- | --- |
| `[excel_book_box_beginner]` | Excel の書籍（基礎） |
| `[excel_book_box_function]` | Excel の書籍（関数） |
| `[js_book_box]` | JavaScript の書籍 |
| `[aws_book_box_beginner]` | AWS の書籍（初心者向け） |
| `[excel_udemy_box_beginner]` | Excel の Udemy（初心者向け） |
| `[aws_udemy_box_beginner]` | AWS の Udemy（初心者向け） |
| `[js_school_box]` | JavaScript のスクール |
| `[aws_school_box]` | AWS のスクール |
| `[aws_cert_box_clf]` | AWS の資格（CLF） |
| `[aws_cert_box_saa]` | AWS の資格（SAA） |

枠を加えるときは、`parts/` にファイルを置き、`functions.php` の `SI_NOTE_BOX_SHORTCODES` に1行加える。

## パンくずリスト

親テーマの `st_breadcrumb()` を使い、`functions.php` のフィルターで次のように変える。

- カテゴリのリンク先を、そのカテゴリのロードマップのページにする（親カテゴリ：スラッグが同じ固定ページ。例：/js.html。子カテゴリ：親ロードマップの子のページで、スラッグが同じもの。例：/js/js-basic.html）。ロードマップのページがないカテゴリは、カテゴリの一覧のまま。
- ロードマップのページは、長いタイトルではなく、カテゴリの名前を出す（例：「HOME > JavaScript > 基礎」）。

ロードマップのページの決まりは、BlogOS の「カテゴリの立ち上げ」と同じ。

## 関連記事

本文に BlogOS が管理する関連記事（`related-box`、または「関連記事」の見出し）がある記事では、テーマの関連記事（`kanren.php`）を出さない（二重になるため）。すべての記事の本文に関連記事が入ったら、`kanren.php` と `si_note_has_related_in_content()` を削除する。

## 固定ページの URL の .html

固定ページの URL の末尾に .html を付ける（例：/js.html）。以前はプラグイン「.html on PAGES」で行っていたが、プラグインの更新が止まっているため、`functions.php` に移した。プラグインを無効にした後は、「設定 → パーマリンク」で「変更を保存」を押す（プラグインが無効にするときに、.html なしで書き換えのルールを保存し直すため）。

## 表示回数

投稿を表示するたびに、カスタムフィールド `post_views_count` に数える（トップページの「人気記事」の並びに使う）。以前のテーマから同じ名前で数えているため、名前を変えないこと。

## STINGER8 のテーマ（stinger8）から切り替えるときの注意

- 追加CSS・ヘッダー画像は、テーマごとに保存されるため、切り替えた後に設定し直す（追加CSSは、このテーマの `style.css` に移す）。
- AdSense の自動広告のコードは、テーマには書かない。Site Kit の AdSense の設定で「コードを配置する」を有効にする。
