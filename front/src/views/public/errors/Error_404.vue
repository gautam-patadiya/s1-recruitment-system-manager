<template>
    <div class="mh-100 text-center">
        <div class="mt-50">
            <h1 class="error-text mb-0 text-white">404</h1>
            <h1 class="text-white">Whoops! You are on wrong page.</h1>
            <router-link :to="{name: 'home'}" class="text-center ant-btn ant-btn-default ant-btn-background-ghost">Go To Home</router-link>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            };
        }
    }
</script>

<style lang="scss">
    .error-text{
        font-size: 12rem;
    }
</style>
