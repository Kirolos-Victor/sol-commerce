<template>
    <inertia-link :href="'/product/'+product.url">
        <div class="relative">

            <product-image :product="product"></product-image>
            <div v-if="product.perishable && cart && cart.location_id && (!product.locations.some(location => location.id === cart.location_id) || cart.non_perishable)"
                class="na-image-overlay">Not available in your area
            </div>

        </div>

        <h2 :class="{ 'font-serif text-2xl mb-1': !simple, 'text-sm uppercase mb-1': simple }">{{ product.title }}</h2>

        <div class="text-primary mb-4" v-if="product.reviews && product.reviews.length > 0 && !simple">
            <div class="text-lg mr-2">
                <star-rating v-model:rating="product.rating" :read-only="true" :active-color="'#AA947C'"
                             :show-rating="false" :star-size="20" :inline="true" :increment="0.1"></star-rating>
            </div>
            <div class="text-xs mt-2">{{ product.rating.toFixed(1) }} stars from {{ product.reviews.length }}
                review{{ product.reviews.length > 1 ? 's' : '' }}
            </div>
        </div>

        <div class="mb-2">
            <div v-if="product.enquire_only">Enquire for pricing</div>
            <div v-else>${{ product.price_range }}</div>
            <!-- <div class="text-xs">or 4 payments from ${{ formatPrice(product.variants[0].price / 4) }} with Afterpay <img class="inline-block" src="/assets/icons/afterpay.svg"></div> -->
        </div>

        <span v-if="!simple"
              class="cursor-pointer uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs">View Options</span>
    </inertia-link>
</template>

<script>
import ButtonText from '@/Components/ButtonText'
import StarRating from 'vue-star-rating'
import ProductImage from "./ProductImage";

export default {
    props: {
        product: {
            type: Object,
            default: {},
        },
        simple: {
            type: Boolean,
            default: false,
        }
    },


    components: {
        ProductImage,
        ButtonText,
        StarRating
    },

    computed: {
        cart() {
            return this.$page.props.cart
        }
    },

    methods: {
        // formatPrice(value) {
        //     console.log(value);
        // 	return value.toFixed(2)
        // },
    },
}
</script>
