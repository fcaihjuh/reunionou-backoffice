<template>
  <div>
    <router-view v-if="$store.state.ready" />
    <template v-else>
      <div class="chargement has-text-light">
        <p>Chargement, veuillez patienter</p>
        <button class="button is-loading is-dark"></button>
      </div>
    </template>
  </div>
</template>
<script>
export default {
  mounted() {
    this.$store.commit("setReady", true);
    if (!this.$store.state.token) {
      this.seConnecter();
    } else {
      this.$api
        //.get(`members/${this.$store.state.member.id}/signin`)
        .get(`members/signin`)
        .then(this.demarrer)
        .catch(this.seConnecter);
    }
  },
  methods: {
    seConnecter() {
      this.$store.commit("setToken", false);
      this.$router.push("/login");
      this.ready();
    },
    ready() {
      this.$store.commit("setReady", true);
    },
    demarrer() {
      this.$api.get("members").then((response) => {
        this.$store.commit("setMembers", response.data);
        this.ready();
      });
    },
  },
};
</script>
<style lang="scss">
 @import "./scss/bulma.scss";
</style>