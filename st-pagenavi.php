<?php
/**
 * ページナビ
 */

if(st_is_mobile()){ //スマホの場合 ?>
	<div class="st-pagelink">
	<?php
	global $wp_query;
	$big = 999999999; // need an unlikely integer
	echo paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'end_size'           => 0,
		'mid_size'           => 1,
	) );
	?>
	</div>
<?php }else{ //PCの場合 ?>
	<div class="st-pagelink">
	<?php
	global $wp_query;
	$big = 999999999; // need an unlikely integer
	echo paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages
	) );
	?>
	</div>
<?php } ?>