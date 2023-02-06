<script setup>
import TheHeader from "@/Layouts/Blocks/TheHeader.vue";
import TheFooter from "@/Layouts/Blocks/TheFooter.vue";

</script>

<template>
    <main>
        <TheHeader></TheHeader>
        <article>
            <slot/>
        </article>
        <TheFooter></TheFooter>
    </main>
</template>

<style scoped lang="scss">
main {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
article {
    flex-grow: 2;
}
</style>
