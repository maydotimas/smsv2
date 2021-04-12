<template>
  <div class="createPost-container">
    <el-form
      ref="postForm"
      :model="schoolyear"
      :rules="rules"
      class="form-container"
    >
      <sticky v-if="isEdit===false && created===false" :class-name="'sub-navbar active'">
        <el-button
          v-loading="loading"
          style="margin-left: 10px"
          type="success"
          @click="confirmation"
        >
          Save New School Year Record
        </el-button>
        <el-button v-loading="loading" type="warning" @click="confirmCancel">
          Cancel
        </el-button>
      </sticky>

      <sticky v-if="isEdit===true || created === true" :class-name="'sub-navbar active'">
        <el-button
          v-loading="loading"
          style="margin-left: 10px"
          type="success"
          @click="confirmation"
        >
          Update School Year Record
        </el-button>
        <el-button v-loading="loading" type="warning" @click="confirmCancel">
          Cancel
        </el-button>
      </sticky>

      <div class="createPost-main-container">
        <el-row>
          <el-col>
            <el-row>
              <el-card class="box-card user-bio" style="margin-top: 2%">
                <el-form-item
                  style="margin-bottom: 40px"
                  prop="name"
                >
                  <MDinput
                    v-model="schoolyear.name"
                    :maxlength="50"
                    name="name"
                    required
                  >
                    School Year Name
                  </MDinput>
                </el-form-item>
                <div class="postInfo-container">
                  <el-row>
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="School Year"
                        class="postInfo-container-item"
                        prop="year"
                      >
                        <el-select
                          v-model="schoolyear.year"
                          placeholder="Select Year"
                          @change="filterDates"
                        >
                          <el-option label="2020" value="2020" />
                          <el-option label="2021" value="2021" />
                          <el-option label="2022" value="2022" />
                          <el-option label="2023" value="2023" />
                          <el-option label="2024" value="2024" />
                          <el-option label="2025" value="2025" />
                          <el-option label="2026" value="2026" />
                          <el-option label="2027" value="2027" />
                          <el-option label="2028" value="2028" />
                          <el-option label="2029" value="2029" />
                          <el-option label="2030" value="2030" />
                          <el-option label="2031" value="2031" />
                          <el-option label="2032" value="2032" />
                          <el-option label="2033" value="2033" />
                          <el-option label="2034" value="2034" />
                          <el-option label="2035" value="2035" />
                        </el-select>
                      </el-form-item>
                    </el-col>
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Start"
                        class="postInfo-container-item"
                        prop="start"
                      >
                        <el-date-picker
                          v-model="schoolyear.start"
                          name="start"
                          type="date"
                          :picker-options="datePickerOptions"
                          placeholder="School Year Start"
                          style="width: 100%"
                          value-format="yyyy-MM-dd"
                          format="yyyy-MM-dd"
                          :default-value="start_date"
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="End"
                        class="postInfo-container-item"
                        prop="end"
                      >
                        <el-date-picker
                          v-model="schoolyear.end"
                          name="end"
                          placeholder="School Year Start"
                          style="width: 100%"
                          value-format="yyyy-MM-dd"
                          format="yyyy-MM-dd"
                          :default-value="end_date"
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="24">
                      <el-form-item
                        label-width="120px"
                        label="Description"
                        prop="description"
                      >
                        <el-input
                          v-model="schoolyear.description"
                          type="textarea"
                          name="description"
                          maxlength="500"
                          minlength="5"
                          placeholder="School Year Description"
                        />
                      </el-form-item>
                    </el-col>
                  </el-row>
                </div>
              </el-card>
            </el-row>
          </el-col>
          <el-col>
            <el-card
              class="box-card user-bio"
              style="margin-top: 2%; margin-bottom: 2%"
            >
              <el-row>
                <el-col>
                  <el-form-item
                    label="Configure Tuition Fee Per Department"
                    title="Please check all department's tuition fee configuration"
                    prop="fee"
                  >
                    <el-checkbox-group v-model="schoolyear.fee">
                      <el-checkbox v-for="department in departments" :key="department.id" :label="department.name" name="departments" />
                    </el-checkbox-group>
                  </el-form-item>
                </el-col>
              </el-row>
            </el-card>
            <el-row v-for="(fee, index) in departments" v-show="checkIfApplicable(fee.name)" :key="index">
              <el-card class="box-card user-bio">
                <div slot="header" class="clearfix">
                  <span>{{ fee.name }} Tuition Fee </span>
                </div>
                <div v-if="schoolyear.department_fees[index]" class="postInfo-container">
                  <!-- Annual -->
                  <el-col :span="24" style="margin-bottom: 2%">
                    <el-card class="box-card user-bio">
                      <div slot="header" class="clearfix text-center">
                        <span class="text-center">{{ schoolyear.department_fees[index].annual.type }}</span>
                      </div>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Tuition Fee"
                            prop="tuition_fee"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].annual.total_tuition_fee"
                              type="number"
                              name="tuition_fee"
                              maxlength="50"
                              min="0"
                              value="0"
                              required
                              @change="setTuition(schoolyear.department_fees[index])"
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Miscellaneous"
                            prop="misc_fee"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].annual.total_misc_fee"
                              type="number"
                              name="misc_fee"
                              maxlength="50"
                              min="0"
                              value="0"
                              required
                              @change="setMisc(schoolyear.department_fees[index])"
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                    </el-card>
                  </el-col>
                  <!-- Semestral -->
                  <el-col :span="7" style="margin-left: 2%;">
                    <el-card class="box-card user-bio">
                      <div slot="header" class="clearfix text-center">
                        <span class="text-center">{{ schoolyear.department_fees[index].semestral.type }}</span>
                      </div>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Tuition Fee"
                            prop="tuition_fee"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].semestral.total_tuition_fee"
                              type="number"
                              name="tuition_fee"
                              maxlength="50"
                              :min="schoolyear.department_fees[index].annual.total_tuition_fee"
                              value="0"
                              required
                              @change="setTFMonthly(schoolyear.department_fees[index].semestral,1)"
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Miscellaneous"
                            prop="misc_fee"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].semestral.total_misc_fee"
                              type="number"
                              name="misc_fee"
                              maxlength="50"
                              :min="schoolyear.department_fees[index].annual.total_misc_fee"
                              value="0"
                              required
                              @change="setMiscMonthly(schoolyear.department_fees[index].semestral,1)"
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Enrollment (TF)"
                            prop="semestral_enrollment_tf"
                            style="font-size:10px;"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].semestral.enrollment_tuition_fee"
                              type="number"
                              name="semestral_enrollment_tf"
                              maxlength="50"
                              :max="schoolyear.department_fees[index].semestral.total_tuition_fee"
                              min="0"
                              value="0"
                              required
                              @change="setTFMonthly(schoolyear.department_fees[index].semestral,1)"
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24" style="border-top: 1px;">
                          <el-form-item
                            label-width="140px"
                            label="Enrollment (Misc)"
                            prop="enrollment_misc"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].semestral.enrollment_misc_fee"
                              type="number"
                              name="semestral_enrollment_misc"
                              maxlength="50"
                              :max="schoolyear.department_fees[index].semestral.total_misc_fee"
                              min="0"
                              value="0"
                              required
                              @change="setMiscMonthly(schoolyear.department_fees[index].semestral,1)"
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Monthly (TF)"
                            prop="monthly_tf"
                            style="font-size:10px;"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].semestral.monthly_tuition_fee"
                              type="number"
                              name="monthly_tf"
                              maxlength="50"
                              min="0"
                              value="0"
                              required
                              readonly
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24" style="border-top: 1px;">
                          <el-form-item
                            label-width="140px"
                            label="Monthly (Misc)"
                            prop="monthly_misc"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].semestral.monthly_misc_fee"
                              type="number"
                              name="monthly_misc"
                              maxlength="50"
                              min="0"
                              value="0"
                              required
                              readonly
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                    </el-card>
                  </el-col>
                  <el-col :span="1">&nbsp;</el-col>
                  <!-- Quarterly -->
                  <el-col :span="7">
                    <el-card class="box-card user-bio">
                      <div slot="header" class="clearfix text-center">
                        <span class="text-center">{{ schoolyear.department_fees[index].quarterly.type }}</span>
                      </div>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Tuition Fee"
                            prop="tuition_fee"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].quarterly.total_tuition_fee"
                              type="number"
                              name="tuition_fee"
                              maxlength="50"
                              :min="schoolyear.department_fees[index].annual.total_tuition_fee"
                              value="0"
                              required
                              @change="setTFMonthly(schoolyear.department_fees[index].quarterly,3)"
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Miscellaneous"
                            prop="misc_fee"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].quarterly.total_misc_fee"
                              type="number"
                              name="misc_fee"
                              maxlength="50"
                              :min="schoolyear.department_fees[index].annual.total_misc_fee"
                              value="0"
                              required
                              @change="setMiscMonthly(schoolyear.department_fees[index].quarterly,3)"
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Enrollment (TF)"
                            prop="quarterly_enrollment_tf"
                            style="font-size:10px;"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].quarterly.enrollment_tuition_fee"
                              type="number"
                              name="quarterly_enrollment_tf"
                              maxlength="50"
                              :max="schoolyear.department_fees[index].quarterly.total_tuition_fee"
                              min="0"
                              value="0"
                              required
                              @change="setTFMonthly(schoolyear.department_fees[index].quarterly,3)"
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24" style="border-top: 1px;">
                          <el-form-item
                            label-width="140px"
                            label="Enrollment (Misc)"
                            prop="enrollment_misc"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].quarterly.enrollment_misc_fee"
                              type="number"
                              name="quarterly_enrollment_misc"
                              maxlength="50"
                              :max="schoolyear.department_fees[index].quarterly.total_misc_fee"
                              min="0"
                              value="0"
                              required
                              @change="setMiscMonthly(schoolyear.department_fees[index].quarterly,3)"
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Monthly (TF)"
                            prop="monthly_tf"
                            style="font-size:10px;"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].quarterly.monthly_tuition_fee"
                              type="number"
                              name="monthly_tf"
                              maxlength="50"
                              min="0"
                              value="0"
                              required
                              readonly
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24" style="border-top: 1px;">
                          <el-form-item
                            label-width="140px"
                            label="Monthly (Misc)"
                            prop="monthly_misc"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].quarterly.monthly_misc_fee"
                              type="number"
                              name="monthly_misc"
                              maxlength="50"
                              min="0"
                              value="0"
                              required
                              readonly
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                    </el-card>
                  </el-col>
                  <el-col :span="1">&nbsp;</el-col>
                  <!-- Monthly -->
                  <el-col :span="7">
                    <el-card class="box-card user-bio">
                      <div slot="header" class="clearfix text-center">
                        <span class="text-center">{{ schoolyear.department_fees[index].monthly.type }}</span>
                      </div>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Tuition Fee"
                            prop="tuition_fee"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].monthly.total_tuition_fee"
                              type="number"
                              name="tuition_fee"
                              maxlength="50"
                              :min="schoolyear.department_fees[index].annual.total_tuition_fee"
                              value="0"
                              required
                              @change="setTFMonthly(schoolyear.department_fees[index].monthly,8)"
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Miscellaneous"
                            prop="misc_fee"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].monthly.total_misc_fee"
                              type="number"
                              name="misc_fee"
                              maxlength="50"
                              :min="schoolyear.department_fees[index].annual.total_misc_fee"
                              value="0"
                              required
                              @change="setMiscMonthly(schoolyear.department_fees[index].monthly,8)"
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Enrollment (TF)"
                            prop="quarterly_enrollment_tf"
                            style="font-size:10px;"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].monthly.enrollment_tuition_fee"
                              type="number"
                              name="quarterly_enrollment_tf"
                              maxlength="50"
                              :max="schoolyear.department_fees[index].monthly.total_tuition_fee"
                              min="0"
                              value="0"
                              required
                              @change="setTFMonthly(schoolyear.department_fees[index].monthly,8)"
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24" style="border-top: 1px;">
                          <el-form-item
                            label-width="140px"
                            label="Enrollment (Misc)"
                            prop="enrollment_misc"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].monthly.enrollment_misc_fee"
                              type="number"
                              name="quarterly_enrollment_misc"
                              maxlength="50"
                              :max="schoolyear.department_fees[index].monthly.total_misc_fee"
                              min="0"
                              value="0"
                              required
                              @change="setMiscMonthly(schoolyear.department_fees[index].monthly,8)"
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                      <el-row>
                        <el-col :span="24">
                          <el-form-item
                            label-width="140px"
                            label="Monthly (TF)"
                            prop="monthly_tf"
                            style="font-size:10px;"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].monthly.monthly_tuition_fee"
                              type="number"
                              name="monthly_tf"
                              maxlength="50"
                              min="0"
                              value="0"
                              required
                              readonly
                            />
                          </el-form-item>
                        </el-col>
                        <el-col :span="24" style="border-top: 1px;">
                          <el-form-item
                            label-width="140px"
                            label="Monthly (Misc)"
                            prop="monthly_misc"
                          >
                            <el-input
                              v-model="schoolyear.department_fees[index].monthly.monthly_misc_fee"
                              type="number"
                              name="monthly_misc"
                              maxlength="50"
                              min="0"
                              value="0"
                              required
                              readonly
                            />
                          </el-form-item>
                        </el-col>
                      </el-row>
                    </el-card>
                  </el-col>
                </div>
              </el-card>
              <br>
            </el-row>
          </el-col>
        </el-row>
      </div>
    </el-form>
  </div>
