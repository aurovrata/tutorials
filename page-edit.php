<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 *
 * Template Name: Edit posts
 * Template Post Type: page
 */
$has_story = false;
$is_registered = false;
if( isset($_COOKIE['_opt-by-email']) ){
  $otp = $_COOKIE['_opt-by-email'];
  $email = false;
  if(function_exists('get_email_by_otp')) $email = get_email_by_otp($otp);
	$is_registered = $email !== false;
  if( isset($_GET['story'])) {
		$sid = $_GET['story'];
		$validate_email = get_post_meta($sid, 'author-email', true);
		if($validate_email and $validate_email===$email) $has_story = true;
	}
}

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			if($has_story) echo do_shortcode('[cf7form cf7key="form-to-post" cf7_2_post_id="'.$sid.'"]');
			else if($is_registered) echo do_shortcode('[cf7form cf7key="form-to-post"]');
			else{ //display the default page.
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}

				endwhile; // End the loop.
			}
      ?>
      </main><!-- #main -->
  	</div><!-- #primary -->

  <?php
  get_footer();
