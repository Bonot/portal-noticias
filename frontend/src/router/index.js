import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Login from '../views/Login.vue'
import AuthorsView from '../views/Authors/View.vue'
import AuthorsCreate from '../views/Authors/Create.vue'
import AuthorsEdit from '../views/Authors/Edit.vue'
import ArticlesView from '../views/Articles/View.vue'
import ArticlesCreate from '../views/Articles/Create.vue'
import ArticlesEdit from '../views/Articles/Edit.vue'
import authorization from '../middlewares/authorization'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login,
      beforeEnter: authorization
    },
    {
      path: '/',
      name: 'home',
      component: HomeView,
      beforeEnter: authorization
    },
    {
      path: '/autores',
      name: 'authors',
      component: AuthorsView,
      beforeEnter: authorization
    },
    {
      path: '/autores/novo',
      name: 'authorsCreate',
      component: AuthorsCreate,
      beforeEnter: authorization
    },
    {
      path: '/autores/:id/editar',
      name: 'authorsEdit',
      component: AuthorsEdit,
      beforeEnter: authorization
    },
    {
      path: '/noticias',
      name: 'articles',
      component: ArticlesView,
      beforeEnter: authorization
    },
    {
      path: '/noticias/novo',
      name: 'articlesCreate',
      component: ArticlesCreate,
      beforeEnter: authorization
    },
    {
      path: '/noticias/:id/editar',
      name: 'articlesEdit',
      component: ArticlesEdit,
      beforeEnter: authorization
    },
  ]
})

export default router
