<template>
  <div>
    <Header />
    <section class="section">
      <div v-if="member">
        <div class="card">
          <div class="card-content">
            <div class="media">
              <div class="media-left">
                <figure class="image is-48x48">
                  <img :src="avatar(member)" />
                </figure>
              </div>
              <div class="media-content">
                <p class="title is-4">{{ member.fullname }}</p>
                <p class="subtitle is-6">{{ member.email }}</p>
              </div>
              <div class="media-right">
                <router-link
                  div="box"
                  :to="{ name: 'Profil' }"
                  class="button is-success is-small"
                  >Modifier mon profil</router-link
                >
                <button @click="deleteMember" class="button is-danger is-small">
                  Supprimer mon compte
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="box mt-5">
          {{ messages.length }} dernier(s) messages postés
        </div>
        <template v-for="message in messages">
          <message
            :message="message"
            :key="message.id"
            :event="event"
          />
        </template>
      </div>
    </section>
  </div>
</template>

<script>
import Message from "../components/Message.vue";
export default {
  components: {
    Message,
  },
  data() {
    return {
      member: false,
      allMessages: [],
      event: "",
      deleteMemberMessage: "",
    };
  },
  mounted() {
    this.member = this.$store.getters.getMember(this.$route.params.idMember);

    this.$api.get("events").then((response) => {
      response.data.forEach((event) => {
        this.event = event;
        this.$api.get(`events/${event.id}/posts`).then((response) => {
          response.data.forEach((message) => {
            if (message.member_id == this.member.id) {
              this.allMessages.push(message);
            }
          });
        });
      });
    });
  },
  computed: {
    messages() {
      return this.allMessages
        .sort((ma, mb) => new Date(ma.created_at) < new Date(mb.created_at))
        .slice(0, 10);
    },
  },
  methods: {
    deleteMember() {
      if (confirm("êtes-vous sûre de vouloir supprimer votre membre ?")) {
        this.$api.delete(`user/${this.member.id}`).then((response) => {
          this.$router.push("/user");
        });
      }
    },
  },
};
</script>

<style lang="scss">
@import "../scss/bulma.scss";
</style>

