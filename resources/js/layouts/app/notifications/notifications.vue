<template>
  <div class="columns">
    <div class="column is-8 is-offset-2">
      <!-- Box -->
      <div class="box no-padding">
        <!-- Tabs -->
        <div class="tabs is-centered">
          <ul>
            <li
              v-for="navigation in navigations"
              :key="navigation.text"
              :class="[{ 'is-active': navigation.active }]"
              @click="notifSelected(navigation)"
            >
              <a>{{ navigation.text }}</a>
            </li>
          </ul>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="has-text-centered loading-content">
          <p>Getting notifications..</p>
          <i class="mdi mdi-loading mdi-spin"></i>
        </div>

        <!-- Content -->
        <div v-else class="notification-content">
          <div
            v-if="!notifications.length"
            class="has-text-centered"
            style="padding: 8px;"
          >
            <span>You have no notification.</span>
          </div>

          <!-- Media -->
          <div
            v-else
            v-for="notification in notifications"
            :key="notification.id"
            class="media notifications"
          >
            <!-- Media Left -->
            <figure class="media-left">
              <p class="image is-48x48">
                <img
                  class="is-rounded"
                  :src="notification.data.user_emitter.avatar"
                  alt="notification.data.user_emitter.avatar"
                />
              </p>
            </figure>

            <!-- Media Content -->
            <div class="media-content">
              <div class="content">
                <!-- Notif Message -->
                <a
                  :href="
                    `${notification.data.href}?from=notif&notif_id=${notification.id}`
                  "
                  class="notification-message has-text-grey-darker"
                  >{{ notification.data.message }}</a
                >

                <!-- Notif Footer -->
                <nav class="level is-mobile">
                  <!-- Left -->
                  <div class="level-left">
                    <!-- Notif Unread Mark -->
                    <div class="level-item">
                      <div
                        class="unread-mark"
                        :class="[{ show: notification.read_at === null }]"
                      ></div>
                    </div>

                    <!-- Notif Time -->
                    <div class="level-item dense has-text-grey">
                      <span v-text="notification.created_at"></span>
                    </div>
                  </div>
                </nav>
              </div>
            </div>

            <!-- Media Right -->
            <div class="media-right">
              <delete-button
                :notification="notification"
                @delete="deleteNotification"
              ></delete-button>
            </div>
          </div>

          <!-- Footer -->
          <div
            class="has-text-grey has-text-centered"
            style="padding: 10px;cursor:pointer;"
            :class="{ 'is-hidden': nextPage === null }"
          >
            <span v-if="!getMoreLoading" @click="getMoreNotifications"
              >See more notifications</span
            >
            <div v-else>
              <span>Getting more notification..</span>
              <i class="mdi mdi-loading mdi-spin" style="margin-left:2px;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DeleteButton from './delete-button.vue';

export default {
  components: {
    DeleteButton,
  },
  data() {
    return {
      navigations: [
        {
          text: 'All',
          value: 'all',
          active: true,
        },
        {
          text: 'Read',
          value: 'read',
          active: false,
        },
        {
          text: 'Unread',
          value: 'unread',
          active: false,
        },
      ],
      nextPage: null,
      notifications: null,
      loading: false,
      getMoreLoading: false,
    };
  },
  created() {
    this.getNotifications({}).then(res => {
      if (res) this.resetNotificationCount();
    });
  },
  methods: {
    async resetNotificationCount() {
      axios
        .post('/notifications/count/reset')
        .then(() => {
          const event = new CustomEvent('notifications-count-reset');
          window.dispatchEvent(event);
        })
        .catch(() => {});
    },
    async getNotifications(query) {
      try {
        // Set empty current notifs
        this.notifications = null;

        // Turn on loading
        this.loading = true;

        // Call API
        const res = await axios.get('/notifications/data', {
          params: query,
        });
        const { ok, data, links } = res.data;

        if (ok) {
          // Set notif
          this.notifications = data;

          this.nextPage = links.next;

          return true;
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Error: Failed to get notifications data.',
        });
      } finally {
        this.loading = false;
      }
    },
    async getMoreNotifications() {
      try {
        // Turn on loading
        this.getMoreLoading = true;

        // Call API
        const res = await axios.get(this.nextPage);
        const { ok, data, links } = res.data;

        if (ok) {
          // Push new notif
          this.notifications.push(...data);

          this.nextPage = links.next;
        }
      } catch (err) {
        toast({
          type: 'is-danger',
          message: 'Error: Failed to get notifications data.',
        });
      } finally {
        this.getMoreLoading = false;
      }
    },
    notifSelected(e) {
      // Remove old active navigation
      for (const navigation of this.navigations) {
        navigation.active = false;
      }

      // Set Active
      const activeNavigation = this.navigations.find(
        nav => nav.value === e.value
      );
      activeNavigation.active = true;

      // Get new notifications
      this.getNotifications({
        tab: activeNavigation.value,
      });
    },
    deleteNotification(notif) {
      this.notifications.splice(this.notifications.indexOf(notif), 1);
    },
  },
};
</script>

<style lang="scss" scoped>
.box.no-padding {
  padding: 0px !important;
}

.level-item.dense {
  margin-right: 4px !important;
}

.tabs {
  margin-bottom: 0px;
}

.loading-content {
  padding: 15px 0px;

  .mdi-spin {
    font-size: 25px;
  }
}

.notifications {
  margin-top: 0px;
  padding: 12px 12px;

  .icon {
    margin-right: 1px;
  }

  button.delete {
    margin-top: 2px;
  }
}

.notifications {
  .unread-mark {
    display: none;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #00d1b2;
  }

  .unread-mark.show {
    display: block;
  }
}
</style>
