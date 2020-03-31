<template>
  <div class="is-inline">
    <a class="navbar-item" @click="logout">Logout</a>
  </div>
</template>

<script>
export default {
  methods: {
    async logout() {
      try {
        // Call
        const res = await axios.post('/logout', {
          _token: document
            .querySelector('meta[name=csrf-token]')
            .getAttribute('content'),
        });

        if (res.status === 204) {
          window.location.href = '/login';
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Failed to logout.',
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
</style>