<style lang="scss" scoped>
    //.btn {}
    // .btn-default {}
    // .btn-primary {}
</style>

<template>
<loading-view v-if="!loaded" :loading="!loaded"></loading-view>
<div v-else class="laravel-menu-builder-menu-item-list py-3">
    <draggable :element="'ul'" :list="menuItems" class="dragArea remove-ul-padding" :options="draggable_options">
        <li v-for="(menuItem, index) in menuItems" :item="menuItem" :index="index" :key="menuItem.id">
            <div class="flex menu-item-row px-2">
                <div class="w-3/4 sortable-handle py-2">
                    {{ menuItem.name }}
                </div>
                <div class="w-1/4 py-2">
                    <mb-edit-button
                        :styleType="'link'"
                        :eleClasses="{ 'mr-4': true, }"
                        :svgClasses="{ 'text-70': true, 'hover:text-primary': true }"
                        v-on:click.native="$router.push({
                            name: 'laravel-menu-builder-menu-item',
                            params: {
                                'menu_item_id': menuItem.id,
                            },
                        })"
                    ></mb-edit-button>
                    <mb-delete-button
                        :styleType="'link'"
                        :eleClasses="{ 'mr-4': true, }"
                        :svgClasses="{ 'text-70': true, 'hover:text-primary': true }"
                        v-on:click.native="openDeleteModal(menuItem.id)"
                    ></mb-delete-button>
                </div>
            </div>

            <draggable v-if="menuItem.children" :element="'ul'" :list="menuItem.children" class="dragArea" :options="draggable_options">
                <li v-for="(menuItem, index) in menuItem.children" :item="menuItem" :index="index" :key="menuItem.id">
                    <div class="flex menu-item-row px-2">
                        <div class="w-3/4 sortable-handle py-2">
                            <p class="text-90">{{ menuItem.name }}</p>
                        </div>
                        <div class="w-1/4 py-2">
                            <mb-edit-button
                                :styleType="'link'"
                                :eleClasses="{ 'mr-4': true, }"
                                :svgClasses="{ 'text-70': true, 'hover:text-primary': true }"
                                v-on:click.native="$router.push({
                                    name: 'laravel-menu-builder-menu-item',
                                    params: {
                                        'menu_item_id': menuItem.id,
                                    },
                                })"
                            ></mb-edit-button>
                            <mb-delete-button
                                :styleType="'link'"
                                :eleClasses="{ 'mr-4': true, }"
                                :svgClasses="{ 'text-70': true, 'hover:text-primary': true }"
                                v-on:click.native="openDeleteModal(menuItem.id)"
                            ></mb-delete-button>
                        </div>
                    </div>

                    <draggable v-if="menuItem.children" :element="'ul'" :list="menuItem.children" class="dragArea" :options="draggable_options">
                        <li v-for="(menuItem, index) in menuItem.children" :item="menuItem" :index="index" :key="menuItem.id">
                            <div class="flex menu-item-row px-2">
                                <div class="w-3/4 sortable-handle py-2">
                                    {{ menuItem.name }}
                                </div>
                                <div class="w-1/4 py-2">
                                    <mb-edit-button
                                        :styleType="'link'"
                                        :eleClasses="{ 'mr-4': true, }"
                                        :svgClasses="{ 'text-70': true, 'hover:text-primary': true }"
                                        v-on:click.native="$router.push({
                                            name: 'laravel-menu-builder-menu-item',
                                            params: {
                                                'menu_item_id': menuItem.id,
                                            },
                                        })"
                                    ></mb-edit-button>
                                    <mb-delete-button
                                        :styleType="'link'"
                                        :eleClasses="{ 'mr-4': true, }"
                                        :svgClasses="{ 'text-70': true, 'hover:text-primary': true }"
                                        v-on:click.native="openDeleteModal(menuItem.id)"
                                    ></mb-delete-button>
                                </div>
                            </div>
                        </li>
                    </draggable>
                </li>
            </draggable>
        </li>
    </draggable>

    <portal to="modals">
        <transition name="fade">
            <delete-resource-modal
                v-if="deleteModalOpen"
                @confirm="confirmDelete"
                @close="closeDeleteModal"
                :mode="'delete'"
            >
                <div slot-scope="{ uppercaseMode, mode }" class="p-8">
                    <heading :level="2" class="mb-6">{{ __('Menu Item') }}</heading>
                    <p class="text-80 leading-normal">{{ __('Are you sure you want to ' + mode + ' this menu item?') }}</p>
                </div>
            </delete-resource-modal>
        </transition>
    </portal>
