<template>
  <router-link :to="{name:'Evenement', params:{id:evenement.id}}" class="box">
    <div class="media-content">
        <p class="title is-4">{{evenement.title}}</p>
    </div>
  </router-link>
</template>

<script>
export default {
  props: ["evenement"],
};
</script>

<style></style>