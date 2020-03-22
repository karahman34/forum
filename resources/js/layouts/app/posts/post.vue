<template>
  <div>
    <!-- Post Header -->
    <div id="post-header">
      <!-- Author Avatar -->
      <img id="post-author-avatar" :src="post.author.avatar" :alt="post.author.avatar" />
      <!-- Author -->
      <a
        :href="`/users/${post.author.username}`"
        id="post-author-username"
      >{{ post.author.username }}</a>
      <!-- Post Circle divider -->
      <span id="post-divider"></span>
      <!-- Post Created Time -->
      <span class="has-text-grey has-text-weight-medium">{{ post.created_at }}</span>

      <div class="is-pulled-right" style="padding-top: 2px;">
        <!-- Seen Time Count -->
        <span class="post-misc">
          <span class="mdi mdi-eye"></span>
          {{ post.seen.count ? post.seen.count : 0 }}
        </span>

        <!-- Comment Count -->
        <span class="post-misc">
          <span class="mdi mdi-comment-text-multiple"></span>
          {{ post.comments_count }}
        </span>

        <!-- Options -->
        <span class="is-pulled-right">
          <options :right="true">
            <post-menus :post="post" :auth="auth"></post-menus>
          </options>
        </span>
      </div>
    </div>

    <!-- Post Title -->
    <div id="post-title" class="is-capitalized">{{ post.title }}</div>

    <!-- Post Body -->
    <div id="post-body">{{ post.body }}</div>

    <!-- Attached Images -->
    <div v-if="post.images.length">
      <p class="title is-5" style="margin-bottom: 12px;">Attached images</p>
      <img
        v-for="(image,i) in post.images"
        :key="i"
        :src="image.src"
        :alt="image.src"
        class="post-image"
        @click="showImage = image.src"
      />
    </div>

    <!-- Image Modal -->
    <image-modal v-if="showImage !== null" :image="showImage" @close="showImage = null"></image-modal>
  </div>
</template>

<script>
import options from '../components/options.vue';
import ImageModal from '../components/imageModal.vue';

export default {
  components: {
    options,
    ImageModal,
  },
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
      showImage: null,
    };
  },
  mounted() {
    window.addEventListener('post-delete', ({ postId }) => {
      window.location.href = '/users/{{ $post->author->username }}';
    });
  },
};
</script>