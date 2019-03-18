import { menuItemTree } from './components/menuItemTree';
//import { menuItemNode } from './components/menuItemNode';
import { editMenuItemForm } from './components/editMenuItemForm';

Nova.booting((Vue, router) => {
    // Enable devtools
    //Vue.config.devtools = true;

    router.addRoutes([
        {
            name: 'laravel-menu-builder-menu-item-create',
            path: '/menu-builder/menus/:menuId/menu-items/create',
            component: editMenuItemForm,
            props: (route) => {
                return { action: 'create', ...route.params }
            },
        },
        {
            name: 'laravel-menu-builder-menu-item-update',
            path: '/menu-builder/menus/:menuId/menu-items/:menuItemId/edit',
            component: editMenuItemForm,
            props: (route) => {
                return { action: 'update', ...route.params }
            },
        },
    ]);

    Vue.component('menu-item-tree', menuItemTree);
})
