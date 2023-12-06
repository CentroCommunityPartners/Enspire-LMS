<?php


/***********************************************************************
 *                           THUMBNAIL                                 *
 **********************************************************************/

function es_insert_pharsed_thumbnail($image_url, $post_id)
{
    // Add Featured Image to Post
    $image_pathinfo = pathinfo($image_url);
    $image_extension = $image_pathinfo['extension'];

    if (empty($image_extension)) return;

    $image_name = $image_pathinfo['filename'] . '.' . $image_extension;
    $upload_dir = wp_upload_dir(); // Set upload folder
    $image_data = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
    $filename = basename($unique_file_name); // Create image file name

// Check folder permission and define file location
    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

// Create the image  file on the server
    file_put_contents($file, $image_data);

// Check image file type
    $wp_filetype = wp_check_filetype($filename, null);

// Set attachment data
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );

// Create the attachment
    $attach_id = wp_insert_attachment($attachment, $file, $post_id);

// Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

// Define attachment metadata
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);

// Assign metadata to attachment
    wp_update_attachment_metadata($attach_id, $attach_data);

// And finally assign featured image to post
    set_post_thumbnail($post_id, $attach_id);
}


/***********************************************************************
 *                             USER                                    *
 **********************************************************************/

function es_get_parser_author_id()
{
    $parserUser = [
        'user_login' => 'centro-blog',
        'user_email' => 'centro-blog@centro.com',
        'user_pass' => wp_generate_password(),
        'first_name' => 'Centro',
        'last_name' => 'Blog',
        'display_name' => 'CENTRO Blog',
        'role' => 'editor',
        'description' => __('This user has been programmatically created to serve as the author of automatically parsed Blog entries.'),
    ];

    $userByLogin = username_exists($parserUser['user_login']);
    $userByEmail = email_exists($parserUser['user_email']);

    if ($userByLogin || $userByEmail) { // if either of them is an user ID (i.e. != false)
        $userID = $userByLogin ? $userByLogin : $userByEmail; // userID = whichever is not false
        es_write_parser_log(sprintf(__('Parser author user found (ID: %d)'), $userID));

        return $userID;
    } // $userByLogin || $userByEmail

    // otherwise write log and create user
    es_write_parser_log(sprintf(__('%s author user not found. Proceeding to create one.'), $parserUser['display_name']), 'warn');

    $userID = wp_insert_user($parserUser);

    if (is_wp_error($userID)) {
        es_write_parser_log(sprintf(__('Failed to create new %1$s user. Message: "%2$s"'), $parserUser['display_name'],
            $userID->get_error_message()), 'error');
        $userID = 1; // use the first user id as a fallback
    } else {
        es_write_parser_log(sprintf(__('New %1$s author user successfully created (ID: %2$d)'), $parserUser['display_name'], $userID));
    } // is_wp_error( $userID )

    return $userID;
}


/***********************************************************************
 *                             LOG                                     *
 **********************************************************************/
function es_file_force_contents($filePath, $contents, $flags = 0, $context = null)
{
    $parts = explode('/', $filePath);
    $file = array_pop($parts);
    $dir = '';
    foreach ($parts as $part) {
        if (!is_dir($dir .= "/$part")) {
            mkdir($dir);
        }
    }
    file_put_contents("$dir/$file", $contents, $flags, $context);
}

function es_write_parser_log($message, $type = 'info')
{
    $validTypes = ['info', 'debug', 'error', 'warn'];

    if (!in_array(strtolower($type), $validTypes))  $type = 'info';
    $type = strtoupper($type);

    $separator = '|';
    $dateTime = new DateTime();

    $log = sprintf('%s %s %s %s %-5s %s %s',
            $dateTime->format('Y-m-d'),
            $separator,
            $dateTime->format('H:i:s.v'),
            $separator,
            $type,
            $separator,
            $message) . PHP_EOL;

    $folder_year = $dateTime->format('Y');
    $folder_month = $dateTime->format('m');
    $file_name =  $dateTime->format('Y-m-d') . '.log';
    $file = PARSER_LOG_DIR. $folder_year . '/' . $folder_month. '/'.$file_name;

    es_file_force_contents($file, $log, FILE_APPEND);
}

function es_get_parser_log_content($date = null)
{
    $directory = PARSER_LOG_DIR;

    // Create the file path based on the provided date or current date if not specified
    $dateTime = $date ? new DateTime($date) : new DateTime();
    $folder_year = $dateTime->format('Y');
    $folder_month = $dateTime->format('m');
    $file_name = $dateTime->format('Y-m-d') . '.log';
    $file = $directory . $folder_year . '/' . $folder_month . '/' . $file_name;

    // Check if the file exists
    if (file_exists($file)) {
        // Read and return the file content
        return file_get_contents($file);
    } else {
        return 'File not found';
    }
}

/***********************************************************************
 *                            RETRIEVE                                 *
 **********************************************************************/

