<template>
  <div class="media post d-flex flex-column-reverse">
    <like :user="user" :model="comment" name="comment"></like>
    <div class="media-body">
      <form v-if="editing" @submit.prevent="update">
        <div class="form-group">
          <textarea
            rows="10"
            v-model="body"
            class="form-control"
            required
          ></textarea>
        </div>
        <button class="btn btn-primary" :disabled="isInvalid">Update</button>
        <button class="btn btn-outline-secondary" @click="cancel" type="button">
          Cancel
        </button>
      </form>
      <div v-else>
        <div v-html="bodyHtml"></div>
        <div class="row">
          <div class="col-9">
            <div class="ml-auto">
              <a
                v-if="author(comment.user_id)"
                @click.prevent="edit"
                class="btn btn-sm btn-outline-info"
                >Edit</a
              >
              <button
                v-if="author(comment.user_id)"
                @click="destroy"
                class="btn btn-sm btn-outline-danger"
              >
                Delete
              </button>
            </div>
          </div>
          <div class="col-3">
            <user-avatar :model="comment" label="commented"></user-avatar>
          </div>
        </div>
      </div>
    </div>
    <hr />
  </div>
</template>

<script>
export default {
  props: ["comment", "user"],
  data() {
    return {
      editing: false,
      body: this.comment.body,
      bodyHtml: this.comment.body_html,
      id: this.comment.id,
      postId: this.comment.post_id,
      beforeEditCache: null,
    };
  },
  methods: {
    author(commentUserId) {
      if (commentUserId == this.user.id) {
        return true;
      }

      return false;
    },

    edit() {
      this.beforeEditCache = this.body;
      this.editing = true;
    },
    cancel() {
      this.body = this.beforeEditCache;
      this.editing = false;
    },
    update() {
      axios
        .patch(this.endpoint, {
          body: this.body,
        })
        .then((res) => {
          this.editing = false;
          this.bodyHtml = res.data.body_html;
          this.$toast.success(res.data.message, "Sucess", { timeout: 3000 });
        })
        .catch((err) => {
          this.$toast.error(err.response.data.message, "Error", {
            timeout: 3000,
          });
        });
    },
    destroy() {
      this.$toast.warning("Are you sure about that?", "Confirm", {
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: "once",
        id: "post",
        zindex: 999,
        title: "Hey",
        position: "center",
        buttons: [
          [
            "<button><b>YES</b></button>",
            (instance, toast) => {
              axios.delete(this.endpoint).then((res) => {
                this.$emit("deleted");
              });
              instance.hide({ transitionOut: "fadeOut" }, toast, "button");
            },
            true,
          ],
          [
            "<button>NO</button>",
            function (instance, toast) {
              instance.hide({ transitionOut: "fadeOut" }, toast, "button");
            },
          ],
        ],
      });
    },
  },
  computed: {
    isInvalid() {
      return this.body.length < 10;
    },
    endpoint() {
      return `/posts/${this.postId}/comments/${this.id}`;
    },
  },
};
</script>

<style scoped>
.media-body {
  width: 100%;
}

hr {
  height: 1px;
  margin-top: 1rem;
  margin-bottom: 1rem;
  border: 1px;
  width: 100%;
  border-top: 1px solid #dee2ed;
}
</style>