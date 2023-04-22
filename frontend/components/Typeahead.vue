<template>
  <div class="typeahead">
    <div class="typeahead__toggle" ref="toggle" @mousedown.prevent="toggle">
      <input type="text" class="typeahead__search" ref="search">
      <span class="typeahead__text" ref="text">Sample Text</span>
    </div>
    <ul class="typeahead__list" v-if="open">
      <li class="typeahead__item">
        <a class="typeahead__link">Test Option 3</a>
      </li>
      <li class="typeahead__item">
        <a class="typeahead__link">Test Option 2</a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      open: false
    }
  },
  methods: {
    toggle(e) {
      if (e.target === this.$refs.toggle || e.target === this.$refs.search || e.target === this.$refs.text) {
        if(this.open) {
          this.$refs.search.blur();
        } else {
          this.$refs.search.focus();
        }
      }
    }
  }
}
</script>

<style>
.typeahead {
  border-radius: 3px;
  border: 1px solid #ccc;
  position: relative;
  z-index: 1;
  width: 100%;
  font-size: 14px;
}
.typeahead__open {
  border: 1px solid #41B883;
}
.typeahead__open .typeahead__text {
  color: #999;
  opacity: 0.4;
}
.typeahead__toggle {
  position: relative;
  z-index: 1;
  width: 100%;
}
.typeahead__search {
  position: absolute;
  top: 0;
  left: 0;
  line-height: 1em;
  font-size: 1em;
  padding: 10px;
  width: 100%;
  display: block;
  cursor: text;
  background: transparent;
  border: none;
  outline: none;
  z-index: 2;
}
.typeahead__text {
  min-height: 36px;
  font-size: 1em;
  line-height: 1em;
  padding: 10px;
  display: inline-block;
  position: relative;
  z-index: 3;
}
.typeahead__list {
  margin: 0;
  padding: 0;
  max-height: 200px;
  overflow-y: scroll;
}
.typeahead__item {
  display: block;
  border-top: 1px solid #f4f4f4;
}
.typeahead__link {
  display: block;
  padding: 10px;
  line-height: 1em;
  font-size: 1em;
  cursor: pointer;
}
.typeahead__active {
  background: #41B883;
  color: #fff;
}
</style>
