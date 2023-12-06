<?php

/**
 * Class InactiveUsers
 * This is a really bad code, hardcode, fucking code....
 * For remove inactive user by last session time ( older that 3 year )
 * I'm sorry, but i don't have time for better code ))
 */

class InactiveUsers {
	private $inactive_users_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'inactive_users_add_plugin_page' ) );
	}

	public function inactive_users_add_plugin_page() {
		add_users_page(
			'Inactive Users', // page_title
			'Inactive Users', // menu_title
			'manage_options', // capability
			'inactive-users', // menu_slug
			array( $this, 'inactive_users_create_admin_page' ) // function
		);
	}

	public function inactive_users_create_admin_page() {
		$this->inactive_users_options = get_option( 'inactive_users_option_name' );
		require_once( ABSPATH . 'wp-admin/includes/user.php' );

		$year_3 = (365 * 86400) * 3;
		$compare_time = time() - $year_3;
		?>


        <div class="wrap">
            <h2>Inactive Users</h2>
            <p>Automatically delete inactive users older that <?= Date('d-m-Y',$compare_time) ?></p>
			<?php
			echo $_GET['from'];


			?>
            <table id="inactive-users">
                <tr>
                    <th>id</th>
                    <th>login</th>
                    <th>email</th>
                    <th>last login</th>
                    <th>Status</th>
                </tr>
                <tbody>
				<?php


				$user_query   = new WP_User_Query(  ); //array( 'number' => 1000, 'offset' => 0 )

				if ( ! empty( $user_query->results ) ) {
					foreach ( $user_query->results as $user ) {

						$user_id = $user->ID;

						$sessions = get_user_meta( $user_id, 'session_tokens' )[0];

						if ( ! is_array( $sessions ) ) {
							continue;
						}

						$last_login = end( $sessions )['login'];

						?>

						<?php if ( $last_login < $compare_time ):

							?>
                            <tr>
                                <td>
									<?php echo $user_id; ?>
                                </td>
                                <td>
									<?php echo $user->user_login; ?>
                                </td>
                                <td>
									<?php echo $user->user_email; ?>
                                </td>
                                <td>
									<?php echo Date( 'j-m-Y', $last_login ); ?>
                                </td>
                                <td>
									<?php
									echo( wp_delete_user( $user_id ) ? 'deleted' : 'error' );
									?>
                                </td>
                            </tr>

						<?php endif; ?>


						<?php
					}
				} else {
					echo 'No users found.';
				}
				?>
                </tbody>
            </table>
            <iframe id="txtArea1" style="display:none"></iframe>
            <button id="btnExport" onclick="fnExcelReport();"> EXPORT</button>
            <script>
              function fnExcelReport () {
                var tab_text = '<table border=\'2px\'><tr bgcolor=\'#87AFC6\'>'
                var textRange
                var j = 0
                tab = document.getElementById('inactive-users') // id of table

                for (j = 0; j < tab.rows.length; j++) {
                  tab_text = tab_text + tab.rows[j].innerHTML + '</tr>'
                  //tab_text=tab_text+"</tr>";
                }

                tab_text = tab_text + '</table>'
                tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, '')//remove if u want links in your table
                tab_text = tab_text.replace(/<img[^>]*>/gi, '') // remove if u want images in your table
                tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, '') // reomves input params

                var ua = window.navigator.userAgent
                var msie = ua.indexOf('MSIE ')

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                  txtArea1.document.open('txt/html', 'replace')
                  txtArea1.document.write(tab_text)
                  txtArea1.document.close()
                  txtArea1.focus()
                  sa = txtArea1.document.execCommand('SaveAs', true, 'Say Thanks to Sumit.xls')
                } else                 //other browser not tested on IE 11
                  sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text))

                return (sa)
              }
            </script>
        </div>

		<?php
	}

}

if ( is_admin() ) {
	$inactive_users = new InactiveUsers();
}

/*
 * Retrieve this value with:
 * $inactive_users_options = get_option( 'inactive_users_option_name' ); // Array of All Options
 * $last_date_0 = $inactive_users_options['last_date_0']; // Last Date
 */
