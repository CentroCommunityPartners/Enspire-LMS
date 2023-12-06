<?php

add_action(
    'learndash_course_completed',
    function ( $data ) {
        // Example 2 ANY user completed Course 4...
        if ( $data['course']->ID == 19010 ) {

            wp_redirect( home_url('/survey') ); exit; 

        }
    },
    20
);