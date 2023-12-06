<?php
$defaults = [
    'class_wrap' => '',
    'header' => [
        ['thumbnail', ['size' => 'medium', 'has_permalink' => true]]
    ],
    'body' => [
        ['meta', [['meta_date', ['format' => 'j F, Y']], ['meta_taxonomy', ['taxonomy' => 'category']]]],
        ['title', ['tag' => 'h4', 'link' => true]],
        ['excerpt']
    ],
    'footer' => [
        ['permalink', ['text' => 'Read more', 'class' => 'postcard__permalink btn-transparent']]
    ],
];


$post_id = get_the_id();
$args = isset($args) ? wp_parse_args($defaults, $args) : $defaults;
$class_wrap = $args['class_wrap'] ? ' ' . $args['class_wrap'] : '';

?>

<article class="postcard<?= $class_wrap ?>">
    <?php
    $card_header = isset($args['header']) ? es_get_postcard_part($post_id, $args['header']) : null;
    $card_body = isset($args['body']) ? es_get_postcard_part($post_id, $args['body']) : null;
    $card_footer = isset($args['footer']) ? es_get_postcard_part($post_id, $args['footer']) : null;

    if ($card_header) printf('<div class="postcard__header">%s</div>', $card_header);
    if ($card_body) printf('<div class="postcard__body">%s</div>', $card_body);
    if ($card_footer) printf('<div class="postcard__footer">%s</div>', $card_footer);
    ?>
</article>