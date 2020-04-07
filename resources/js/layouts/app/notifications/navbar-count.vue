<template>
  <a id="navbar-item-notification" href="/notifications" class="navbar-item">
    <!-- PC -->
    <span id="pc">
      <i class="mdi mdi-bell-outline"></i>

      <!-- Loading -->
      <i v-if="notificationCounts === null" class="mdi mdi-loading mdi-spin"></i>
      <!-- Notif Count -->
      <span v-if="notificationCounts !== null && notificationCounts > 0">{{ notificationCounts }}</span>
    </span>
    <!-- Mobile -->
    <span id="mobile">
      <span>Notifications</span>
      <!-- Loading -->
      <i v-if="notificationCounts === null" class="mdi mdi-loading mdi-spin"></i>
      <!-- Notif Count -->
      <span
        v-if="notificationCounts !== null && notificationCounts > 0"
        id="notification-count"
      >{{ notificationCounts }}</span>
    </span>
  </a>
</template>

<script>
export default {
  props: {
    auth: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      notificationCounts: null,
    };
  },
  mounted() {
    // Get current notifications count.
    this.getNotificationCounts();

    // Listen for event
    window.addEventListener('notifications-count-reset', () => {
      this.notificationCounts = 0;
    });
  },
  methods: {
    listenNotifications() {
      Echo.private(`App.User.${this.auth.id}`).notification(notification => {
        this.notificationCounts += 1;
      });
    },
    async getNotificationCounts() {
      try {
        // Call API
        const res = await axios.get('/notifications/count');
        const { ok, data } = res.data;

        if (ok) {
          // Set notification count
          this.notificationCounts = data.count;

          // Listen for subsequent notifications
          this.listenNotifications();
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Failed to get notifications count.',
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
#navbar-item-notification {
  position: relative;

  #pc {
    i {
      font-size: 20px;
    }

    span {
      position: absolute;
      top: 15%;
      right: 16%;
      font-weight: 600;
      font-size: 12px;
      padding: 0px 5px;
      border-radius: 50%;
      color: white;
      background-color: red;
      text-shadow: 0px 1px rgb(172, 165, 165);
    }

    .mdi-loading {
      position: absolute;
      top: 2%;
      right: 5%;
      font-size: 16px;
    }
  }

  #mobile {
    #notification-count {
      font-size: 15px;
      font-weight: 600;
      padding: 0px 5px;
      color: white;
      background-color: red;
      border-radius: 50%;
    }

    .mdi-loading {
      margin-left: 3px;
    }
  }

  @media screen and (max-width: 1023px) {
    #pc {
      display: none;
    }

    #mobile {
      display: inline;
    }
  }

  @media screen and (min-width: 1024px) {
    #pc {
      display: inline;
    }

    #mobile {
      display: none;
    }
  }
}
</style>