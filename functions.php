<?php
function addIntNav() { 
	$options = get_option('int_nav_options');
	if ( $options['int_nav_frame'] == 1 ) { ?>
	<iframe seamless frameborder=0 scrolling=no width="100%"<?php if ( ( $options['int_nav_height'] != 0 ) ) { ?> height="<?php echo $options['int_nav_height']; ?>px"<?php } ?> src="<?php echo WP_PLUGIN_URL; ?>/intuitive-navigation/load-frame.php?post_id=<?php the_ID(); ?>" name="int_nav_frame" id="int_nav_frame"></iframe>
	<?php } else {
		$p = initIntNav();
		buildIntNav( $p );
	}
}
function buildIntNav( $p ) { 
	$options = get_option('int_nav_options');
?>
	<div id="int-nav">
		<table width="100%"<?php if ( ( $options['int_nav_height'] != 0 ) ) { ?> style="height:<?php echo $options['int_nav_height']; ?>px;"<?php } ?> class="int-nav-table"><tr>
	<?php if ( $p['no_next'] != TRUE ) { ?>
		<?php if ( ( $options['int_nav_display_thumbs'] == 1 ) && ( $p['next']['img'] != '' ) ) { ?>
			<td valign="middle" style="width:1px;padding-right:10px;">
				<a target="_parent" href="<?php echo $p['next']['perm']; ?>" title="Read: <?php echo $p['next']['title'] ?>"><?php echo $p['next']['img']; ?></a>
			</td>
		<?php } ?>
		<td valign="middle">
			<div class="lbl"><?php echo $options['int_nav_prev_text']; ?></div>
			<div class="prevnext"><a target="_parent" href="<?php echo $p['next']['perm']; ?>" title="Read: <?php echo $p['next']['title']; ?>"><?php echo $p['next']['title']; ?></a></div>
		</td>
	<?php } ?>
		<td width="20">&nbsp;</td>
	<?php if ( $p['no_prev'] != TRUE ) { ?>
		<td valign="middle" style="text-align:right;">
			<div class="lbl"><?php echo $options['int_nav_next_text']; ?></div>
			<div class="prevnext"><a target="_parent" href="<?php echo $p['prev']['perm']; ?>" title="Read: <?php echo $p['prev']['title']; ?>"><?php echo $p['prev']['title']; ?></a></div>
		</td>
		<?php if ( ( $options['int_nav_display_thumbs'] == 1 ) && ( $p['prev']['img'] != '' ) ) { ?>
			<td valign="middle" style="padding-left:10px;text-align:right;width:1px;">
				<a target="_parent" href="<?php echo $p['prev']['perm']; ?>" title="Read: <?php echo $p['prev']['title']; ?>"><?php echo $p['prev']['img']; ?></a>
			</td>
		<?php } ?>
	<?php } ?>
		</tr></table>
	</div>
<?php 
}

function initIntNav () {
	$options = get_option('int_nav_options');
	if ( $options['int_nav_frame'] == 1 ) {
		$post_id = $_GET['post_id'];
	} else {
		$post_id = get_the_ID();
	}

	$taxonomy = $_COOKIE['int_nav_term_taxonomy'];
	$term_id = $_COOKIE['int_nav_term_id'];

	if ( $taxonomy == "cat") {
		if ( in_category( $term_id, $post_id )) {
			$p = doIntuitive ( $post_id, "cat", $term_id );
		} else {
			$p = notIntuitive( $post_id );
		}
	} elseif ( $taxonomy == "post_tag" ) {
		if ( has_tag( $term_id, $post_id )) {
			$p = doIntuitive ( $post_id, "tag_id", $term_id );
		} else {
			$p = notIntuitive( $post_id );
		}
	} else {
		$p = notIntuitive( $post_id );
	}
	if ( $options['int_nav_swap'] == 1 ) {
		$t = $p;
		$p['next'] = $t['prev'];
		$p['prev'] = $t['next'];
		$p['no_next'] = $t['no_prev'];
		$p['no_prev'] = $t['no_next'];
	}
	return $p;
}

function loadIntJS() {
    wp_enqueue_script( 'int-nav-script', WP_PLUGIN_URL . '/intuitive-navigation/int-nav-script.js');
}    
add_action('init', 'loadIntJS');

function get_the_slug() {
	global $post;
	if ( is_single() || is_page() ) {
		return $post->post_name;
	} else {
		return "";
	}
}

add_filter('template_include','start_buffer_capture',1);
function start_buffer_capture($template) {
	ob_start('end_buffer_capture');  
	return $template;
}
function end_buffer_capture($buffer) {
	$options = get_option('int_nav_options');
	if ( is_single() ) {
		if ( $options['int_nav_bold'] == 1 ) { $highlight = "highlightLink(Get_Cookie( 'int_nav_term_url' ), '" . get_the_ID() . "'); "; }
		if ( $options['int_nav_frame'] == 1 ) { $resize_frame = "resizeFrame('int_nav_frame'); "; }
		$injection = $resize_frame . $highlight;
		if ( preg_match( '/(<body)([^>]*)(onload)([^>]*)(>)/i', $buffer ) == 0 ){
			$search = "/(<body)([^>]*)(>)/i";
			$replace = '$1 onload="' . $injection . '" $2$3';
		} else {
			$search = "/(<body)([^>]*)( onload=)(['" . '"' . "])([^>]*)(>)/i";
			$replace = "$1$2$3$4" . $injection . "$5$6";
		}
		$buffer = preg_replace($search, $replace ,$buffer);
	}
	return $buffer;
}

