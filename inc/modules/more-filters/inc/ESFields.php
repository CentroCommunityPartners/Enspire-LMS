<?php

abstract class EsFieldInput
{
    protected $type;
    protected $id;
    protected $name;
    protected $slug;
    protected $value;
    protected $label;
    protected $labels;
    protected $attr;
    protected $settings;
    protected $class_wrap;
    protected $class_input;
    protected $is_required;
    protected $conditional_logic;
    protected $is_conditional_logic;
    protected $context;


    function __construct($args = [])
    {
        if (!$args) {
            return;
        }

        $this->setAll($args);
    }

    function setAll($args)
    {
        $this->name = $args['name'];
        $this->slug = isset($args['slug']) ? $args['slug'] : $args['name'];
        $this->value = isset($args['value']) ? $args['value'] : '';
        $this->label = $this->build_label($args);
        $this->labels = isset($args['labels']) ? $args['labels'] : '';
        $this->id = isset($args['id']) ? $args['id'] : '';
        $this->attr = isset($args['attr']) ? $args['attr'] : [];
        $this->settings = isset($args['settings']) ? $args['settings'] : [];
        $this->class_wrap = isset($args['class_wrap']) ? $args['class_wrap'] : [];
        $this->class_input = isset($args['class_input']) ? $args['class_input'] : [];
        $this->is_required = isset($this->attr['required']) && $this->attr['required'];
        $this->context = isset($this->attr['context']) && $this->attr['context'] ? $this->attr['context'] : '';
        $this->conditional_logic = isset($args['conditional_logic']) && is_array($args['conditional_logic']) ? $args['conditional_logic'] : 0;
        $this->is_conditional_logic = !$this->conditional_logic || $this->is_conditional_logic();

        if ($this->slug) {
            $this->set_value_default();
        }

        $this->add_js_var(); //for conditional logic & dynamic selects
    }

    function render()
    {
        $input_id = $this->input_id();
        $field_id = $this->field_id();
        $field_classes = $this->field_classes();
        $field_attrs = $this->field_attrs();

        $html = '';
        $html_input = $this->render_input();

        if ($html_input) {
            $html = '<div id="' . $field_id . '" class="' . $field_classes . '" ' . $field_attrs . '>';
            $html .= $this->render_label($input_id, $this->label, $this->is_required);
            $html .= $html_input;
            $html .= '</div>';
        }

        echo $html;
    }

    protected function render_input()
    {
        $classes = $this->input_classes();
        $attrs = $this->input_attrs();
        $id = $this->input_id();
        $input_tpl = '<input type="%s"  name="%s" id="%s" class="%s" %s />';

        if ($this->value) {
            $val = 'value="' . esc_attr($this->value) . '" ';
            $attrs = $val . $attrs;
        }

        return sprintf($input_tpl, $this->type, $this->name, $id, $classes, $attrs);
    }

    protected function render_label($id = '', $label = '', $is_required = '')
    {
        $output = '';

        if ($label) {
            $label .= $is_required ? '<span class="esl-required">*</span>' : '';
            $output = sprintf('<label class="esl-label" for="%s">%s</label>', $id, $label);
        }

        return $output;
    }

    protected function build_label($args)
    {
        $label = '';

        if ((isset($args['context']) && $args['context']) && isset($args['labels'][$args['context']])) {
            $label = $args['labels'][$args['context']];
        } else if (isset($args['label'])) {
            $label = $args['label'];
        }

        return $label;
    }

    protected function field_id()
    {
        return 'esl-field-' . $this->id;
    }

    protected function field_classes()
    {
        $classes = ['esl-field', 'esl-field-' . $this->type, 'esl-field-' . $this->name];

        if (isset($this->taxonomy)) {
            $classes[] = 'esl-field-taxonomy';
        }

        if ($this->is_required) {
            $classes[] = 'esl-required';
        }

        if ($this->class_wrap) {
            $classes[] = $this->class_wrap;
        }

        if ($this->conditional_logic) {
            $classes[] = 'esl-conditional';

            if (!$this->is_conditional_logic) {
                $classes[] = 'esl-hidden';
            }
        }

        return implode(' ', $classes);
    }

    protected function field_attrs()
    {
        $attrs = [];

        if (!$this->is_conditional_logic) {
            $attrs[] = 'hidden';
        }

        return implode(' ', $attrs);
    }

    protected function input_id()
    {
        return 'esl-input-' . $this->id;
    }

    protected function input_classes()
    {
        $classes = ['esl-input', 'esl-input-' . $this->type];

        if ($this->class_input) {
            $classes[] = $this->class_input;
        }

        return implode(' ', $classes);
    }

