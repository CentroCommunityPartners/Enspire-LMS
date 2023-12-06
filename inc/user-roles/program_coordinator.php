<?php
//$cap_leader_group        = get_role( 'group_leader' )->capabilities;
//$cap_program_coordinator = get_role( 'program_coordinator' )->capabilities;
//$cap_program_coordinator = array_merge( $cap_leader_group, $cap_program_coordinator );
//
//$custom_cap              = array(
//	'delete_essays'                       => true,
//	'delete_groups'                       => true,
//	'delete_others_assignments'           => true,
//	'delete_others_essays'                => true,
//	'delete_others_groups'                => true,
//	'delete_private_groupss'              => true,
//	'delete_published_assignments'        => true,
//	'delete_published_essays'             => true,
//	'delete_published_groups'             => true,
//	'edit_assignments'                    => true,
//	'edit_essays'                         => true,
//	'edit_groups'                         => true,
//	'edit_others_assignments'             => true,
//	'edit_others_essays'                  => true,
//	'edit_others_groups'                  => true,
//	'edit_private_groupss'                => true,
//	'edit_published_assignments'          => true,
//	'edit_published_essays'               => true,
//	'edit_published_groups'               => true,
//	'group_leader'                        => true,
//	'level_0'                             => true,
//	'propanel_widgets'                    => true,
//	'publish_essays'                      => true,
//	'publish_groups'                      => true,
//	'read'                                => true,
//	'read_assignment'                     => true,
//	'read_essays'                         => true,
//	'read_private_essays'                 => true,
//	'read_private_groups'                 => true,
//	'wpProQuiz_show_statistics'           => true,
//	'upload_files'                        => true,
//	'edit_courses'                        => true,
//	'edit_published_courses'              => true,
//	'delete_courses'                      => true,
//	'delete_published_courses'            => true,
//	'publish_courses'                     => true,
//	'delete_private_courses'              => true,
//	'edit_private_courses'                => true,
//	'read_private_courses'                => true,
//	'copy_posts'                          => true,
//	'create_posts'                        => true,
//	'delete_assignments'                  => true,
//	'delete_posts'                        => true,
//	'delete_private_assignments'          => true,
//	'delete_private_posts'                => true,
//	'delete_published_posts'              => true,
//	'edit_posts'                          => true,
//	'edit_private_assignments'            => true,
//	'edit_private_posts'                  => true,
//	'edit_published_posts'                => true,
//	'manage_options'                      => true,
//	'publish_assignments'                 => true,
//	'publish_posts'                       => true,
//	'read_course'                         => true,
//	'read_group'                          => true,
//	'read_private_assignments'            => true,
//	'read_private_posts'                  => true,
//	'users_insights_export_users'         => true,
//	'users_insights_list_users'           => true,
//	'users_insights_manage_custom_fields' => true,
//	'users_insights_manage_groups'        => true,
//	'users_insights_manage_options'       => true,
//	'users_insights_manage_segments'      => true,
//	'users_insights_update_user_profile'  => true,
//	'users_insights_view_reports'         => true,
//);

//remove_role( 'program_coordinator' );
//add_role( 'program_coordinator', __( 'Program Coordinator' ), $custom_cap );


/**
 * Edit/Remove Menu items
 */
function edit_program_coordinator_menu() {
	$current_role = wp_get_current_user()->roles[0];
	if ( $current_role != 'program_coordinator' ) {
		return;
	}

	global $menu;
	global $submenu;

	$submenu_key     = 'learndash-lms';
	$subitems_remove = array( 'Overview', 'Notifications', 'Reports', 'Settings', 'Add-ons' );
	$submenu         = remove_menu_subitems( $submenu, $submenu_key, $subitems_remove );


	$submenu_key     = 'badgeos_badgeos';
	$subitems_remove = array(
		'Welcome',
		'Settings',
		'Assets',
		'OB Integration',
		'Add-Ons',
		'Help / Support',
		'Tools'
	);
	$submenu         = remove_menu_subitems( $submenu, $submenu_key, $subitems_remove );


}

add_action( 'admin_menu', 'edit_program_coordinator_menu', 9999 );


function remove_menu_subitems( $submenu, $submenu_key, $remove_subitems ) {

	if ( ! is_array( $remove_subitems ) || empty( $submenu_key ) ) {
		return $submenu;
	}

	foreach ( $submenu[ $submenu_key ] as $key => $arr ) {
		if ( in_array( $arr[0], $remove_subitems ) ) {
			unset( $submenu[ $submenu_key ][ $key ] );
		}
	}

	return $submenu;
}


/**
 * Remove Notification Dashboard for low users
 */
add_action( 'admin_enqueue_scripts', 'program_coordinator_theme_style' );
add_action( 'login_enqueue_scripts', 'program_coordinator_theme_style' );

function program_coordinator_theme_style() {
	$user_role = wp_get_current_user()->roles[0];
	if ( $user_role == 'program_coordinator' ) {
		?>
        <style>
            li#toplevel_page_users_insights,
            a#tab-sfwd-courses_page_courses-options,
            a#tab-sfwd-courses_page_courses-shortcodes,
            a#tab-sfwd-lessons_page_lessons-options,
            a#tab-sfwd-topic_page_topics-options,
            a#tab-sfwd-quiz_page_quizzes-options,
            a#tab-groups_page_groups-options,
            a#tab-sfwd-question_page_questions-options,
            a#tab-sfwd-certificates_page_certificate-options,
            a#tab-sfwd-assignment_page_assignments-options{
                display: none ! important;
            }
        </style>
		<?php
	}
}