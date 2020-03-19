<template>
  <div>
    <!-- Edit -->
    <a
      v-if="auth !== null && auth.id === post.user_id"
      class="dropdown-item"
      :href="`/posts/${post.id}/edit`"
    >
      <i class="mdi mdi-pencil"></i>
      Edit
    </a>

    <!-- Save -->
    <div v-if="auth !== null" class="dropdown-item" @click="toggleSave">
      <!-- Save Loading Icon -->
      <i v-if="saveLoading" class="mdi mdi-loading mdi-spin loading-icon"></i>

      <span v-else>
        <!-- Saved Icon -->
        <i v-if="isSaved()" class="mdi mdi-check"></i>
        <!-- Unsaved icon -->
        <i v-else class="mdi mdi-close"></i>
      </span>
      Save
    </div>

    <!-- Delete -->
    <div
      v-if="auth !== null && auth.id === post.user_id"
      class="dropdown-item"
      @click="deletePost"
    >
      <!-- Save Loading Icon -->
      <i v-if="deleteLoading" class="mdi mdi-loading mdi-spin loading-icon"></i>

      <!-- Delete Icon -->
      <i v-else class="mdi mdi-trash-can"></i>
      Delete
    </div>
  </div>
</template>

<script>
export default {
  props: {
    post: {
      type: Object,
      required: true,
    },
    auth: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      saveLoading: false,
      deleteLoading: false,
    };
  },
  methods: {
    isSaved() {
      return this.post.saved === 1 ? true : false;
    },
    toggleSave() {
      if (this.isSaved()) {
        this.unSavePost();
      } else {
        this.savePost();
      }
    },
    async savePost() {
      try {
        this.saveLoading = true;

        const res = await axios.post(`/posts/${this.post.id}/save`);
        const { ok } = res.data;

        if (ok) {
          this.post.saved = 1;

          toast({
            message: 'Post saved.',
            type: 'is-success',
          });
        }
      } catch (err) {
        toast({
          message: 'Failed to save post.',
          type: 'is-danger',
        });
      } finally {
        this.saveLoading = false;
      }
    },
    async unSavePost() {
      try {
        this.saveLoading = true;

        const res = await axios.post(`/posts/${this.post.id}/unsave`);
        const { ok } = res.data;

        if (ok) {
          this.post.saved = 0;
          toast({
            message: 'Post unsaved.',
            type: 'is-success',
          });

          this.removePost('unsave');
        }
      } catch (err) {
        toast({
          message: 'Failed to unsaved post.',
          type: 'is-danger',
        });
      } finally {
        this.saveLoading = false;
      }
    },
    async deletePost() {
      try {
        this.deleteLoading = true;

        const res = await axios.delete(`/posts/${this.post.id}`);
        const { ok } = res.data;

        if (ok) {
          this.post.saved = 1;
          toast({
            message: 'Post deleted.',
            type: 'is-success',
          });

          this.removePost('delete');
        }
      } catch (err) {
        toast({
          message: 'Failed to delete post.',
          type: 'is-danger',
        });
      } finally {
        this.deleteLoading = false;
      }
    },
    removePost(context) {
      const event = new CustomEvent(`post-${context}`);
      event.postId = this.post.id;
      window.dispatchEvent(event);
    },
  },
};
</script>

<style lang="scss" scoped>
.loading-icon {
  font-size: 18px;
}

.dropdown-item {
  cursor: pointer;
}

.dropdown-item:hover {
  background: rgba(184, 180, 180, 0.3);
}
</style>
