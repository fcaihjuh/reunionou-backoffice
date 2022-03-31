<template>
  <div>
      <Header />
      <section class="section">
        <template v-for="evenement in evenements">
            <Evenement :key="evenement.id" :evenement="evenement" />
        </template>
      </section>
  </div>
</template>

<script>
export default {
  methods: {
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
      //console.log(this.evenements)
    });
  },
};

</script>