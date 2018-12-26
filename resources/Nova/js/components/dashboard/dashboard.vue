<style lang="scss" scoped>
</style>

<template>
<div v-if="loaded">
    <heading class="mb-6">
        Menu Builder Dashboard
    </heading>
</div>
</template>

<script>
    export default {
        name: 'dashboard',

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
            console.log('menu builder dashboard created!');
            await this.loadComponent();

            this.loaded = true;
        },

        beforeMount() {},

        mounted() {
            console.log('menu builder dashboard mounted!');
        },

        beforeUpdate() {},

        updated() {},


        methods: {
            loadComponent() {
                console.log('dashboard loadComponent() called!');
            },
        },
    };
</script>
