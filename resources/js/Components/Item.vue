<script setup>
import { computed } from "vue";
import BadgeDanger from "@/Components/Badge/BadgeDanger.vue";
import BadgePurple from "@/Components/Badge/BadgePurple.vue";
import { formatNumber } from "@/Utils/format";

const props = defineProps({
    name: { required: true, type: String, },
    imgLink: { required: true, type: String, },
    slotsCount: { required: true, type: Number, },
    canBeSoldOnFleaMarket: { required: true, type: Boolean, },
    tradeOffers: {
        type: Array,
        default: () => [{traderName: '', avgPrice: 0.00, currencySymbol: ''}]
    }
});

const bestTraderOffer = computed(() => {
    let tradeOffers = props.tradeOffers.filter((item) => item.traderName !== 'FleaMarket');
    if (!tradeOffers.length) {
        return null;
    }
    return tradeOffers.reduce((prev, curr) => curr.avgPrice > prev.avgPrice ? curr : prev);
});

const fleaMarketTradeOffer = computed(
    () => props.tradeOffers.filter((offer) => offer.traderName === 'FleaMarket').pop()
);
</script>

<template>
    <div class="flex flex-wrap bg-lightGray p-3 rounded">
        <header class="w-full flex">
            <div class="img-container">
                <img class="object-scale-down w-full h-full rounded-md" :src="imgLink" :alt="'imageOf:' + name">
            </div>
            <span class="flex-1 px-4 text-center text-xl font-semibold">
                {{ name }}
            </span>
        </header>
        <section class="w-full text-xl">
            <h5 class="font-bold">Per Item (avg 24h):</h5>
            <p class="flex justify-between">
                <span>Flea Market:</span>
                <BadgeDanger v-if="!canBeSoldOnFleaMarket" class="h-fit self-center">Can't be sold</BadgeDanger>
                <BadgePurple v-else-if="!fleaMarketTradeOffer" class="h-fit self-center">Not found</BadgePurple>
                <span v-else>{{ formatNumber(fleaMarketTradeOffer.avgPrice) }}{{ fleaMarketTradeOffer.currencySymbol }}</span>
            </p>
            <p class="flex justify-between">
                <span>Best trader{{ bestTraderOffer ? ` (${bestTraderOffer.traderName})` : '' }}:</span>
                <BadgePurple v-if="!bestTraderOffer" class="h-fit self-center">Not found</BadgePurple>
                <span v-else>{{ formatNumber(bestTraderOffer.avgPrice) }}{{ bestTraderOffer.currencySymbol }}</span>
            </p>
            <h5 class="font-bold">Per Slot (avg 24h):</h5>
            <p class="flex justify-between">
                <span>Flea Market:</span>
                <BadgeDanger v-if="!canBeSoldOnFleaMarket" class="h-fit self-center">Can't be sold</BadgeDanger>
                <BadgePurple v-else-if="!fleaMarketTradeOffer" class="h-fit self-center">Not found</BadgePurple>
                <span v-else>{{ formatNumber(fleaMarketTradeOffer.avgPrice / slotsCount) }}{{ fleaMarketTradeOffer.currencySymbol }}</span>
            </p>
            <p class="flex justify-between">
                <span>Best trader{{ bestTraderOffer ? ` (${bestTraderOffer.traderName})` : '' }}:</span>
                <BadgePurple v-if="!bestTraderOffer" class="h-fit self-center">Not found</BadgePurple>
                <span v-else>{{ formatNumber(bestTraderOffer.avgPrice / slotsCount) }}{{ bestTraderOffer.currencySymbol }}</span>
            </p>
        </section>
    </div>
</template>

<style scoped lang="scss">
.img-container {
    max-height: 125px;
    max-width: 200px;
}
</style>
