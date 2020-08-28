<?php
/**
 * Template Name: Custom Post Type Archive
 * Template Post Type: post, page, Custom_Post_Types
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

$q = get_queried_object();

list($r, $yearUrl, $monthUrl, $query) = explode("/", $_SERVER['REQUEST_URI']);
if(isset($yearUrl) && $yearUrl!="")$year = intval($yearUrl);
if(isset($monthUrl) && $monthUrl!="")$month = intval($monthUrl);


switch ($month) {
    case 1:
        $monthName = "January";
        break;
    case 2:
        $monthName = "Febuary";
        break;
    case 3:
        $monthName = "March";
        break;
    case 4:
        $monthName = "April";
        break;
    case 5:
        $monthName = "May";
        break;
    case 6:
        $monthName = "June";
        break;
    case 7:
        $monthName = "July";
        break;
    case 8:
        $monthName = "August";
        break;
    case 9:
        $monthName = "September";
        break;
    case 10:
        $monthName = "October";
        break;
    case 11:
        $monthName = "November";
        break;
    case 12:
        $monthName = "December";
        break;
}

include(TEMPLATEPATH . '/header.php');
?>

<script>
    jQuery(document).ready(function( $ ) {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<main id="main-content" class="container-fluid cpt-archive">  
    <div class="row header">
        <div class="container-fluid">
            <div class="container">
                <div class="col-12 title">
                    <h1>Archive</h1>
                    <?php if(!isset($monthName) && (!isset($year) || $year===0)):
                        echo "<h2>Current Articles</h2>";
                    elseif(isset($monthName) && (!isset($year) || $year===0)):
                        echo "<h2>Current Articles from the month of ".$monthName."</h2>";
                    else:
                        echo "<h2>Articles from ".$monthName." ".$year."</h2>";
                    endif ?>
                </div>
            </div>
        </div>
    </div><!-- header -->
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="content-area">
                    <div id="content" class="site-content" role="main">
                        <div class="row content">
                            <div class="col-12">
                                <?php if (is_tax() || is_category() || is_tag() || is_month() || is_archive()) :
                                    $category_query = new WP_Query( array(
                                            'posts_per_page' => 6,
                                            'post_type' => 'Custom_Post_Types',
                                            'post_status' => 'publish',
                                            'paged' => 1,
                                            'date_query' => array(
                                                array(
                                                    'year' => $year,
                                                    'month' => $month

                                                ),
                                            ),
                                        )
                                    );

                                    if ( $category_query->have_posts() ) :
                                        while ( $category_query->have_posts() ) :
                                            $category_query->the_post(); ?>
                                            <article class="row">
                                                <div class="col-12 col-md-4">
                                                    <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                                                    <?php if ( has_post_thumbnail() ) : ?>
                                                        <div class="image image-rewrite" style="background-image: url('<?php the_post_thumbnail_url( 'large' ); ?>');"></div>
                                                    <?php else : ?>
                                                        <div class="image image-rewrite" style="background-image: url('<?php bloginfo('template_directory'); ?>/assets/img/Custom_Post_Types-default-thumb.jpg');"></div>
                                                    <?php endif; ?>
                                                    </a>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div class="metadata">
                                                        <?php the_time('F jS, Y'); ?> &bull;
                                                        <?php $terms = wp_get_object_terms( $post->ID,  'Custom_Post_Types-category' );
                                                        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
                                                            echo '<ul>';
                                                            foreach( $terms as $term ) :
                                                                $termslist[] =  '<li><a href="' . get_term_link( $term->slug, 'Custom_Post_Types-category' ) . '">' . esc_html( $term->name ) . '</a></li>';
                                                            endforeach;
                                                            echo implode(", ",$termslist);
                                                            unset($termslist);
                                                            echo '</ul>';
                                                        endif; ?>
                                                    </div>
                                                    <h5 class="title">
                                                        
                                                        <?php
                                                        $Custom_Post_Types_sponsored= get_field('Custom_Post_Types-sponsored');
                                                            if ($Custom_Post_Types_sponsored) {
                                                                echo '<i class="fa fa-star" data-toggle="tooltip" data-placement="top" title="This is a Sponsored Article."></i>';
                                                            };
                                                            ?>
                                                        <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                                                            <?php the_title(); ?>
                                                        </a>
                                                    </h5>
                                                    <h6 class="subtitle">
                                                        <?php echo get_post_meta( $post->ID, 'subtitle', true ); ?>
                                                    </h6>
                                                    <div class="entry">
                                                        <?php the_excerpt(); ?>
                                                    </div>
                                                    <div class="full-link">
                                                        <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="read-more">
                                                            Read More
                                                        </a>
                                                    </div>
                                                </div>
                                            </article>
                                        <?php endwhile; ?>
                                        <?php wp_reset_postdata(); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if($category_query->max_num_pages > 1): ?>
                                    <button
                                    id="Custom_Post_Types-load-more-archive-posts"
                                    class="btn btn-secondary load-more"
                                    data-page="2"
                                    data-pagemax ="<?= $category_query->max_num_pages; ?>"
                                    data-month="<?= $month; ?>"
                                    data-year="<?= $year; ?>"
                                    data-field="id"
                                    data-operator="IN"
                                    >Load more&nbsp;</button>
                                <?php endif; ?>
                            </div>
                        </div><!-- #content -->
                    </div><!-- #primary -->
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <div class="Custom_Post_Types-info">
                    <h6>Submissions</h6>
                    <div class="meta-text">
                        <button onclick="location.href='/content/Custom_Post_Types-submissions';" type="button" class="btn btn-primary d-block w-100 mx-auto my-2">Submit</button>
                    </div>
                    <hr />
                </div>
                
                <div class="Custom_Post_Types-archives">
                    <h4>Past Issues</h4>
                    <ul>
                        <?php
                        wp_get_archives(array(
                            'type'            =>  'monthly',
                            'limit'           =>  '',
                            'format'          =>  'html',
                            'before'          => '',
                            'after'           => '',
                            'show_post_count' => false,
                            'echo'            => 1,
                            'order'           => 'DESC',
                            'post_type'       => 'Custom_Post_Types'
                        ));


                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main><!-- #main-content -->
<script>
    var Custom_Post_Types_posts = '<?php echo serialize( $category_query->query_vars ) ?>',
    Custom_Post_Types_current_page = 1,
    Custom_Post_Types_max_page = <?php echo $category_query->max_num_pages ?>,
    Custom_Post_Types_tax_query = '<?php echo serialize( $category_query->tax_query ) ?>'
</script>
<?php
get_footer();