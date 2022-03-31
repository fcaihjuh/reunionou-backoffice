<template>
  <div>
    <Header />
    <section class="section">
      <h4 class="title is-4 has-text-centered">Mes évènements</h4>
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-11-tablet is-10-desktop is-9-widescreen">
            <template v-for="event in events">
              <event
                class="box"
                :key="event.id"
                :event="event"
              />
            </template>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import Event from "../components/Event.vue";
export default {
  components: {
    Event,
  },
  data() {
    return {
      events: [],
    };
  },
  mounted() {
    this.$api.get("events").then((response) => {
      this.events = response.data;
    });
  },
};
</script>
<style lang="scss">
 @import "../scss/bulma.scss";
</style>
