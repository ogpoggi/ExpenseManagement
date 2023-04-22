<template>
    <div>
        <h1 class="text-3xl font-black mb-10">
            Hello {{ $auth.user.name }}!
        </h1>

        <div class="mb-10">
            <span>Here is a list of your previous subscriptions!</span>
        </div>

        <p v-if="$fetchState.pending">Loading...</p>
        <p v-else-if="$fetchState.error">Error! :(</p>
        <template v-else>
            <template v-if="subscriptions.length">
                <div v-for="(subscription, index) in subscriptions"
                     :key="subscription.company.id"
                     :class="`flex ${index + 1 == subscriptions.length ? '' : 'pb-10 mb-10 border-b'}`">
                    <div class="w-1/3 h-56 relative overflow-hidden rounded-lg">
                        <img :src="subscription.company.featured_image.path" class="object-cover w-full h-full"></img>
                    </div>


                    <div class="w-full pl-14">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold">{{ subscription.company.title }}</h1>
                            <span class="block font-semibold">Total ${{ subscription.price / 100 }}</span>
                        </div>
                        <p class="mb-5">
            <span class="text-gray-600 text-sm uppercase">
                From <strong>{{ subscription.start_date.split('T')[0] }}</strong> To <strong>{{ subscription.end_date.split('T')[0] }}</strong>
            </span>
                        </p>
                        <p class="leading-loose mb-5">
                            {{ subscription.company.description }}
                        </p>
                        <NuxtLink to="'/companies/' + subscription.company.id"
                                  class="text-purple-600 font-bold">
                            More details...
                        </NuxtLink>
                    </div>
                </div>
            </template>
        </template>
    </div>
</template>

<script>
export default {
    middleware: 'auth',

    data: () => ({
      subscriptions: []
    }),

    async fetch() {
        const response = await this.$axios.$get('/subscriptions')

        this.subscriptions = response.data;
    },

    head() {
        return {
            title: 'Profile',
        }
    },
}
</script>
