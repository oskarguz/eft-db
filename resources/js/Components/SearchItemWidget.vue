<script setup>
import { getByName } from "@/Api/ItemApi";
import BaseTextInput from "@/Components/Buttons/BaseTextInput.vue";
import Item from "@/Components/Item.vue";
import { ref } from "vue";

const SEARCH_DELAY_IN_SECONDS = 2;
const itemsList = ref([]);

let ajaxRequest = null;
let searchDelayId = null;

const onInputKeyup = async (e) => {
    let value = e.target.value;

    if (searchDelayId) {
        clearTimeout(searchDelayId);
    }

    let delay = SEARCH_DELAY_IN_SECONDS * 1000;
    if (value === '') {
        delay = 0;
    }

    searchDelayId = setTimeout(() => {
        if (value.length < 3) {
            return;
        }
        if (ajaxRequest) {
            ajaxRequest.cancel();
        }

        ajaxRequest = axios.CancelToken.source();

        getByName(value, 20, 0, ajaxRequest.token)
            .then((response) => {
                itemsList.value = response.data;
            })
            .catch((reason) => {
                if (axios.isCancel(reason)) {
                    console.log('cancelled');
                    return;
                }
                console.error(reason);
            });
    }, delay);
}

const getTradeOffersFromItem = (item) => {
    if (!item.hasOwnProperty('prices') || typeof item.prices !== 'object') {
        return [];
    }

    return item.prices.map((offer) => {
        return {
            traderName: offer.vendor.name,
            avgPrice: offer.price_rub,
            currencySymbol: '₽',
        }
    });
}
</script>

<template>
    <div class="flex flex-wrap">
        <label class="text-center block w-full text-2xl mb-8 font-semibold text-fontPrimary">Sell price checker</label>
        <BaseTextInput @keyup="onInputKeyup"
                       placeholder="Item name..."
                       class="w-3/4 mx-auto p-3 text-xl"
        />
        <div class="flex flex-wrap w-full border-0 rounded-lg bg-white mt-10 py-4"
             v-show="itemsList.length"
        >
            <Item
                v-for="item in itemsList"
                :trade-offers="getTradeOffersFromItem(item)"
                :can-be-sold-on-flea-market="Boolean(item.can_be_sold_on_flea_market || false)"
                :slots-count="(item.height ?? 1) * (item.width ?? 1)"
                :img-link="item.img_link || ''"
                :name="item.name || ''"
                class="my-1 w-full">
            </Item>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
