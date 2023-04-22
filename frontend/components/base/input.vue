<template>
    <input
        class="relative block w-full rounded border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-sky-50 sm:text-sm sm:leading-6"
        :value="value"
        @input="$emit('input', $event.target.value)"
    />
</template>


<script>
export default {
    props: {
        value: {
            type: String,
            required: true,
        },
    },
}
</script>
