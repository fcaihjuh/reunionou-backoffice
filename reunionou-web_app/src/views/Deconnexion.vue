<template>
    <div>Déconnexion en cours</div>
</template>

<script>
export default{
    mounted(){
        this.$store.commit("setToken", false);
        setTimeout(()=>{
            this.$router.push("/connexion");
        }, 100);
    },
}
</script>