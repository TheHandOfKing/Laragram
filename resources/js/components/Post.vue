<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="card-title">
            <div
              class="image-carousel-custom d-flex flex-column align-items-center"
            >
              <user-avatar :model="post" label="Posted"></user-avatar>
              <h1 class="post-title">{{ name }}</h1>
              <carousel style="width: 100%">
                <slide
                  v-for="(image, index) of images"
                  :key="index"
                  :src="image"
                >
                  <img :src="image" alt="post-image" />
                </slide>
              </carousel>
            </div>
          </div>

          <hr />

          <div class="media d-flex justify-content-between">
            <like :model="post" :name="'post'"></like>
          </div>

          <div class="media-body d-flex flex-column">
            <div v-html="bodyHtml"></div>
            <div class="row">
              <div class="col-4"></div>
              <div class="col-4"></div>
              <div class="col-4"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["post"],

  data() {
    return {
      name: this.post.name,
      description: this.post.description,
      bodyHtml: this.post.body_html,
      images: this.post.image_url,
    };
  },

  methods: {},
};
</script>

<style scoped>
.post-title {
  width: 100%;
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>