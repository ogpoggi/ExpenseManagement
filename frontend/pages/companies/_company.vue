<template>
    <p v-if="$fetchState.pending">Loading...</p>
    <p v-else-if="$fetchState.error">Error! :(</p>
    <div v-else class="flex">
        <div class="w-1/2 h-80 relative overflow-hidden rounded-lg">
            <img :src="company.images[0].path" class="object-cover w-full h-full"></img>
        </div>

        <div class="w-full pl-14">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ company.title }}</h1>
                <span class="block font-semibold">${{ company.price_per_day / 100 }} per day</span>
            </div>
            <p class="leading-loose mb-5">
                {{ company.description }}
            </p>
            <Button class="mt-7">Book</Button>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
      company: {}
    }),

    async fetch() {
        const response = await this.$axios.$get('/companies/' + this.$route.params.company)

        this.company = response.data;
    },

    head() {
        return {
            title: this.company.title,
        }
    },
}
</script>
