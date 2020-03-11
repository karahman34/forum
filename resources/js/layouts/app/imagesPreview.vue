<template>
  <div v-if="images.length" id="images-preview-container">
    <!-- image Preview -->
    <div
      v-for="(image, index) in images"
      :key="index"
      class="is-inline-block image-preview"
      @mouseenter="image.active = true"
      @mouseleave="image.active = false"
    >
      <!-- The Image -->
      <img :src="image.src" class="the-image" :class="{ 'image-active': image.active }" />
      <!-- Delete Button -->
      <span
        class="delete is-medium"
        :class="{ 'image-delete-active': image.active }"
        @click="deleteImage(image)"
      ></span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    images: {
      type: Array,
      required: true,
    },
  },
  methods: {
    deleteImage(image) {
      this.$emit('delete', image);
    },
  },
};
</script>

<style scoped>
#images-preview-container {
  margin: 10px 0px;
}

@media screen and (max-width: 768px) {
  #images-preview-container .the-image {
    max-height: 97px !important;
  }
}

#images-preview-container .the-image {
  max-height: 110px;
  width: auto !important;
  margin-right: 10px;
}

.image-preview {
  position: relative;
}

.image-active {
  filter: blur(1px);
  transition: filter 0.2s ease-in-out;
}

.image-preview .delete {
  display: none;
  position: absolute;
  top: 35%;
  right: 44%;
  z-index: 99999;
  background: black;
  opacity: 60%;
}

.image-delete-active {
  display: inline !important;
}
</style>
