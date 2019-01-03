<style lang="scss" scoped>
</style>

<template>
<loading-view v-if="!loaded" :loading="!loaded"></loading-view>
<div v-else>
    <h1 class="mb-3 text-90 font-normal text-2xl">Edit Menu Item</h1>
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
                <button @click.stop.prevent="updateMenuItem(true)" type="button" dusk="update-and-continue-editing-button" class="ml-auto btn btn-default btn-primary mr-3">
                    Update &amp; Continue Editing
                </button>

                <button @click.stop.prevent="updateMenuItem(false)" type="button" dusk="update-menu-item-button" class="btn btn-default btn-primary">
                    Update Menu Item
                </button>
            </div>
        </form>
    </div>
</div>
</template>

<script>
    import api from 'mb_api';

    export default {
        name: 'menu-item',

        components: {},

        props: {
            menu_item_id: {
                type: Number,
                required: true,
                default() {
                    return 0;
                }
            },
        },

        data() {
            return {
                loaded: false,
                menu_item: {},
            };
        },

        computed: {},

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
                if (!this.menu_item_id) {
                    console.error('No menu item id found');
                    return false;
                }

                return api.getMenuItem(this.menu_item_id).then((menu_item) => {
                    this.menu_item = menu_item;
                });
            },

            toggle_is_divider() {
                this.menu_item.is_divider = !this.menu_item.is_divider;
            },

            updateMenuItem(continue_editing = true) {
                api.updateMenuItem(this.menu_item_id, {
                    is_divider: this.menu_item.is_divider,
                    name: this.menu_item.name,
                    url: this.menu_item.url,
                }).then((response) => {
                    this.menu_item = response.data;
                    this.$toasted.show(this.__('The menu item was updated!'), { type: 'success' });
                }).catch((error) => {
                    this.$toasted.show(this.__('There was a problem updating this menu item.'), { type: 'error' });
                });

                if (!continue_editing) {
                    this.$router.go(-1);
                }
            },
        },
    };
</script>
