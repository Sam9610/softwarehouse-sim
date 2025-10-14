import { createRouter, createWebHistory } from 'vue-router'
import MainMenu from '../views/MainMenu.vue'
import GameView from '../views/GameView.vue'
import Production from '../views/Production.vue'
import Sales from '../views/Sales.vue'
import HR from '../views/HR.vue'

const routes = [
  { 
    path: '/', 
    name: 'MainMenu', 
    component: MainMenu 
  },
  {
    path: '/game/:id',
    component: GameView,
    children: [
      { path: 'production', component: Production, alias: '' },
      { path: 'sales', component: Sales },
      { path: 'hr', component: HR }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router