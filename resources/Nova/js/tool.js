import { menuItemTree } from './components/menuItemTree';
//import { menuItemNode } from './components/menuItemNode';
import { menuItem } from './components/menuItem';

Nova.booting((Vue, router) => {
    // Enable devtools
    //Vue.config.devtools = true;

    router.addRoutes([
        {
            name: 'laravel-menu-builder-menu-item',
            path: '/menu-builder/edit-menu-item/:menu_item_id',
            component: menuItem,
            props: true,
        },
    ]);

    Vue.component('menu-item-tree', menuItemTree);
    //Vue.component('menu-item-node', menuItemNode);
})
