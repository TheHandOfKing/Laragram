<template>
  <div>
    <div class="row mt-4" v-cloak v-if="count">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title">
              <h2>{{ title }}</h2>
            </div>
            <comment
              @deleted="remove(index)"
              v-for="(comment, index) in comments"
              :user="user"
              :comment="comment"
              :key="comment.id"
            ></comment>

            <div class="text-center mt-3" v-if="nextUrl">
              <button
                @click.prevent="fetch(nextUrl)"
                class="btn btn-outline-secondary"
              >
                Load More Comments
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <new-comment @created="add" :post-id="post.id"></new-comment>
  </div>
</template>

<script>
import Comment from "./Comment.vue";
import NewComment from "./NewComment.vue";

export default {
  props: ["post", "user"],

  data() {
    return {
      postId: this.post.id,
      count: this.post.comments_count,
      comments: [],
      nextUrl: null,
    };
  },

  created() {
    this.fetch(`/posts/${this.postId}/comments`);
  },

  methods: {
    add(comment) {
      this.comments.push(comment);
      this.count++;
    },

    remove(index) {
      this.comments.splice(index, 1);
      this.count--;
    },

    fetch(endpoint) {
      axios.get(endpoint).then((data) => {
        //Object destructuring
        console.log(data);
        this.comments.push(...data.data.data);
        this.nextUrl = data.next_page_url;
      });
    },
  },

  computed: {
    title() {
      return this.count + " " + (this.count > 1 ? "Comments" : "Comment");
    },
  },
  components: { Comment, NewComment },
};
</script>