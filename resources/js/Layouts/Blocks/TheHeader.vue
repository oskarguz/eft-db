<script setup>
import { openNav } from "@/Utils/sideNavigation";

</script>

<template>
    <header class="flex justify-between shrink-0 bg-primary text-fontPrimary p-6 lg:px-14">
        <div>
            <i class="fi fi-br-menu-burger cursor-pointer text-2xl hover:text-3xl transition-all duration-500" @click="openNav()"></i>
        </div>
        <h1 class="text-xl font-bold text-end">
            Escape from Tarkov - Tools <i class="fi fi-rr-hammer align-middle"></i><br/>
            <span class="text-[10px]">WORK IN PROGRESS</span>
        </h1>

    </header>
</template>

<style scoped lang="scss">

</style>
