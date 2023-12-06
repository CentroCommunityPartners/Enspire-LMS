<?php
function es_get_buddyboss_cell_phone($user_id) {
    $field_id = 20;
    return  xprofile_get_field_data($field_id, $user_id);
} 