/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const schoolYearRoutes = {
  path: '/schoolyear',
  component: Layout,
  redirect: '/schoolyear',
  meta: { title: 'schoolyears', icon: 'list', permissions: ['manage schoolyear'] },
  children: [
    {
      path: '',
      component: () => import('@/views/schoolyear/List'),
      name: 'SchoolYears',
      meta: { title: 'schoolyears', icon: 'user', noCache: true, permissions: ['manage schoolyear'] },
    },
    {
      hidden: true,
      path: 'new',
      component: () => import('@/views/schoolyear/New'),
      name: 'AddNewSchoolYear',
      meta: { title: 'newschoolyear', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/schoolyear/Edit'),
      name: 'EditSchoolYearDetails',
      meta: { title: 'editschoolyear', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'view/:id(\\d+)',
      component: () => import('@/views/schoolyear/SchoolYearDetails'),
      name: 'viewSchoolYearDetails',
      meta: { title: 'viewschoolyear', icon: 'user', noCache: true },
    },
  ],
};

export default schoolYearRoutes;
