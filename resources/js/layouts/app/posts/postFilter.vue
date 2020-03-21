<template>
  <nav class="level">
    <!-- Left -->
    <div class="level-left">
      <div class="level-item">
        <!-- Select Sort -->
        <div class="field sort-input">
          <div class="control has-icons-left">
            <div class="select primary is-rounded">
              <select v-model="selectedSort" @change="reloadWithQS">
                <option
                  v-for="sort in sortOptions"
                  class="sort-options is-capitalized"
                  :key="sort.value"
                  :value="sort.value"
                >{{ sort.text }}</option>
              </select>
            </div>
            <div class="icon is-small is-left">
              <i class="mdi mdi-sort icon-filter"></i>
            </div>
          </div>
        </div>

        <!-- The Activator -->
        <button class="button is-rounded" @click="toggleFilterTagsModal(true)">
          <i class="mdi mdi-tag-text-outline"></i>
          <span style="margin-left: 6px;">Tags</span>
        </button>
        <!-- The Modal -->
        <div ref="filterTagsModal" class="modal">
          <div class="modal-background" @click="toggleFilterTagsModal(false)"></div>
          <div class="modal-content">
            <!-- The Card -->
            <div class="card">
              <div class="card-content">
                <p class="card-title">Filter Tags</p>

                <!-- Search Tags -->
                <div class="field">
                  <div class="control has-icons-left">
                    <input
                      type="text"
                      class="input"
                      placeholder="Search tags.."
                      @keyup="searchTags"
                    />
                    <span class="icon is-small is-left">
                      <i class="mdi mdi-magnify icon-filter"></i>
                    </span>
                  </div>
                </div>

                <!-- Loading -->
                <div v-if="searchTagsLoading" class="has-text-centered">
                  <i class="mdi mdi-loading mdi-spin"></i>
                </div>
                <div v-else>
                  <!-- Tags List -->
                  <span v-if="tags.length" class="tags" style="margin-bottom:5px;">
                    <span
                      v-for="(tag, i) in tags"
                      :key="i"
                      class="tag is-medium"
                      :class="[!tagSelected(tag) ? 'is-light' : 'is-primary']"
                      style="cursor:pointer;"
                    >
                      <span @click="addTag(tag)">{{ tag.name }}</span>
                      <button v-if="tagSelected(tag)" class="delete" @click="removeTag(tag)"></button>
                    </span>
                  </span>

                  <!-- No Tags Result -->
                  <div
                    v-else
                    class="has-text-centered has-text-grey"
                    style="margin-top: 10px;"
                  >No Result.</div>
                </div>

                <!-- Apply Query -->
                <div class="has-text-right">
                  <button class="button is-primary" @click="reloadWithQS">Apply</button>
                </div>
              </div>
            </div>
          </div>
          <button
            class="modal-close is-large"
            aria-label="close"
            @click="toggleFilterTagsModal(false)"
          ></button>
        </div>
      </div>
    </div>

    <!-- Right -->
    <div class="level-right">
      <div class="level-item">
        <!-- Search Field -->
        <div class="field">
          <div class="control has-icons-left">
            <input
              v-model="search"
              class="input is-rounded"
              type="text"
              name="q"
              placeholder="Search.."
              @keydown.enter="reloadWithQS"
            />
            <span class="icon is-small is-left">
              <i class="mdi mdi-magnify icon-filter"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      popular: null,
      searchTagsLoading: false,
      tags: [],
      selectedTags: [],
      search: null,
      selectedSort: 'new',
      sortOptions: [
        {
          text: 'New',
          value: 'new',
        },
        {
          text: 'Old',
          value: 'old',
        },
      ],
    };
  },
  created() {
    // Bind current query string
    this.bindCurrentQS();

    // Get Food Tags
    this.getTags();
  },
  methods: {
    async getTags(params = null) {
      try {
        // Fetch Api
        const res = await axios.get('/tags', { params });
        const tags = res.data;

        this.tags = tags;
      } catch (err) {
        throw Error(err);
      }
    },
    searchTags(event) {
      this.searchTagsLoading = true;
      const q = event.target.value;
      this.getTags({ q }).then(() => (this.searchTagsLoading = false));
    },
    addTag(tag) {
      if (!this.selectedTags.includes(tag.name)) {
        this.selectedTags.push(tag.name);
      }
    },
    removeTag(tag) {
      this.selectedTags.splice(this.selectedTags.indexOf(tag.name), 1);
    },
    tagSelected(tag) {
      return this.selectedTags.includes(tag.name);
    },
    bindCurrentQS() {
      const urlParams = new URLSearchParams(window.location.search);

      if (urlParams.has('q')) {
        this.search = urlParams.get('q');
      }

      if (urlParams.has('sort')) {
        this.selectedSort = urlParams.get('sort');
      }

      if (urlParams.has('tags')) {
        this.selectedTags = urlParams.get('tags').split(',');
      }

      if (urlParams.has('popular')) {
        this.popular = urlParams.get('popular');
      }
    },
    reloadWithQS() {
      // Set QS
      const queryString = [
        ['q', this.search],
        ['sort', this.selectedSort],
        ['tags', this.selectedTags.join(',')],
        ['popular', this.popular],
      ];

      const finalQS = queryString
        .reduce((qs, cv) => {
          const [key, val] = cv;

          if (val) {
            qs.push(cv.join('='));
          }

          return qs;
        }, [])
        .join('&');

      // Get Origin
      const origin = window.location.origin;

      // Reload page with new QS
      window.location.href = `${origin}?${finalQS}`;
    },
    toggleFilterTagsModal(val) {
      const filterModal = this.$refs.filterTagsModal;

      if (val) {
        filterModal.classList.add('is-active');
      } else {
        filterModal.classList.remove('is-active');
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.level {
  margin-bottom: 0px !important;
}

@media screen and (max-width: 769px) {
  .level {
    margin-bottom: 11px !important;
  }

  .level-right {
    margin-top: 0px !important;
  }
}

.icon-filter {
  font-size: 22px;
}

.sort-input {
  margin-top: 12px;
  margin-right: 6px;
}
</style>
