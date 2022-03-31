<template>
  <div class="container">
    <Header />
    <br>
    <div class="columns is-centered">
      <div class="column is-half">
        <article class="media notification is-warning">
            <figure class="media-left">
              <span class="icon">
<i class="fa far fa-address-card"></i>
</span>
            </figure>
            <div class="media-content">
              <div class="content">
                <h2 class="title">Mon profil</h2>
                <p class="is-size-5">
                  Prénom nom : <b>{{ $store.state.member.fullname }}</b> <br>
                  Email : <b>{{ $store.state.member.email }}</b><br>
                  Pseudo : <b>{{ $store.state.member.username }}</b>
                  Mot de passe : <b>{{ $store.state.member.password }}</b>

                </p>
                              <router-link div="box" :to="{name : 'ModifierProfil', params :{id: member.id}}" class="button is-success is-small" >Modifier</router-link>    
                              <button @click="deleteConversation" class="button button is-danger is-small" >Supprimer</button>              
              </div>
            </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      member:false,
    };
  },
  mounted() {
    this.$api.get("member/id").then((response) => {
      this.users = response.data;
      //console.log(this.users);
    });
  },
};
</script>

<style scoped lang="scss">
h2 {
  font-size: 20px;
}
</style>