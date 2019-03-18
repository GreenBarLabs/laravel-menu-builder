<style lang="scss" scoped>
</style>

<template>
<loading-view v-if="!loaded" :loading="!loaded"></loading-view>
<div v-else>
    <h1 class="mb-3 text-90 font-normal text-2xl">{{ actionTitleCase }} Menu Item</h1>
    <div class="card overflow-hidden">
        <form autocomplete="off">
            <default-field :field="{ 'name': 'is_divider' }">
                <template slot="field">
                    <checkbox
                        class="py-2"
                        :name="'is_divider'"
                        @input="toggle_is_divider"
                        :checked="menu_item.is_divider"
                    />
                </template>
            </default-field>

            <default-field :field="{ 'name': 'name' }">
                <template slot="field">
                    <input type=text class="w-full form-control form-input form-input-bordered" v-model="menu_item.name">
                </template>
            </default-field>

            <default-field :field="{ 'name': 'url' }">
                <template slot="field">
                    <input type=text class="w-full form-control form-input form-input-bordered" v-model="menu_item.url">
                </template>
            </default-field>

            <div class="bg-30 flex justify-content-end px-8 py-4">
                <button @click.stop.prevent="handleSubmit()" type="button" dusk="update-and-continue-editing-button" class="ml-auto btn btn-default btn-primary mr-3">
                    {{ actionTitleCase }} &amp; Continue Editing
                </button>

                <button @click.stop.prevent="handleSubmit(true)" type="button" dusk="update-menu-item-button" class="btn btn-default btn-primary">
                    {{ actionTitleCase }} Menu Item
                </button>
            </div>
        </form>
    </div>
</div>
</template>

<script>
    import api from 'mb_api';

    export default {
        name: 'edit-menu-item-form',

        components: {},

        props: {
            action: {
                type: String,
                validator: function (value) {
                    return [ 'create', 'update' ].indexOf(value) !== -1
                },
            },
            menuId: {
                type: Number,
                required: true,
            },
            menuItemId: {
                type: Number,
                default() {
                    return 0;
                },
                validator(value) {
                    return (this.action == 'update' && value > 0) ? true : false;
                },
            },
        },

        data() {
            return {
                loaded: false,
                menu_item: {
                    is_divider: false,
                    name: '',
                    url: '',
                },
            };
        },

        computed: {
            actionTitleCase() {
                return _.startCase(this.action);
            }
        },

        watch: {},

        beforeCreate() {},

        async created() {
            await this.loadComponent();
            this.loaded = true;
        },

        beforeMount() {},

        mounted() {},

        beforeUpdate() {},

        updated() {},


        methods: {
            loadComponent() {
                if (this.action == 'create') {
                    this.clearForm();
                } else {
                    if (!this.menuItemId) {
                        return false;
                    }
                    return api.getMenuItem(this.menuItemId).then((menu_item) => {
                        this.menu_item = menu_item;
                    });
                }
            },

            clearForm() {
                this.menu_item = {
                    is_divider: false,
                    name: '',
                    url: '',
                };
            },

            toggle_is_divider() {
                this.menu_item.is_divider = !this.menu_item.is_divider;
            },

            async handleSubmit(return_to_menu = false) {
                if (this.action == 'create') {
                    await this.createMenuItem();

                    if (return_to_menu) {
                        this.clearForm();
                    } else {
                        this.$router.push({
                            name: 'laravel-menu-builder-menu-item-update',
                            params: {
                                'menuId': this.menuId,
                                'menuItemId': this.menu_item.id,
                            },
                        });
                    }
                } else {
                    await this.updateMenuItem();
                }

                if (return_to_menu) {
                    this.$router.push({ path: '/resources/menus/' + this.menuId });
                }
            },

            createMenuItem() {
                return api.createMenuItem(this.menuId, {
                    menu_id: this.menuId,
                    is_divider: this.menu_item.is_divider,
                    name: this.menu_item.name,
                    url: this.menu_item.url,
                })
                .then((created_menu_item) => {
                    //console.log('created_menu_item', created_menu_item);
                    this.menu_item = created_menu_item;
                    this.$toasted.show(this.__('The menu item was created!'), { type: 'success' });
                }).catch((error) => {
                    this.$toasted.show(this.__('There was a problem creating the menu item.'), { type: 'error' });
                });
            },

            updateMenuItem() {
                return api.updateMenuItem(this.menuId, this.menuItemId, {
                    is_divider: this.menu_item.is_divider,
                    name: this.menu_item.name,
                    url: this.menu_item.url,
                }).then((updated_menu_item) => {
                    //console.log('updated_menu_item', updated_menu_item);
                    this.menu_item = updated_menu_item;
                    this.$toasted.show(this.__('The menu item was updated!'), { type: 'success' });
                }).catch((error) => {
                    this.$toasted.show(this.__('There was a problem updating this menu item.'), { type: 'error' });
                });
            },
        },
    };
</script>
