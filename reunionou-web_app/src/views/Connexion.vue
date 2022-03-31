<template>
<section class="hero Is-warning is-fullheight">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-5-tablet is-4-desktop is-3-widescreen">
                    <div class="box">
                        <h1 class="title">Reunionou</h1>
                        <h2 class="subtitle">Se connecter</h2> 
                    </div>  
                <form class="box" @submit.prevent="validation">
                    <!-- form pour interagir avec le formulaire -->
                <div class="field">
                <label for class="label">Email</label>
                <div class="control has-icons-left">
                    <input type="email" v-model="email" class="input" required />
                    <span class="icon is-small is-left">
                    <i class="fa fa-envelope"></i>
                    </span>
                </div>
                </div>
                <div class="field">
                    <label for class="label">Mot de passe</label>
                    <div class="control has-icons-left">
                    <input type="password" v-model="password" class="input" required />
                    <span class="icon is-small is-left">
                    <i class="fa fa-lock"></i>
                    </span>
                </div>
                </div>
                <div class="buttons">
                <button class="button">Valider</button>
                    <router-link class="button is-text" to="inscription">Créer un compte</router-link>
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
    data(){
        return {
            email: "",
            password: ""
        }
    },
     methods: {
        validation(){
            let donnees = {
                email:this.email,
                password:this.password,
            };
            this.$api.post('/signin')
            .then(response => {
                console.log(response)
                //this.$store.commit('setToken', response.data.token)
                //this.$store.commit('setMember', response.data.member)
                this.$router.push('/home')
            }).catch((error) => {
                alert(error.response.data.message);
            })
        },
    },
}
</script>

<style scoped>
</style>