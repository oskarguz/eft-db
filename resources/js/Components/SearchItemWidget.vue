<script setup>
let request;
function onInputChange() {
    // validation
}
</script>

<template>
    <div>
        <label>Search for item:</label>
        <input type="text"
               value="Test"
               class="w-full"
               @change="onInputChange"
        >
    </div>
</template>

<style scoped lang="scss">

</style>
