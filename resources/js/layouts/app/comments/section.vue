<template>
  <div id="comment-app">
    <!-- Create Comment -->
    <comment-create
      v-if="auth !== null"
      :avatar="auth.avatar"
      :post-id="postId"
      @create="commentCreatedHandler"
    ></comment-create>

    <!-- Loading -->
    <div v-if="loading" id="comment-loading" class="has-text-centered">
      Getting comments data..
    </div>

    <!-- Comment Section -->
    <div v-else id="comment-section">
      <!-- Top -->
      <div class="comment-filter-container">
        <div class="level is-mobile">
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
              <!-- Comment Filter -->
              <comment-filter
                :selected-sort.sync="selectedSort"
                @filter="sortComments"
              ></comment-filter>
            </div>
          </div>
        </div>
      </div>

      <!-- Comment List Container -->
      <comment
        v-for="comment in comments"
        :key="comment.id"
        :id="`comment-${comment.id}`"
        :auth="auth"
        class="comment"
        :class="[{ focus: focusFromNotif === comment.id }]"
        :comment="comment"
        :can-pin="postAuthor === 'y'"
        @delete="deleteComment"
        @pin-update="updatePinComment"
      ></comment>

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
import Comment from './comment.vue';
import Create from './create.vue';
import Pagination from './pagination.vue';
import VueScrollTo from 'vue-scrollto';

export default {
  components: {
    Comment,
    Pagination,
    'comment-create': Create,
  },
  props: {
    postId: {
      type: [String, Number],
      required: true,
    },
    auth: {
      type: Object,
      default: null,
    },
    postAuthor: {
      type: String,
      default: 'n',
    },
  },
  data() {
    return {
      loading: true,
      comments: [],
      focusFromNotif: null,
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
    async seeCommentFromNotif(notif_id) {
      try {
        const { search } = window.location;
        const urlParams = new URLSearchParams(search);

        const from = urlParams.get('from');
        const notif_id = urlParams.get('notif_id');

        if (from && from === 'notif' && notif_id) {
          // Get notif
          const notif = await findNotif(notif_id);
          // Get comment
          const res = await axios.get(`/comments/${notif.data.action_id}`);
          const { ok, data } = res.data;
          if (ok) {
            // Set focus comment
            this.focusFromNotif = data.id;
            // Push comment if not exist
            if (!this.comments.find(c => c.id === data.id)) {
              this.comments.push(data);
            }
            // Scroll to comment el
            VueScrollTo.scrollTo(`#comment-${data.id}`, {
              offset: -110,
            });
            // Remove focus after 1 second
            setTimeout(() => {
              this.focusFromNotif = null;
            }, 1500);
          }
        }
      } catch (err) {
        return false;
      }
    },
    async getComments() {
      try {
        // Turn on loading
        this.loading = true;
        // Fetch comments
        const res = await axios.get(`/posts/${this.postId}/comments`, {
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

        this.seeCommentFromNotif();
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Failed to get comments data.',
        });
      } finally {
        this.loading = false;
      }
    },
    commentCreatedHandler(comment) {
      this.comments.unshift(comment);
      this.total += 1;
    },
    updatePinComment({ commentId, val }) {
      const comment = this.comments.find(comment => comment.id === commentId);
      comment.pinned = val.toLowerCase();
    },
    sortComments(val) {
      this.selectedSort = val;
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
