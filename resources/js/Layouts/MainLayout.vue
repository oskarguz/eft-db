<script setup>
import TheHeader from "@/Layouts/Blocks/TheHeader.vue";
import TheFooter from "@/Layouts/Blocks/TheFooter.vue";

</script>

<template>
    <main class="flex flex-col min-h-screen items-stretch">
        <TheHeader></TheHeader>
        <article class="flex grow shrink-0">
            <slot/>
        </article>
        <TheFooter></TheFooter>
    </main>
</template>

<style scoped lang="scss">

</style>
