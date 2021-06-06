<template>
  <a
    title="Click to mark as favorite post (Click again to undo)"
    :class="classes"
    @click.prevent="toggle"
  >
    <svg
      fill="gray"
      style="height: 55px; width: 55px"
      version="1.1"
      viewBox="0 0 48 48"
      xml:space="preserve"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
    >
      <g class="st0" id="Padding__x26__Artboard" />
      <g id="Icons">
        <g>
          <path
            class="st1"
            d="M30.37337,33.9599l-5.96166-8.79194c-0.19733-0.29102-0.62609-0.29102-0.82342,0l-5.96166,8.79194    c-0.27508,0.40568-0.90915,0.21098-0.90915-0.27917V14.31835c0-0.27473,0.22271-0.49744,0.49744-0.49744h13.57016    c0.27473,0,0.49744,0.22271,0.49744,0.49744v19.36238C31.28252,34.17088,30.64845,34.36558,30.37337,33.9599z"
          />
          <g>
            <line
              class="st1"
              x1="19.16389"
              x2="24.19888"
              y1="16.12722"
              y2="16.12722"
            />
            <line
              class="st1"
              x1="25.54154"
              x2="26.5905"
              y1="16.12722"
              y2="16.12722"
            />
          </g>
          <path
            class="st1"
            d="M30.37337,33.9599l-5.96166-8.79194c-0.19733-0.29102-0.62609-0.29102-0.82342,0l-5.96166,8.79194    c-0.27508,0.40568-0.90915,0.21098-0.90915-0.27917V14.31835c0-0.27473,0.22271-0.49744,0.49744-0.49744h13.57016    c0.27473,0,0.49744,0.22271,0.49744,0.49744v19.36238C31.28252,34.17088,30.64845,34.36558,30.37337,33.9599z"
          />
        </g>
      </g>
    </svg>
  </a>
</template>
<script>
export default {
  props: ["post"],
  data() {
    return {
      isFavorited: this.post.is_favorited,
      count: this.post.favorites_count,
      id: this.post.id,
    };
  },
  computed: {
    classes() {
      return [
        "favorite",
        "mt-2",
        !this.signedIn ? "off" : this.isFavorited ? "favorited" : "",
      ];
    },
    endpoint() {
      return `/posts/${this.id}/favorites`;
    },
  },
  methods: {
    toggle() {
      if (!this.signedIn) {
        this.$toast.warning("Please login to favorite this post", "Warning", {
          timeout: 3000,
          position: "bottomLeft",
        });
        return;
      }
      this.isFavorited ? this.destroy() : this.create();
    },

    destroy() {
      axios.delete(this.endpoint).then((res) => {
        this.count--;
        this.isFavorited = false;
      });
    },

    create() {
      axios.post(this.endpoint).then((res) => {
        this.count++;
        this.isFavorited = true;
      });
    },
  },
};
</script>