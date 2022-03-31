<template>
  <transition name="fade">
    <article class="media box" v-if="member && afficher">
      <figure class="media-left">
        <p class="image is-64x64">
          <img :src="avatar(member)" />
        </p>
      </figure>
      <div class="media-content">
        <div class="content">
          <p>
            <router-link
              :to="{ name: 'Member', params: { idMember: member.id } }"
            >
              <strong>{{ member.fullname }}</strong>
            </router-link> <small>{{ member.email }}</small>
            <br />
            <span v-html="message.message"></span>
            <br />
            <small>{{ dateMessage }}</small>
          </p>
        </div>
      </div>
      <div
        class="media-right"
        v-if="message.member_id == $store.state.member.id"
      >
        <a @click="deleteMessage" div="box" class="button delete is-danger"></a>
      </div>
    </article>
  </transition>
</template>
<script>
export default {
  props: ["message"],
  data() {
    return {
      afficher: true,
    };
  },
  computed: {
    member() {
      return this.$store.getters.getMember(this.message.member_id);
    },
    dateMessage() {
      let date = new Date(this.message.created_at);
      return (
        date.toLocaleDateString("fr-FR") +
        " à " +
        date.toLocaleTimeString("fr-FR")
      );
    },
  },
  methods: {
    deleteMessage() {
      if (confirm("êtes-vous sûre de vouloir supprimer le message ?")) {
        this.$api
          .delete(
            `channels/${this.message.channel_id}/posts/${this.message.id}`
          )
          .then(() => {
            this.afficher = false;
          })
          .catch((error) => {
            if (error.response.data.message) {
              alert(error.response.data.message);
            }
          });
      }
    },
  },
};
</script>

<style lang="scss">
 @import "../scss/bulma.scss";
</style>