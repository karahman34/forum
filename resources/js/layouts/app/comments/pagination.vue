<template>
  <nav class="pagination comment-pagination" role="navigation" aria-label="pagination">
    <!-- Previos -->
    <a
      class="pagination-previous"
      :disabled="prev === false"
      @click="changePage(currentPage - 1)"
    >Previous</a>
    <!-- Next -->
    <a
      class="pagination-next"
      :disabled="next === false"
      @click="changePage(currentPage + 1)"
    >Next page</a>
    <ul class="pagination-list">
      <!-- First Page -->
      <template v-if="currentPage !== 1">
        <!-- The Page -->
        <li>
          <a class="pagination-link" aria-label="Goto page 1" @click="changePage(1)">1</a>
        </li>
        <!-- The Ellipsis -->
        <li>
          <span class="pagination-ellipsis">&hellip;</span>
        </li>
      </template>
      <!-- Following page -->
      <li v-for="page in lastPage" :key="page">
        <a
          class="pagination-link"
          :class="{ 'is-current is-primary': page === currentPage }"
          :aria-label="`Goto page ${page}`"
          @click="changePage(page)"
        >{{ page }}</a>
      </li>
      <!-- Last Page -->
      <template v-if="currentPage !== lastPage" class="is-marginless is-paddingless">
        <!-- The Ellipsis -->
        <li>
          <span class="pagination-ellipsis">&hellip;</span>
        </li>
        <!-- The Page -->
        <li>
          <a
            class="pagination-link"
            :aria-label="`Goto page ${lastPage}`"
            @click="changePage(lastPage)"
          >{{ lastPage }}</a>
        </li>
      </template>
    </ul>
  </nav>
</template>

<script>
export default {
  props: {
    currentPage: {
      type: Number,
      required: true,
    },
    lastPage: {
      type: Number,
      required: true,
    },
    prev: {
      type: Boolean,
      required: true,
    },
    next: {
      type: Boolean,
      required: true,
    },
  },
  methods: {
    changePage(page) {
      this.$emit('click', page);
    },
  },
};
</script>

<style scoped>
.is-primary {
  background: hsl(171, 100%, 41%) !important;
  border-color: hsl(171, 100%, 41%) !important;
}
</style>
