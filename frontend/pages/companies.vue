<template>
<div>
  <h1>Sélectionnez vos dépenses</h1>
</div>
</template>

<script>

export default {
  data: () => ({
    errors: [],
    companies: [],
  }),

  methods:{

  },

  async fetch() {
    this.errors = [];
  },


}
</script>

<style>
.page-enter-active,
.page-leave-active {
  transition-property: opacity;
  transition-timing-function: ease-in-out;
  transition-duration: 500ms;
}

.page-enter,
.page-leave-to {
  opacity: 0;
}
</style>
