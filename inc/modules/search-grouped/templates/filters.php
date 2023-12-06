<?php
$get_type = isset($_GET['type']) ? $_GET['type'] : '';
$get_s_name = is_search() ? 's' : 'search';

$filter_params = json_encode(array(
    'type' => 'search',
    'getparam' => $get_s_name,
));
?>

<div class="ensp-ajax-filters">
    <div class="esl-form">

        <?php
        if ($settings['has_grouped_section'] && count($post_types) > 1) :
            $option_format = '<option class="ensp-filter-input__item" %s value="%s">%s</option>';

            $filter_params = json_encode(array(
                'type' => 'post-type',
                'getparam' => 'type',
            ));
            ?>
            <div class="ensp-filter esl-field" data-filter='<?= $filter_params ?>'>
                <select class="ensp-filter-input">
                    <?php
                    printf($option_format, '', '', 'Type');

                    foreach ($post_types as $post_type_id) {
                        $post_type_obj = get_post_type_object($post_type_id);

                        if (!$post_type_obj) {
                            continue;
                        }


                        $selected = $post_type_id == $get_type ? 'selected' : '';


                        $post_type_name = $post_type_obj->labels->singular_name;

                        printf($option_format, $selected, $post_type_id, $post_type_name);
                    }
                    ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="ensp-filter esl-field search-field search-form" data-filter='<?= $filter_params ?>'>
            <input type="text" placeholder="SEARCH" class="ensp-filter-input" value="<?= $s ?>">
            <button type="submit"><i class="icon-search"></i></button>
        </div>

        <?php
        if (isset($settings['has_grouped_section']) && count($post_types) > 1) {
            echo '<button id = "ensp-filter-clearall" class="button-transparent" > Clear All </button >';
        } ?>
    </div>
</div>