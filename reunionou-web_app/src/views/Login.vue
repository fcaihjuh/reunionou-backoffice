<template>
  <section class="hero is-fullheight">
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-6-tablet is-5-desktop is-4-widescreen">
            <h1 class="title">REUNIONOU</h1>
            <div class="box">
              <h4 class="title is-4 has-text-centered">Se connecter</h4>
              <form @submit.prevent="loginAccount">
                <div class="field">
                  <label class="label">Email</label>
                  <div class="control">
                    <input
                      class="input"
                      type="email"
                      v-model="mail"
                      required
                    />
                  </div>
                </div>

                <div class="field">
                  <label class="label">Mot de passe</label>
                  <div class="control">
                    <input
                      class="input"
                      type="password"
                      v-model="password"
                      required
                    />
                  </div>
                </div>
                <button class="button is-primary is-outlined">
                  Se connecter
                </button>
                <h4 class="error-login">{{ errorMessage }}</h4>
              </form>
            </div>
            <div class="box">
              Pas encore inscrit ?
              <router-link class="is-text" to="/signup"
                >S'inscrire</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  mounted() {
    if (this.$store.state.token) {
      this.$router.push("/"); //Si le client essaie d'accéder à la page login à travers l'URL et que celui-ci est déjà connecté, le renvoyer vers la page des conversation
    }
  },
  data() {
    return {
      mail: "test@test.com",
      password: "test",
      errorMessage: "",
    };
  },
  methods: {
    loginAccount() {
      this.$api
        .post("/signin", {
          mail: this.mail,
          password: this.password,
        })
        .then((response) => {
          this.$store.commit("setToken", response.data.token);
          this.$store.commit("setMember", response.data.member);
          this.$router.push("/events");
          this.errorMessage = "";
        })
        .catch((error) => {
          this.errorMessage = error.response.data.message;
        });
    },
  },
};
</script>
<style lang="scss">
 @import "../scss/bulma.scss";
</style>

