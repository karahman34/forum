<template>
  <div id="root-app" class="container">
    <div class="columns is-centered">
      <div class="column is-10-tablet is-half-desktop">
        <div class="card">
          <div class="card-content">
            <p class="card-title">{{ isEditting ? 'Edit Post' : 'Create Post' }}</p>
            <!-- Err Notif -->
            <div v-if="errNotif !== null" class="notification is-danger" v-text="errNotif"></div>

            <!-- Form -->
            <form id="form-post" @submit.prevent="formSubmitHandler">
              <!-- Title Field -->
              <div class="field">
                <label for="title" class="label">Title</label>
                <input
                  type="text"
                  ref="titleField"
                  class="input"
                  :class="{'is-danger': errors.title}"
                  name="title"
                  placeholder="Title"
                  v-model="form.title"
                />
                <span v-if="errors.title" class="help is-danger" v-text="errors.title" />
              </div>

              <!-- Body Field -->
              <div class="field">
                <label for="body" class="label">Body</label>
                <textarea
                  name="body"
                  id="body"
                  ref="bodyField"
                  cols="30"
                  rows="3"
                  class="textarea"
                  :class="{'is-danger': errors.body}"
                  placeholder="Body"
                  v-model="form.body"
                ></textarea>
                <span v-if="errors.body" class="help is-danger" v-text="errors.body" />
              </div>

              <!-- Tag Field -->
              <div class="field">
                <label for="tag" class="label">Tag</label>
                <input
                  type="text"
                  class="input"
                  :class="{'is-danger': errors.tags}"
                  placeholder="Enter tag"
                  v-model="tag"
                  @keydown.enter="addTag"
                />
              </div>

              <!-- Selected Tags -->
              <div class="field is-grouped is-grouped-multiline">
                <div v-for="tag in selectedTags" :key="tag" class="control">
                  <div class="tags has-addons">
                    <span class="tag is-link is-light" v-text="tag"></span>
                    <span class="tag is-delete" @click="removeTag(tag)"></span>
                  </div>
                </div>
              </div>
              <!-- Tags Errors -->
              <span v-if="errors.tags" class="help is-danger" v-text="errors.tags"></span>

              <!-- File Field -->
              <div class="file" :class="{'image-mb': !errors.images}" style="margin-top:8px;">
                <label class="file-label">
                  <input
                    ref="imageInput"
                    multiple
                    type="file"
                    name="images"
                    class="file-input"
                    :class="[{'is-danger': errors.images}]"
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

              <!-- Thumbnails Previes -->
              <image-preview
                class="image-preview-c"
                :images="selectedImages"
                @delete="deleteThumbnail"
              ></image-preview>

              <!-- Submit button -->
              <div @click="formSubmitHandler">
                <button
                  type="button"
                  class="button is-primary"
                  :class="{'is-loading': loading}"
                >{{ isEditting ? 'Update' : 'Submit' }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
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
    post: {
      type: Object,
      default: null,
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
      isEditting: false,
      loading: false,
      errNotif: null,
      form: {
        title: null,
        body: null,
      },
      errors: {
        title: null,
        body: null,
        images: null,
        tags: null,
      },
      selectedImages: [],
      tag: null,
      selectedTags: [],
    };
  },
  created() {
    this.initializeForm();
  },
  methods: {
    initializeForm() {
      if (this.post !== null) {
        // Set editting
        this.isEditting = true;
        // Sync form data
        this.form.title = this.post.title;
        this.form.body = this.post.body;

        //Sync tags
        this.selectedTags = this.post.tags.map(tag => tag.name);

        // Get post images
        const images = this.post.images.map(image => {
          // Format source image
          const src = `/storage/${image.url}`;
          // Set image thumbnail preview
          this.selectedImages.push({
            src,
            file: null,
            active: false,
            originalSrc: image.url,
          });
        });
      }
    },
    onImageChange(e) {
      const files = e.target.files;
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        this.selectedImages.push({
          file,
          active: false,
          src: window.URL.createObjectURL(file),
        });
      }

      this.$refs.imageInput.value = null;
    },
    deleteThumbnail(image) {
      this.selectedImages.splice(this.selectedImages.indexOf(image), 1);
    },
    addTag() {
      if (this.selectedTags.indexOf(this.tag) === -1) {
        this.selectedTags.push(this.tag);
        this.tag = null;
      }
    },
    removeTag(tag) {
      this.selectedTags.splice(this.selectedTags.indexOf(tag), 1);
    },
    formSubmitHandler() {
      // Turn on loading
      this.loading = true;

      // Set form data
      const formData = new FormData();
      formData.append('_token', this.csrf);
      formData.append('_method', this.method);
      formData.append('title', this.$refs.titleField.value);
      formData.append('body', this.$refs.bodyField.value);
      // Set tags
      for (const tag of this.selectedTags) {
        formData.append('tags[]', tag);
      }
      // Set post & old images
      for (const image of this.selectedImages) {
        if (image.file !== null) {
          formData.append('images[]', image.file);
        } else {
          formData.append('old_images[]', image.originalSrc);
        }
      }

      // Send data
      axios
        .post(this.action, formData)
        .then(res => {
          window.location = res.data.next_url;
        })
        .catch(err => {
          const errStatus = err.response && err.response.status;

          if (errStatus === 422) {
            const errFields = err.response.data.errors;
            validateForm(errFields, this.errors);
          } else {
            this.errNotif = 'Error: Failed to create Post.';
          }
        })
        .finally((this.loading = false));
    },
  },
};
</script>

<style lang="scss" scoped>
@media screen and (max-width: 768px) {
  .container {
    padding-top: 35px !important;
  }
}

.image-mb {
  margin-bottom: 10px !important;
}

.tag {
  padding: 15px !important;
  font-size: 14px !important;
}
</style>