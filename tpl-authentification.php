<?php
/**
 * Template Name: Authentification
 */

get_header();

//$plan_monthly_id = 95566;
//$plan_yearly_id = 95615;

?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <div class="authentification-layout">


                <div class="authentification-layout__hero alignfull mt-remove-main">
                    <h1 class="authentification-layout__title"><?php the_title() ?></h1>
                </div>

                <div class="authentification-layout__content container-small">

                    <div class="es-signup monthly">

                        <div class="es-signup__header block-p">

                            <div class="es-signup__header-row">

                                <div class="es-signup__header-left">
                                    <span>Membership Plan</span>
 
                                </div>

                                <div class="es-signup__header-right">

                                    <?php echo do_shortcode( 'mepr-membership-registration-form id="1886"' ) ?>

                                </div>

                            </div>


                        </div>


                    </div>

                </div>

            </div>

        </main><!-- #main -->
    </div>
<?php
get_footer();



