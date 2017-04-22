<?php
/**
 * Template Name:WorkOut
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

get_header();
?>
	
<!-- #Content -->
<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group">
		
			<div class="entry-content" itemprop="mainContentOfPage">
			
				<?php 
					while ( have_posts() ){
						the_post();							// Post Loop
						mfn_builder_print( get_the_ID() );	// Content Builder & WordPress Editor Content
					}
				?>
				
				<div class="section section-page-footer">
					<div class="section_wrapper clearfix">
					
					<div class="press-list-wrapper">
<div class="newsletthome">
<h4>TRAINING VIDEOS</h4>
</div>
<ul class="video-blog-list">

	<?php
	$args =array('post_type'=>'workout','posts_per_page'=> 1, 'order_by'=>'ASC');
	$workout= new WP_query($args);
	 while ($workout->have_posts()) : $workout->the_post();
                           $ifrme_code = get_field('iframe_code');?>
	<li data-src="<?php echo $video_src; ?>"><?php echo $ifrme_code;?></li>

	<?php endwhile; ?>
	
  </ul>	
</div>

<div class="Right-side-section">
<ul class="tab-nav">
 	<li><a href="javascript:void(0)" class="active" rel="recent-post">Recent Post</a></li>
	<li><a href="javascript:void(0)" rel="popular-post">Popular Post</a></li>
 	
</ul>

<div class="nav-content">
<div id="recent-post"  class="tab in">
<ul class="video-blog-list">
<h3>Recent Post</h3>

	<?php
	$args =array('post_type'=>'workout', 'posts_per_page'=> 3, 'order_by'=>'ASC');
	$workout= new WP_query($args);
	 while ($workout->have_posts()) : $workout->the_post();
                           $ifrme_code = get_field('iframe_code');?>
	<li data-src="<?php echo $video_src; ?>"><?php echo $ifrme_code;?></li>

	<?php endwhile; ?>
	
  </ul>	
  </div>
  
 
       
<div id="popular-post" class="tab">
  <ul class="video-blog-list">
<h3>Popular Post</h3>

<ul class="posts">
	<?php
$catid=18;
$args = ['post_type' => 'workout',
    'tax_query' => [
        [ 
            'taxonomy' => 'popular_video',
            'terms' => 18,
            'include_children' => true 
        ],
    ],
    
];
    	$workout= new WP_query($args);
	 while ($workout->have_posts()) : $workout->the_post();

                           $ifrme_code = get_field('iframe_code');?>
	<li data-src="<?php echo $video_src; ?>"><?php echo $ifrme_code;?></li>

	<?php endwhile; ?>
	</div>

</ul>

</div>
</div>

					
					
						<?php /*?><div class="column one page-pager">
							<?php
								// List of pages
								wp_link_pages(array(
									'before'			=> '<div class="pager-single">',
									'after'				=> '</div>',
									'link_before'		=> '<span>',
									'link_after'		=> '</span>',
									'next_or_number'	=> 'number'
								));
							?>
						</div><?php */?>
						
			
					</div>
				</div>
                            <div class="other-threelayout">
                                <ul class="video-blog-list-other">

	<?php
	$args =array('post_type'=>'workout','posts_per_page'=> 100,'order_by'=>'ASC');
	$workout= new WP_query($args);
	 while ($workout->have_posts()) : $workout->the_post();
                           $ifrme_code = get_field('iframe_code');?>
	<li data-src="<?php echo $video_src; ?>"><?php echo $ifrme_code;?></li>

	<?php endwhile; ?>
	
  </ul>	
                                
                            </div>
			</div>
			
			<?php if( mfn_opts_get('page-comments') ): ?>
				<div class="section section-page-comments">
					<div class="section_wrapper clearfix">
					
						<div class="column one comments">
							<?php comments_template( '', true ); ?>
						</div>
						
					</div>
				</div>
			<?php endif; ?>
	
		</div>
		
		<!-- .four-columns - sidebar -->
		<?php //get_sidebar(); ?>

	</div>
</div>

<script>
(function($){
$(document).ready(function(){
	$(".tab-nav li a").click(function(){
		$(".tab-nav li a").removeClass("active");$(this).addClass("active");
		$(".nav-content .tab").removeClass("in");$rel=$(this).attr("rel");
		$('#'+$rel).addClass("in");
	});
	
	
});

}(jQuery));
</script>

<?php get_footer(); ?>