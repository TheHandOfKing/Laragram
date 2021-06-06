<template>
  <div class="d-flex like-controls">
    <div class="likes d-flex">
      <a
        @click.prevent="like"
        :title="title('up')"
        class="like cursor-pointer"
        v-bind:class="{ liked: liked }"
      >
        <svg
          version="1.1"
          id="Capa_1"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          x="0px"
          y="0px"
          viewBox="0 0 293.896 293.896"
          style="enable-background: new 0 0 293.896 293.896"
          xml:space="preserve"
        >
          <path
            d="M258.281,112.004h-75.48l2.656-12.137c1.968-8.998,4.467-22.756,5.19-37.208c1.173-23.438-2.308-53.138-25.554-61.533
	C165.059,1.116,161.293,0,156.4,0c-19.844,0-24.172,16.838-24.985,26.875c-3.937,48.515-18.636,81.269-30.272,100.203l-7.42,12.072
	v-14.607c0-5.071-4.11-9.182-9.182-9.182H16.762c-5.071,0-9.182,4.111-9.182,9.182v160.172c0,5.071,4.11,9.182,9.182,9.182h67.779
	c5.071,0,9.182-4.111,9.182-9.182v-9.731l6.368,5.472c9.467,8.145,21.167,12.63,32.943,12.63l68.714-0.027
	c15.463,0,28.039-12.576,28.039-28.033c0-1.978-0.215-3.969-0.64-5.915l-1.602-7.346l6.612-3.579
	c9.061-4.904,14.689-14.352,14.689-24.656c0-2.714-0.395-5.407-1.174-8.003l-2.001-6.674l5.567-4.189
	c7.029-5.289,11.06-13.424,11.06-22.322c0-1.645-0.149-3.305-0.444-4.936l-1.48-8.189l7.783-2.943
	c10.86-4.106,18.156-14.65,18.156-26.236C286.316,124.579,273.74,112.004,258.281,112.004z M75.359,275.533H25.943V133.725h49.416
	V275.533z"
          />
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
        </svg>
      </a>
      <a
        @click.prevent="unlike"
        :title="title('down')"
        class="like-down"
        v-bind:class="{ unliked: !liked }"
      >
        <svg
          version="1.1"
          id="Capa_1"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          x="0px"
          y="0px"
          viewBox="0 0 512 512"
          style="enable-background: new 0 0 512 512"
          xml:space="preserve"
        >
          <g>
            <g>
              <path
                d="M117.333,10.667h-64C23.936,10.667,0,34.603,0,64v170.667C0,264.064,23.936,288,53.333,288h96V21.461
			C140.395,14.72,129.344,10.667,117.333,10.667z"
              />
            </g>
          </g>
          <g>
            <g>
              <path
                d="M512,208c0-18.496-10.581-34.731-26.347-42.667c3.285-6.549,5.013-13.803,5.013-21.333
			c0-18.517-10.603-34.752-26.368-42.688c4.885-9.728,6.315-20.928,3.861-32.043C463.381,47.659,443.051,32,419.819,32H224
			c-13.995,0-35.968,4.416-53.333,12.608v228.651l2.56,1.301l61.44,133.12V480c0,3.243,1.472,6.315,3.989,8.341
			c0.683,0.512,16.512,12.992,38.677,12.992c24.683,0,64-39.061,64-85.333c0-29.184-10.453-65.515-16.981-85.333h131.776
			c28.715,0,53.141-21.248,55.637-48.363c1.387-15.211-3.691-29.824-13.653-40.725C506.923,232.768,512,220.821,512,208z"
              />
            </g>
          </g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
          <g></g>
        </svg>
      </a>
      <span class="likes-count">{{ count }}</span>
    </div>

    <favorite v-if="name === 'post'" :post="model"></favorite>
  </div>
</template>

<script>
import Favorite from "./Favorite.vue";
export default {
  props: ["name", "model", "user"],
  computed: {
    classes() {
      return this.signedIn ? "" : "off";
    },

    liked() {
      return ["like", this.liked ? "liked" : ""];
    },

    endpoint() {
      return `/${this.name}s/${this.id}/like`;
    },
  },
  components: {
    Favorite,
  },
  data() {
    return {
      count: this.model.likes_count || 0,
      id: this.model.id,
      liked: this.model.is_liked,
    };
  },
  methods: {
    title(likeType) {
      let titles = {
        up: `This ${this.name} is useful`,
        down: `This ${this.name} is not useful`,
      };
      return titles[likeType];
    },
    like() {
      this._like(1);
    },
    unlike() {
      this._like(0);
    },
    _like(like) {
      if (!this.signedIn) {
        this.$toast.warning(
          `Please login to like the ${this.name}`,
          "Warning",
          {
            timout: 3000,
            position: "bottomLeft",
          }
        );
        return;
      }
      axios.post(this.endpoint, { like }).then((res) => {
        this.$toast.success(res.data.message, "Success", {
          timeout: 3000,
          position: "bottomLeft",
        });
        this.liked = res.data.isLiked;
        this.count = res.data.likesCount;
      });
    },
  },
};
</script>