</template>

<script>
import Sticky from '@/components/Sticky'; // Sticky header
import MDinput from '@/components/MDinput';

import Resource from '@/api/resource';
import permission from '@/directive/permission';
import waves from '@/directive/waves';

const schoolYearResource = new Resource('schoolyears');
const departmentResource = new Resource('departments');

const defaultForm = {
  schoolyear: {
    id: '',
    name: '',
    description: '',
    start: '',
    end: '',
    year: '',
    fee: [],
    department_fees: [],
    family: [],
  },
};

export default {
  name: 'NewSchoolYear',
  components: {
    Sticky,
    // Pagination,
    MDinput,
  },
  directives: { permission, waves },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
    sy: {
      type: Object,
      default: () => {
        return {
          id: '',
          name: '',
          description: '',
          start: '',
          end: '',
          year: '',
          fee: [],
          department_fees: [],
          family: [],
        };
      },
    },
  },
  data() {
    return {
      formTitle: 'Confirm School Year Details',
      hasStudentNoError: false,
      schoolyear: Object.assign({}, defaultForm.schoolyear),
      year_start: 2020,
      year_end: 2026,
      departments: [],
      loading: false,
      start_date: '',
      end_date: '',
      created: false,
      datePickerOptions: {
        // disabledDate(date) {
        //   return date < new Date();
        // },
      },
      rules: {
        name: [
          { required: true, message: 'Please enter school year name.', trigger: 'blur' },
          { required: true, trigger: 'change' },
        ],
        year: [
          { required: true, message: 'Please select year.', trigger: 'blur' },
          { required: true, trigger: 'change' },
        ],
        start: [
          { required: true, message: 'Please select start date.', trigger: 'blur' },
          { required: true, trigger: 'change' },
        ],
        end: [
          { required: true, message: 'Please select end date.', trigger: 'blur' },
          { required: true, trigger: 'change' },
        ],
        description: [
          { required: true, message: 'Please input school year description.', trigger: 'blur' },
          { required: true, trigger: 'change' },
        ],
        fee: [
          { type: 'array', required: true, message: 'Please select at least one department.', trigger: 'blur' },
          { type: 'array', required: true, message: 'Please select at least one department.', trigger: 'change' },
        ],
      },
    };
  },
  created(){
    this.clearData();
    this.getDepartments();
    this.startDate();
    this.endDate();
  },
  methods: {
    clearData() {
      this.schoolyear = {
        id: '',
        name: '',
        description: '',
        start: '',
        end: '',
        year: '',
        fee: [],
        department_fees: [],
        family: [],
      };
    },
    async getDepartments() {
      const { data } = await departmentResource.list({});
      // console.log(data);
      this.departments = data;
      this.checkIfEdit();
      var x = 0;
      for (x = 0; x < data.length; x++) {
        this.getDepartmentFee(data[x].id).then(this.setDepartmentFee.bind(null, [data[x].id, data[x].name]));
      }
    },
    // call back in saving data
    setDepartmentFee(deparment, data) {
      var dept_id = deparment[0];
      var dept_name = deparment[1];
      if (data !== false){
        var department_fee = data;
        console.log(dept_name);
        console.log(data);
        if (department_fee.length > 0){
          this.schoolyear.fee.push(dept_name);
          this.schoolyear.department_fees.push(
            {
              department_id: dept_id,
              annual: {
                department_id: department_fee[0].department_id,
                grade_id: '',
                type: department_fee[0].type,
                total_tuition_fee: department_fee[0].total_tuition_fee,
                total_misc_fee: department_fee[0].total_misc_fee,
                enrollment_tuition_fee: department_fee[0].enrollment_tuition_fee,
                enrollment_misc_fee: department_fee[0].enrollment_misc_fee,
                monthly_tuition_fee: department_fee[0].monthly_tuition_fee,
                monthly_misc_fee: department_fee[0].monthly_misc_fee,
              },
              semestral: {
                department_id: department_fee[1].department_id,
                grade_id: '',
                type: department_fee[1].type,
                total_tuition_fee: department_fee[1].total_tuition_fee,
                total_misc_fee: department_fee[1].total_misc_fee,
                enrollment_tuition_fee: department_fee[1].enrollment_tuition_fee,
                enrollment_misc_fee: department_fee[1].enrollment_misc_fee,
                monthly_tuition_fee: department_fee[1].monthly_tuition_fee,
                monthly_misc_fee: department_fee[1].monthly_misc_fee,
              },
              quarterly: {
                department_id: department_fee[2].department_id,
                grade_id: '',
                type: department_fee[2].type,
                total_tuition_fee: department_fee[2].total_tuition_fee,
                total_misc_fee: department_fee[2].total_misc_fee,
                enrollment_tuition_fee: department_fee[2].enrollment_tuition_fee,
                enrollment_misc_fee: department_fee[2].enrollment_misc_fee,
                monthly_tuition_fee: department_fee[2].monthly_tuition_fee,
                monthly_misc_fee: department_fee[2].monthly_misc_fee,
              },
              monthly: {
                department_id: department_fee[3].department_id,
                grade_id: '',
                type: department_fee[3].type,
                total_tuition_fee: department_fee[3].total_tuition_fee,
                total_misc_fee: department_fee[3].total_misc_fee,
                enrollment_tuition_fee: department_fee[3].enrollment_tuition_fee,
                enrollment_misc_fee: department_fee[3].enrollment_misc_fee,
                monthly_tuition_fee: department_fee[3].monthly_tuition_fee,
                monthly_misc_fee: department_fee[3].monthly_misc_fee,
              },
            }
          );
        } else {
          this.schoolyear.department_fees.push(
            {
              department_id: dept_id,
              annual: {
                department_id: dept_id,
                grade_id: '',
                type: 'ANNUAL',
                total_tuition_fee: '0',
                total_misc_fee: '0',
                enrollment_tuition_fee: '0',
                enrollment_misc_fee: '0',
                monthly_tuition_fee: '0',
                monthly_misc_fee: '0',
              },
              semestral: {
                department_id: dept_id,
                grade_id: '',
                type: 'SEMESTRAL',
                total_tuition_fee: '0',
                total_misc_fee: '0',
                enrollment_tuition_fee: '0',
                enrollment_misc_fee: '0',
                monthly_tuition_fee: '0',
                monthly_misc_fee: '0',
              },
              quarterly: {
                department_id: dept_id,
                grade_id: '',
                type: 'QUARTERLY',
                total_tuition_fee: '0',
                total_misc_fee: '0',
                enrollment_tuition_fee: '0',
                enrollment_misc_fee: '0',
                monthly_tuition_fee: '0',
                monthly_misc_fee: '0',
              },
              monthly: {
                department_id: dept_id,
                grade_id: '',
                type: 'MONTHLY',
                total_tuition_fee: '0',
                total_misc_fee: '0',
                enrollment_tuition_fee: '0',
                enrollment_misc_fee: '0',
                monthly_tuition_fee: '0',
                monthly_misc_fee: '0',
              },
            }
          );
        }
      } else {
        this.schoolyear.department_fees.push(
          {
            department_id: dept_id,
            annual: {
              department_id: dept_id,
              grade_id: '',
              type: 'ANNUAL',
              total_tuition_fee: '0',
              total_misc_fee: '0',
              enrollment_tuition_fee: '0',
              enrollment_misc_fee: '0',
              monthly_tuition_fee: '0',
              monthly_misc_fee: '0',
            },
            semestral: {
              department_id: dept_id,
              grade_id: '',
              type: 'SEMESTRAL',
              total_tuition_fee: '0',
              total_misc_fee: '0',
              enrollment_tuition_fee: '0',
              enrollment_misc_fee: '0',
              monthly_tuition_fee: '0',
              monthly_misc_fee: '0',
            },
            quarterly: {
              department_id: dept_id,
              grade_id: '',
              type: 'QUARTERLY',
              total_tuition_fee: '0',
              total_misc_fee: '0',
              enrollment_tuition_fee: '0',
              enrollment_misc_fee: '0',
              monthly_tuition_fee: '0',
              monthly_misc_fee: '0',
            },
            monthly: {
              department_id: dept_id,
              grade_id: '',
              type: 'MONTHLY',
              total_tuition_fee: '0',
              total_misc_fee: '0',
              enrollment_tuition_fee: '0',
              enrollment_misc_fee: '0',
              monthly_tuition_fee: '0',
              monthly_misc_fee: '0',
            },
          }
        );
      }
    },
    setTuition(fee_fee){
      fee_fee.semestral.total_tuition_fee = parseFloat(fee_fee.annual.total_tuition_fee);
      fee_fee.quarterly.total_tuition_fee = parseFloat(fee_fee.annual.total_tuition_fee);
      fee_fee.monthly.total_tuition_fee = parseFloat(fee_fee.annual.total_tuition_fee);
      this.setTFMonthly(fee_fee.semestral, 1);
      this.setTFMonthly(fee_fee.quarterly, 3);
      this.setTFMonthly(fee_fee.monthly, 8);
    },
    setMisc(fee_fee){
      fee_fee.semestral.total_misc_fee = parseFloat(fee_fee.annual.total_misc_fee);
      fee_fee.quarterly.total_misc_fee = parseFloat(fee_fee.annual.total_misc_fee);
      fee_fee.monthly.total_misc_fee = parseFloat(fee_fee.annual.total_misc_fee);
      this.setMiscMonthly(fee_fee.semestral, 1);
      this.setMiscMonthly(fee_fee.quarterly, 3);
      this.setMiscMonthly(fee_fee.monthly, 8);
    },
    setTFMonthly(fee_fee, months){
      if (parseFloat(fee_fee.enrollment_tuition_fee) > parseFloat(fee_fee.total_tuition_fee)){
        fee_fee.enrollment_tuition_fee = fee_fee.total_tuition_fee;
        fee_fee.monthly_tuition_fee = 0;
      } else {
        fee_fee.monthly_tuition_fee = parseFloat(parseFloat(fee_fee.total_tuition_fee) - parseFloat(fee_fee.enrollment_tuition_fee)) / months;
      }
    },
    setMiscMonthly(fee_fee, months){
      if (parseFloat(fee_fee.enrollment_misc_fee) > parseFloat(fee_fee.total_misc_fee)){
        fee_fee.enrollment_misc_fee = fee_fee.total_misc_fee;
        fee_fee.monthly_misc_fee = 0;
      } else {
        fee_fee.monthly_misc_fee = parseFloat(parseFloat(fee_fee.total_misc_fee) - parseFloat(fee_fee.enrollment_misc_fee)) / months;
      }
    },
    // test
    check(){
      // console.log(this.schoolyear.department_fees);
    },
    // check to display family information
    checkIfApplicable(type) {
      if (type === 'None' && this.schoolyear.fee.length === 0) {
        return true;
      }
      return this.schoolyear.fee.includes(type);
    },
    // cancel checker
    confirmCancel() {
      this.$confirm(
        'Changes will be discarded. Continue?',
        'Warning',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
        }
      )
        .then(() => {
          this.$router.go(-1);
        });
    },
    filterDates(){
      this.startDate();
      this.endDate();
    },
    startDate(){
      var x = '';
      if (this.schoolyear.year === '') {
        var d = new Date();
        var n = d.getFullYear();
        x = n + '-09-01';
        this.schoolyear.start = '';
      } else {
        x = this.schoolyear.year + '-09-01';
        this.schoolyear.start = x;
      }
      this.start_date = x;
    },
    endDate(){
      var x = '';
      if (this.schoolyear.year === '') {
        var d = new Date();
        var n = d.getFullYear();
        x = (n + 1) + '-05-01';
        this.schoolyear.end = '';
      } else {
        x = (parseInt(this.schoolyear.year) + 1) + '-05-01';
        this.schoolyear.end = x;
      }
      this.end_date = x;
    },
    /* load edit data to form data */
    checkIfEdit(){
      // console.log('sy edit data');
      // console.log(this.sy);
      if (this.isEdit === true){
        this.schoolyear.id = this.sy.id;
        this.schoolyear.name = this.sy.name;
        this.schoolyear.start = this.sy.start;
        this.schoolyear.end = this.sy.end;
        this.schoolyear.description = this.sy.description;
        this.schoolyear.year = this.sy.year;
      }
    },
    /* Check for duplicate school year details */
    async getDepartmentFee(department_id) {
      if (this.isEdit === true){
        const { data } = await schoolYearResource.list({
          school_year_id: this.sy.id,
          department: department_id,
        });
        return data;
      }
      return false;
    },
    /* Check for duplicate school year details */
    async checkDuplicateSchoolYear() {
      const { data } = await schoolYearResource.list({
        year: this.schoolyear.year,
        is_edit: this.isEdit || this.created,
        id: this.schoolyear.id,
      });
      if (data.length > 0) {
        return true;
      } else {
        return false;
      }
    },
    async checkDuplicateSchoolName() {
      const { data } = await schoolYearResource.list({
        name: this.schoolyear.name,
        is_edit: this.isEdit || this.created,
        id: this.schoolyear.id,
      });
      if (data.length > 0) {
        return true;
      } else {
        return false;
      }
    },
    /* Update */
    confirmSave(text, data){
      console.log(this.schoolyear);
      if (data) {
        this.$message({
          message: 'School Year Name already exists.',
          type: 'error',
        });
        this.hasDuplicate = true;
        // hide dialog view
        this.confirmDialogView = false;
      } else {
        this.hasDuplicate = false;
        this.$refs.postForm.validate((valid) => {
          if (valid) {
            alert(this.isEdit);
            alert(this.created);
            if (this.isEdit === false && this.created === false){
              this.$confirm(
                'Do you want to create ' + this.schoolyear.year + "'s record?",
                'Confirm Save School Year Details',
                {
                  confirmButtonText: 'OK',
                  cancelButtonText: 'Cancel',
                  center: true,
                }
              )
                .then(() => {
                  schoolYearResource
                    .store(this.schoolyear)
                    .then((response) => {
                      this.$message({
                        message:
                          'New School Year ' +
                          this.schoolyear.year +
                          ' / ' +
                          this.schoolyear.name +
                          ' has been created successfully.',
                        type: 'success',
                        duration: 5 * 1000,
                      });
                      this.schoolyear.id = response.data.id;
                      this.created = true;
                      this.loading = false;
                    })
                    .catch((error) => {
                      console.log(error);
                    });
                })
                .catch(() => {
                  this.$message({
                    type: 'info',
                    message: 'Create cancelled',
                  });
                });
            } else {
              this.$confirm(
                'Do you want to update ' + this.schoolyear.year + "'s record?",
                'Confirm Update School Year Details',
                {
                  confirmButtonText: 'OK',
                  cancelButtonText: 'Cancel',
                  center: true,
                }
              )
                .then(() => {
                  schoolYearResource
                    .update(this.schoolyear.id, this.schoolyear)
                    .then((response) => {
                      this.$message({
                        message:
                          'School Year ' +
                          this.schoolyear.year +
                          ' / ' +
                          this.schoolyear.name +
                          ' has been updated successfully.',
                        type: 'success',
                        duration: 5 * 1000,
                      });
                      // console.log(response);
                      this.loading = false;
                    })
                    .catch((error) => {
                      console.log(error);
                    });
                })
                .catch(() => {
                  this.$message({
                    type: 'info',
                    message: 'Update cancelled',
                  });
                });
            }
          } else {
            // console.log('error submit!!');
            return false;
          }
        });
      }
    },
    // call back in saving data
    doWork(text, data) {
      if (data) {
        this.$message({
          message: 'School Year already exists.',
          type: 'error',
        });
        this.hasDuplicate = true;
        // hide dialog view
        this.confirmDialogView = false;
      } else {
        this.checkDuplicateSchoolName().then(this.confirmSave.bind(null, 'text'));
      }
    },
    // proceed with saving or updating
    confirmation() {
      if (this.schoolyear.name === '') {
        this.$message({
          message: 'School Year Name is required.',
          type: 'error',
        });
        return;
      } else {
        this.checkDuplicateSchoolYear().then(this.doWork.bind(null, 'text'));
      }
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import '~@/styles/mixin.scss';
.createPost-container {
  position: relative;
  .createPost-main-container {
    padding: 0 45px 20px 50px;
    .postInfo-container {
      position: relative;
      @include clearfix;
      margin-bottom: 10px;
      .postInfo-container-item {
        float: left;
      }
    }
  }
  .word-counter {
    width: 40px;
    position: absolute;
    right: -10px;
    top: 0px;
  }
}
</style>
<style>
.createPost-container label.el-form-item__label {
  text-align: left;
}
</style>