function es_retrieve_wordpress_posts($rss_atom)
{
    include_once(MODULES_PATH . '/pharser/pharse/pharse.php');

    $startTime = time();
    es_write_parser_log(__('Parsing started'), 'debug');

    $xml = Pharse::file_get_dom($rss_atom);

    if (!$xml) {
        $message = sprintf(__("Could not retrieve %s RSS feed."), PARSER['name']);
        es_write_parser_log($message, 'error');

        return new WP_Error(500, __($message));
    }

    $entries = [];

    foreach ($xml('entry') as $entry) {
        $entryProps = [
            'title' => '',
            'author' => '',
            'source' => '',
            'published' => '',
            'updated' => '', //todo make functionality update posts, now is only insert
            'summary' => '',
            'content' => '',
            'categories' => [],
        ];

        $entryProps['hash'] = hash('sha256', trim($entry->select('id', 0)->getPlainText()));
        $entryProps['title'] = trim($entry->select('title', 0)->getPlainText());
        $entryProps['author'] = trim($entry->select('author', 0)->getPlainText());
        $entryProps['source'] = trim($entry->select('link', 0)->getAttribute('href'));
        $entryProps['published'] = trim($entry->select('published', 0)->getPlainText());
        $entryProps['summary'] = trim($entry->select('summary', 0)->getPlainText());
        $entryProps['content'] = $entry->select('content', 0)->getInnerText();

        foreach ($entry->select('category') as $category) {
            $entryProps['categories'][] = $category->getAttribute('term');
        }

        // validate if the props are not empty
        foreach ($entryProps as $key => $value) {
            if (empty($value)) {
                es_write_parser_log(sprintf('Empty property: "%s" for entry: "%s"', $key, $entryProps['title']), 'warn');

                if ($key === 'summary' || $key === 'categories') {
                    continue; // skip validating optional summary,categories field
                }

                $message = __('Could not reliably parse the RSS feed - empty properties detected.');
                es_write_parser_log($message, 'error');

                return new WP_Error(500, __($message));
            }
        }

        $entries[] = $entryProps;
    }

    $parsingTime = time() - $startTime;
    es_write_parser_log(sprintf(__('Parsing finished after %d sec.'), $parsingTime), 'debug');

    return $entries;
}

/**
 * SquareSpace JSON Parser
 * $url = https://www.centrocommunity.org/blog/?format=json-pretty
 */
