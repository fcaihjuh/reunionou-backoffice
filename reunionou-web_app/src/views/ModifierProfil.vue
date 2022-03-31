<template>
  <section class="hero is-fullheight">
    <Header />
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-12-tablet is-11-desktop is-10-widescreen">
            <h4 class="title is-4 has-text-centered">Modifier le profil</h4>
            <form class="box" @submit.prevent="modifierProfil">
                <div class="field">
                <label class="label">Prénom nom</label>
                <div class="control">
                  <input class="input" v-model="modifierProfil.fullname" />
                </div>
              </div>
              <div class="field">
                <label class="label">Pseudo</label>
                <div class="control">
                  <input class="input" v-model="modifierProfil.username" />
                </div>
              </div>
              <div class="field">
                <label class="label">Email</label>
                <div class="control">
                  <input class="input" v-model="modifierProfil.email" />
                </div>
              </div>
               <div class="field">
                <label class="label">Mot de passe</label>
                <div class="control">
                  <input class="input" v-model="modifierProfil.password" />
                </div>
              </div>
              <div class="buttons">
                <button class="button is-info is-outlined">Modifier</button>
                <router-link class="button is-outlined" to="/"
                  >Annuler</router-link
                >
              </div>
            </form>
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
        idProfil : this.$route.params.id,
      modifierProfil: {
        fullname: "",
        username: "",
        email:"",
        password:"",
      },
    };
  },
  methods: {
      //Si aucune modification n'est faite sur le sujet et label, leurs ancienne valeurs restent ainsi.
    modifierEvenement() {
      this.$api
        .put(`event/${this.idEvenement}`,this.modifierEvenement)
        .then(() => {
        this.$router.push('/');
        })
        .catch((error) => {
          alert(error.response.data.message);
        });
    },
  },
};
</script>
<style scoped></style>