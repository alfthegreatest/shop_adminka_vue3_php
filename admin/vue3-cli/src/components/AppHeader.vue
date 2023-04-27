<template>
    <header class="sticky">
        <a href="/" class="logo">AlfTheGreatest</a>
        <router-link
            v-for="tab in tabs"
            :key="tab.link"
            :to="tab.link"
            tag="button"
            class="button"
        >{{ tab.title }}</router-link>
    </header>
</template>

<script>
export default {
    name: "AppHeader",
    props: ['tabs'],
}
</script>

<style scoped>
    .button.router-link-exact-active {
        border-bottom: solid 3px steelblue;
    }
</style>
