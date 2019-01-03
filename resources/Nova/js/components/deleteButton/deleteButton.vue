<style lang="scss" scoped>
    //.btn {}
    // .btn-default {}
    // .btn-primary {}
</style>

<template>
<button :class="ele_classes" type="button" title="Delete">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" :class="svg_classes">
        <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
    </svg>
</button>
</template>

<script>
    export default {
        name: 'mb-delete-button',

        props: {
            styleType: {
                type: String,
                default() {
                    return 'button';
                },
            },
            overwriteEleClasses: {
                type: Boolean,
                default() {
                    return false;
                },
            },
            eleClasses: {
                type: Object,
                default() {
                    return {};
                },
            },
            overwriteSvgClasses: {
                type: Boolean,
                default() {
                    return false;
                },
            },
            svgClasses: {
                type: Object,
                default() {
                    return {};
                },
            },
        },

        components: {},

        data() {
            return {
                defualtBtnClasses: {
                    'cursor-pointer': true,
                    'btn': true,
                    'btn-default': true,
                    'btn-icon': true,
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
            svg_classes() {
                return (this.overwriteSvgClasses) ? this.svgClasses : Object.assign({}, this.defaultSvgClasses, this.svgClasses);
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
