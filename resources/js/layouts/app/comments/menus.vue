<template>
  <div class="dropdown is-hoverable">
    <!-- The Dots Trigger -->
    <div class="dropdown-trigger">
      <span class="options-dropdown-trigger"></span>
      <span class="options-dropdown-trigger"></span>
      <span class="options-dropdown-trigger"></span>
    </div>
    <!-- Dropdown Structure -->
    <div class="dropdown-menu">
      <div class="dropdown-content options-dropdown-content" role="menu">
        <!-- Edit -->
        <a class="dropdown-item" :href="`/comments/${comment.id}/edit`" target="_blank">
          <i class="mdi mdi-pencil"></i>
          Edit
        </a>
        <!-- Delete -->
        <a class="dropdown-item" @click="deleteComment">
          <!-- Trash icon -->
          <i v-if="!deleteLoading" class="mdi mdi-trash-can"></i>
          <!-- Loading icon -->
          <i v-else class="mdi mdi-loading mdi-spin"></i>
          Delete
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    comment: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      deleteLoading: false,
    };
  },
  methods: {
    async deleteComment() {
      try {
        if (this.deleteLoading) {
          return;
        }

        // Turn on loading
        this.deleteLoading = true;
        // Fetch Api
        const res = await axios.delete(`/comments/${this.comment.id}`);

        if (res.data.ok) {
          this.$emit('deleted', this.comment);
        }
      } catch (err) {
        throw Error(err);
      } finally {
        this.deleteLoading = false;
      }
    },
  },
};
</script>

<style scoped>
.options-dropdown-trigger {
  display: inline-block;
  background: grey;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  margin-bottom: 3px;
}

.options-dropdown-content {
  width: auto;
  max-width: 100px;
}
.options-dropdown-content i {
  margin-right: 3px;
}

.dropdown-menu {
  left: -80px !important;
}
</style>