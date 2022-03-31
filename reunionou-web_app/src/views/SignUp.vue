<template>
  <section class="hero is-fullheight">
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-6-tablet is-5-desktop is-4-widescreen">
            <h1 class="title">REUNIONOU</h1>
            <div class="box">
              <h4 class="title is-4 has-text-centered">S'inscrire</h4>
              <form @submit.prevent="createAccount">
                <div class="field">
                  <label class="label">Prénom Nom</label>
                  <div class="control">
                    <input
                      class="input"
                      type="text"
                      v-model="fullname"
                      required
                    />
                  </div>
                </div>

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
                  S'inscrire
                </button>
                <h4 class="error-login">{{ errorMessage }}</h4>
              </form>
            </div>
            <div class="box">
              <div>
                Vous avez déjà un compte ?
                <router-link to="/login">Se connecter</router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  data() {
    return {
      fullname: "test",
      mail: "test@test.com",
      password: "test",
      errorMessage: "",
    };
  },
  methods: {
    createAccount() {
      this.$api
        .post("signup", {
          fullname: this.fullname,
          mail: this.mail,
          password: this.password,
        })
        .then((response) => {
          alert("Votre compte a bien été crée. Vous pouvez vous connecter");
          this.$router.push("/login");
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

