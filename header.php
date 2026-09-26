<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>
<html class="i7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>
<html class="ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!-->
<html <?php language_attributes(); ?>>
	<!--<![endif]-->
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
		<meta charset="<?php bloginfo( 'charset' ); ?>" >
		<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=yes">
		<meta name="format-detection" content="telephone=no" >
		
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script>
			 (adsbygoogle = window.adsbygoogle || []).push({
				  google_ad_client: "ca-pub-4328587429965234",
				  enable_page_level_ads: true
			 });
		</script>
		
		<?php if ( is_home() && !is_paged() ): ?>
			<meta name="robots" content="index,follow">
		<?php elseif ( is_search() or is_404() ): ?>
			<meta name="robots" content="noindex,follow">
		<?php elseif ( !is_category() && is_archive() ): ?>
			<meta name="robots" content="noindex,follow">
		<?php elseif ( is_paged() ): ?>
			<meta name="robots" content="noindex,follow">
		<?php endif; ?>

		<link rel="alternate" type="application/rss+xml" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> RSS Feed" href="<?php bloginfo( 'rss2_url' ); ?>" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" >
		<!--[if lt IE 9]>
		<script src="<?php echo esc_url( get_template_directory_uri() ) ; ?>/js/html5shiv.js"></script>
		<![endif]-->
		<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?> >
		<div id="st-ami">
			<div id="wrapper">
				<div id="wrapper-in">
					<header>
						<div id="headbox-bg">
							<div class="clearfix" id="headbox">
								<?php get_template_part( 'st-accordion-menu' ); //アコーディオンメニュー ?>
								<div id="header-l">
									<!-- ロゴ又はブログ名 -->
									<p class="sitename">
										<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
											<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>
										</a>
									</p>
									<!-- ロゴ又はブログ名ここまで -->
									<!-- キャプション -->
									<?php if ( is_front_page() ) { ?>
										<h1 class="descr">
											<?php bloginfo( 'description' ); ?>
										</h1>
									<?php } else { ?>
										<p class="descr">
											<?php bloginfo( 'description' ); ?>
										</p>
									<?php } ?>
								</div><!-- /#header-l -->

							</div><!-- /#headbox-bg -->
						</div><!-- /#headbox clearfix -->

						<div id="gazou-wide">
							<?php get_template_part( 'st-header-menu' ); //カスタムヘッダーメニュー ?>

							<?php if ( (get_header_image()) && (is_front_page()) ) : //カスタムヘッダー ?>
								<div id="st-headerbox">
									<div id="st-header">
										<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
									</div>
								</div>
							<?php endif;?>

						</div>
						<!-- /gazou -->

					</header>
					<div id="content-w">