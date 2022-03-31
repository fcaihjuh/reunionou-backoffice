<template>
  <section class="hero is-fullheight">
    <Header />
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-12-tablet is-11-desktop is-10-widescreen">
            <h4 class="title is-4 has-text-centered">Modifier l'évènement</h4>
            <form class="box" @submit.prevent="editEvent">
                           <div class="field">
                <label class="label">Titre</label>
                <div class="control">
                  <input class="input" v-model="editedEvent.topic" />
                </div>
              </div>
              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <input class="input" v-model="editedEvent.label" />
                </div>
              </div>
              <div class="field">
                <label class="label">Lieu</label>
                <div class="control">
                  <input class="input" v-model="editedEvent.place" />
                </div>
              </div>
               <div class="field">
                <label class="label">Date</label>
                <div class="control">
                  <input class="input" v-model="editedEvent.date" />
                </div>
              </div>
               <div class="field">
                <label class="label">Heure</label>
                <div class="control">
                  <input class="input" v-model="editedEvent.heure" />
                </div>
              </div>
              <div class="buttons">
                <button class="button is-info is-outlined">Modifier</button>
                <router-link class="button is-outlined" to="/events"
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
        idEvent : this.$route.params.id,
      editedEvent: {
        label: "",
        topic: "",
        place:"",
        date:"",
        heure:"",
      },
    };
  },
  mounted(){
      this.$api.get(`/events/${this.idEvent}`).then((response) => {
          this.editedEvent.label = response.data.label;
          this.editedEvent.topic = response.data.topic;
           this.editedEvent.place = response.data.place;
            this.editedEvent.date = response.data.date;
             this.editedEvent.heure = response.data.heure;
      })
  },
  methods: {
      //Si aucune modification n'est faite sur le sujet et label, leurs ancienne valeurs restent ainsi.
    editEvent() {
      this.$api
        .put(`/events/${this.idEvent}`,this.editedEvent)
        .then(() => {
        this.$router.push('/events');
        })
        .catch((error) => {
          alert(error.response.data.message);
        });
    },
  },
};
</script>
<style lang="scss">
 @import "../scss/bulma.scss";
</style>
