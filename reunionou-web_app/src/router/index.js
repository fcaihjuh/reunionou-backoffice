import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [{
        path: '/events',
        name: 'Events',
        component: () =>
            import ('../views/Events.vue')
    },
    {
        path: '/signup',
        name: 'SignUp',
        component: () =>
            import ('../views/SignUp.vue')
    },
    {
        path: '/login',
        name: 'Login',
        component: () =>
            import ('../views/Login.vue')
    },
    {
        path: '/createevent',
        name: '/createEvent',
        component: () =>
            import ('../views/createEvent.vue')
    },
    {
        path: '/event/:id',
        name: 'Event',
        component: () =>
            import ('../views/Event.vue')
    },
    {
        path: '/signout',
        name: '/SignOut',
        component: () =>
            import ('../views/SignOut.vue')
    },
    {
        path: '/Members',
        name: 'Members',
        component: () =>
            import ('../views/Members')
    },
    {
        path: '/Member/:idMember',
        name: 'Member',
        component: () =>
            import ('../views/Member.vue')
    },
    {
        path: '/EditEvent/:id',
        name: 'EditEvent',
        component: () =>
            import ('../views/EditEvent.vue')
    },
    {
        path:'/invitation',
        name:'Invitation',
        component:()=>
        import('../views/Invitation.vue')
    },
    {
        path:'/profil',
        name:'Profil',
        component:()=>
        import('../views/Profil.vue')
    },
    {
        path: '/ModifierProfil',
        name: 'ModifierProfil',
        component: () =>
            import ('../views/ModifierProfile.vue')
    },
]

const router = new VueRouter({
    routes
})

export default router