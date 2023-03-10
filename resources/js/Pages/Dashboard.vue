<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head } from '@inertiajs/vue3';
import SearchItemWidget from "@/Components/SearchItemWidget.vue";
</script>

<template>
    <Head title="Dashboard"></Head>

    <MainLayout>
        <section class="flex flex-col justify-center grow px-4 bg-primary">
            <SearchItemWidget></SearchItemWidget>
        </section>
    </MainLayout>
</template>

<style scoped lang="scss">

</style>
