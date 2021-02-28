<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        v-model="listQuery.title"
        :placeholder="$t('table.school_year_search')"
        style="width: 200px;"
        class="filter-item"
        @keyup.enter.native="getList"
      />
      <el-select v-model="listQuery.type" :placeholder="$t('table.school_year_status')" clearable style="width: 200px" class="filter-item">
        <el-option v-for="item in schoolYearStatus" :key="item" :label="item" :value="item" />
      </el-select>
      <el-button
        v-waves
        class="filter-item"
        type="primary"
        icon="el-icon-search"
        @click="getList"
      >{{ $t('table.search') }}</el-button>
      <router-link to="/schoolyear/new">
        <el-button
          v-permission="['manage schoolyear']"
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
        >{{ $t('table.add') }}</el-button>
      </router-link>
    </div>
    <el-table v-loading="loading" :data="list" border fit highlight-current-row>
      <el-table-column align="center" label="#" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Year">
        <template slot-scope="scope">
          <span>{{ scope.row.year }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Start">
        <template slot-scope="scope">
          <span>{{ scope.row.start }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="End">
        <template slot-scope="scope">
          <span>{{ scope.row.end }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.status')" class-name="status-col" width="100">
        <template slot-scope="{row}">
          <el-tag :type="row.status | statusFilter">
            {{ checkStatus(row.status) }}
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column label="Is Locked" class-name="status-col" width="100">
        <template slot-scope="{row}">
          <el-tag :type="row.is_locked | lockFilter">
            {{ checkLock(row.is_locked) }}
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="300">
        <template slot-scope="scope">
          <router-link :to="'/schoolyear/view/'+scope.row.id">
            <el-button
              v-permission="['manage schoolyear']"
              type="success"
              size="small"
              icon="el-icon-view"
              title="View"
            />
          </router-link>
          <router-link :to="'/schoolyear/edit/'+scope.row.id">
            <el-button
              v-permission="['manage schoolyear']"
              type="primary"
              size="small"
              icon="el-icon-edit"
              title="Edit"
            />
          </router-link>
          <el-button
            v-permission="['manage schoolyear']"
            type="danger"
            size="small"
            icon="el-icon-delete"
            title="Delete"
            @click="handleDelete(scope.row.id, scope.row.name);"
          />

          <el-button
            v-if="scope.row.status === 0"
            v-permission="['manage schoolyear']"
            type="success"
            size="small"
            icon="el-icon-switch-button"
            title="Activate"
            @click="handleActivate(scope.row.id, scope.row.name, 1);"
          />
          <el-button
            v-if="scope.row.status === 1"
            v-permission="['manage schoolyear']"
            type="warning"
            size="small"
            icon="el-icon-switch-button"
            title="Deactivate"
            @click="handleActivate(scope.row.id, scope.row.name, 0);"
          />
          <el-button
            v-if="scope.row.is_locked === 0"
            v-permission="['manage schoolyear']"
            type="warning"
            size="small"
            icon="el-icon-lock"
            title="Lock"
            @click="handleLock(scope.row.id, scope.row.name, 1, scope.row);"
          />
          <el-button
            v-if="scope.row.is_locked === 1"
            v-permission="['manage schoolyear']"
            type="success"
            size="small"
            icon="el-icon-lock"
            title="Unlock"
            @click="handleLock(scope.row.id, scope.row.name, 0, scope.row);"
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

const schoolYearResource = new Resource('schoolyears');
const lockSyResource = new Resource('lock-sy');
const statusSyResource = new Resource('status-sy');

export default {
  name: 'SchoolYearList',
  components: { Pagination },
  directives: { permission, waves },
  filters: {
    statusFilter(status) {
      if (status === 0){
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
      },
      total: 0,
      loading: true,
      feeFormVisible: false,
      newPaymentFormVisible: false,
      currentStudent: {},
      submitted: false,
      formTitle: '',
      downloading: false,
      /* Dialog */
      activeActivity: 'first',
      updating: false,
      studentValid: false,
      schoolYearStatus: ['All', 'Active', 'Inactive'],
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      const { limit, page } = this.listQuery;
      this.loading = true;
      const { data } = await schoolYearResource.list(this.listQuery);
      this.list = data.data;
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
      if (this.currentStudent.id !== undefined) {
        schoolYearResource
          .update(this.currentStudent.id, this.currentStudent)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Student info has been updated successfully',
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
        schoolYearResource
          .store(this.currentStudent)
          .then((response) => {
            this.$message({
              message:
                'New Student ' +
                this.currentStudent.name +
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
    handleLock(id, name, status, data) {
      var message = '';
      var stat = '';
      if (status === 1) {
        message = 'lock';
        stat = 'locked';
      } else {
        message = 'unlock';
        stat = 'unlocked';
      }
      this.$confirm(
        'This will ' + message + ' ' + name + '. Continue?',
        'Warning',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
        }
      )
        .then(() => {
          lockSyResource
            .update(id, { lock_status: status })
            .then((response) => {
              this.$message({
                message:
                  'School Year ' +
                  name +
                  ' has been ' + stat + ' successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.getList();
              this.loading = false;
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Delete canceled',
          });
        });
    },
    handleActivate(id, name, status, data) {
      var message = '';
      var stat = '';
      if (status === 0) {
        message = 'deactivate';
        stat = 'deactivated';
      } else {
        message = 'activate';
        stat = 'activated';
      }
      this.$confirm(
        'This will ' + message + ' ' + name + '. For activation, other school years will be deactivated. Continue?',
        'Warning',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
        }
      )
        .then(() => {
          statusSyResource
            .update(id, { active_status: status })
            .then((response) => {
              this.$message({
                message:
                  'School Year ' +
                  name +
                  ' has been ' + stat + ' successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.getList();
              this.loading = false;
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Delete canceled',
          });
        });
    },
    handleDelete(id, name) {
      this.$confirm(
        'This will delete ' + name + '. Continue?',
        'Warning',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
        }
      )
        .then(() => {
          schoolYearResource.destroy(id).then((response) => {
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
            message: 'Delete canceled',
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
      this.currentStudent = {
        name: '',
        description: '',
        tuition: '0',
        misc: '0',
      };
    },
    checkStatus(deleted_at) {
      if (deleted_at === 1){
        return 'Active';
      } else {
        return 'Inactive';
      }
    },
    checkLock(status) {
      if (status === 1){
        return 'Locked';
      } else {
        return 'Open';
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
