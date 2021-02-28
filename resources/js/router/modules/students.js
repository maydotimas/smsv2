/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const studentRoutes = {
  path: '/student',
  component: Layout,
  redirect: '/student',
  meta: {
    title: 'students',
    icon: 'user',
    permissions: ['view student'],
  },
  children: [
    {
      path: '',
      component: () => import('@/views/students/List'),
      name: 'Students',
      meta: { title: 'students', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'new',
      component: () => import('@/views/students/New'),
      name: 'AddNewStudent',
      meta: { title: 'newstudent', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/students/Edit'),
      name: 'EditStudent',
      meta: { title: 'editstudent', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'view/:id(\\d+)',
      component: () => import('@/views/students/SelfProfile'),
      name: 'ViewStudentDetails',
      meta: { title: 'viewstudent', icon: 'user', noCache: true },
    },
  ],
};

export default studentRoutes;
