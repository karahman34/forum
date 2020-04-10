<template>
  <div class="card post-card">
    <div class="card-content">
      <div class="post-card-header">
        <!-- User Image -->
        <img :src="post.author.avatar" :alt="post.author.avatar" class="post-card-avatar" />

        <!-- Username -->
        <a
          :href="`/users/${post.author.username}`"
          class="post-card-username"
        >{{ post.author.username }}</a>

        <!-- Dot -->
        <span class="post-card-username-image-divider"></span>

        <!-- Created Time  -->
        <span>{{ post.created_at }}</span>
      </div>

      <!-- Title -->
      <a :href="`/posts/${post.id}`" class="post-card-title">{{ post.title }}</a>

      <!-- Tags -->
      <span v-for="tag in post.tags" :key="tag" class="tag is-medium post-card-tag">{{ tag }}</span>

      <!-- Post Misc -->
      <div class="post-card-misc-container">
        <!-- Seen Count -->
        <span class="post-card-misc">
          <i class="mdi mdi-eye"></i>
          <span>{{ post.seens_count ? post.seens_count : 0 }}</span>
        </span>

        <!-- Comments Count -->
        <span class="post-card-misc">
          <i class="mdi mdi-comment-text-multiple"></i>
          <span>{{ post.comments_count ? post.comments_count : 0 }}</span>
        </span>

        <!-- Options -->
        <span class="is-pulled-right">
          <options :right="true">
            <post-menus :post="post" :auth="auth"></post-menus>
          </options>
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import Options from '../components/options.vue';

export default {
  props: {
    auth: {
      type: Object,
      default: null,
    },
    post: {
      type: Object,
      required: true,
    },
  },
  components: {
    Options,
  },
};
</script>

<style lang="scss" scoped>
</style>