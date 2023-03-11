<script setup>
import { getByName } from "@/Api/ItemApi";
import BaseTextInput from "@/Components/Input/BaseTextInput.vue";
import Item from "@/Components/Item.vue";
import { reactive, ref } from "vue";
import DefaultButton from "@/Components/Button/DefaultButton.vue";

const SEARCH_DELAY_IN_SECONDS = 2;

const itemsList = ref([]);
const state = reactive({
    showLoadMoreButton: true,
    disableInputs: false,
});
const pagination = reactive({
    limit: 20,
    offset: 0,
});

let ajaxRequest = null;
let searchDelayId = null;
let searchInputValue = '';

const onInputKeyup = (e) => {
    searchInputValue = e.target.value;
    if (searchDelayId) {
        clearTimeout(searchDelayId);
    }

    let delay = SEARCH_DELAY_IN_SECONDS * 1000;
    if (searchInputValue === '') {
        delay = 0;
    }

    searchDelayId = setTimeout(() => {
        if (searchInputValue.length < 3) {
            return;
        }
        if (ajaxRequest) {
            ajaxRequest.cancel();
        }

        ajaxRequest = axios.CancelToken.source();
        pagination.offset = 0;

        getByName(searchInputValue, pagination.limit, pagination.offset, ajaxRequest.token)
            .then((response) => {
                itemsList.value = response.data;

                state.showLoadMoreButton = itemsList.value.length === pagination.limit
            })
            .catch((reason) => {
                if (axios.isCancel(reason)) {
                    return;
                }
                console.error(reason);
            });
    }, delay);
}

const loadMoreItems = () => {
    state.disableInputs = true;

    pagination.offset += pagination.limit;
    getByName(searchInputValue, pagination.limit, pagination.offset, ajaxRequest.token)
        .then((response) => {
            itemsList.value = [...itemsList.value, ...response.data];
            state.disableInputs = false;
        })
        .catch((reason) => {
            console.error(reason);
            state.disableInputs = false;
        });
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
        <BaseTextInput :disabled="state.disableInputs"
                       @keyup="onInputKeyup"
                       placeholder="Item name..."
                       class="w-3/4 mx-auto p-3 text-xl disabled:cursor-not-allowed"
        />
        <div class="flex flex-wrap w-full border-0 rounded-lg bg-white mt-10 pt-4"
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
            <DefaultButton class="w-full mt-2 disabled:cursor-not-allowed"
                @click="loadMoreItems"
                v-show="state.showLoadMoreButton"
                :disabled="state.disableInputs">
                Show more
            </DefaultButton>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
