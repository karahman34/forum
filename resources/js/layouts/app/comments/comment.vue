<template>
  <div class="comment-container">
    <!-- Comment Header -->
    <div class="comment-header">
      <!-- User Avatar -->
      <img :src="comment.user.avatar" :alt="comment.user.avatar" class="comment-user-avatar" />

      <!-- Username -->
      <a
        :href="`/users/${comment.user.username}`"
        class="comment-username"
      >{{ comment.user.username }}</a>

      <!-- Divider -->
      <span class="comment-header-divider"></span>

      <!-- Created Time -->
      <span class="comment-created-time">{{ comment.created_at }}</span>

      <span class="is-pulled-right">
        <!-- Pin Comment Button -->
        <button
          v-if="canPin"
          class="button is-rounded is-primary pinned-button"
          :class="[{'unpinned': comment.pinned === 'n'}, {'is-loading': pinLoading}]"
          @click="togglePin(comment)"
        >
          <i class="mdi mdi-pin"></i>
          <span>Pinned</span>
        </button>

        <!-- Pinned Comment -->
        <div v-else>
          <button v-if="comment.pinned === 'y'" class="button is-rounded is-primary pinned-button">
            <i class="mdi mdi-pin"></i>
            <span>Pinned</span>
          </button>
        </div>

        <!-- Options Menu -->
        <menus
          v-if="auth !== null && comment.user_id === auth.id"
          :comment="comment"
          @deleted="(comment) => $emit('delete', comment)"
        ></menus>
      </span>
    </div>

    <!-- Comment Body -->
    <div class="comment-body">{{ comment.body }}</div>

    <!-- Attached Images -->
    <img
      v-for="(image, i) in comment.images"
      :key="i"
      :src="image.src"
      :alt="image.src"
      class="comment-image"
    />
  </div>
</template>

<script>
import Menus from './menus.vue';

export default {
  components: {
    Menus,
  },
  props: {
    comment: {
      type: Object,
      required: true,
    },
    canPin: {
      type: Boolean,
      required: true,
    },
    auth: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      pinLoading: false,
    };
  },
  methods: {
    togglePin(comment) {
      comment.pinned === 'y'
        ? this.unpinComment(comment.id)
        : this.pinComment(comment.id);
    },
    pinComment(commentId) {
      // Turn on loading
      this.pinLoading = true;

      // Fetch API
      axios
        .post(`/comments/${commentId}/pin`, {})
        .then(({ data }) => {
          if (data.ok) {
            this.updatePin('y');

            toast({
              message: 'Comment pinned.',
              type: 'is-success',
            });
          }
        })
        .catch(err => {
          toast({
            message: 'Failed to pin comment.',
            type: 'is-danger',
          });
        })
        .finally(() => (this.pinLoading = false));
    },
    unpinComment(commentId) {
      // Turn on loading
      this.pinLoading = true;

      // Fetch API
      axios
        .post(`/comments/${commentId}/unpin`, {})
        .then(({ data }) => {
          if (data.ok) {
            this.updatePin('n');

            toast({
              message: 'Comment unpinned.',
              type: 'is-success',
            });
          }
        })
        .catch(err => {
          toast({
            message: 'Failed to unpin comment.',
            type: 'is-danger',
          });
        })
        .finally(() => (this.pinLoading = false));
    },
    updatePin(val) {
      this.$emit('pin-update', {
        val,
        commentId: this.comment.id,
      });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>