    protected function input_attrs()
    {
        $attrs = [];

        if (!$this->is_conditional_logic) {
            $attrs[] = 'disabled';
        }

        if ($this->attr) {
            foreach ($this->attr as $attr_name => $attr_val) {

                //skip id
                if (in_array($attr_name, ['id', 'selected'])) {
                    continue;
                }

                if (in_array($attr_name, ['required', 'multiple']) && $attr_val) {
                    $attrs[] = $attr_name;
                } else {
                    if ($attr_val) {
                        $attrs[] = $attr_name . '="' . $attr_val . '" ';
                    }
                }
            }
        }

        return implode(' ', $attrs);
    }

    protected function is_conditional_logic()
    {
        if (!is_array($this->conditional_logic)) {
            return true;
        }

        foreach ($this->conditional_logic as $condition_row) {
            $is_valid_row = true;

            foreach ($condition_row as $condition) {
                $input_value = $this->get_value_by_name($condition['name']);
                $is_valid_condition = false;

                switch ($condition['operator']) {
                    case '==':
                        $is_valid_condition = ($input_value == $condition['value']);

                        break;

                    case '!=':
                        $is_valid_condition = ($input_value != $condition['value']);
                        break;

                    case '==empty':
                        $is_valid_condition = !(bool)$input_value;
                        break;

                    case '!=empty':
                        $is_valid_condition = (bool)$input_value;
                        break;
                    case '==contains':

                        if (is_array($input_value)) {
                            $is_valid_condition = in_array($condition['value'], $input_value);
                        } else {
                            $is_valid_condition = (strpos($input_value, $condition['value']) !== false);
                        }

                        break;
                }

                //if one of conditions is false then all row condition is false
                if (!$is_valid_condition) {
                    $is_valid_row = false;
                    break;
                }
            }

            //if one of row of conitions is true (OR) then stop and return true
            if ($is_valid_row) {
                return $is_valid_row;
            }
        }

        return false;
    }

    function set_value($value)
    {
        $this->value = $value;
    }

    function get_slug()
    {
        return $this->slug;
    }

    function set_value_default()
    {
        if (isset($_GET[$this->slug])) {
            $this->value = $this->serialize_value($_GET[$this->slug]);
        }
    }


    function serialize_value($value)
    {
        return $value;
    }

    function get_value_by_name($name)
    {
        return isset($_GET[$name]) ? $this->serialize_value($_GET[$name]) : '';
    }

    protected function add_js_var()
    {
        //use this filter to add somethink to "eslisting" js var;
        add_filter('js_var_esfields', function ($fields) {
            $fields['esl-field-' . $this->id] = $this->get_js_var_arr();

            return $fields;
        }, 5);
    }

    protected function get_js_var_arr()
    {
        $args = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'field_type' => $this->type,
            'conditionals' => $this->conditional_logic,
            'required' => $this->is_required,
        ];

        return $args;
    }
}

abstract class EsFieldChoices extends EsFieldInput
{

    protected $taxonomy;
    protected $taxonomy_args;
    protected $taxonomy_defaults;
    protected $choices;

    function __construct($args = [])
    {
        parent::__construct();

        $this->taxonomy_defaults = [
            'hide_empty' => true,
            'hierarchical' => true,
            'child_of' => 0,
            'depth' => 999,
            'orderby' => 'term_id',
        ];

        $this->setAll($args);
    }

    function setAll($args)
    {
        parent::setAll($args);

        if (isset($args['taxonomy'])) {
            $this->taxonomy = $args['taxonomy'];
            $this->attr['data-taxonomy'] = $this->taxonomy;
            $this->name = 'taxonomy-' . $args['taxonomy'];
            $this->slug = isset($args['slug']) ? $args['slug'] : $args['taxonomy'];
            $taxonomy_args = isset($args['taxonomy_args']) ? $args['taxonomy_args'] : [];
            $this->taxonomy_args = array_replace($this->taxonomy_defaults, $taxonomy_args);

        } else {
            $this->choices = isset($args['choices']) ? $args['choices'] : [];
            $this->name = $args['name'];
        }
    }

    function serialize_value($value)
    {

        if (is_string($value)) {
            if (strpos($value, ',') !== false) {
                $value = explode(',', $value);
            } else {
                $value = [$value];
            }
        }

        return $value;
    }


    /**
     * Get categories (terms) from the db.
     *
     * @return array
     */
    function get_categories()
    {
        $depth = isset($this->taxonomy_args['depth']) ? $this->taxonomy_args['depth'] : 999;


        $categories = get_terms($this->taxonomy, $this->taxonomy_args);

        if (!is_array($categories) || empty($categories)) {
            return [];
        }

        // This ensures that no categories with a product count of 0 is rendered.
        if ($this->taxonomy_args['hide_empty']) {
            $categories = array_filter(
                $categories,
                function ($category) {
                    return 0 !== $category->count;
                }
            );
        }

        return $this->build_category_tree($categories, $depth);
    }

    protected function get_categories_choice()
    {
        $choices = [];

        $categories = $this->get_categories();

        if ($categories) {
            foreach ($categories as $category) {
                $choices[$category->term_id] = $category->name;
            }
        }

        return $choices;
    }

