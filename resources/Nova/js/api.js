/**
 * The Api functions for Nova endpoints
**/

export default {

    /**
     * Menu Items Operations
    **/

    listMenuItems(menu_id) {
        return window.axios
            .get('/nova-vendor/greenbar/laravel-menu-builder/menus/'+ menu_id +'/menu-items')
            .then(response => response.data.data);
    },

    saveMenuItemOrder(menu_id, params) {
        return window.axios
            .post('/nova-vendor/greenbar/laravel-menu-builder/menus/'+ menu_id +'/save-menu-order', params)
            .then(response => response.data.data);
    },

    getMenuItem(menu_id, menu_item_id) {
        return window.axios
            .get('/nova-vendor/greenbar/laravel-menu-builder/menus/'+ menu_id +'/menu-items/'+ menu_item_id)
            .then(response => response.data.data);
    },

    createMenuItem(menu_id, params) {
        return window.axios
            .post('/nova-vendor/greenbar/laravel-menu-builder/menus/'+ menu_id +'/menu-items', params)
            .then(response => response.data.data);
    },

    updateMenuItem(menu_id, menu_item_id, params) {
        return window.axios
            .put('/nova-vendor/greenbar/laravel-menu-builder/menus/'+ menu_id +'/menu-items/'+ menu_item_id, params)
            .then(response => response.data.data);
    },

    deleteMenuItem(menu_id, menu_item_id) {
        return window.axios
            .delete('/nova-vendor/greenbar/laravel-menu-builder/menus/'+ menu_id +'/menu-items/'+ menu_item_id)
            .then(response => response.data);
    },
};