</div>
</template>

<script>
    import api from 'mb_api';
    import { makeRandomKey } from 'mb_helpers';
    import draggable from 'vuedraggable'; // https://github.com/SortableJS/Vue.Draggable
    import { editButton } from '../editButton';
    import { deleteButton } from '../deleteButton';

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
            'draggable': draggable,
            'mb-edit-button': editButton,
            'mb-delete-button': deleteButton,
        },

        data() {
            return {
                deleteMenuItemId: 0,
                deleteModalOpen: false,
                draggable_order: [],
                draggable_options: {
                    animation: 100,
                    group:{
                        name: 'g1',
                        // pull: true,
                        // put: true,
                    },
                    handle: '.sortable-handle',
                    // store: {
                    //     /**
                    //      * Get the order of elements. Called once during initialization.
                    //      * @param   {Sortable}  sortable
                    //      * @returns {Array}
                    //      */
                    //     get: function (sortable) {
                    //         return this.draggable_order;
                    //     },

                    //     /**
                    //      * Save the order of elements. Called onEnd (when the item is dropped).
                    //      * @param {Sortable}  sortable
                    //      */
                    //     set: function (sortable) {
                    //         this.draggable_order = sortable.toArray();
                    //     }
                    // },
                },
                loaded: false,
                menuItems: [],
            };
        },

        computed: {

        },

        watch: {},

        beforeCreate() {},

        async created() {
            // console.log('menu builder dashboard created!');
            // console.log('resourceId: ', this.resourceId);
            // console.log('resourceName: ', this.resourceName);
            // console.log('field: ', this.field);
            await this.getMenuItems();
            this.loaded = true;
        },

        beforeMount() {},

        mounted() {},

        beforeUpdate() {},

        updated() {},

        methods: {
            makeRandomKey,

            getMenuItems() {
                return api.listMenuItems(this.resourceId).then((menuItems) => {
                    this.menuItems = menuItems;
                });
            },

            openDeleteModal(menu_item_id) {
                this.deleteMenuItemId = menu_item_id;
                this.deleteModalOpen = true
            },

            confirmDelete() {
                if (!this.deleteMenuItemId) {
                    this.closeDeleteModal();
                    return;
                }

                this.deleteMenuItem(this.deleteMenuItemId);
                this.closeDeleteModal();
            },

            removeMenuItemFromDom(menu_items, menu_item_id_to_remove) {
                for (let i = 0; i < menu_items.length; i++) {
                    console.log('menu_items[i].id', menu_items[i].id);
                    console.log('menu_item_id_to_remove', menu_item_id_to_remove);
                    if (menu_items[i].children) {
                        if (this.removeMenuItemFromDom(menu_items[i].children, menu_item_id_to_remove)) {
                            return true;
                        }
                    }
                    if (menu_items[i].id == menu_item_id_to_remove) {
                        console.log('****FOUND ELEMENT****');
                        menu_items.splice(i, 1);
                        return true;
                    }
                }

                return false;
            },

            deleteMenuItem(menu_item_id) {
                console.log('deleteMenuItem() called!');
                console.log('menu_item_id: ', menu_item_id);

                return api.deleteMenuItem(menu_item_id).then((response) => {
                    this.removeMenuItemFromDom(this.menuItems, menu_item_id);
                    this.$toasted.show(this.__('The menu item was successfully deleted.'), { type: 'success' });
                }).catch(() => {
                    this.$toasted.show(this.__('There was a problem deleting this menu item.'), { type: 'error' });
                });
            },

            closeDeleteModal() {
                this.deleteMenuItemId = 0;
                this.deleteModalOpen = false;
            },
        },
    };
</script>
