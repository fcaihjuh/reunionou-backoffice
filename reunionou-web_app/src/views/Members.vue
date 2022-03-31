<template>
  <div>
    <Header />
    <section class="section">
      <h4 class="title is-4 has-text-centered">Liste des membres</h4>
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-9-tablet is-8-desktop is-7-widescreen">
            <template v-for="member in $store.state.members">
              <router-link
                class="card box"
                :to="{ name: 'Member', params: { idMember: member.id } }"
                :key="member.id"
              >
                <div class="card-content" v-if="member && afficher">
                  <div class="media">
                    <div class="media-left">
                      <figure class="image is-48x48">
                        <img :src="avatar(member)" />
                      </figure>
                    </div>
                    <div class="media-content">
                      <div class="content">
                        <p class="title is-4">{{ member.fullname }}</p>
                        <p class="subtitle is-6">{{ member.email }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      afficher: true,
    };
  },
};
</script>

<style lang="scss">
 @import "../scss/bulma.scss";
</style>

