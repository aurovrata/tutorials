<?php
/**
 * The template for displaying all links in course.
 * Template Name: Display course links
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

get_header();

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div id="course-table" class="cf7sg-container">
				<?php
				$courses = get_posts(array(
					'post_type'=>'course-url-form',
					'post_status'=>'any'
				));
				foreach($courses as $course):
					$cc = get_post_meta($course->ID, 'chapter', true);
					$cp = get_post_meta($course->ID, 'page', true);
					$pu = get_post_meta($course->ID, 'url', true);
					$chapters = array();
					foreach($cc as $idx=>$chapter){
				    $uc = sanitize_title($chapter);
				    if(!isset($chapters[$uc])){
				      $chapters[$uc] = array(
				        'text'=>$chapter,
				        'pages'=>array()
				      );
				    }
				    if(isset($cp[$idx])){ //pages
				      $page = $cp[$idx];
				      $up = sanitize_title($page);
				      if(!isset($chapters[$uc]['pages'][$up])){
				        $chapters[$uc]['pages'][$up] = array(
				          'text'=>$page,
				          'urls'=>array()
				        );
				      }
				      if(isset($pu[$idx])){ //linnks
				        $url = $pu[$idx];
				        $chapters[$uc]['pages'][$up]['urls'][] = $url;
				      }
				    }
				  }
				?>
				<div class="course cf7-smart-grid has-grid">
					<h2><?=$course->post_title?></h2>
					<div class="container">
						<?php foreach($chapters as $c):?>
						<div class="row chapter">
							<div class="columns three"><p><?= $c['text']?></p></div>
							<div class="columns nine">
								<?php foreach($c['pages'] as $p):?>
									<div class="row page">
										<div class="columns four"><p><?= $p['text']?></p></div>
										<div class="columns eight">
											<?php foreach($p['urls'] as $url):?>
												<div class="row url">
													<div class="columns full"><a href="<?=$url?>"><?=$url?></a></div>
												</div>
											<?php endforeach;?>
										</div>
									</div>
								<?php endforeach;?>
							</div>
						</div>
					<?php endforeach;?>
					</div>
				<?php endforeach;?>
				</div>
			</div>
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
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
