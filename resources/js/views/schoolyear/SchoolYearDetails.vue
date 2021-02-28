<template>
  <div>
    <sticky :class-name="'sub-navbar active'">
      <router-link :to="'/schoolyear/edit/'+school_year.id">
        <el-button
          style="margin-left: 10px"
          type="success"
        >
          Edit School Year
        </el-button>
      </router-link>
    </sticky>
    <div class="app-container">
      <el-form v-if="school_year" :model="school_year">
        <el-row :gutter="20"><!--
          <el-col :span="8">
            <school-year-card :sy="school_year" />
            <school-year-data :sy="school_year" />
          </el-col> -->
          <el-col :span="24">
            <view-school-year-details :sy="school_year" />
          </el-col>
        </el-row>
      </el-form>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import ViewSchoolYearDetails from './components/ViewSchoolYearDetails.vue';
// import SchoolYearCard from './components/SchoolYearCard.vue';
// import SchoolYearData from './components/SchoolYearData.vue';
const schoolYearResource = new Resource('schoolyears');
import Sticky from '@/components/Sticky'; // Sticky header

export default {
  name: 'SchoolYearDetailsPage',
  // components: { ViewSchoolYearDetails, SchoolYearCard, SchoolYearData },
  components: { ViewSchoolYearDetails, Sticky },
  data() {
    return {
      school_year: {
      },
    };
  },
  created() {
    const id = this.$route.params && this.$route.params.id;
    this.getUser(id);
  },
  methods: {
    async getUser(school_year_id) {
      const data = await schoolYearResource.list({ id: school_year_id });
      this.school_year = data.data[0];
      console.log(this.school_year);
    },
  },
};
</script>
