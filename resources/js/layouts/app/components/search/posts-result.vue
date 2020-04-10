<template>
  <div>
    <!-- Title -->
    <p class="title is-4">
      <span style="margin-right:1px;">Posts</span>
      <span v-if="total !== null">- {{ total }} results</span>
    </p>

    <!-- Loading -->
    <div v-if="loading" class="has-text-centered">
      <p>Getting posts..</p>
      <i class="mdi mdi-loading mdi-spin big-size"></i>
    </div>

    <template v-else>
      <!-- Empty Result -->
      <div
        v-if="!posts.length"
        class="has-text-centered has-text-grey subtitle is-5"
        style="margin-top:15px;"
      >No result for posts.</div>

      <!-- Content -->
      <template v-else>
        <!-- Columns -->
        <div class="columns is-multiline">
          <div v-for="post in posts" :key="post.id" class="column is-half">
            <!-- Post Card -->
            <post-card :post="post" :auth="auth"></post-card>
          </div>
        </div>

        <!-- Get More Button -->
        <button
          v-if="nextPageLink !== null"
          class="button is-primary is-pulled-right"
          :class="{'is-loading': getMoreLoading}"
          @click="getMore"
        >Get more</button>
      </template>
    </template>
  </div>
</template>

<script>
import PostCard from '../../posts/postCard.vue';

export default {
  components: {
    PostCard,
  },
  props: {
    auth: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      posts: [],
      loading: false,
      total: null,
      nextPageLink: null,
      limit: 6,
      getMoreLoading: false,
    };
  },
  computed: {
    query() {
      const { search } = window.location;
      const urlParams = new URLSearchParams(search);

      return {
        limit: this.limit,
        q: urlParams.get('q'),
      };
    },
  },
  created() {
    this.getPosts();
  },
  methods: {
    async getPosts() {
      try {
        // Turn on loading
        this.loading = true;

        // Call Api
        const res = await axios.get('/posts', {
          params: this.query,
        });
        const { ok, data, links, meta } = res.data;

        if (ok) {
          this.posts = data;
          this.total = meta.total;
          this.nextPageLink = links.next;
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Failed to get posts data.',
        });
      } finally {
        this.loading = false;
      }
    },
    async getMore() {
      try {
        // Turn on loading
        this.getMoreLoading = true;

        // Call Api
        const res = await axios.get(this.nextPageLink, {
          params: this.query,
        });
        const { ok, data, links } = res.data;

        if (ok) {
          this.posts.push(...data);
          this.nextPageLink = links.next;
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Failed to get posts data.',
        });
      } finally {
        this.getMoreLoading = false;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
</style>