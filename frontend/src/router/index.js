import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    component: () => import('@/layouts/DefaultLayout.vue'),
    children: [
      { path: '',               name: 'home',               component: () => import('@/pages/public/HomePage.vue') },
      { path: 'explore',        name: 'explore',            component: () => import('@/pages/public/ExplorePage.vue') },
      { path: 'items/:id',      name: 'item-detail',        component: () => import('@/pages/public/ItemDetailPage.vue') },
      { path: 'priority-documents', name: 'priority-docs',  component: () => import('@/pages/public/PriorityDocumentsPage.vue') },
      { path: 'about',          name: 'about',              component: () => import('@/pages/public/AboutPage.vue') },

      { path: 'dashboard',      name: 'dashboard',          component: () => import('@/pages/user/DashboardPage.vue'),       meta: { requiresAuth: true } },
      { path: 'report/found',   name: 'report-found',       component: () => import('@/pages/user/ReportFoundPage.vue'),     meta: { requiresAuth: true } },
      { path: 'report/lost',    name: 'report-lost',        component: () => import('@/pages/user/ReportLostPage.vue'),      meta: { requiresAuth: true } },
      { path: 'my-reports',     name: 'my-reports',         component: () => import('@/pages/user/MyReportsPage.vue'),       meta: { requiresAuth: true } },
      { path: 'my-reports/:id/edit', name: 'edit-report',   component: () => import('@/pages/user/EditReportPage.vue'),      meta: { requiresAuth: true } },
      { path: 'my-claims',      name: 'my-claims',          component: () => import('@/pages/user/MyClaimsPage.vue'),        meta: { requiresAuth: true } },
      { path: 'incoming-claims',name: 'incoming-claims',    component: () => import('@/pages/user/IncomingClaimsPage.vue'),  meta: { requiresAuth: true } },
      { path: 'notifications',  name: 'notifications',      component: () => import('@/pages/user/NotificationsPage.vue'),   meta: { requiresAuth: true } },
      { path: 'profile',        name: 'profile',            component: () => import('@/pages/user/ProfilePage.vue'),         meta: { requiresAuth: true } },
    ],
  },
  {
    path: '/',
    component: () => import('@/layouts/AuthLayout.vue'),
    children: [
      { path: 'login',    name: 'login',    component: () => import('@/pages/auth/LoginPage.vue'),    meta: { guestOnly: true } },
      { path: 'register', name: 'register', component: () => import('@/pages/auth/RegisterPage.vue'), meta: { guestOnly: true } },
    ],
  },
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      { path: '',            name: 'admin-dashboard',   component: () => import('@/pages/admin/AdminDashboardPage.vue') },
      { path: 'items',       name: 'admin-items',       component: () => import('@/pages/admin/AdminItemsPage.vue') },
      { path: 'claims',      name: 'admin-claims',      component: () => import('@/pages/admin/AdminClaimsPage.vue') },
      { path: 'users',       name: 'admin-users',       component: () => import('@/pages/admin/AdminUsersPage.vue') },
      { path: 'categories',  name: 'admin-categories',  component: () => import('@/pages/admin/AdminCategoriesPage.vue') },
    ],
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/pages/public/NotFoundPage.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()

  if (auth.token && !auth.user) {
    try { await auth.fetchProfile() } catch { auth.logout() }
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) return { name: 'login', query: { redirect: to.fullPath } }
  if (to.meta.guestOnly   && auth.isAuthenticated)   return { name: 'dashboard' }
  if (to.meta.requiresAdmin && !auth.isAdmin)         return { name: 'home' }
})

export default router
