<template>
    <div id="app">
        <div class="container">
            <header is="AppHeader" :tabs="tabs"></header>

            <div class="row">
                <div class="col-sm-1"></div>

                <keep-alive>
                    <router-view class="col-sm-10"></router-view>
                </keep-alive>

                <div class="col-sm-1"></div>
            </div>

            <footer is="AppFooter"></footer>
        </div>
    </div>
</template>

<script>
    import AppHeader from './AppHeader.vue';
    import AppFooter from './AppFooter.vue';

    export default {
        name: 'app',
        components: {
            AppHeader,
            AppFooter
        },
        data () {
            return {
                tabs: [{
                    link: '/',
                    title: 'Товары'
                }, {
                    link: '/categories',
                    title: 'Категории'
                }, {
                    link: '/brands',
                    title: 'Бренды'
                }]
            }
        },
        created () {
            this.$store.dispatch('categories/getCategories');
            this.$store.dispatch('brands/getBrands');
            this.$store.dispatch('products/getProducts');
        }
    }
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

h1, h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}
</style>
