<template>
  <div>
    <Header />
    <section class="section">
      <h4 class="title is-4 has-text-centered">Mes Evenements</h4>
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-11-tablet is-10-desktop is-9-widescreen">
            <template v-for="evenement in evenements">
              <evenement
                class="box"
                :key="evenement.id"
                :evenement="evenement"
              />
            </template>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import Evenement from "../components/Evenement.vue";
export default {
  components: {
    Evenement,
  },
  data() {
    return {
      evenements: [],
    };
  },
  mounted() {
    this.$api.get("events").then((response) => {
      this.evenements = response.data;
    });
  },
};
</script>
<style scoped></style>
