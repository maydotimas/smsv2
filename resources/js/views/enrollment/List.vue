<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        v-model="listQuery.title"
        :placeholder="$t('table.enrollment_search')"
        style="width: 200px;"
        class="filter-item"
        @keyup.enter.native="getList"
      />
      <el-select v-model="listQuery.type" :placeholder="$t('table.enrollment_status')" clearable style="width: 200px" class="filter-item">
        <el-option v-for="item in enrollmentStatus" :key="item" :label="item" :value="item" />
      </el-select>
      <el-select v-model="listQuery.sy" :placeholder="$t('table.enrollment_sy')" clearable style="width: 200px" class="filter-item">
        <el-option label="All" value="All" />
        <el-option v-for="item in school_year" :key="item" :label="item.name" :value="item.id" />
      </el-select>
      <el-button
        v-waves
        class="filter-item"
        type="primary"
        icon="el-icon-search"
        @click="getList"
      >{{ $t('table.search') }}</el-button>
      <router-link to="/enrollment/new">
        <el-button
          v-permission="['manage enrollment']"
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
        >{{ $t('table.add') }}</el-button>
      </router-link>
      <el-button
        v-waves
        :loading="downloading"
        class="filter-item"
        type="primary"
        icon="el-icon-download"
        @click="handleDownload"
      >Export</el-button>
    </div>
    <el-table v-if="list" v-loading="loading" :data="list" border fit highlight-current-row>
      <el-table-column align="center" label="#" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Type">
        <template slot-scope="scope">
          <span>{{ scope.row.type }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Student No">
        <template slot-scope="scope">
          <span v-if="scope.row.type === 'OLD'">{{ scope.row.student.student_no }}</span>
          <span v-if="scope.row.type === 'NEW'">-</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Last Name">
        <template slot-scope="scope">
          <span>{{ scope.row.last_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="First Name">
        <template slot-scope="scope">
          <span>{{ scope.row.first_name }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.status')" class-name="status-col" width="120">
        <template slot-scope="{row}">
          <el-tag :type="row.deleted_at | statusFilter">
            {{ row.status }}
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" label="School Year">
        <template slot-scope="scope">
          <span>{{ scope.row.school_year.year }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="SY Status">
        <template slot-scope="scope">
          <el-tag :type="scope.row.school_year.is_locked | lockFilter">
            {{ isLocked(scope.row.school_year.is_locked) }}
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="200">
        <template slot-scope="scope">
          <router-link :to="'/reservation/view/'+scope.row.id">
            <el-button
              v-permission="['manage reservation']"
              type="success"
              size="small"
              icon="el-icon-view"
              title="View"
            />
          </router-link>
          <router-link v-if="scope.row.status==='RESERVED' && scope.row.school_year.is_locked===0" :to="'/reservation/edit/'+scope.row.id">
            <el-button
              v-permission="['manage reservation']"
              type="primary"
              size="small"
              icon="el-icon-edit"
              title="Edit"
            />
          </router-link>
          <el-button
            v-if="scope.row.status==='RESERVED' && scope.row.school_year.is_locked===0"
            v-permission="['manage reservation']"
            type="danger"
            size="small"
            icon="el-icon-delete"
            title="Delete"
            @click="handleDelete(scope.row.id, scope.row.first_name);"
          />
        </template>
      </el-table-column>
    </el-table>

    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getList"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import permission from '@/directive/permission';
import waves from '@/directive/waves';
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination

const reservationResource = new Resource('enrollments');
const schoolYearResource = new Resource('schoolyears');

export default {
  name: 'EnrollmentList',
  components: { Pagination },
  directives: { permission, waves },
  filters: {
    statusFilter(status) {
      if (status !== null){
        return 'danger';
      } else {
        return 'success';
      }
    },
    lockFilter(status) {
      if (status !== 0){
        return 'danger';
      } else {
        return 'success';
      }
    },
  },
  data() {
    return {
      list: [],
      listQuery: {
        page: 1,
        limit: 20,
        title: '',
        type: '',
        sy: '',
      },
      school_year: {},
      total: 0,
      loading: true,
      feeFormVisible: false,
      newPaymentFormVisible: false,
      currentreservation: {},
      submitted: false,
      formTitle: '',
      downloading: false,
      /* Dialog */
      activeActivity: 'first',
      updating: false,
      reservationValid: false,
      enrollmentStatus: ['All', 'Enrolled', 'Cancelled'],
    };
  },
  created() {
    this.getList();
    this.getSchoolYear();
  },
  methods: {
    isLocked(status){
      if (status === 0) {
        return 'OK';
      } else {
        return 'Locked';
      }
    },
    async getSchoolYear() {
      this.loading = true;
      const { data } = await schoolYearResource.list({});
      this.school_year = data.data;
      this.loading = false;
    },
    async getList() {
      const { limit, page } = this.listQuery;
      this.loading = true;
      const { data } = await reservationResource.list(this.listQuery);
      this.list = data.data;
      console.log('hello');
      console.log(this.list);
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = data.total;
      console.log(this.total);
      this.loading = false;
    },
    handleSubmit() {
      this.submitted = true;
      if (this.currentreservation.id !== undefined) {
        reservationResource
          .update(this.currentreservation.id, this.currentreservation)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'reservation info has been updated successfully',
              duration: 5 * 1000,
            });
            this.getList();
            this.submitted = false;
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => {
            this.feeFormVisible = false;
            this.submitted = false;
          });
      } else {
        reservationResource
          .store(this.currentreservation)
          .then((response) => {
            this.$message({
              message:
                'New reservation ' +
                this.currentreservation.name +
                ' has been created successfully.',
              type: 'success',
              duration: 5 * 1000,
            });
            this.resetForm();
            this.feeFormVisible = false;
            this.submitted = false;
            this.getList();
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    handleDelete(id, name) {
      this.$confirm(
        'This will cancel reservation for ' + name + '. Continue?',
        'Warning',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
        }
      )
        .then(() => {
          reservationResource.destroy(id).then((response) => {
            this.$message({
              type: 'success',
              message: 'Delete completed',
            });
          });
          this.getList();
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Delete cancelled',
          });
        });
    },
    handleDownload() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then((excel) => {
        const tHeader = ['id', 'user_id', 'name', 'email', 'role'];
        const filterVal = ['index', 'id', 'name', 'email', 'role'];
        const data = this.formatJson(filterVal, this.list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'user-list',
        });
        this.downloading = false;
      });
    },
    resetForm() {
      this.currentreservation = {
        name: '',
        description: '',
        tuition: '0',
        misc: '0',
      };
    },
    checkStatus(deleted_at) {
      if (deleted_at === null) {
        return 'Active';
      } else {
        return 'Deleted';
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.edit-input {
  padding-right: 100px;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
.dialog-body {
  padding: 0px 0px;
}
.dialog-footer {
  text-align: left;
  padding-top: 0;
  margin-left: 150px;
}
.app-container {
  flex: 1;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
  .block {
    float: left;
    min-width: 250px;
  }
  .clear-left {
    clear: left;
  }
}
</style>