    /**
     * Build hierarchical tree of categories.
     *
     * @param array $categories List of terms.
     *
     * @return array
     */
    protected function build_category_tree($categories, $depth)
    {
        $categories_by_parent = [];

        foreach ($categories as $category) {
            if (!isset($categories_by_parent['cat-' . $category->parent])) {
                $categories_by_parent['cat-' . $category->parent] = [];
            }

            $categories_by_parent['cat-' . $category->parent][] = $category;
        }


        $tree = $categories_by_parent['cat-' . $this->taxonomy_args['child_of']];
        unset($categories_by_parent['cat-' . $this->taxonomy_args['child_of']]);


        foreach ($tree as $category) {

            if (!empty($categories_by_parent['cat-' . $category->term_id])) {
                $category->children = $this->fill_category_children($categories_by_parent['cat-' . $category->term_id], $categories_by_parent);

                //add custom meta to object
                $group_label = get_term_meta($category->term_id, 'group_label', true);
                if ($group_label && $category->children) {
                    $category->group_label = $group_label;
                }
            }
        }


        return $tree;
    }

    /**
     * Build hierarchical tree of categories by appending children in the tree.
     *
     * @param array $categories List of terms.
     * @param array $categories_by_parent List of terms grouped by parent.
     *
     * @return array
     */
    protected function fill_category_children($categories, $categories_by_parent)
    {

        foreach ($categories as $category) {
            if (!empty($categories_by_parent['cat-' . $category->term_id])) {
                $category->children = $this->fill_category_children($categories_by_parent['cat-' . $category->term_id], $categories_by_parent);

                //add custom meta to object
                $group_label = get_term_meta($category->term_id, 'group_label', true);
                if ($group_label && $category->children) {
                    $category->group_label = $group_label;
                }
            }
        }

        return $categories;
    }

    protected function is_assoc_array(array $arr)
    {
        if (array() === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Different array to option format
     * ['red' => 'Red'] to [ ['value' => 'red', 'title' => 'Red'] ]
     * ['red','blue']   to [ ['value' => 'red', 'title' => 'red'],  ['value' => 'blue', 'title' => 'blue'] ]
     *
     * @param $arr
     *
     * @return array|array[]
     */
    protected function serializeArrayToOptions($arr)
    {

        if (!$arr) {
            return [];
        }

        $is_assoc = $this->is_assoc_array($arr);

        if ($is_assoc) {
            $arr = array_map(function ($val, $key) {
                return ['value' => $key, 'title' => $val];
            }, $arr);
        } else {
            $arr = array_map(function ($val) {
                return ['value' => $val, 'title' => $val];
            }, $arr);
        }

        return $arr;
    }

    /**
     * Terms array to option format
     * [ obj, obj] to [ ['value' => obj->term_id, 'title' => obj->name] ]
     *
     * @param $terms
     *
     * @return array|array[]
     */
    protected function serializeTermsToOptions($terms)
    {
        return array_map(function ($term) {
            return ['value' => $term->term_id, 'title' => $term->name];
        }, $terms);

    }
}

class EsFieldNumber extends EsFieldInput
{
    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'number';

        $this->setAll($args);
    }
}

class EsFieldText extends EsFieldInput
{
    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'text';
        $this->setAll($args);
    }
}

class EsFieldHeading extends EsFieldInput
{

    function __construct($args = [])
    {
        parent::__construct();
        $this->type = 'heading';
        $this->setAll($args);
    }


    function render()
    {
        $field_id = $this->field_id();
        $field_classes = $this->field_classes();
        $field_attrs = $this->field_attrs();

        $html = '';
        $html_input = $this->render_input();

        if ($html_input) {
            $html = '<div id="' . $field_id . '" class="' . $field_classes . '" ' . $field_attrs . '>';
            $html .= '<h2 class="esl-heading">' . $this->label . '</h2>';
            $html .= $html_input;
            $html .= '</div>';
        }

        echo $html;
    }

    function render_input()
    {
        //zaglushka ))
        return '<input type="hidden">';
    }
}

class EsFieldPostTitle extends EsFieldInput
{

    function __construct($args = [])
    {
        parent::__construct();
        $this->type = 'post_title';
        $this->setAll($args);

    }

    function render_input()
    {
        $input_id = $this->input_id();
        $input_tpl = '<input type="text" name="post_title" id="%s" value="%s" class="esl-input esl-input-post_title"  required/>';
        $value = $this->value ? esc_attr($this->value) : '';

        return sprintf($input_tpl, $input_id, $value);
    }
}

class EsFieldButton extends EsFieldInput
{

    function __construct($args = [])
    {
        parent::__construct();
        $this->type = 'button';
        $this->setAll($args);
    }

