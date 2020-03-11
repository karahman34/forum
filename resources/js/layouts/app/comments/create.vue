<template>
  <div id="root-div">
    <!-- Header Text -->
    <p class="title is-5">Write Comment</p>

    <div class="comment-form-container">
      <!-- Avatar -->
      <img :src="avatar" :alt="avatar" class="comment-form-avatar" />

      <span id="comment-form-content">
        <!-- Body Field -->
        <span class="field is-inline-block">
          <textarea
            v-model="body"
            class="textarea"
            :class="{'is-danger': formError !== null}"
            name="body"
            cols="67"
            rows="3"
            placeholder="Write Comment.."
            style="width:100% !important;"
          ></textarea>
          <span v-if="formError !== null" class="help is-danger">{{ formError }}</span>

          <!-- Upload Image Icon -->
          <i ref="imageInput" class="mdi mdi-camera upload-icon" @click="openInputImage"></i>
          <!-- Submit button -->
          <span
            class="button is-primary is-pulled-right"
            :class="{'is-loading': loading }"
            style="margin-top: 9px;"
            @click="createCommentHandler"
          >Submit</span>

          <!-- Thumbnail images -->
          <image-preview :images="selectedImages" @delete="deleteImage" style="margin-top:24px;"></image-preview>
        </span>

        <!-- Hidden input field -->
        <input ref="imageInput" class="is-hidden" type="file" multiple @change="fileChangeHandler" />
      </span>
    </div>
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
        formData.append('body', this.body);
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

  .comment-form-container {
    padding: 10px 0px;

    @media screen and (min-width: 1048px) {
      .comment-form-avatar {
        margin-right: 12px;
      }
    }

    @media screen and (max-width: 859px) {
      .comment-form-avatar {
        display: none;
      }
    }

    .comment-form-avatar {
      height: 45px;
      border-radius: 50%;
      vertical-align: top;
    }
  }
}
</style>
