<template>
  <div id="notification-menus">
    <!-- Mark Read -->
    <div v-if="notification.read_at === null" class="dropdown-item" @click="markRead">
      <i v-if="markLoading" class="mdi mdi-loading mdi-spin"></i>
      <i v-else class="mdi mdi-eye"></i>
      <span>Mark as read</span>
    </div>

    <!-- Unmark Read -->
    <div v-else class="dropdown-item" @click="markUnRead">
      <i v-if="markLoading" class="mdi mdi-loading mdi-spin"></i>
      <i v-else class="mdi mdi-eye-off"></i>
      <span>Mark as unread</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    notification: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      markLoading: false,
    };
  },
  methods: {
    async markRead() {
      try {
        this.markLoading = true;
        // Call Api
        const res = await axios.post(
          `/notifications/${this.notification.id}/mark-read`
        );
        const { ok, data } = res.data;

        if (ok) {
          this.updateReadMark(true);

          toast({
            type: 'is-success',
            message: 'Change saved.',
          });
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Failed to update notification.',
        });
      } finally {
        this.markLoading = false;
      }
    },
    async markUnRead() {
      try {
        this.markLoading = true;
        // Call Api
        const res = await axios.post(
          `/notifications/${this.notification.id}/mark-unread`
        );
        const { ok, data } = res.data;

        if (ok) {
          this.updateReadMark(null);

          toast({
            type: 'is-success',
            message: 'Change saved.',
          });
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Failed to update notification.',
        });
      } finally {
        this.markLoading = false;
      }
    },
    updateReadMark(val) {
      this.$emit('markRead', val);
    },
  },
};
</script>

<style lang="scss" scoped>
#notification-menus {
  width: 100% !important;
}

.dropdown-item {
  cursor: pointer;
}
</style>
