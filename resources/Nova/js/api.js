/**
 * The Api functions for Nova endpoints
**/

export default {

    /**
     * Menu Items Operations
    **/

    listMenuItems(menu_id) {
        return window.axios
            .get('/nova-vendor/greenbar/laravel-menu-builder/list-menu-items/' + menu_id)
            .then(response => response.data.data);
    },

    getMenuItem(menu_item_id) {
        return window.axios
            .get('/nova-vendor/greenbar/laravel-menu-builder/get-menu-item/' + menu_item_id)
            .then(response => response.data.data);
    },

    createMenuItem(params) {
        return window.axios
            .post('/nova-vendor/greenbar/laravel-menu-builder/create-menu-item', params)
            .then(response => response.data.data);
    },

    updateMenuItem(menu_item_id, params) {
        return window.axios
            .post('/nova-vendor/greenbar/laravel-menu-builder/update-menu-item/' + menu_item_id, params)
            .then(response => response.data);
    },

    deleteMenuItem(menu_item_id) {
        return window.axios
            .post('/nova-vendor/greenbar/laravel-menu-builder/delete-menu-item/' + menu_item_id, {
                'menu_item_id': menu_item_id,
            }).then(response => response.data);
    },
};

