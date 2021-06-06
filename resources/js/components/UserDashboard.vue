<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="first-part">
          <div class="your-info">
            <h1>YOUR INFORMATION</h1>

            <div class="user-info mr-1">
              <div class="row-custom mt-3">
                <p>Name</p>
                <p>{{ user.name }}</p>
              </div>
              <div class="row-custom mt-3">
                <p>Email</p>
                <p>{{ user.email }}</p>
              </div>
              <div class="row-custom mt-3">
                <p>Role</p>
                <p>{{ user.roles }}</p>
              </div>
            </div>
          </div>

          <div class="account-image ml-5">
            <h1>User Image</h1>
            <img :src="user.avatar" alt="user-image" />
            <p class="mt-4"></p>
          </div>
        </div>

        <div class="top-part mt-5 pb-4">
          <div class="holder d-flex justify-content-between align-center">
            <h1>My Posts</h1>
            <input
              style="height: 30px; width: 200px"
              class="form-control"
              type="search"
              placeholder="Search posts"
              name=""
              @change.prevent="searchPosts"
              id=""
            />
          </div>
          <div class="posts">
            <table class="table" v-if="allPosts.data.length > 0">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Post Name</th>
                  <th>Location</th>
                  <th>Views</th>
                  <th>Posted</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(post, index) of allPosts.data" :key="index">
                  <td>
                    <div class="post-id">
                      <a class="one-post" :href="post.url_admin">
                        {{ post.id }}
                      </a>
                    </div>
                  </td>
                  <td>
                    <span class="active-circle bg-success"></span>
                    {{ post.trimmed_name }}
                  </td>
                  <td>{{ post.location }}</td>
                  <td>{{ post.views }}</td>
                  <td>
                    {{ post.created_date }}
                  </td>
                  <td>
                    <div class="actions">
                      <a
                        :href="postUrl(post.id)"
                        class="btn btn-sm btn-outline-info"
                        >Edit</a
                      >
                      <a
                        @click.prevent="deletePost(post.id)"
                        class="btn btn-sm btn-outline-danger"
                        href=""
                        >Delete</a
                      >
                    </div>
                  </td>
                </tr>
              </tbody>

              <pagination
                :data="allPosts"
                @pagination-change-page="getResults"
              ></pagination>
            </table>
            <div v-else class="alert alert-secondary">
              You currently have no posts, let people hear about you by posting
              some! To post something you can go
              <a href="/posts/create">here</a>
            </div>
          </div>
        </div>

        <div class="second-part mt-5 flex-column">
          <div class="holder d-flex justify-content-between align-center">
            <h1>Archived Posts</h1>
          </div>

          <div class="archived-posts">
            <h1>Archived Posts</h1>
            <div class="wrapper d-flex flex-column">
              <div
                v-for="(favorite, index) in favorites"
                :key="index"
                class="post"
              >
                <div class="post-info d-flex justify-content-between">
                  <div class="user-info">
                    <a :href="postSlug(favorite.slug)">
                      <div class="user-info__basic">
                        <h5 class="mb-0">
                          {{ favorite.trimmed_name }}
                        </h5>
                        <p class="text-muted mb-0">
                          Posted: {{ favorite.created_date }}
                        </p>
                      </div>

                      <div class="d-flex flex-column">
                        <p>
                          This post was archived
                          {{ favorite.favorites_count }} times by other users
                        </p>

                        <p v-if="favorite.is_liked">You liked the post</p>
                        <p v-else-if="favorite.is_liked == false">
                          You didn't like the post
                        </p>
                      </div>
                    </a>
                  </div>

                  <div class="location">
                    <p>Location: {{ favorite.location }}</p>
                    <p>Views: {{ favorite.views }}</p>
                  </div>

                  <div class="action">
                    <favorite :post="favorite"></favorite>
                  </div>
                </div>
                <hr />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
import Favorite from "./Favorite.vue";

export default {
  components: {
    Favorite,
  },

  created() {
    this.getResults();
  },

  props: ["posts", "user", "favorites"],

  data() {
    return {
      allPosts: this.posts,
      keyword: "",
    };
  },

  methods: {
    postSlug(slug) {
      return `/posts/${slug}`;
    },

    postUrl(id) {
      return `/posts/${id}/edit`;
    },

    getResults(page = 0) {
      axios
        .get(
          "/posts?keyword=" +
            this.keyword +
            "&page=" +
            page +
            "&user=true&user_id=" +
            this.user.id
        )
        .then((response) => {
          this.allPosts = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    searchPosts() {
      this.keyword = event.currentTarget.value;
      axios
        .get(
          "/posts?keyword=" +
            this.keyword +
            "&user=true&user_id=" +
            this.user.id
        )
        .then((res) => {
          this.allPosts = res.data;
        })
        .catch((err) => console.log(err));
    },

    deletePost(id) {
      axios
        .delete("/posts/" + id + "?admin=false")
        .then((response) => {
          this.allPosts = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    dateToHuman(date) {
      if (typeof date == "undefined") return;
      return moment(date, "YYYYMMDD").fromNow();
    },
  },
};
</script>