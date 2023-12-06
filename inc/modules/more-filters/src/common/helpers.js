export function search_category(categories, term_id) {

    function search_in_children(categories, term_id) {
        for (let i = 0; i < categories.length; i++) {
            const category = categories[i]

            if (category.term_id == term_id) {
                return category
            }

            if (category?.children) {
                const find = search_in_children(category.children, term_id)
                if (find) {
                    return find
                }
            }
        }
    }

    for (let i = 0; i < categories.length; i++) {
        const category = categories[i]

        if (category.term_id == term_id) {
            return category
        }

        if (category?.children) {
            const find = search_in_children(category.children, term_id)
            if (find) {
                return find
            }
        }
    }

}

export function serialize_categories_to_options(categories) {
    if (!categories) {
        return;
    }
    return categories.map(category => {
        return {text: category.name, value: category.term_id}
    });
}