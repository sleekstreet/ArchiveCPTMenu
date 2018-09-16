<?php
/**
 * Template Name: Custom Post Type Archive
 * Template Post Type: post, page, bsj
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * 
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
                                            'post_type' => 'bsj',
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
                                                        <div class="image image-rewrite" style="background-image: url('<?php bloginfo('template_directory'); ?>/assets/img/bsj-default-thumb.jpg');"></div>
                                                    <?php endif; ?>
                                                    </a>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div class="metadata">
                                                        <?php the_time('F jS, Y'); ?> &bull;
                                                        <?php $terms = wp_get_object_terms( $post->ID,  'bsj-category' );
                                                        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
                                                            echo '<ul>';
                                                            foreach( $terms as $term ) :
                                                                $termslist[] =  '<li><a href="' . get_term_link( $term->slug, 'bsj-category' ) . '">' . esc_html( $term->name ) . '</a></li>';
                                                            endforeach;
                                                            echo implode(", ",$termslist);
                                                            unset($termslist);
                                                            echo '</ul>';
                                                        endif; ?>
                                                    </div>
                                                    <h5 class="title">
                                                        <?php if(class_exists('ICC_SIMPLE_MEMBERSHIP') && ICC_SIMPLE_MEMBERSHIP::showMemberLock($post->ID)) :
                                                            echo '<i class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="For ICC Members only"></i>';
                                                        endif; ?>
                                                        <?php
                                                        $bsj_sponsored= get_field('bsj-sponsored');
                                                            if ($bsj_sponsored) {
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
                                    id="bsj-load-more-archive-posts"
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
                <div class="bsj-info">
                    <h6>Submissions</h6>
                    <div><a href="https://www.iccsafe.org/wp-content/uploads/2018-editorial-calendar.pdf" target="_blank" ref="noopener">Check out upcoming BSJ topics</a> and send us articles for consideration:</div>
                    <div class="meta-text">
                        <button onclick="location.href='/content/bsj-submissions';" type="button" class="btn btn-primary d-block w-100 mx-auto my-2">Submit</button>
                        <div>Or <a href="mailto:tlukasik@iccsafe.org?subject=Building Safety Journal submission">send by email</a></div>
                    </div>
                    <hr />
                </div>
                <?php if(class_exists('ICC_AD')) ICC_AD::showAdds(); ?><!-- ads widget -->
                <div class="bsj-archives">
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
                            'post_type'       => 'bsj'
                        ));


                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main><!-- #main-content -->
<script>
    var bsj_posts = '<?php echo serialize( $category_query->query_vars ) ?>',
    bsj_current_page = 1,
    bsj_max_page = <?php echo $category_query->max_num_pages ?>,
    bsj_tax_query = '<?php echo serialize( $category_query->tax_query ) ?>'
</script>
<?php
get_footer();