/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const reservationRoutes = {
  path: '/reservation',
  component: Layout,
  redirect: '/reservation',
  meta: {
    title: 'reservations',
    icon: 'user',
    permissions: ['view reservation'],
  },
  children: [
    {
      path: '',
      component: () => import('@/views/reservation/List'),
      name: 'Reservations',
      meta: { title: 'reservation', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'new',
      component: () => import('@/views/reservation/New'),
      name: 'AddNewReservation',
      meta: { title: 'newreservation', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/reservation/Edit'),
      name: 'EditReservation',
      meta: { title: 'editreservation', icon: 'user', noCache: true },
    },
    {
      hidden: true,
      path: 'view/:id(\\d+)',
      component: () => import('@/views/reservation/SelfProfile'),
      name: 'ViewReservationDetails',
      meta: { title: 'viewreservation', icon: 'user', noCache: true },
    },
  ],
};

export default reservationRoutes;
