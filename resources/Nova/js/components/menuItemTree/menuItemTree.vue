<style lang="scss" scoped>
    //.btn {}
    // .btn-default {}
    // .btn-primary {}
</style>

<template>
    <loading-view v-if="!loaded" :loading="!loaded"></loading-view>
    <div v-else class="laravel-menu-builder-menu-item-list py-3">
        <div class="ml-3 w-full flex justify-end items-center py-4">
            <mb-create-button
                :styleType="'button'"
                :eleClasses="{ 'mr-4': true, }"
                v-on:click.native="$router.push({
                    name: 'laravel-menu-builder-menu-item-create',
                    params: {
                        'menuId': resourceId,
                        'menuItemId': 0,
                    },
                })"
            ></mb-create-button>

            <mb-save-sort-button
                :styleType="'button'"
                :eleClasses="{ 'mr-4': true, }"
                v-on:click.native="saveMenuItemOrder()"
            ></mb-save-sort-button>
        </div>

        <menu-item-node
            v-if="menuItems.length > 0"
            class="dragArea remove-ul-padding"
            :menuId="resourceId"
            :items="menuItems"
            :draggableOptions="draggable_options"
            @deleteMenuItemNode="deleteMenuItem"
        ></menu-item-node>
    </div>
</template>

<script>
    import api from 'mb_api';
    import { makeRandomKey } from 'mb_helpers';
    import { menuItemNode } from '../menuItemNode';
    import { createButton } from '../createButton';
    import { saveSortButton } from '../saveSortButton';

    export default {
        name: 'menu-item-tree',

        props: {
            resourceId: {
                type: Number,
                default() {
                    return 0;
                },
            },
            resourceName: {
                type: String,
                default() {
                    return '';
                },
            },
            field: {
                type: Object,
                default() {
                    return {};
                },
            },
        },

        components: {
            'menu-item-node': menuItemNode,
            'mb-create-button': createButton,
            'mb-save-sort-button': saveSortButton,
        },

        data() {
            return {
                draggable_options: {
                    animation: 100,
                    group:{
                        name: 'g1',
                    },
                    handle: '.sortable-handle',
                },
                loaded: false,
                menuItems: [],
                sortOrder: [],
            };
        },

        computed: {},

        watch: {},

        beforeCreate() {},

        async created() {
            await this.getMenuItems();
            this.loaded = true;
        },

        beforeMount() {},

        mounted() {},

        beforeUpdate() {},

        updated() {},

        methods: {
            makeRandomKey,

            saveMenuItemOrder() {
                return api.saveMenuItemOrder(this.resourceId, {
                    sort_order: JSON.stringify(this.menuItems),
                }).then((menu_items) => {
                    this.menuItems = menu_items;
                    this.$toasted.show(this.__('The menu order was successfully saved.'), { type: 'success' });
                }).catch((error) => {
                    console.error(error);
                    this.$toasted.show(this.__('There was a problem in saving the menu item order.'), { type: 'error' });
                });
            },

            getMenuItems() {
                return api.listMenuItems(this.resourceId).then((menu_items) => {
                    this.menuItems = menu_items;
                }).catch((error)=>{
                    this.$toasted.show(this.__('There was a problem in retrieving the menu items.'), { type: 'error' });
                });
            },

            removeMenuItemFromDom(menu_items, menu_item_id_to_remove) {
                for (let i = 0; i < menu_items.length; i++) {
                    if (menu_items[i].children) {
                        if (this.removeMenuItemFromDom(menu_items[i].children, menu_item_id_to_remove)) {
                            return true;
                        }
                    }
                    if (menu_items[i].id == menu_item_id_to_remove) {
                        menu_items.splice(i, 1);
                        return true;
                    }
                }
                return false;
            },

            deleteMenuItem(menu_item_id) {
                return api.deleteMenuItem(this.resourceId, menu_item_id)
                .then((response) => {
                    this.removeMenuItemFromDom(this.menuItems, menu_item_id);
                    this.$toasted.show(this.__('The menu item was successfully deleted.'), { type: 'success' });
                }).catch((error) => {
                    this.$toasted.show(this.__('There was a problem deleting the menu item.'), { type: 'error' });
                });
            },
        },
    };
</script>