    function render()
    {
        $input_id = $this->input_id();
        $input_classes = $this->input_classes();
        $input_attrs = $this->input_attrs();

        $field_id = $this->field_id();
        $field_classes = $this->field_classes();
        $field_attrs = $this->field_attrs();

        $input_id = $input_id ? sprintf('id="%s"', $input_id) : '';
        $input_classes = $input_classes ? sprintf('class="%s"', $input_classes) : '';

        $html = '';
        $html_input = '<button ' . $input_id . ' ' . $input_classes . ' ' . $input_attrs . '>' . $this->label . '</button>';


        if ($html_input) {
            $html = '<div id="' . $field_id . '" class="' . $field_classes . '" ' . $field_attrs . '>';
            $html .= $html_input;
            $html .= '</div>';
        }

        echo $html;
    }

}

class EsFieldEmail extends EsFieldInput
{
    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'email';
        $this->setAll($args);
    }
}

class EsFieldPhone extends EsFieldInput
{
    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'phone';
        $this->setAll($args);
    }
}

class EsFieldUrl extends EsFieldInput
{
    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'url';
        $this->setAll($args);
    }
}

class EsFieldUserPhone extends EsFieldPhone
{
    function __construct($args = [])
    {
        parent::__construct();
        $this->setValueByDefault();
    }

    function setValueByDefault()
    {
        if (!$this->value && $user_id = get_current_user_id()) {
            $user = get_userdata($user_id);

            if (isset($user->user_phone) && !empty($user->user_phone)) {
                $this->value = $user->user_phone;
            }
        }
    }
}

class EsFieldUserEmail extends EsFieldEmail
{
    function __construct($args = [])
    {
        parent::__construct();
        $this->setValueByDefault();
    }

    function setValueByDefault()
    {
        if (!$this->value && $user_id = get_current_user_id()) {
            $user = get_userdata($user_id);

            if (isset($user->user_email) && !empty($user->user_email)) {
                $this->value = $user->user_email;
            }
        }
    }
}

class EsFieldCheckboxMenu extends EsFieldChoices
{
    protected $taxonomy;
    protected $taxonomy_args;
    protected $taxonomy_defaults;
    protected $choices;

    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'checkbox';
        $this->taxonomy_defaults = [
            'hide_empty' => true,
            'hierarchical' => true,
            'child_of' => 0,
            'depth' => 999,
            'orderby' => 'term_id',
        ];
        $this->setAll($args);
    }


    function render_input()
    {
        $uid = uniqid('esl-field-checkbox-');
        $output = '';

        if ($this->taxonomy) {
            if ($categories = $this->get_categories()) {
                $output .= $this->render_search();
                $output .= $this->render_checkbox_list($categories, $uid, 0);
            }
        } else {
            if ($this->choices) {
                $output = $this->render_checkbox_choices();
            }
        }

        return $output;
    }

    function render_search()
    {
        $search_html = '<div class="esl-list-search-wrap">';
        $search_html .= '<input type="text" placeholder="Search" class="esl-list-search">';
        $search_html .= '</div>';

        return $search_html;
    }

    /**
     * Render a list of terms.
     *
     * @param array $categories List of terms.
     * @param int $uid Unique ID for the rendered block, used for HTML IDs.
     * @param int $depth Current depth.
     *
     * @return string Rendered output.
     */

    protected function render_checkbox_list($categories, $uid, $depth = 0)
    {

        if ($depth > $this->taxonomy_args['depth']) {
            return;
        }


        $output = '';

        $classes = [
            'esl-list',
        ];


        $output .= '<ul class="' . implode(' ', $classes) . '">';
        foreach ($categories as $term) {

            $has_children = !empty($term->children);
            $class = 'esl-list-item';
            $class .= $has_children ? ' has-children' : '';

            $item_id = 'esl-input-' . $this->id . '-' . $term->term_id;
            $item_value = $term->term_id;

            $output .= '<li class="' . $class . '" data-lvl="' . absint($depth) . '">';
            $output .= sprintf('<input type="checkbox" id="%s" name="%s" value="%s">', $item_id, $this->name, $item_value);
            $output .= sprintf('<label for="%s">%s</label>', $item_id, $term->name);
            $output .= $has_children && $depth < $this->taxonomy_args['depth']
                ? '<button class="btn-toggle-ul" aria-expanded="false"><i class="bb-icon-angle-down"></i></button>' : '';
            $output .= $has_children ? $this->render_checkbox_list($term->children, $uid, $depth + 1) : '';
            $output .= '</li>';
        }

        $output .= '</ul>';


        return $output;
    }


    function render_checkbox_choices()
    {
        $input_id = $this->input_id();
        $input_classes = $this->input_classes();
        $is_assoc = $this->is_assoc_array($this->choices);

        $html = '';
        $i = 0;

        $html .= '<div class="esl-list-wrap">';
        $html .= sprintf('<ul id="%s" class="%s" >', $input_id, $input_classes);
        foreach ($this->choices as $key => $label) {
            $i++;
            $item_id = $input_id . '-' . $i;
            $item_value = $is_assoc ? $key : $label;
            $selected = selected($this->value, $item_value, false);
            $tpl = '<input type="checkbox" id="%s" class="%s" name="%s" value="%s" %s>';
            $html .= sprintf($tpl, $item_id, $input_classes, $this->name, $item_value, $selected);
            $html .= sprintf('<label for="%s">%s</label>', $item_id, $label);
        }
        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }

}

