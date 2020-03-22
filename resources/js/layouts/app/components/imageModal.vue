<template>
  <div class="modal is-active">
    <div class="modal-background" @click="closeModal"></div>
    <div class="modal-content">
      <p class="image">
        <!-- Image -->
        <img :src="image" :alt="image" />
      </p>
    </div>
    <!-- Close -->
    <button class="modal-close is-large" aria-label="close" @click="closeModal"></button>
  </div>
</template>

<script>
export default {
  props: {
    image: {
      type: String,
      required: true,
    },
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
  },
};
</script>

<style lang="scss" scoped>
</style>