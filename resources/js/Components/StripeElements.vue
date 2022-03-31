<template>
    <stripe-element :element="cardElement" @change="event = $event" class="w-full bg-white border p-3 border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25" />
    <div v-if="event && event.error">{{ event.error.message }}</div>
    <button @click="registerCard">Add</button>

</template>

<script>
import { defineComponent, ref } from "vue";
import { useStripe, StripeElement } from "vue-use-stripe";

export default defineComponent({
    components: { StripeElement },
    setup() {
        const event = ref(null);

        const {
            stripe,
            elements: [cardElement],
        } = useStripe({
            key: 'pk_test_DP6w7JVPSpYp5HhNkHLR43bw',
            elements: [
                { 
                    type: "card",
                    options: {
                        hidePostalCode: true
                    }
                }
            ],
        });

        const registerCard = () => {
            if (event.value?.complete) {
                axios.get('/stripe/token').then(response => {
                    console.log(response);

                    return stripe.value?.confirmCardSetup(response.data, {
                        payment_method: {
                            card: cardElement.value,
                        },
                    });
                });
            }
        };

        return {
            event,
            cardElement,
            registerCard,
        };
    },
});
</script>