function es_retrieve_squarespace_posts($url)
{
    $startTime = time();
    es_write_parser_log(__('Parsing started'), 'debug');

    function curl_get_contents($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT,
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    $data = json_decode(curl_get_contents($url))->items;

    if (empty($data[0])) {
        $message = sprintf(__("Could not retrieve %s RSS feed."), PARSER['name']);
        es_write_parser_log($message, 'error');

        return new WP_Error(500, __($message));
    }

    $entries = [];

    foreach ($data as $entry) {

        $entryProps = [
            'hash' => '',
            'title' => '',
            'author' => '',
            'summary' => '',
            'thumbnail_url' => '',
            'published' => '',
            'updated' => '', //todo make functionality update posts, now is only insert
            'content' => '',
            'categories' => [],
        ];


        $entryProps['hash'] = hash('sha256', trim($entry->id));
        $entryProps['title'] = trim($entry->title);
        $entryProps['published'] = round($entry->addedOn / 1000);
        $entryProps['updated'] = round($entry->updatedOn / 1000);
        $entryProps['content'] = $entry->body;
        $entryProps['categories'] = $entry->categories;

        if (strpos($entry->contentType, 'image') !== false) {
            $entryProps['thumbnail_url'] = $entry->assetUrl;
        }

        $entries[] = $entryProps;
    }

    $parsingTime = time() - $startTime;
    es_write_parser_log(sprintf(__('Parsing finished after %d sec.'), $parsingTime), 'debug');


    return $entries;
}



/***********************************************************************
 *                              INSERT                                 *
 **********************************************************************/

function es_insert_parsed_posts() {
    $authorID = es_get_parser_author_id();

    //$entries = array_reverse( es_retrieve_wordpress_posts(PARSER['feed']));
    $entries =  array_reverse( es_retrieve_squarespace_posts( PARSER['feed'] ));

    if($entries[count($entries) - 1]['hash'] === get_option( PARSER['slug'] . '_feed_hash')){
        es_write_parser_log( __( 'Posts from feed already exists' ), 'warn' );
        return;
    }

    update_option( PARSER['slug'] . '_parser_status', 'running' );

    if ( is_wp_error( $entries ) ) {
        $message = $entries->get_error_message();
        es_write_parser_log( $message, 'error' );

        return new WP_Error( 500, $message );
    } // is_wp_error( $entries )

    if ( count( $entries ) === 0 ) {
        es_write_parser_log( __( 'Empty array of entries received - likely because the feed has not been updated (see previous log messages)' ),'warn' );
        return;
    }

    es_write_parser_log( sprintf( 'Successfully retrieved %d entries', count( $entries ) ) );


    foreach ( $entries as $entry ) {


        $queryParams = [
            'meta_key'   => PARSER['slug'] . '_entry_hash',
            'meta_value' => $entry['hash'],
            'post_type'  => PARSER['post_type'],
        ];

        $query = new WP_Query( $queryParams );

        if ( $query->have_posts() ) {
            es_write_parser_log( sprintf( __( 'Entry already exists: "%s"' ), $entry['title'] ), 'warn' );
            continue; // skip current foreach iteration
        }

        $entry['content'] = str_replace('data-src="','src="', $entry['content'] );

        $newPostArgs = [
            'post_author'  => $authorID,
            'post_date'    => date( 'Y-m-d H:i:s',  $entry['published']  ),
            'post_content' => $entry['content'],
            'post_title'   => wp_strip_all_tags( $entry['title'] ),
            'post_excerpt' => wp_strip_all_tags( $entry['summary'] ),
            'post_status'  => 'publish',
            'post_type'    => PARSER['post_type'],
        ];

        $newPostID = wp_insert_post( $newPostArgs );

        if ( is_wp_error( $newPostID ) ) {
            es_write_parser_log( sprintf( __( 'Failed to insert new entry to database. Entry: "%s". Message: "%s".' ),
                $entry['title'],
                $newPostID->get_error_message() ), 'error' );
            continue; // skip current foreach iteration
        } // is_wp_error( $newPostID )

        es_write_parser_log( sprintf( __( 'Entry "%s" successfully created (ID: %d)' ), $newPostArgs['post_title'], $newPostID ) );

        update_post_meta( $newPostID, PARSER['slug'] . '_entry_hash', $entry['hash'] );

        //Write Last Hash in Option
        update_option( PARSER['slug'] . '_feed_hash', $entry['hash'] );

        if(!empty($entry['thumbnail_url'])){
            es_insert_pharsed_thumbnail($entry['thumbnail_url'], $newPostID);
        }

        // add source link to acf custom field
        //update_field( 'url', $entry['source'], $newPostID );

        // define category array
        $postTerms = [];

        // now that we've successfully inserted the post, let's add the categories
        // first retrieve the main term
        if ( ! empty( PARSER['main_term'] ) ) {

            $mainTerm = array(
                'name' => PARSER['main_term'],
                'slug' => sanitize_title( PARSER['main_term'] ),
            );

            $parsTerm = term_exists( $mainTerm['slug'], PARSER['taxonomy'] );
            $termDescription
                = sprintf( __( 'This category has been programmatically created as a result of automatically parsed %s Blog entries.' ),
                PARSER['name'] );


            if ( ! is_array( $parsTerm ) ) { // valid result should be array
                es_write_parser_log( sprintf( __( '%s category not found' ), $mainTerm['name'] ), 'warn' );

                $parsTerm = wp_insert_term( $mainTerm['name'], PARSER['taxonomy'], [
                    'slug'        => $mainTerm['slug'],
                    'description' => $termDescription
                ] );

                if ( ! is_array( $parsTerm ) ) {
                    es_write_parser_log( sprintf( __( 'Failed to create required and non-existing %s category.' ), $mainTerm['name'] ), 'error' );
                    continue; // skip current foreach iteration
                } // ! is_array( $parsTerm )

                es_write_parser_log( sprintf( __( 'New %1$s category successfully created (ID: %2$d)' ), $mainTerm['name'], $parsTerm['term_id'] ) );
            } // ! is_array( $parsTerm )

            $postTerms = [ $mainTerm['slug'] ];
        }

        // parse categories, insert missing ones
        foreach ( $entry['categories'] as $category ) {
            $slug = sanitize_title( $category );

            $term = term_exists( $slug, PARSER['taxonomy'] );

            if ( ! is_array( $term ) ) {
                es_write_parser_log( sprintf( __( 'No category found with slug = "%s". Proceeding to create one.' ), $slug ), 'warn' );

                $term = wp_insert_term( ucwords( $category ), PARSER['taxonomy'],
                    [ 'parent' => $parsTerm['term_id'], 'slug' => $slug, 'description' => $termDescription ] );

                if ( ! is_array( $term ) ) {
                    es_write_parser_log( sprintf( __( 'Failed to create category with slug = "%s"' ), $slug ), 'error' );
                    continue; // skip current foreach iteration
                } // ! is_array( $term )

                es_write_parser_log( sprintf( __( 'Category "%s" successfully created (ID: %d)' ), $category, $term['term_id'] ) );
            }

            $postTerms[] = $slug;
        } // $entry['categories'] as $category

        // attach categories to newly created news entry
        if ( count( $postTerms ) > 0 ) {
            $setPostTerms = wp_set_object_terms( $newPostID, $postTerms, PARSER['taxonomy'] );
            $termsString  = implode( ', ', $postTerms );
            if ( ! is_array( $setPostTerms ) ) {
                $message = sprintf( __( 'Failed to set the following categories: %s' ), $termsString );
                es_write_parser_log( $message, 'error' );

                return new WP_Error( 500, $message );
            }
            es_write_parser_log( sprintf( __( 'Categories successfully set: %s' ), $termsString ) );
        }
    } // $entries as $entry

    update_option( PARSER['slug'] . '_parser_last_run', time() );
}