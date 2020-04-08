<template>
  <a class="navbar-item">
    <!-- Search Icon -->
    <span style="cursor:pointer;" @click="openModal">
      <i class="mdi mdi-magnify"></i>
      <span class="is-hidden-desktop">Search</span>
    </span>

    <!-- Modal -->
    <div class="modal section" :class="{'is-active': modalActive}">
      <!-- Background -->
      <div class="modal-background" @click="closeModal"></div>
      <!-- Content -->
      <div class="modal-content">
        <div class="box">
          <form @submit.prevent="goSearch">
            <!-- Search Input -->
            <div class="field">
              <label for="search" class="label">
                <span>Search</span>
              </label>
              <p class="control has-icons-left">
                <input
                  id="search"
                  type="text"
                  class="input"
                  v-model="searchInput"
                  placeholder="Search for peoples,posts"
                />
                <span class="icon is-small is-left">
                  <i class="mdi mdi-magnify"></i>
                </span>
              </p>
            </div>
          </form>
        </div>
      </div>
      <!-- Close Modal -->
      <button class="modal-close is-large" aria-label="close" @click="closeModal"></button>
    </div>
  </a>
</template>

<script>
export default {
  data() {
    return {
      modalActive: false,
      searchInput: '',
    };
  },
  methods: {
    openModal() {
      this.modalActive = true;
    },
    closeModal() {
      this.modalActive = false;
    },
    goSearch() {
      window.location.href = `/search?q=${this.searchInput}`;
    },
  },
};
</script>

<style lang="scss" scoped>
#search-submit {
  z-index: 99999;
  cursor: pointer !important;
}

.mdi-magnify {
  font-size: 20px;
  cursor: pointer;
}
</style>