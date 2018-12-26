<style lang="scss" scoped>
</style>

<template>
<div v-if="loaded">
    menu component
</div>
</template>

<script>
    export default {
        name: 'menu',

        components: {},

        props: {},

        data() {
            return {
                loaded: false,
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

        mounted() {
            console.log('menu builder: menu component mounted!');
        },

        beforeUpdate() {},

        updated() {},


        methods: {
            loadComponent() {
                console.log('menu component loadComponent() called!');
            },
        },
    };
</script>
