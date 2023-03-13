<script setup>
import { closeNav } from "@/Utils/sideNavigation";
import { Link } from "@inertiajs/vue3";

</script>

<template>
    <nav id="mainSideNav" class="min-h-screen w-0 fixed top-0 left-0 bg-gray-800 text-primary overflow-hidden pt-[60px]">
        <a class="closebtn" @click="closeNav"><i class="fi fi-br-cross"></i></a>

        <Link href="/" class="mt-3"><i class="fi fi-sr-usd-circle align-middle"></i> Item price checker</Link>

        <span class="text-[10px] absolute bottom-5 left-5">WORK IN PROGRESS</span>
    </nav>
</template>

<style scoped lang="scss">

nav#mainSideNav {
    transition: 0.5s;
    z-index: 9999;
    *:not(.closebtn) {
        min-width: 250px !important;
        overflow: hidden !important;
    }
}

nav#mainSideNav a {
    padding: 8px 8px 8px 16px;
    text-decoration: none;
    font-size: 18px;
    display: block;
    transition: 0.6s;

    &:hover {
        padding-left: 25px;
    }
}

nav#mainSideNav .closebtn {
    $iconTop: 15px;
    $iconRight: 25px;
    $iconSize: 18px;

    position: absolute;
    top: $iconTop;
    right: $iconRight;
    font-size: $iconSize;
    margin-left: 50px;
    transition: 0.4s;
    cursor: pointer;

    &:hover {
        font-size: $iconSize * 1.5;
        top: $iconTop * 0.75;
        right: $iconRight * 0.75;
    }
}

</style>
