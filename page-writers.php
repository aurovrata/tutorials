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
 * Template Name: Writer posts
 * Template Post Type: page
 */
if( !isset($_COOKIE['_opt-by-email']) ) wp_redirect(home_url('/email-otp-failed'));
$otp = $_COOKIE['_opt-by-email'];
$email = false;
if(function_exists('get_email_by_otp')) $email = get_email_by_otp($otp);


get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php

			// Start the Loop.
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

			endwhile; // End the loop.

      //display existing posts for the writer identified by the OTP plugin.
			global $post;
			$posts = get_posts(array(
					'post_type' => 'post',
					'post_status' => 'any',
					'posts_per_page'=> -1,
					'meta_key' => 'author-email',
					'meta_value'=> $email
			));
			$noun = 'first';
			if($posts):
				foreach($posts as $post):
					setup_postdata( $post );
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<h2 class="entry-title">
							<?php
							switch($post->post_status){
								case 'publish':
									the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</h2>' );
									break;
								default:
									the_title();
									break;
							}
							?>
							</h2>
						</header><!-- .entry-header -->

						<?php twentynineteen_post_thumbnail(); ?>

						<div class="entry-content">
							<?php the_excerpt(); ?>
							<div>
								<a href="<?= home_url("/form-to-post?story=$post->ID") ?>">Edit submitted story</a>
							</div>
						</div><!-- .entry-content -->

						<footer class="entry-footer">
							<?php twentynineteen_entry_footer(); ?>
						</footer><!-- .entry-footer -->
					</article><!-- #post-<?php the_ID(); ?> -->
          <?php
				endforeach;
				$noun = 'next';
			endif;
			wp_reset_postdata();
      ?>
			<section class="">
				<header class="page-header">
					<h1 class="page-title">Submit a story</h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<?php
						printf(
							'<p>Ready to publish your %s post? <a href="%s">Get started here</a>.</p>',
							$noun, esc_url( home_url( '/form-to-post' ))
						);
					?>
				</div><!-- .page-content -->
			</section><!-- .no-results -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
