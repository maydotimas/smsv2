<template>
  <div class="createPost-container">
    <el-form
      ref="postForm"
      :model="sy"
      class="form-container"
      readonly
    >
      <div class="createPost-main-container">
        <el-card class="box-card student-bio">
          <div
            slot="header"
            class="clearfix"
          >
            <span>{{ sy.name }} Information</span>
          </div>
          <div class="postInfo-container">
            <el-row>
              <el-col :span="12">
                <el-form-item
                  label-width="120px"
                  label="Description:"
                  class="postInfo-container-item"
                />
                <el-form-item
                  :label="sy.description"
                  class="postInfo-container-item"
                />
              </el-col>
              <el-col :span="12">
                <el-form-item
                  label-width="120px"
                  label="Inclusive Years:"
                  class="postInfo-container-item"
                />
                <el-form-item
                  :label="'' + sy.year + ' to ' + (sy.year+1)"
                  class="postInfo-container-item"
                />
              </el-col>
              <el-col :span="12">
                <el-form-item
                  label-width="120px"
                  label="Start:"
                  class="postInfo-container-item"
                />
                <el-form-item
                  :label="sy.start"
                  class="postInfo-container-item"
                />
              </el-col>
              <el-col :span="12">
                <el-form-item
                  label-width="120px"
                  label="End:"
                  class="postInfo-container-item"
                />
                <el-form-item
                  :label="sy.end"
                  class="postInfo-container-item"
                />
              </el-col>
              <el-col :span="12">
                <el-form-item
                  label-width="120px"
                  label="Status:"
                  class="postInfo-container-item"
                />
                <el-form-item
                  :label="getStatus(sy.status)"
                  class="postInfo-container-item"
                />
              </el-col>
              <el-col :span="12">
                <el-form-item
                  label-width="120px"
                  label="Departments:"
                  class="postInfo-container-item"
                />
                <el-form-item
                  :label="getDepartmentsSy()"
                  class="postInfo-container-item"
                />
              </el-col>
            </el-row>
          </div>
        </el-card>
      </div>

      <div v-for="fee in department_fees" :key="fee.department_id" class="createPost-main-container">
        <el-card class="box-card student-bio">
          <div
            slot="header"
            class="clearfix"
          >
            <span>{{ fee.department_name }} Tuition Details</span>
          </div>
          <div class="postInfo-container">
            <el-table :data="fee.fees" style="width: 100%">
              <el-table-column
                prop="type"
                label=""
                width="150"
              />
              <el-table-column label="Tuition Info">
                <el-table-column
                  prop="total_tuition_fee"
                  label="Tuition Fee"
                  width="100"
                />
                <el-table-column
                  prop="total_misc_fee"
                  label="Misc Fee"
                  width="100"
                />
                <el-table-column label="Enrollment">
                  <el-table-column
                    prop="enrollment_tuition_fee"
                    label="Tuition Fee"
                    width="120"
                  />
                  <el-table-column
                    prop="enrollment_misc_fee"
                    label="Misc Fee"
                    width="120"
                  />
                </el-table-column>
                <el-table-column label="Installment">
                  <el-table-column
                    prop="monthly_tuition_fee"
                    label="Monthly TF"
                    width="120"
                  />
                  <el-table-column
                    prop="monthly_misc_fee"
                    label="Monthly Misc"
                    width="120"
                  />
                  <el-table-column
                    prop="months"
                    label="Payments"
                    width="120"
                  />
                </el-table-column>
              </el-table-column>
              <el-table-column
                prop="total"
                label="Total"
                width="150"
              />
            </el-table>
          </div>
        </el-card>
      </div>
    </el-form>
  </div>
</template>

<script>
import permission from '@/directive/permission';
import waves from '@/directive/waves';

import Resource from '@/api/resource';
const departmentResource = new Resource('departments');

