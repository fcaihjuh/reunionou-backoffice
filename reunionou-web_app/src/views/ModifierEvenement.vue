<template>
  <section class="hero is-fullheight">
    <Header />
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-12-tablet is-11-desktop is-10-widescreen">
            <h4 class="title is-4 has-text-centered">Modifier l'événement'</h4>
            <form class="box" @submit.prevent="modifierEvenement">
                           <div class="field">
                <label class="label">Titre</label>
                <div class="control">
                  <input class="input" v-model="modifierEvenement.topic" />
                </div>
              </div>
              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <input class="input" v-model="modifierEvenement.label" />
                </div>
              </div>
              <div class="field">
                <label class="label">Lieu</label>
                <div class="control">
                  <input class="input" v-model="modifierEvenement.place" />
                </div>
              </div>
               <div class="field">
                <label class="label">Date</label>
                <div class="control">
                  <input class="input" v-model="modifierEvenement.date" />
                </div>
              </div>
               <div class="field">
                <label class="label">Heure</label>
                <div class="control">
                  <input class="input" v-model="modifierEvenement.heure" />
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
        idEvenement : this.$route.params.id,
      modifierEvenement: {
        label: "",
        topic: "",
        place:"",
        date:"",
        heure:"",
      },
    };
  },
  mounted(){
      //Je récupère le label et le sujet avant modification pour les afficher dans le input
      this.$api.get(`titre/${this.idEvenement}`).then((response) => {
          this.modifierEvenement.label = response.data.label;
          this.modifierEvenement.topic = response.data.topic;
      })
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