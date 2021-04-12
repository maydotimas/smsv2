<template>
  <new-enrollment v-if="user.first_name" :is-edit="true" :edit-reservation="reservation" :user="user" />
</template>

<script>
import Resource from '@/api/resource';
import NewEnrollment from './components/NewEnrollment.vue';

const reservationResource = new Resource('reservations');
const studentResource = new Resource('students');

export default {
  name: 'EditReservation',
  components: { NewEnrollment },
  data(){
    return {
      reservation: {},
      user: {},
      student_id: false,
    };
  },
  created() {
    const id = this.$route.params && this.$route.params.id;
    this.getReservation(id).then(this.getUser.bind(null, 'text'));
  },
  methods: {
    async getReservation(reservation_id) {
      const data = await reservationResource.list({ id: reservation_id });
      this.reservation = data.data[0];
      this.student_id = this.reservation.student_id;
      if (this.reservation.status === 'ENROLLED') {
        this.$alert('Student already enrolled. Cannot edit record', 'Reservation Error', {
          confirmButtonText: 'OK',
          callback: action => {
            this.$message({
              type: 'info',
              message: `Redirecting to Reservation.`,
            });
            this.$router.push('/reservation');
          },
        });
      }
    },
    async getUser() {
      if (this.reservation.type === 'OLD') {
        const data = await studentResource.list({ id: this.student_id });
        this.user = data.data[0];
      } else {
        this.user = {
          first_name: this.reservation.first_name,
          middle_name: this.reservation.middle_name,
          last_name: this.reservation.last_name,
          birthdate: this.reservation.birthdate,
          mobile: this.reservation.mobile,
        };
      }
      this.is_loaded = true;
    },
  },
};
</script>