class EsFieldCheckbox extends EsFieldChoices
{
    protected $taxonomy;
    protected $taxonomy_args;
    protected $taxonomy_defaults;
    protected $choices;

    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'checkbox';
        $this->taxonomy_defaults = [
            'hide_empty' => true,
            'hierarchical' => true,
            'child_of' => 0,
            'depth' => 999,
            'orderby' => 'term_id',
        ];
        $this->setAll($args);
    }


    function render_input()
    {
        if ($this->taxonomy) {
            if ($categories = $this->get_categories()) {
                $options = $this->serializeTermsToOptions($categories);
            }
        } else {
            $options = $this->serializeArrayToOptions($this->choices);
        }

        return $options ? $this->render_checkbox_list($options) : '';
    }

    /**
     * @param $options
     *
     * @return string
     */
    protected function render_checkbox_list($options)
    {


        $output = '<ul class="esl-list">';
        foreach ($options as $option) {
            $item_id = 'esl-input-' . $this->id . '-' . $option['value'];
            $checked = '';

            //todo create function
            if ($this->value) {
                $checked = in_array($option['value'], $this->value) ? 'checked="checked"' : '';
            }


            $output .= '<li class="esl-list-item">';
            $output .= sprintf('<input type="checkbox" id="%s" name="%s" value="%s" %s>', $item_id, $this->name, $option['value'], $checked);
            $output .= sprintf('<label for="%s">%s</label>', $item_id, $option['title']);
            $output .= '</li>';
        }

        $output .= '</ul>';


        return $output;
    }

}

class EsFieldCountry extends EsFieldSelect
{
    protected $choices;

    function __construct($args = [])
    {
        parent::__construct();

        $this->setAll($args);
        $this->type = 'select';
        $this->choices = $this->get_countries();
    }

    function get_countries()
    {
        return [
            "United States",
            "Canada",
            "Afghanistan",
            "Albania",
            "Algeria",
            "American Samoa",
            "Andorra",
            "Angola",
            "Anguilla",
            "Antarctica",
            "Antigua and/or Barbuda",
            "Argentina",
            "Armenia",
            "Aruba",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bermuda",
            "Bhutan",
            "Bolivia",
            "Bosnia and Herzegovina",
            "Botswana",
            "Bouvet Island",
            "Brazil",
            "British Indian Ocean Territory",
            "Brunei Darussalam",
            "Bulgaria",
            "Burkina Faso",
            "Burundi",
            "Cambodia",
            "Cameroon",
            "Cape Verde",
            "Cayman Islands",
            "Central African Republic",
            "Chad",
            "Chile",
            "China",
            "Christmas Island",
            "Cocos (Keeling) Islands",
            "Colombia",
            "Comoros",
            "Congo",
            "Cook Islands",
            "Costa Rica",
            "Croatia (Hrvatska)",
            "Cuba",
            "Cyprus",
            "Czech Republic",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic",
            "East Timor",
            "Ecudaor",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Ethiopia",
            "Falkland Islands (Malvinas)",
            "Faroe Islands",
            "Fiji",
            "Finland",
            "France",
            "France, Metropolitan",
            "French Guiana",
            "French Polynesia",
            "French Southern Territories",
            "Gabon",
            "Gambia",
            "Georgia",
            "Germany",
            "Ghana",
            "Gibraltar",
            "Greece",
            "Greenland",
            "Grenada",
            "Guadeloupe",
            "Guam",
            "Guatemala",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Heard and Mc Donald Islands",
            "Honduras",
            "Hong Kong",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran (Islamic Republic of)",
            "Iraq",
            "Ireland",
            "Israel",
            "Italy",
            "Ivory Coast",
            "Jamaica",
            "Japan",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Korea, Democratic People's Republic of",
            "Korea, Republic of",
            "Kosovo",
            "Kuwait",
            "Kyrgyzstan",
            "Lao People's Democratic Republic",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libyan Arab Jamahiriya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Macau",
            "Macedonia",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands",
            "Martinique",
            "Mauritania",
            "Mauritius",
            "Mayotte",
            "Mexico",
            "Micronesia, Federated States of",
            "Moldova, Republic of",
            "Monaco",
            "Mongolia",
            "Montserrat",
            "Morocco",
            "Mozambique",
            "Myanmar",
            "Namibia",
            "Nauru",
            "Nepal",
            "Netherlands",
            "Netherlands Antilles",
            "New Caledonia",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "Niue",
            "Norfork Island",
            "Northern Mariana Islands",
            "Norway",
            "Oman",
            "Pakistan",
            "Palau",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Pitcairn",
            "Poland",
            "Portugal",
            "Puerto Rico",
            "Qatar",
            "Reunion",
            "Romania",
            "Russian Federation",
            "Rwanda",
            "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Vincent and the Grenadines",
            "Samoa",
            "San Marino",
            "Sao Tome and Principe",
            "Saudi Arabia",
            "Senegal",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "South Georgia South Sandwich Islands",
            "South Sudan",
            "Spain",
            "Sri Lanka",
            "St. Helena",
            "St. Pierre and Miquelon",
            "Sudan",
            "Suriname",
            "Svalbarn and Jan Mayen Islands",
            "Swaziland",
            "Sweden",
            "Switzerland",
            "Syrian Arab Republic",
            "Taiwan",
            "Tajikistan",
            "Tanzania, United Republic of",
            "Thailand",
            "Togo",
            "Tokelau",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Turks and Caicos Islands",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
            "United Kingdom",
            "United States minor outlying islands",
            "Uruguay",
            "Uzbekistan",
            "Vanuatu",
            "Vatican City State",
            "Venezuela",
            "Vietnam",
            "Virigan Islands (British)",
            "Virgin Islands (U.S.)",
            "Wallis and Futuna Islands",
            "Western Sahara",
            "Yemen",
            "Yugoslavia",
            "Zaire",
            "Zambia",
            "Zimbabwe"
        ];
    }
}

