/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const enrollmentRoutes = {
  path: '/enrollment',
  component: Layout,
  redirect: '/enrollment',
  meta: {
    title: 'enrollments',
    icon: 'user',
    permissions: ['view enrollment'],
  },
  children: [
    {
      path: '',
      component: () => import('@/views/enrollment/List'),
      name: 'Enrollments',
      meta: { title: 'enrollment', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'new',
      component: () => import('@/views/enrollment/New'),
      name: 'AddNewEnrollment',
      meta: { title: 'newenrollment', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/enrollment/Edit'),
      name: 'EditEnrollment',
      meta: { title: 'editenrollment', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'view/:id(\\d+)',
      component: () => import('@/views/enrollment/SelfProfile'),
      name: 'ViewEnrollmentDetails',
      meta: { title: 'viewenrollment', icon: 'user', noCache: true },
    },
  ],
};

export default enrollmentRoutes;
