<?php
/*
Plugin Name: Archive Template
*/

function your_plugin_archive_template( $template ) {
    if ( is_post_type_archive( 'product' ) ) {
        $new_template = plugin_dir_path( __FILE__ ) . '/templates/archive-product.php';
        if ( file_exists( $new_template ) ) {
            return $new_template;
        }
    }
    return $template;
}
add_filter( 'archive_template', 'your_plugin_archive_template' );

?>

<!-- archive-product.php -->
<?php get_header(); ?>

<div class="container">
    <div class="row">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'medium', array( 'class' => 'card-img-top' ) ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-body">
                            <h4 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <?php if ( get_post_meta( get_the_ID(), '_price', true ) ) : ?>
                                <h5><?php echo get_post_meta( get_the_ID(), '_price', true ); ?></h5>
                            <?php endif; ?>
                            <?php $product_categories = get_the_terms( get_the_ID(), 'category' ); ?>
                            <?php if ( $product_categories && ! is_wp_error( $product_categories ) ) : ?>
                                <div class="mb-2">
                                    <?php foreach ( $product_categories as $product_category ) : ?>
                                        <span class="badge bg-secondary"><?php echo esc_html( $product_category->name ); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php $product_brands = get_the_terms( get_the_ID(), 'brand' ); ?>
                            <?php if ( $product_brands && ! is_wp_error( $product_brands ) ) : ?>
                                <div class="mb-2">
                                    <?php foreach ( $product_brands as $product_brand ) : ?>
                                        <span class="badge bg-secondary"><?php echo esc_html( $product_brand->name ); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No products found.', 'your-textdomain' ); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>