<template>
  <div>
    <sticky :class-name="'sub-navbar active'">
      <router-link v-if="reservation.status === 'RESERVED' && reservation.school_year.is_locked === 0" :to="'/reservation/edit/'+reservation.id">
        <el-button
          v-loading="loading"
          style="margin-left: 10px"
          type="success"
        >
          Edit Reservation
        </el-button>
      </router-link>
      <el-button v-loading="loading" type="warning" @click="back">
        Back
      </el-button>
    </sticky>
    <div class="app-container">
      <el-form v-if="is_loaded" :model="user">
        <el-row>
          <el-col :span="6">
            <user-card :user="user" :reservation="reservation" />
            <user-bio :user="user" :reservation="reservation" />
          </el-col>
          <el-col :span="18">
            <user-activity :user="user" :reservation="reservation" />
          </el-col>
        </el-row>
      </el-form>
    </div>
  </div>
</template>

<script>
import Sticky from '@/components/Sticky'; // Sticky header
import UserBio from './components/UserBio';
import UserCard from './components/UserCard';
import UserActivity from './components/UserActivity';
import Resource from '@/api/resource';

const reservationResource = new Resource('reservations');
const studentResource = new Resource('students');

export default {
  name: 'StudentProfile',
  components: { Sticky, UserBio, UserCard, UserActivity },
  data() {
    return {
      reservation: false,
      student_id: false,
      user: {},
      is_loaded: false,
      loading: false,
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
      this.reservation.reservation_student = {
        first_name: this.reservation.first_name,
        middle_name: this.reservation.middle_name,
        last_name: this.reservation.last_name,
        birthdate: this.reservation.birthdate,
        mobile: this.reservation.mobile,
        avatar: 'uploads\\default.png',
      };
    },
    async getUser() {
      const data = await studentResource.list({ id: this.student_id });
      if (data.length > 0) {
        this.user = data.data[0];
      }
      this.is_loaded = true;
    },
    back(){
      this.$router.push('/reservation');
    },
  },
};
</script>
