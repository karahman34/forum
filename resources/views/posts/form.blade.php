@extends('layouts.app')

@section('content')
<div id="vue" class="container" data-post="{{ $post ?? null }}">
  <div class="columns is-centered">
    <div class="column is-half">
      <div class="card">
        <div class="card-content">
          <p class="card-title">
            @if ($method === 'POST')
              Create
            @else
              Update
            @endif
            Post
          </p>

          {{-- Err Notif --}}
          <div v-if="errNotif !== null" class="notification is-danger" v-text="errNotif"></div>

          {{-- Form --}}
          <form id="form-post" action="{{ $action }}">
            @csrf
            @method($method)

            {{-- Title Field --}}
            <div class="field">
              <label for="title" class="label">Title</label>
            <input type="text" ref="titleField" class="input" name="title" placeholder="Title" v-model="form.title">
              <span v-if="errors.title" class="help is-danger" v-text="errors.title" />
            </div>

            {{-- Body Field --}}
            <div class="field">
              <label for="body" class="label">Body</label>
              <textarea name="body" id="body" ref="bodyField" cols="30" rows="3" class="textarea" placeholder="Body" v-model="form.body"></textarea>
              <span v-if="errors.body" class="help is-danger" v-text="errors.body" />
            </div>

            {{-- Tag Field --}}
            <div class="field">
              <label for="tag" class="label">Tag</label>
              <input type="text" class="input" placeholder="Enter tag" v-model="tag" @keydown.enter="addTag">
            </div>

            {{-- Selected Tags --}}
            <div class="field is-grouped is-grouped-multiline">
              <div v-for="tag in selectedTags" :key="tag" class="control">
                <div class="tags has-addons">
                  <span class="tag is-link is-light" v-text="tag"></span>
                  <span class="tag is-delete" @click="removeTag(tag)"></span>
                </div>
              </div>
            </div>
            
            {{-- Tags Errors --}}
            <span v-if="errors.tags" class="help is-danger" v-text="errors.tags"></span>

            {{-- File Field --}}
            <div class="file" style="margin-top:14px;">
              <label class="file-label">
                <input class="file-input" type="file" name="images" @change="onImageChange" multiple>
                <span class="file-cta">
                  <span class="file-icon">
                    <i class="mdi mdi-upload mdi-24px"></i>
                  </span>
                  <span class="file-label">
                    Choose a images..
                  </span>
                </span>
              </label>
            </div>

            {{-- File Errors --}}
            <span v-if="errors.images" class="help is-danger" v-text="errors.images"></span>

            {{-- Thumbnails Previes --}}
            <div v-if="selectedImages.length" id="thumbnails-preview-container">
              {{-- Thumbnail Preview --}}
              <div 
                v-for="(image, index) in selectedImages"
                :key="index"
                class="is-inline-block thumbnail-preview"
                @mouseenter="image.active = true"
                @mouseleave="image.active = false"
              >
                {{-- The Image --}}
                <img :src="image.src" :class="{'thumbnail-image-active': image.active}">
                {{-- Delete Button --}}
                <span class="delete is-medium" :class="{'thumbnail-delete-active': image.active}" @click="deleteThumbnail(image)"></span>
              </div>
            </div>

            {{-- Submit button --}}
            <div style="margin-top:15px;">
              <button type="button" class="button is-primary" :class="{'is-loading': loading}" @click="formSubmitHandler">
                @if ($method === 'POST')
                  Create
                @else
                  Update
                @endif
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
  <script src="/dist/js/vue.js"></script>
  <script>
    const el = new Vue({
      el: '#vue',
      data() {
        return {
          tag: null,
          selectedTags: [],
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
          loading: false,
          errNotif: null,
          selectedImages: [],
        };
      },
      mounted () {
        this.initializeForm();
      },
      methods: {
        initializeForm() {
          const postAttribute = this.$el.getAttribute('data-post');
          if (postAttribute) {
            // Sync form data
            const post = JSON.parse(postAttribute);
            this.form.title = post.title;
            this.form.body = post.body;

            //Sync tags
            this.selectedTags = post.tags.map(tag => tag.name);

            // Get post images
            const images = post.images.map(image => {
              // Format source image
              const src = `/storage/${image.image}`;
              // Set image thumbnail preview
              this.selectedImages.push({
                src,
                file: null,
                active: false,
                originalSrc: image.image,
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
          // Get the form
          const form = document.getElementById('form-post');
          const action = form.action;

          // Set form data
          const formData = new FormData;
          formData.append('_token', form.querySelector('input[name="_token"]').value);
          formData.append('_method', form.querySelector('input[name="_method"]').value);
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
          axios.post(action, formData)
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
            .finally(this.loading = false);
        },
      },
    });
  </script>
@endpush

@push('css')
  <style>
    #thumbnails-preview-container {
      margin-top: 20px;
    }

    #thumbnails-preview-container img {
      max-width: 100px;
      margin-right: 10px;
    }

    .thumbnail-preview {
      position: relative;
    }

    .thumbnail-image-active  {
      filter: blur(1px);
      transition: filter 0.2s ease-in-out;
    }

    .thumbnail-preview .delete {
      display: none;
      position: absolute;
      top: 40px;
      right: 48px;
      z-index: 99999;
      background: black;
      opacity: 60%;
    }

    .thumbnail-delete-active {
      display: inline !important;
    }

    .tag {
      padding: 15px !important;
      font-size: 14px !important;
    }
  </style>
@endpush