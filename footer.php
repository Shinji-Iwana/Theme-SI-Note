</div><!-- /contentw -->
<footer>
<div id="footer">
<div id="footer-in">
<?php //フッターメニュー
$defaults = array(
	'theme_location'  => 'secondary-menu',
	'container'       => 'div',
	'container_class' => 'footermenubox clearfix ',
	'menu_class'      => 'footermenust',
	'depth'           => 1,
);
wp_nav_menu( $defaults );
?>
	<div class="footer-wbox clearfix">

		<div class="footer-c">
			<!-- フッターのメインコンテンツ -->
			<p class="footerlogo">
			<!-- ロゴ又はブログ名 -->
				<?php if ( !is_home() || !is_front_page() ) { ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php } ?>
					<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>
				<?php if ( !is_home() || !is_front_page() ) { ?>
					</a>
				<?php } ?>
			</p>

			<p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'description' ); ?></a>
			</p>
		</div>
	</div>

	<p class="copy">Copyright&copy;
		<?php bloginfo( 'name' ); ?>
		,
		<?php echo date( 'Y' ); ?>
		All Rights Reserved.</p>

</div>
</div>
</footer>
</div>
<!-- /#wrapperin -->
</div>
<!-- /#wrapper -->
</div><!-- /#st-ami -->
<!-- ページトップへ戻る -->
	<div id="page-top"><a href="#wrapper" class="fa fa-angle-up"></a></div>
<!-- ページトップへ戻る　終わり -->
<?php wp_footer(); ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll(".sitemap-toggle");

    toggles.forEach(function (toggle) {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();

            const parent = this.parentElement;
            parent.classList.toggle("open");
        });
    });
});
</script>

</body></html>
