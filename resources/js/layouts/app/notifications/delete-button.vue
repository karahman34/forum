<template>
  <div>
    <div v-if="loading">
      <i class="mdi mdi-loading mdi-spin"></i>
    </div>
    <button v-else class="delete" @click="deleteNotification"></button>
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
      loading: false,
    };
  },
  methods: {
    async deleteNotification() {
      try {
        this.loading = true;
        const res = await axios.delete(
          `/notifications/${this.notification.id}`
        );
        const { ok } = res.data;

        if (ok) {
          this.$emit('delete', this.notification);
          toast({
            type: 'is-success',
            message: 'Notification deleted.',
          });
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Failed to delete notification.',
        });
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style lang="scss" scoped></style>
