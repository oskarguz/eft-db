<script setup>
import { getByName } from "@/Api/ItemApi";
import BaseTextInput from "@/Components/Input/BaseTextInput.vue";
import Item from "@/Components/Item.vue";
import { reactive, ref, watch, nextTick } from "vue";
import DefaultButton from "@/Components/Button/DefaultButton.vue";
import DefaultSpinner from "@/Components/Spinner/DefaultSpinner.vue";
import Alert from "@/Components/Alert/Alert.vue";

const SEARCH_DELAY_IN_SECONDS = 2;

const state = reactive({
    showLoadMoreButton: false,
    disableInputs: false,
    loading: false,
});
const pagination = reactive({
    limit: 20,
    offset: 0,
});
const alert = reactive({
    message: '',
    isVisible: false,
});

const itemsList = ref([]);
const searchValue = ref('');
const searchInput = ref(null);

watch(() => state.loading, (loading) => {
    if (loading === true) {
        toggleAlert(false);
    }
});

let ajaxRequest = null;
let searchDelayId = null;

const onSearchInputChange = (e) => {
    state.loading = true;

    if (searchDelayId) {
        clearTimeout(searchDelayId);
    }

    searchValue.value = e.target.value;
    if (searchValue.value.length < 3) {
        state.loading = false;
        return;
    }

    let delay = SEARCH_DELAY_IN_SECONDS * 1000;

    searchDelayId = setTimeout(() => {
        if (ajaxRequest) {
            ajaxRequest.cancel();
        }

        ajaxRequest = axios.CancelToken.source();
        pagination.offset = 0;

        getByName(searchValue.value, pagination.limit, pagination.offset, ajaxRequest.token)
            .then((response) => {
                itemsList.value = response.data;

                state.showLoadMoreButton = itemsList.value.length === pagination.limit;
                state.loading = false;

                if (itemsList.value.length === 0) {
                    toggleAlert(true, 'Not found.');
                }
            })
            .catch((reason) => {
                state.loading = false;
                if (axios.isCancel(reason)) {
                    return;
                }
                toggleAlert(true, 'Service is not available, try again later.');
            });
    }, delay);
}

const loadMoreItems = () => {
    if (state.loading) {
        return;
    }

    state.disableInputs = state.loading = true;

    pagination.offset += pagination.limit;
    getByName(searchValue.value, pagination.limit, pagination.offset, ajaxRequest.token)
        .then((response) => {
            itemsList.value = [...itemsList.value, ...response.data];
            state.disableInputs = state.loading = false;

            if (response.data.length === 0) {
                toggleAlert(true, 'No more items to display.')
            }
        })
        .catch((reason) => {
            state.disableInputs = state.loading = false;
            toggleAlert(true, 'Service is not available, try again later.');
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

const toggleAlert = (visibility, message) => {
    alert.isVisible = Boolean(visibility);
    alert.message = String(message);
    if (alert.isVisible) {
        nextTick(() => {
            window.scrollTo(0, document.body.scrollHeight);
        });
    }
}

const clearSearchInput = () => {
    searchValue.value = '';
    itemsList.value = [];
    nextTick(() => {
        searchInput.value.$el.focus();
    });
}
</script>

<template>
    <div class="flex flex-wrap">
        <label class="text-center block w-full text-2xl mb-8 font-semibold text-fontPrimary">Sell price checker</label>
        <div class="relative w-3/4 mx-auto">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none" aria-hidden="true">
                <i class="w-5 h-5 text-gray-400 fi-rr-search"></i>
            </div>
            <BaseTextInput :disabled="state.disableInputs"
                           :value="searchValue"
                           @input="onSearchInputChange"
                           placeholder="Item name..."
                           class="w-full pl-9 pr-12 p-3 text-xl disabled:cursor-not-allowed"
                           ref="searchInput"
            />
            <div v-if="state.loading" class="absolute inset-y-0 right-0 flex items-center pointer-events-none">
                <DefaultSpinner class="text-primary fill-orange mr-3 h-8 w-8"></DefaultSpinner>
            </div>
            <div v-else-if="searchValue.length" class="absolute inset-y-0 right-0 flex items-center">
                <button class="w-11 text-2xl h-full bg-red-500 text-primary rounded-r-lg pt-2 hover:bg-red-600 hover:ring hover:ring-orange"
                        @click="clearSearchInput">
                    <i class="fi fi-br-cross-circle"></i>
                </button>
            </div>
        </div>
        <div class="flex flex-wrap w-full border-0 rounded-lg bg-white mt-10 pt-4"
             v-show="itemsList.length"
             :class="state.loading ? 'opacity-50' : ''"
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
            <DefaultButton class="w-full mt-2 disabled:cursor-not-allowed text-center relative"
                @click="loadMoreItems"
                v-show="state.showLoadMoreButton"
                :disabled="state.disableInputs">
                Show more
                <div v-show="state.loading"
                     class="absolute inset-y-0 right-0 flex items-center pointer-events-none pr-3"
                     aria-hidden="true"
                >
                    <DefaultSpinner class="w-8 h-8 text-purple-600 fill-white"></DefaultSpinner>
                </div>
            </DefaultButton>
        </div>
        <div class="w-full">
            <Alert class="my-4" :visible="alert.isVisible && !state.loading">
                {{ alert.message }}
            </Alert>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
