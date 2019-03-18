<style lang="scss" scoped>
    //.btn {}
    // .btn-default {}
    // .btn-primary {}
</style>

<template>
<button :class="ele_classes" type="button" dusk="create-button">
    Save {{ resourceName }} Order
</button>
</template>

<script>
    export default {
        name: 'mb-save-sort-button',

        props: {
            resourceName: {
                type: String,
                default() {
                    return 'Menu Item';
                },
            },
            styleType: {
                type: String,
                default() {
                    return 'button';
                },
            },
            eleClasses: {
                type: Object,
                default() {
                    return {};
                },
            },
            overwriteEleClasses: {
                type: Boolean,
                default() {
                    return false;
                },
            },
        },

        components: {},

        data() {
            return {
                defualtBtnClasses: {
                    'btn': true,
                    'btn-default': true,
                    'btn-primary': true,
                },
                defaultLinkClasses: {
                    'appearance-none': true,
                    'cursor-pointer': true,
                },
                defaultSvgClasses: {
                    'fill-current': true,
                }
            };
        },

        computed: {
            default_classes() {
                return (this.styleType == 'button') ? this.defualtBtnClasses : this.defaultLinkClasses;
            },
            ele_classes() {
                return (this.overwriteEleClasses) ? this.default_classes : Object.assign({}, this.default_classes, this.eleClasses);
            },
        },

        watch: {},

        beforeCreate() {},

        created() {},

        beforeMount() {},

        mounted() {},

        beforeUpdate() {},

        updated() {},

        methods: {},
    };
</script>
