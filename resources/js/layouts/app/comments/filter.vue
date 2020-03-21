<template>
  <!-- Sort -->
  <div class="field">
    <div class="control has-icons-left">
      <div class="select primary is-rounded">
        <select v-model="localSelectedSort" @change="sortComments">
          <option value="new" selected>New</option>
          <option value="old">Old</option>
        </select>
      </div>
      <div class="icon is-small is-left">
        <i class="mdi mdi-sort icon-filter"></i>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    selectedSort: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      localSelectedSort: 'new',
    };
  },
  methods: {
    sortComments() {
      // Update parent sort
      this.$emit('update:selected-sort', this.localSelectedSort);
      // Emit filter
      this.$emit('filter', this.localSelectedSort);
    },
  },
  watch: {
    selectedSort: {
      immediate: true,
      handler(val) {
        if (this.localSelectedSort !== val) {
          this.localSelectedSort = val;
        }
      },
    },
  },
};
</script>

<style lang="scss" scoped>
</style>