class EsFieldEditor extends EsFieldInput
{

    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'editor';
        $this->setAll($args);
    }

    function render()
    {
        $input_id = $this->input_id();
        $field_id = $this->field_id();
        $field_classes = $this->field_classes();
        $field_attrs = $this->field_attrs();

        $html = '<div id="' . $field_id . '" class="' . $field_classes . '" ' . $field_attrs . '>';
        $html .= $this->render_label($input_id, $this->label, $this->is_required);
        $html .= $this->render_input();
        $html .= '</div>';

        echo $html;
    }

    function render_input()
    {
        $input_id = $this->input_id();
        $input_name = $this->name;
        $input_value = isset($this->value) ? $this->value : '';

        $args = array(
            'media_buttons' => false, // This setting removes the media button.
            'textarea_name' => $input_name, // Set custom name.
            'textarea_rows' => get_option('default_post_edit_rows', 10), //Determine the number of rows.
            'quicktags' => false, // Remove form as HTML button.
        );

        if (isset($this->settings['media']) && $this->settings['media'] == true) {
            $args['media_buttons'] = true;
        }

        if (isset($this->settings['basic']) && $this->settings['basic'] === true) {
            $args['tinymce'] = [
                'toolbar1' => 'bold,italic,underline,separator,separator,link,unlink,undo,redo,bullist,numlist,blockquote',
                'toolbar2' => '',
                'toolbar3' => '',
            ];
        }

        ob_start();
        wp_editor($input_value, $input_id, $args);

        return ob_get_clean();
    }
}

class EsFieldSelect extends EsFieldChoices
{
    protected $taxonomy;
    protected $taxonomy_args;
    protected $taxonomy_defaults;
    protected $choices;

    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'select';
        $this->taxonomy_defaults = [
            'hide_empty' => true,
            'hierarchical' => true,
            'child_of' => 0,
            'depth' => 1,
            'orderby' => 'term_id',
        ];
        $this->setAll($args);
    }

    function render_input()
    {

        $options = [];

        if ($this->taxonomy) {
            if ($categories = $this->get_categories()) {
                $options = $this->serializeTermsToOptions($categories);
            }
        } else {
            $options = $this->serializeArrayToOptions($this->choices);
        }

        if (!$options) {
            return '';
        }

        $input_id = $this->input_id();
        $input_name = $this->name;
        $input_classes = $this->input_classes();
        $input_attr = $this->input_attrs();

        return $this->render_select($options, $this->value, $input_id, $input_name, $input_classes, $input_attr);
    }


    function render_select($options, $input_value, $input_id, $input_name, $input_classes, $input_attr)
    {

        $html = sprintf('<select id="%s" name="%s" class="%s" %s />', $input_id, $input_name, $input_classes, $input_attr);

        if (isset($this->attr['placeholder'])) {
            $placeholder_selected = !$input_value ? 'selected' : '';
            $placeholder = sprintf('<option value="" %s>%s</option>', $placeholder_selected, $this->attr['placeholder']);
            $html .= $placeholder;
        }

        foreach ($options as $option) {
            $selected = '';

            //todo create function for this
            if ($this->value) {
                $selected = in_array($option['value'], $this->value) ? 'selected="selected"' : '';
            }

            $html .= sprintf('<option value="%s" %s>%s</option>', $option['value'], $selected, $option['title']);
        }

        $html .= '</select>';

        return $html;
    }

}

