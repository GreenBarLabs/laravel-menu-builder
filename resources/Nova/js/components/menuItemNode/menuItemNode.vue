<style lang="scss" scoped>
    //.btn {}
    // .btn-default {}
    // .btn-primary {}
</style>

<template>
<draggable :tag="'ul'" :list="items" v-bind="draggableOptions">
    <li v-for="(item, index) in items" :key="makeRandomKey(item.id)">
        <div class="flex menu-item-row px-2">
            <div class="w-3/4 sortable-handle py-3">{{ item.name }}</div>
            <div class="w-1/4 py-2">
                <mb-edit-button
                    :styleType="'link'"
                    :eleClasses="{ 'mr-4': true }"
                    :svgClasses="{ 'text-70': true, 'hover:text-primary': true }"
                    v-on:click.native="$router.push({
                        name: 'laravel-menu-builder-menu-item-update',
                        params: {
                            'menuId': menuId,
                            'menuItemId': item.id,
                        },
                    })"
                ></mb-edit-button>
                <mb-delete-button
                    :styleType="'link'"
                    :eleClasses="{ 'mr-4': true, }"
                    :svgClasses="{ 'text-70': true, 'hover:text-primary': true }"
                    v-on:click.native="openDeleteModal(item.id)"
                ></mb-delete-button>
            </div>
        </div>

        <menu-item-node
            class="dragArea"
            :menuId="menuId"
            :items="item.children"
            :draggableOptions="draggableOptions"
            @deleteMenuItemNode="deleteMenuItem"
        ></menu-item-node>
    </li>

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
</draggable>
</template>

<script>
    import api from 'mb_api';
    import { makeRandomKey } from 'mb_helpers';

    import draggable from 'vuedraggable'; // https://github.com/SortableJS/Vue.Draggable
    import { editButton } from '../editButton';
    import { deleteButton } from '../deleteButton';

    export default {
        name: 'menu-item-node',

        props: {
            menuId: {
                type: Number,
                required: true,
            },
            items: {
                type: Array,
                default() {
                    return [];
                },
            },
            draggableOptions: {
                type: Object,
                default() {
                    return {};
                },
            }
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
            };
        },

        computed: {},

        watch: {},

        beforeCreate() {},

        created() {},

        beforeMount() {},

        mounted() {},

        beforeUpdate() {},

        updated() {},

        methods: {
            makeRandomKey,

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

            deleteMenuItem(menu_item_id = null) {
                // Delete the menuItemNode from the parent menuItemTree's data structure
                if (!menu_item_id && this.deleteMenuItemId) {
                    menu_item_id = this.deleteMenuItemId;
                }
                this.$emit('deleteMenuItemNode', menu_item_id);
            },

            closeDeleteModal() {
                this.deleteMenuItemId = 0;
                this.deleteModalOpen = false;
            },
        },
    };
</script>
