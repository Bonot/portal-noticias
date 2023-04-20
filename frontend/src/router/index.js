import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AuthorsView from '../views/Authors/View.vue'
import AuthorsCreate from '../views/Authors/Create.vue'
import AuthorsEdit from '../views/Authors/Edit.vue'
import ArticlesView from '../views/Articles/View.vue'
import ArticlesCreate from '../views/Articles/Create.vue'
import ArticlesEdit from '../views/Articles/Edit.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/autores',
      name: 'authors',
      component: AuthorsView
    },
    {
      path: '/autores/novo',
      name: 'authorsCreate',
      component: AuthorsCreate
    },
    {
      path: '/autores/:id/editar',
      name: 'authorsEdit',
      component: AuthorsEdit
    },
    {
      path: '/noticias',
      name: 'articles',
      component: ArticlesView
    },
    {
      path: '/noticias/novo',
      name: 'articlesCreate',
      component: ArticlesCreate
    },
    {
      path: '/noticias/:id/editar',
      name: 'articlesEdit',
      component: ArticlesEdit
    },
  ]
})

export default router