class EsFieldSelectGrouped extends EsFieldChoices
{
    protected $choices;
    protected $taxonomy;
    protected $taxonomy_args;

    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'select_grouped';
        $this->taxonomy_defaults = [
            'hide_empty' => true,
            'hierarchical' => true,
            'child_of' => 0,
            'depth' => 99,
            'orderby' => 'term_id',
        ];
        $this->setAll($args);
    }

    function render_input()
    {
        if (!$this->taxonomy) {
            return;
        }

        $categories = $this->get_categories();
        $input_id = $this->input_id();
        $input_classes = $this->input_classes();
        $input_attr = $this->input_attrs();

        $html = '';
        $html .= sprintf('<select id="%s" name="%s" class="%s" %s />', $input_id, $this->name, $input_classes, $input_attr);

        if (isset($this->attr['placeholder'])) {
            $placeholder_selected = !$this->value ? 'selected' : '';
            $placeholder = sprintf('<option value="" %s>%s</option>', $placeholder_selected, $this->attr['placeholder']);
            $html .= $placeholder;
        }

        foreach ($categories as $category) {
            $tpl_option = '<option value="%s" %s>%s</option>';

            if (isset($category->children)) {
                $html .= '<optgroup label="' . $category->name . '">';
                foreach ($category->children as $subcategory) {
                    $item_value = $subcategory->term_id;
                    $item_label = $subcategory->name;


                    $selected = '';
                    //todo create function for this
                    if ($this->value) {
                        $selected = in_array($item_value, $this->value) ? 'selected="selected"' : '';
                    }

                    $html .= sprintf('<option value="%s" %s>%s</option>', $item_value, $selected, $item_label);
                }
                $html .= '</optgroup>';
            } else {
                $item_value = $category->term_id;
                $item_label = $category->name;

                $selected = '';

                //todo create function for this
                if ($this->value) {
                    $selected = in_array($item_value, $this->value) ? 'selected="selected"' : '';
                }


                $html .= sprintf('<option value="%s" %s>%s</option>', $item_value, $selected, $item_label);
            }

        }

        $html .= '</select>';

        return $html;
    }
}

class EsFieldSelectHierarchical extends EsFieldChoices
{
    protected $taxonomy;
    protected $taxonomy_args;
    protected $taxonomy_defaults;
    protected $categories;

    function __construct($args = [])
    {
        parent::__construct();

        $this->type = 'select_hierarchical';

        //static or dynamic build

        if (!isset($args['settings']['render_type'])) {
            $args['settings']['render_type'] = 'static';
        }

        if (!isset($args['settings']['depth'])) {
            $args['settings']['depth'] = $args['settings']['render_type'] == 'static' ? 2 : 99;
        }


        $this->settings['render_type'] = $args['settings']['render_type'];
        $this->settings['depth'] = $args['settings']['depth'];


        $this->taxonomy_defaults = [
            'hide_empty' => false,
            'hierarchical' => true,
            'child_of' => 0,
            'depth' => $this->settings['depth'],
            'orderby' => 'term_id',
        ]; //for dynamic 99 default

        $this->setAll($args);

        $this->categories = $this->get_categories();

    }

