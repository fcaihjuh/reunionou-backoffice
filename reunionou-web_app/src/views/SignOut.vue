<template>
  <div>Deconnexion en cours</div>
</template>

<script>
export default {
  mounted() {
    this.$store.commit("setToken", false);
    setTimeout(() => {
      this.$router.push("/login");
    }, 100);
  },
};
</script>
<style lang="scss">
 @import "../scss/bulma.scss";
</style>
