<template>
    <div>
        <h1>Бренды</h1>
        <brands-new></brands-new>
        <table>
            <thead>
                <th>id</th>
                <th>Бренд</th>
                <th></th>
            </thead>
            <tbody>
                <tr
                    is="BrandsItem"
                    v-for="brand in brands"
                    :brand="brand"
                    :key="brand.is"
                ></tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import BrandsItem from "./BrandsItem";
    import BrandsNew from "./BrandsNew";

    export default {
        components: {
            BrandsItem,
            BrandsNew
        },
        computed: {
            brands() {
                return this.$store.state.brands.all
            }
        }
    }
</script>
