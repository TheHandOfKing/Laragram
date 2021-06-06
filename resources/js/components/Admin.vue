<template>
  <div class="container-fluid">
    <div class="d-flex justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div class="left">
              <button class="btn btn-light" @click="enableAdminPanel">
                Admin Dashboard
              </button>
              <button class="btn btn-light" @click="enableAdminInfo">
                Profile
              </button>
            </div>

            <div class="right d-flex">
              <button
                v-if="adminPanel"
                class="btn btn-light"
                @click="enablePostsInfo"
              >
                Posts
              </button>
              <button
                v-if="adminPanel"
                class="btn btn-light"
                @click="enableUsersInfo"
              >
                Users
              </button>
              <input
                v-if="adminPanel && postsEnabled"
                type="search"
                class="form-control"
                name=""
                placeholder="Search posts"
                id=""
                @change.prevent="searchPosts"
              />

              <input
                v-if="adminPanel && usersInfo"
                type="search"
                class="form-control"
                name=""
                placeholder="Search users"
                id=""
                @change.prevent="searchUsers"
              />
            </div>
          </div>

          <div class="card-body">
            <div class="container">
              <table v-if="adminPanel" class="table postsTable">
                <thead>
                  <tr v-if="postsEnabled">
                    <th>Id</th>
                    <th>User</th>
                    <th>Post Name</th>
                    <th>Location</th>
                    <th>Views</th>
                    <th>Posted</th>
                    <th>Actions</th>
                  </tr>
                  <tr v-if="usersInfo">
                    <th>Id</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody class="users" v-if="usersInfo">
                  <tr v-for="(user, index) of allUsers.data" :key="index">
                    <td>
                      <div class="post-id">
                        <a class="one-post" :href="userUrl(user.id)">
                          {{ user.id }}
                        </a>
                      </div>
                    </td>
                    <td>
                      <div class="user-info d-flex align-items-center">
                        <a :href="userUrl(user.id)">
                          <div class="user-info__img">
                            <img
                              :src="user.avatar"
                              class="avatar"
                              alt="User Img"
                            />
                          </div>
                          <div class="user-info__basic">
                            <h5 class="mb-0">{{ user.name }}</h5>
                          </div>
                        </a>
                      </div>
                    </td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.roles }}</td>
                    <td>
                      {{ dateToHuman(user.created_at) }}
                    </td>
                    <td>
                      <div class="actions">
                        <a
                          :href="userUrl(user.id)"
                          class="btn btn-sm btn-outline-info"
                          >Edit</a
                        >
                        <a
                          @click.prevent="deleteUser(user.id)"
                          class="btn btn-sm btn-outline-danger"
                          href=""
                          >Delete</a
                        >
                      </div>
                    </td>
                  </tr>
                </tbody>
                <tbody
                  class="posts"
                  v-if="postsEnabled && allPosts.data.length > 0"
                >
                  <tr v-for="(post, index) of allPosts.data" :key="index">
                    <td>
                      <div class="post-id">
                        <a class="one-post" :href="postSlug(post.slug)">
                          {{ post.id }}
                        </a>
                      </div>
                    </td>
                    <td>
                      <div class="user-info">
                        <a :href="userUrl(post.user.id)">
                          <div class="user-info__img">
                            <img
                              :src="post.user.avatar"
                              class="avatar"
                              alt="User Img"
                            />
                          </div>
                          <div class="user-info__basic">
                            <h5 class="mb-0">{{ post.user.name }}</h5>
                            <p class="text-muted mb-0">{{ post.user.email }}</p>
                          </div>
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
              </table>
              <!-- Pagination -->

              <pagination
                v-if="usersInfo && adminInfo == false"
                :data="allUsers"
                @pagination-change-page="getUserResults"
              ></pagination>

              <pagination
                v-if="
                  postsEnabled && allPosts.data.length > 0 && adminInfo == false
                "
                :data="allPosts"
                @pagination-change-page="getResults"
              ></pagination>

              <!-- End of pagination -->

              <!-- Admin Dashboard -->
              <div v-if="adminInfo == true" class="admin-profile">
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

                    <div class="second-part">
                      <div class="archived-posts">
                        <h1>Archived Posts</h1>
                        <div class="wrapper d-flex flex-column">
                          <div
                            v-for="(favorite, index) in favorites"
                            :key="index"
                            class="post"
                          >
                            <div
                              class="post-info d-flex justify-content-between"
                            >
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
                                      {{ favorite.favorites_count }} times by
                                      other users
                                    </p>

                                    <p v-if="favorite.is_liked">
                                      You liked the post
                                    </p>
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
  props: ["posts", "user", "users", "favorites"],

  // computed: {
  //   searchFunction() {
  //     if (this.adminPanel && this.usersInfo) return this.searchUsers();
  //     else this.searchPosts();
  //   },
  // },

  created() {
    this.getResults();
  },

  data() {
    return {
      keyword: "",
      allPosts: this.posts,
      allUsers: this.users,
      adminPanel: true,
      adminInfo: false,
      postsEnabled: true,
      usersInfo: false,
    };
  },

  methods: {
    dateToHuman(date) {
      if (typeof date == "undefined") return;
      return moment(date, "YYYYMMDD").fromNow();
    },
    postUrl(id) {
      return `/posts/${id}/edit`;
    },

    userUrl(id) {
      return `/users/${id}/edit`;
    },

    postSlug(slug) {
      return `/posts/${slug}`;
    },

    getUserResults(page = 0) {
      axios
        .get("/users?keyword=" + this.keyword + "&page=" + page + "&admin=true")
        .then((response) => {
          this.allUsers = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    getResults(page = 0) {
      axios
        .get("/posts?keyword=" + this.keyword + "&page=" + page + "&admin=true")
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
        .get("/posts?keyword=" + this.keyword + "&admin=true")
        .then((res) => {
          this.allPosts = res.data;
        })
        .catch((err) => console.log(err));
    },

    searchUsers() {
      this.keyword = event.currentTarget.value;
      axios
        .get("/users?keyword=" + this.keyword + "&admin=true")
        .then((res) => {
          this.allUsers = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    // deletion

    deleteUser(id) {
      axios
        .delete("/users/" + id + "?admin=true")
        .then((response) => {
          this.allUsers = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    deletePost(id) {
      axios
        .delete("/posts/" + id + "?admin=true")
        .then((response) => {
          this.allPosts = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    // Frontend part, component activation etc

    enableAdminPanel() {
      this.adminPanel = true;
      this.adminInfo = false;
    },

    enableAdminInfo() {
      this.adminPanel = false;
      this.adminInfo = true;
    },

    enablePostsInfo() {
      this.postsEnabled = true;
      this.usersInfo = false;
    },

    enableUsersInfo() {
      this.postsEnabled = false;
      this.usersInfo = true;
    },
  },
};
</script>