    protected function get_js_var_arr()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'field_type' => $this->type,
            'conditionals' => $this->conditional_logic,
            'taxonomy' => $this->taxonomy,
            'render_type' => $this->settings['render_type'],
            'categories' => $this->categories
        ];
    }

    private function serializeTermHierarchalIds($arrOfIds, $parent_id)
    {

        if (is_array($arrOfIds) && count($arrOfIds) > 1) {
            $acc = [];

            foreach ($arrOfIds as $term_id) {
                $item = get_term_by('id', $term_id, $this->taxonomy);

                if ($item->parent === $parent_id) {
                    $children = $this->serializeTermHierarchalIds($arrOfIds, $item->term_id);
                    $acc = [$item->term_id];
                    $acc = array_merge($acc, $children);
                }
            }

            return $acc;
        } else {
            return $arrOfIds;
        }
    }


    function render()
    {
        $field_id = $this->field_id();
        $field_classes = $this->field_classes();
        $field_attrs = $this->field_attrs();

        $input_classes = $this->input_classes();
        $input_attr = $this->input_attrs();
        $input_name = $this->name;
        $is_static = $this->settings['render_type'] == 'static';
        $is_dynamic = $this->settings['render_type'] == 'dynamic';


        $this->value = $this->serializeTermHierarchalIds($this->value, 0);


        $i = -1;
        $terms_store = [];

        $html = '';
        $html = '<div id="' . $field_id . '" class="' . $field_classes . '" ' . $field_attrs . '>';

        $max_repeat = $this->taxonomy_args['depth'];

        if ($is_dynamic) {

            $count_value = $this->value && is_array($this->value) ? count($this->value) : 0;

            if ($count_value < $max_repeat && $count_value > 0) {
                $max_repeat = $count_value;
            }
        }

        //render children
        while ($i++ < $max_repeat - 1) {

            $terms = [];
            $input_id = $field_id . '-' . $i;

            $input_label = 'Child ' . $i;
            $input_value = isset($this->value[$i]) && $this->value[$i] ? $this->value[$i] : 0;
            $prev_value = isset($this->value[$i - 1]) && $this->value[$i - 1] ? $this->value[$i - 1] : 0;
            $prev_terms = [];

            //var_dump(get_term($input_value) );

            if ($i == 0) {
                $terms = $this->categories;
                $terms_store = isset($this->value[0]) && $this->value[0] ? $this->search_category($this->categories, $this->value[0]) : [];
                $input_label = isset($this->label) && !empty($this->label) ? $this->label : $input_label;
            }


            if ($i > 0) {
                $child_key = 'child_' . $i;

                if ($terms_store) {
                    $prev_terms = $this->search_category($terms_store, $prev_value);
                    $prev_terms = $prev_terms ? $prev_terms[0] : [];
                    $terms = isset($prev_terms->children) ? $prev_terms->children : [];
                }

                //label
                if ($is_static && isset($this->labels[$child_key]['label'])) {
                    $input_label = $this->labels[$child_key]['label'];
                } elseif ($is_dynamic && $prev_terms && isset($prev_terms->group_label) && !empty($prev_terms->group_label)) {
                    $input_label = $prev_terms->group_label;
                }
            }


            if (!$terms && $is_dynamic) {
                break;
            }

            $label_asterisk = $this->is_required ? ' <span class="asterisk">*</span>' : '';

            $html .= '<div class="esl-subfield esl-subfield-' . $i . '">';
            $html .= sprintf('<label for="%s">%s %s</label>', $input_id, $input_label, $label_asterisk);
            $html .= $this->render_select_taxonomy($terms, $input_value, $input_id, $input_name, $input_classes, $input_attr);
            $html .= '</div>';
        }

        $html .= '</div>';

        echo $html;
    }

    protected function render_select_taxonomy($categories, $input_value, $input_id, $input_name, $input_classes, $input_attr)
    {

        if (!$categories) {
            $input_attr .= ' disabled';
        }

        $placeholder_text = isset($input_attr['placeholder']) ? $input_attr['placeholder'] : 'Choose option';
        $placeholder_selected = !$input_value ? ' selected' : '';
        $placeholder = sprintf('<option value="" %s class="option-placeholder">%s</option>', $placeholder_selected, $placeholder_text);

        $html = sprintf('<select id="%s" name="%s" class="%s" %s />', $input_id, $input_name, $input_classes, $input_attr);
        $html .= $placeholder;

        if ($categories) {
            foreach ($categories as $term) {
                $selected = $input_value == $term->term_id ? ' selected' : ''; //selected( $input_value, $term->term_id, false );
                $html .= sprintf('<option value="%s" %s>%s</option>', $term->term_id, $selected, $term->name);
            }
        }

        $html .= '</select>';

        return $html;
    }

    /**
     *
     * @param $categories
     * @param $term_id
     *
     * @return mixed
     */
    protected function search_category($categories, $term_id)
    {
        foreach ($categories as $category) {

            if ($category->term_id == $term_id) {
                return [$category];
            }

            if (isset($category->children)) {
                $find = $this->search_category_in_children($category->children, $term_id);

                if ($find) {
                    return $find;
                }
            }

        }
    }

    /**
     * @param $categories
     * @param $term_id
     *
     * @return mixed
     */
    protected function search_category_in_children($categories, $term_id)
    {
        foreach ($categories as $category) {
            if ($category->term_id == $term_id) {
                return [$category];
            }

            if (isset($category->children)) {
                $find = $this->search_category_in_children($category->children, $term_id);

                if ($find) {
                    return $find;
                }
            }
        }
    }

}


//add_action( 'wp_head', function () {
//	$args = [
//		'id'            => 15,
//		'field'         => 'select_relationship',
//		'label'         => 'Posting Area',
//		'labels'        => [ 'depth_1' => 'Focus', 'depth_2' => 'Services' ],
//		'taxonomy'      => 'listing-area',
//		'taxonomy_args' => [ 'hierarchical' => true ],
//		'class_input'   => 'selectize',
//		'attr'          => [ 'placeholder' => 'Choose one' ],
//		'tab_id'        => 1
//	];
//
//	$field = new EsFieldSelectRelationship2( $args );
//	$field->render();
//	//die();
//} );


add_action('wp_footer', function () {
    $vars = []; //def
    $vars = apply_filters('js_var_esfields', $vars);
    printf("<script>window['esfields'] = %s</script>", json_encode($vars));
}, 0);