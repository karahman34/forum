<template>
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-half">
        <div class="card">
          <div class="card-content">
            <!-- Card Title -->
            <p class="card-title">Edit Comment</p>

            <!-- Notification -->
            <div
              v-if="notif"
              class="notification has-text-weight-semibold"
              :class="[notifError ? 'is-danger' : 'is-success']"
              style="padding:14px !important;"
            >{{ notif }}</div>

            <!-- Form -->
            <form method="POST" @submit.prevent="updateCommentHandler">
              <!-- Body Field -->
              <div class="field">
                <label for="body" class="label">Body</label>
                <textarea
                  name="body"
                  id="body"
                  cols="30"
                  rows="3"
                  class="textarea"
                  :class="{'is-danger': errors.body !== null}"
                  placeholder="Comment Body.."
                  v-model="body"
                ></textarea>
                <span v-if="errors.body !== null" class="help is-danger">{{ errors.body }}</span>
              </div>

              <!-- File Field -->
              <div class="file" :class="{'image-mb': !errors.images}" style="margin-top:8px;">
                <label class="file-label">
                  <input
                    ref="imageInput"
                    multiple
                    type="file"
                    name="images"
                    class="file-input"
                    :class="{'is-danger': errors.images}"
                    @change="onImageChange"
                  />
                  <span class="file-cta">
                    <span class="file-icon">
                      <i class="mdi mdi-upload mdi-24px"></i>
                    </span>
                    <span class="file-label">Choose a images..</span>
                  </span>
                </label>
              </div>
              <!-- File Errors -->
              <span v-if="errors.images" class="help is-danger" v-text="errors.images"></span>

              <!-- Image Preview -->
              <images-preview :images="selectedImages" @delete="deleteImage"></images-preview>

              <div style="height:40px;">
                <!-- Submit button -->
                <button
                  class="button is-primary is-pulled-right"
                  :class="{'is-loading': loading}"
                  :disabled="loading"
                  :style="selectedImages.length ? '': 'margin-top:10px;'"
                  @click="updateCommentHandler"
                >Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ImagesPreview from '../imagesPreview.vue';

export default {
  components: {
    ImagesPreview,
  },
  props: {
    comment: {
      type: Object,
      required: true,
    },
    action: {
      type: String,
      required: true,
    },
    method: {
      type: String,
      required: true,
    },
    csrf: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      notif: null,
      notifError: false,
      body: null,
      errors: {
        body: null,
        images: null,
      },
      loading: false,
      selectedImages: [],
    };
  },
  created() {
    this.initializeForm();
  },
  methods: {
    initializeForm() {
      // Set Body
      this.body = this.comment.body;

      // Set old images
      this.comment.images.map(image => {
        this.selectedImages.push({
          file: null,
          active: false,
          src: '/storage/' + image.url,
          originalSrc: image.url,
        });
      });
    },
    onImageChange(e) {
      const files = e.target.files;
      for (const file of files) {
        this.selectedImages.push({
          file,
          active: false,
          src: window.URL.createObjectURL(file),
        });
      }

      this.$refs.imageInput.value = null;
    },
    deleteImage(image) {
      this.selectedImages.splice(this.selectedImages.indexOf(image), 1);
    },
    async updateCommentHandler() {
      // Reset notif
      this.notif = null;
      this.notifError = false;

      try {
        // Turn on loading
        this.loading = true;
        // Set formdata
        const formData = new FormData();
        formData.append('body', this.body);
        formData.append('_csrf', this.csrf);
        formData.append('_method', this.method);
        // Set images & old images
        for (const image of this.selectedImages) {
          if (image.file === null) {
            formData.append('old_images[]', image.originalSrc);
          } else {
            formData.append('images[]', image.file);
          }
        }

        // Send Data
        const res = await axios.post(this.action, formData);
        const { ok } = res.data;

        if (ok) {
          this.notif = 'Success to update comment.';
        }
      } catch (err) {
        const errCode = err.response && err.response.status;

        if (errCode === 422) {
          validateForm(err.response.data.errors, this.errors);
        } else {
          this.notif = 'Error: Failed to update comment.';
          this.notifError = true;
          throw Error(err);
        }
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>