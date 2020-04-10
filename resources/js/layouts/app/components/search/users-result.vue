<template>
  <div>
    <!-- Title -->
    <p class="title is-4">
      <span style="margin-right:1px;">Users</span>
      <span v-if="total !== null">- {{ total }} results</span>
    </p>

    <!-- Loading -->
    <div v-if="loading" class="has-text-centered">
      <p>Getting users..</p>
      <i class="mdi mdi-loading mdi-spin big-size"></i>
    </div>

    <template v-else>
      <!-- Empty Result -->
      <div
        v-if="!users.length"
        class="has-text-centered has-text-grey subtitle is-5"
        style="margin-top:15px;"
      >No result for users.</div>

      <template v-else>
        <!-- Columns -->
        <div class="columns is-multiline is-mobile">
          <div
            v-for="user in users"
            :key="user.id"
            class="column is-6-mobile is-4-tablet is-3-desktop"
          >
            <!-- Media -->
            <div class="box">
              <div class="media">
                <figure class="media-left">
                  <p class="image is-48x48">
                    <!-- Avatar -->
                    <img class="is-rounded" :src="user.avatar" :alt="user.avatar" />
                  </p>
                </figure>

                <div class="media-content">
                  <div class="content">
                    <!-- Username -->
                    <a
                      :href="`/users/${user.username}`"
                      class="is-block user-username has-text-grey-dark title is-5"
                    >{{ user.username }}</a>

                    <!-- Total Post -->
                    <span class="has-text-grey">{{ user.posts_count }} Posts</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div v-if="nextPageLink !== null" style="margin-bottom:60px;">
          <button
            class="button is-primary is-pulled-right"
            :class="{'is-loading': getMoreLoading}"
            @click="getMore"
          >Get More</button>
        </div>
      </template>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      loading: false,
      total: null,
      nextPageLink: null,
      limit: 8,
      getMoreLoading: false,
    };
  },
  created() {
    this.getUsers();
  },
  computed: {
    keyword() {
      const { search } = window.location;
      const urlParams = new URLSearchParams(search);

      return urlParams.get('q');
    },
    query() {
      return {
        q: this.keyword,
        limit: this.limit,
      };
    },
  },
  methods: {
    async getUsers() {
      try {
        // Turn on loading
        this.loading = true;
        // Call Api
        const res = await axios.get('/users', {
          params: this.query,
        });
        const { ok, data, links, meta } = res.data;

        if (ok) {
          this.users = data;
          this.total = meta.total;
          this.nextPageLink = links.next;
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Error: Failed to get users data.',
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
          this.users.push(...data);
          this.nextPageLink = links.next;
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Error: Failed to get users data.',
        });
      } finally {
        this.getMoreLoading = false;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.user-username {
  margin-bottom: 10px;
}

.box {
  padding: 12px 10px;
}
</style>