function manipulateCookieScript() {
	if ( is_category() || is_tag() ) {
		if ( is_tag() ) {
			$term_id = get_query_var('tag_id');
			$taxonomy = 'post_tag';
			$query_type = 'tag_id';
			$term_url = get_tag_link( $term_id );
		} elseif ( is_category() ) {
			$term_id = get_query_var('cat');
			$taxonomy = 'cat';
			$query_type = 'cat';
			$term_url = get_category_link( $term_id );
		}
		query_posts( array ( $query_type => $term_id, 'posts_per_page' => 10000 ) );
		while ( have_posts() ) : the_post();
			$ids[] = get_the_ID();
		endwhile;
		$ids = implode( ',', $ids );
		?><script type="text/javascript">
			Set_Cookie( 'int_nav_term_id', '<?php echo $term_id; ?>', '', '/', '', '' );
			Set_Cookie( 'int_nav_term_taxonomy', '<?php echo $taxonomy; ?>', '', '/', '', '' );
			Set_Cookie( 'int_nav_term_url', '<?php echo $term_url; ?>', '', '/', '', '' );
			Set_Cookie( 'int_nav_ids', '<?php echo $ids; ?>', '', '/', '', '' );
		</script><?php
	} elseif ( is_home() || is_search() ) {
		?><script type="text/javascript">
			Delete_Cookie( 'int_nav_term_id', '/', '' );
			Delete_Cookie( 'int_nav_term_taxonomy', '/', '' );
			Delete_Cookie( 'int_nav_term_url', '/', '' );
			Delete_Cookie( 'int_nav_ids', '/', '' );
		</script><?php
	}
}
add_action( 'wp_footer', 'manipulateCookieScript' );

function notIntuitive( $post_id ) {
	$wp_query->is_single = true;
	global $post;
	$post = get_post( $post_id );
	$p = array();
	$next_post = get_previous_post();
	if ( !$next_post ) {
		$p['no_next'] = TRUE;
	} else {
		$p['next']['id'] = $next_post->ID;
		$p['next']['title'] = $next_post->post_title;
		$p['next']['perm'] = get_permalink($p['next']['id']);
	}

	$prev_post = get_next_post();
	if ( !$prev_post ) {
		$p['no_prev'] = TRUE;
	} else {
		$p['prev']['id'] = $prev_post->ID;
		$p['prev']['title'] = $prev_post->post_title;
		$p['prev']['perm'] = get_permalink($p['prev']['id']);
	}	
	$options = get_option('int_nav_options');
	if ( $options['int_nav_crop_thumbs'] == 1 ) {
		$p['prev']['img'] = get_the_post_thumbnail($p['prev']['id'],'thumbnail');
		$p['next']['img'] = get_the_post_thumbnail($p['next']['id'],'thumbnail');
	} else {
		$p['prev']['img'] = get_the_post_thumbnail($p['prev']['id']);
		$p['next']['img'] = get_the_post_thumbnail($p['next']['id']);
	}
	return $p;
}

function doIntuitive ( $post_id, $query_type, $term_id ) {
	$p = array();
	$next_id = "";
	$prev_id = "";
	$cur_id = "";

	$ids = $_COOKIE['int_nav_ids'];
	$ids = explode( ',', $ids );

	for ( $i = 0; $i<count($ids); $i++ ) {
		if ( $next_id != "" ) {
			$cur_id = $ids[$i];
			$p['next']['id']= $next_id;
			$p['next']['perm'] = get_permalink( $next_id );
			$p['next']['title'] = get_post( $next_id )->post_title;
		}
		if ( $cur_id == $post_id ) {
			if ( $prev_id == $p['prev']['id'] ) { // if post is the most recent
				$p['no_prev'] = TRUE;
			}
			$p['prev']['id'] = $prev_id;
			$p['prev']['perm'] = get_permalink( $prev_id );
			$p['prev']['title'] = get_post( $prev_id )->post_title;
			$next_id = $ids[$i];
			$p_cur_id = get_post( $cur_id )->ID;
		}
		$prev_id = $cur_id;
		$cur_id = $ids[$i];
	}
	
	if ( !$p_cur_id ) {
		$p['no_next'] = TRUE;
		$p['prev']['id'] = $prev_id;
		$p['prev']['perm'] = get_permalink( $prev_id );
		$p['prev']['title'] = get_post( $prev_id )->post_title;
	}
	if ( !$p['next']['id'] ) {
		$p['next']['id'] = $next_id;
		$p['next']['perm'] = get_permalink( $next_id );
		$p['next']['title'] = get_post( $next_id )->post_title;
	}
	
	$options = get_option('int_nav_options');
	if ( $options['int_nav_crop_thumbs'] == 1 ) {
		$p['prev']['img'] = get_the_post_thumbnail($p['prev']['id'],'thumbnail');
		$p['next']['img'] = get_the_post_thumbnail($p['next']['id'],'thumbnail');
	} else {
		$p['prev']['img'] = get_the_post_thumbnail($p['prev']['id']);
		$p['next']['img'] = get_the_post_thumbnail($p['next']['id']);
	}
	wp_reset_query();
	return $p;
}

?>