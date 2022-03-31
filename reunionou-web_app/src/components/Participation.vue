<template>
<article class="media box" v-if="member">
  <figure class="media-left">
    <p class="image is-64x64">
      <img :src="avatar(member)"/>
    </p>
  </figure>
  <div class="media-content">
    <div class="content">
      <p>
        <router-link :to="{name:'Member', params:{idMembre:member.id}}" :key="member.id" class="">
          <strong> {{member.fullname}} </strong>
        </router-link>
        <small>{{member.email}}</small>
        <br>
        {{message.message}}
      </p>
    </div>
  </div>
  <div class="media-right">
    <button class="delete"></button>
  </div>
</article>


</template>

<script>
export default {
  props: ["message"],
  computed: {
      member(){
          return this.$store.getters.getMembre(this.message.member_id)
      },
  }
};
</script>

<style></style>