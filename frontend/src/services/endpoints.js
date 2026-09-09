import api from './api'

export const authService = {
  register: (data) => api.post('/auth/register', data),
  login:    (data) => api.post('/auth/login', data),
  logout:   ()     => api.post('/auth/logout'),
  me:       ()     => api.get('/auth/me'),
  update:   (data) => api.put('/auth/me', data),
}

export const itemService = {
  list:      (params) => api.get('/items', { params }),
  create:    (data)   => api.post('/items', data),
  show:      (id)     => api.get(`/items/${id}`),
  update:    (id, data) => api.put(`/items/${id}`, data),
  remove:    (id)     => api.delete(`/items/${id}`),
  myReports: (params) => api.get('/items/my-reports', { params }),
  close:     (id)     => api.patch(`/items/${id}/close`),
}

export const claimService = {
  submit:   (itemId, data) => api.post(`/items/${itemId}/claims`, data),
  myClaims: (params)       => api.get('/claims/my-claims', { params }),
  incoming: (params)       => api.get('/claims/incoming', { params }),
  approve:  (id, data)     => api.patch(`/claims/${id}/approve`, data),
  reject:   (id, data)     => api.patch(`/claims/${id}/reject`, data),
}

export const notificationService = {
  list:    (params) => api.get('/notifications', { params }),
  read:    (id)     => api.patch(`/notifications/${id}/read`),
  readAll: ()       => api.patch('/notifications/read-all'),
}

export const categoryService = {
  list: () => api.get('/categories'),
}

export const adminService = {
  stats:          ()           => api.get('/admin/stats'),
  listItems:      (params)     => api.get('/admin/items', { params }),
  updateItem:     (id, data)   => api.put(`/admin/items/${id}`, data),
  deleteItem:     (id)         => api.delete(`/admin/items/${id}`),
  listUsers:      (params)     => api.get('/admin/users', { params }),
  updateUser:     (id, data)   => api.put(`/admin/users/${id}`, data),
  deleteUser:     (id)         => api.delete(`/admin/users/${id}`),
  listClaims:     (params)     => api.get('/admin/claims', { params }),
  listCategories: ()           => api.get('/admin/categories'),
  createCategory: (data)       => api.post('/admin/categories', data),
  updateCategory: (id, data)   => api.put(`/admin/categories/${id}`, data),
  deleteCategory: (id)         => api.delete(`/admin/categories/${id}`),
}
