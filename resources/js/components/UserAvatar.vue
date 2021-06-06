<template>
  <div class="user-avatar d-flex">
    <div class="media d-flex flex-column">
      <div class="media-body mt-1">
        <a :href="user.url">{{ user.name }}</a>
      </div>
      <span class="text-muted">{{ postDate }}</span>
    </div>

    <a :href="user.url" class="pr-2">
      <img class="avatar" :src="user.avatar" />
    </a>
  </div>
</template>

<script>
export default {
  props: ["model", "label"],
  computed: {
    postDate() {
      return this.label + " " + this.model.created_date;
    },
  },
  data() {
    return {
      user: this.model.user,
    };
  },
};
</script>

<style scoped>
.user-avatar {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
</style>