export default {
  name: 'ViewSchoolYearDetailsPage',
  directives: { permission, waves },
  props: {
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
      school_year: {
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
      departments: [],
      department_fees: [],
    };
  },
  created() {
    this.setData();
    this.getDepartmentFees();
  },
  methods: {
    setData() {
      this.school_year = this.sy;
    },
    async getDepartmentFees() {
      const { data } = await departmentResource.list({});
      console.log(data);
      this.departments = data;
      var ctr = 0;
      var fee_ctr = 0;
      var is_found = false;
      var dept_fee = [];
      for (ctr = 0; ctr < data.length; ctr++) {
        // reset per dept
        dept_fee = [];
        is_found = false;
        for (fee_ctr = 0; fee_ctr < this.sy.department_fees.length; fee_ctr++) {
          if (this.departments[ctr].id === this.sy.department_fees[fee_ctr].department_id) {
            dept_fee.push(this.sy.department_fees[fee_ctr]);
            is_found = true;
          }
        }
        if (is_found === true) {
          this.setDepartmentFee([this.departments[ctr].id, this.departments[ctr].name], dept_fee);
        } else {
          this.setDepartmentFee([this.departments[ctr].id, this.departments[ctr].name], false);
        }
      }
      console.log(this.department_fees);
    },
    // call back in saving data
    setDepartmentFee(deparment, data) {
      var dept_id = deparment[0];
      var dept_name = deparment[1];
      var temp_dept_fee = [];
      if (data !== false){
        var department_fee = data;
        if (department_fee.length > 0) {
          department_fee.forEach(element => {
            if (element.type === 'ANNUAL') {
              element.enrollment_tuition_fee = element.total_tuition_fee;
              element.enrollment_misc_fee = element.total_misc_fee;
            }
            temp_dept_fee.push({
              department_id: element.department_id,
              grade_id: '',
              type: element.type,
              total_tuition_fee: element.total_tuition_fee.toFixed(2),
              total_misc_fee: element.total_misc_fee.toFixed(2),
              enrollment_tuition_fee: element.enrollment_tuition_fee.toFixed(2),
              enrollment_misc_fee: element.enrollment_misc_fee.toFixed(2),
              monthly_tuition_fee: element.monthly_tuition_fee.toFixed(2),
              monthly_misc_fee: element.monthly_misc_fee.toFixed(2),
              months: this.getMonths(element.type),
              total: this.getTotal(element),
            });
          });
          this.department_fees.push(
            {
              department_name: dept_name,
              department_id: dept_id,
              fees: temp_dept_fee,
            });
        }
      }
    },
    getStatus(status) {
      if (status === '1') {
        return 'Active';
      } else {
        return 'Inactive';
      }
    },
    getDepartmentsSy() {
      var dept_names = [];
      this.department_fees.forEach(element => {
        dept_names.push(element.department_name);
      });
      return dept_names.toString();
    },
    getMonths(type) {
      if (type === 'ANNUAL') {
        return '-';
      } else if (type === 'SEMESTRAL') {
        return 1;
      } else if (type === 'QUARTERLY') {
        return 3;
      } else if (type === 'MONTHLY') {
        return 8;
      }
    },
    getPayments(type) {
      if (type === 'ANNUAL') {
        return 0;
      } else if (type === 'SEMESTRAL') {
        return 1;
      } else if (type === 'QUARTERLY') {
        return 3;
      } else if (type === 'MONTHLY') {
        return 8;
      }
    },
    getTotal(fee) {
      var total = 0;
      if (fee.type === 'ANNUAL'){
        total = parseFloat(fee.total_tuition_fee) + parseFloat(fee.total_misc_fee);
      } else {
        total = parseFloat(fee.enrollment_tuition_fee + fee.enrollment_misc_fee) + parseFloat(parseFloat(fee.monthly_tuition_fee + fee.monthly_misc_fee) * parseFloat(this.getPayments(fee.type)));
      }
      return total.toFixed(2);
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import '~@/styles/mixin.scss';
.createPost-container {
  position: relative;
  .createPost-main-container {
    padding-top: 2%;
    padding-bottom: 2%;
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
