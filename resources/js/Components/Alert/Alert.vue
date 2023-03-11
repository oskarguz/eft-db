<script setup>

const props = defineProps({
    icon: {
        type: String,
        default: () => 'fi-br-info',
    },
    visible: {
        type: Boolean,
        default: () => true,
    }
});

const iClass = ['fi'];
iClass.push(props.icon);
</script>

<template>
    <div v-if="visible"
         class="p-3 text-base text-purple-600 font-bold rounded-lg bg-white flex ring-purple-600 ring"
    >
        <div class="h-5 w-5 mt-0.5">
            <i :class="iClass"></i>
        </div>
        <slot/>
    </div>
</template>

<style scoped lang="scss"></style>
