<template>
  <div class="dropdown is-hoverable" :class="{'is-right': right}">
    <!-- The Dots Trigger -->
    <div class="dropdown-trigger">
      <span class="options-dropdown-trigger"></span>
      <span class="options-dropdown-trigger"></span>
      <span class="options-dropdown-trigger"></span>
    </div>

    <!-- Dropdown Structure -->
    <div class="dropdown-menu">
      <div class="dropdown-content options-dropdown-content" role="menu">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    right: {
      type: Boolean,
      default: false,
    },
  },
};
</script>