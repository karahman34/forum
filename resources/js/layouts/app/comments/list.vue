<template>
  <div id="comment-app">
    <!-- Loading -->
    <div v-if="loading" id="comment-loading" class="has-text-centered">Getting comments data..</div>

    <!-- Comment Section -->
    <div v-else id="comment-section">
      <!-- Comment Filter -->
      <div class="comment-filter-container">
        <div class="level">
          <!-- Left -->
          <div class="level-left">
            <div class="level-item">
              <!-- Comment Total -->
              <span class="title is-4">
                {{ total }}
                Comments
              </span>
            </div>
          </div>

          <!-- Right -->
          <div class="level-right">
            <div class="level-item">
              <!-- Sort -->
              <div class="field">
                <div class="control has-icons-left">
                  <div class="select primary is-rounded">
                    <select v-model="selectedSort" @change="sortComments">
                      <option value="new" selected>New</option>
                      <option value="old">Old</option>
                    </select>
                  </div>
                  <div class="icon is-small is-left">
                    <i class="mdi mdi-sort icon-filter"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Comment List Container -->
      <div v-for="comment in comments" :key="comment.id" class="comment-container">
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

          <!-- Options Menu -->
          <span class="is-pulled-right">
            <menus
              v-if="user !== null && comment.user_id === user.id"
              :comment="comment"
              @deleted="deleteComment"
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

      <!-- Pagination -->
      <pagination
        :next="next_url"
        :prev="prev_url"
        :last-page="last_page"
        :current-page="current_page"
        @click="changePage"
      ></pagination>
    </div>
  </div>
</template>

<script>
import Menus from './menus.vue';
import Pagination from './pagination.vue';

export default {
  components: {
    Menus,
    Pagination,
  },
  props: {
    user: {
      type: Object,
      default: null,
    },
    url: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: true,
      comments: [],
      current_page: 1,
      last_page: null,
      prev_url: null,
      next_url: null,
      total: null,
      selectedSort: 'new',
    };
  },
  created() {
    this.bindCurrentFilter();
    this.getComments();
  },
  methods: {
    bindCurrentFilter() {
      const urlParams = new URLSearchParams(window.location.search);

      if (urlParams.has('sort')) {
        this.selectedSort = urlParams.get('sort');
      }
    },
    async getComments() {
      try {
        // Turn on loading
        this.loading = true;
        // Fetch comments
        const res = await axios.get(this.url, {
          params: {
            page: this.current_page,
            sort: this.selectedSort,
          },
        });
        // Fetch Result
        const { data, meta, links } = res.data;

        this.total = meta.total;
        this.comments = data;
        this.last_page = meta.last_page;
        this.current_page = meta.current_page;
        this.prev_url = links.prev === null ? false : true;
        this.next_url = links.next === null ? false : true;
      } catch (err) {
        throw Error(err);
      } finally {
        this.loading = false;
      }
    },
    sortComments() {
      this.getComments();
    },
    deleteComment(comment) {
      const commentIndex = this.comments.findIndex(c => c.id === comment.id);
      if (commentIndex !== -1) {
        this.comments.splice(commentIndex, 1);
        this.total -= 1;
      }
    },
    changePage(page) {
      this.current_page = page;
      this.getComments();
    },
  },
};
</script>
