<template>
  <div id="root-div">
    <!-- Header Text -->
    <p class="title is-5">Write Comment</p>

    <article class="media comment-container">
      <!-- User Avatar -->
      <figure class="media-left">
        <p class="image is-48x48">
          <img class="is-rounded" :src="avatar" :alt="avatar" />
        </p>
      </figure>

      <!-- Media Content -->
      <div class="media-content">
        <!-- Input -->
        <div class="field">
          <div class="control">
            <textarea
              v-model="body"
              class="textarea"
              :class="{'is-danger': formError}"
              placeholder="Write Comment.."
            ></textarea>
            <span v-if="formError" class="help is-danger">{{ formError }}</span>
          </div>
        </div>

        <!-- Level -->
        <div class="level is-mobile">
          <!-- Left -->
          <div class="level-left">
            <div class="level-item">
              <!-- Upload Image Icon -->
              <i ref="imageInput" class="mdi mdi-camera upload-icon" @click="openInputImage"></i>
              <!-- Hidden input field -->
              <input
                ref="imageInput"
                class="is-hidden"
                type="file"
                multiple
                @change="fileChangeHandler"
              />
            </div>
          </div>
          <!-- Right -->
          <div class="level-right">
            <div class="level-item">
              <!-- Submit button -->
              <button
                class="button is-primary is-pulled-right"
                :class="{'is-loading': loading }"
                @click="createCommentHandler"
              >Submit</button>
            </div>
          </div>
        </div>

        <!-- Thumbnail images -->
        <image-preview :images="selectedImages" @delete="deleteImage" style="margin-top:10px;"></image-preview>
      </div>
    </article>
  </div>
</template>

<script>
import ImagePreview from '../imagesPreview.vue';

export default {
  components: {
    ImagePreview,
  },
  props: {
    avatar: {
      type: String,
      required: true,
    },
    url: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      body: null,
      formError: null,
      selectedImages: [],
    };
  },
  methods: {
    async createCommentHandler() {
      try {
        // Turn on loadidng
        this.loading = true;
        // Set payload
        const formData = new FormData();
        if (this.body !== null) formData.append('body', this.body);
        // Set for images
        for (const image of this.selectedImages) {
          formData.append('images[]', image.file);
        }
        // Fetch Api
        const res = await axios.post(this.url, formData);
        // Result Api
        const { ok } = res.data;

        if (ok) {
          const query = ['sort=new'];
          const { origin, pathname } = window.location;

          window.location.href = `${origin}${pathname}?${query}`;
        }
      } catch (err) {
        const errCode = err.response && err.response.status;

        if (errCode === 422) {
          this.formError = err.response.data.errors.body[0];
        } else {
          throw Error(err);
          this.formError = err;
        }
      } finally {
        this.loading = false;
      }
    },
    openInputImage() {
      this.$refs.imageInput.click();
    },
    fileChangeHandler(e) {
      for (const file of e.target.files) {
        this.selectedImages.push({
          file: file,
          active: false,
          src: window.URL.createObjectURL(file),
        });
      }

      this.$refs.imageInput.value = null;
    },
    deleteImage(image) {
      this.selectedImages.splice(this.selectedImages.indexOf(image), 1);
    },
  },
};
</script>

<style lang="scss" scoped>
#root-div {
  padding-top: 10px;
  border-top: 1px solid rgba(128, 128, 128, 0.5);

  .title {
    margin-bottom: 6px !important;
  }

  .upload-icon {
    cursor: pointer;
    font-size: 22px !important;
  }

  .comment-container {
    border: none;
    margin-top: 5px;
  }
}
</style>
