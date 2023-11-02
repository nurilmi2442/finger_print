<template>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item" v-if="user">
        <span class="navbar-text mr-3" v-if="user">
            welcome {{user.name}}
        </span>
        <inertia-link :href="$route('logout')" as="button" method="get" class="nav-link logout-link" style="display: inline;background: #343a40" type="button">Logout</inertia-link>
    </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li> -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="position: fixed;">
    <!-- Brand Logo -->
    <div class="brand-link text-center">
      <span class="brand-text font-weight-light text" style="font-size: 25px;"><b style="color: #007bff;">IMS </b>ATTENDANCE</span>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a :href="$route('so.index')" class="nav-link" :class="$route().current('so.index') ? 'active' : ''">
            <i class="fa-solid fa-house " style="padding-right:5px;"></i>
              <p> Home </p>
            </a>
          </li>

           <li class="nav-item" :class="$route().current().indexOf('device.') >= 0 ? 'menu-open' : ''">
            <a href="#" class="nav-link" :class="$route().current().indexOf('device.') >= 0 ? 'active' : ''">
                <i class="fa-solid fa-laptop" style="padding-right:5px; "></i>
              <p>
                Device
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

             <ul class="nav nav-treeview">
                 <li class="nav-item">
                <a :href="$route('device.pageSites')" class="nav-link" :class="$route().current('device.pageSites') ? 'active' : ''">
                <i class="fa-solid fa-location-dot" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>    Sites </p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('device.pageDatamesin')" class="nav-link" :class="$route().current('device.pageDatamesin') ? 'active' : ''">
                <i class="fa-solid fa-laptop" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>  Data Machine </p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('device.pageLog')" class="nav-link" :class="$route().current('device.pageLog') ? 'active' : ''">
                <i class="fa-solid fa-file" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>  Log </p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('device.pageDeviceCmd')" class="nav-link" :class="$route().current('device.pageDeviceCmd') ? 'active' : ''">
                <i class="fa-solid fa-file" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>  DeviceCmd </p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('device.pageCreateCmd')" class="nav-link" :class="$route().current('device.pageCreateCmd') ? 'active' : ''">
                <i class="fa-solid fa-file" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>  CreateCmd </p>
                </a>
              </li>
             </ul>
          </li>
          <li class="nav-item" :class="$route().current().indexOf('finger.') >= 0 ? 'menu-open' : ''">
            <a href="#" class="nav-link" :class="$route().current().indexOf('finger.') >= 0 ? 'active' : ''">
                <i class="fa-solid fa-hard-drive" style="padding-right:5px; "></i>
              <p>
                Master
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item">
                <a :href="$route('finger.pageDepartemen')" class="nav-link" :class="$route().current('finger.pageDepartemen') ? 'active' : ''">
                <i class="fa-solid fa-users-line" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>  Department Data</p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('finger.pageDatapegawai')" class="nav-link" :class="$route().current('finger.pageDatapegawai') ? 'active' : ''">
                <i class="fa-solid fa-people-group" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>  Employee Data</p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('finger.pageDatashift')" class="nav-link" :class="$route().current('finger.pageDatashift') ? 'active' : ''">
                <i class="fa-regular fa-clock" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>  Daily Works</p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('finger.pageGroupshift')" class="nav-link" :class="$route().current('finger.pageGroupshift') ? 'active' : ''">
                <i class="fa-solid fa-user-clock" style="padding-right: 5px; padding-left: 15px;"></i>
                  <p>  Group Shift </p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('finger.pageSchedulemaster')" class="nav-link" :class="$route().current('finger.pageSchedulemaster') ? 'active' : ''">
                <i class="fa-solid fa-database " style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p> Working Schedule </p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a :href="$route('finger.pageSchedulemaster')" class="nav-link" :class="$route().current('finger.pageShedulemaster') ? 'active' : ''">
                <i class="fa-solid fa-database " style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p> Working Schedule B </p>
                </a>
              </li> -->
              <li class="nav-item">
                <a :href="$route('finger.pageSchedule')" class="nav-link" :class="$route().current('finger.pageSchedule') ? 'active' : ''">
                <i class="fa-solid fa-calendar-week" style="padding-right:5px; padding-left: 15px;"></i>
                  <p> Schedule</p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('finger.pageUsers')" class="nav-link" :class="$route().current('finger.pageUsers') ? 'active' : ''">
                <i class="fa-solid fa-user" style="padding-right:5px; padding-left: 15px;"></i>
                  <p> Users</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item" :class="$route().current().indexOf('mesin.') >= 0 ? 'menu-open' : ''">
            <a href="#" class="nav-link" :class="$route().current().indexOf('mesin.') >= 0 ? 'active' : ''">
                <i class="fa-solid fa-fingerprint" style="padding-right:5px; "></i>
              <p>
                Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a :href="$route('mesin.pageDatapresensi')" class="nav-link" :class="$route().current('mesin.pageDatapresensi') ? 'active' : ''">
                <i class="fa-solid fa-download" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>    Download Data Presensi </p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('mesin.pageUserfinger')" class="nav-link" :class="$route().current('mesin.pageUserfinger') ? 'active' : ''">
                <i class="fa-solid fa-download" style="padding-right: 5px; padding-left: 15px;" ></i>
                  <p>    Data User & Finger print</p>
                </a>
              </li>
            </ul>
          </li>





           <!-- <li class="nav-item" :class="$route().current().indexOf('transaksi') >= 0 ? 'menu-open' : ''">
            <a href="#" class="nav-link" :class="$route().current().indexOf('transaksi') >= 0 ? 'active' : ''">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>
                Transaksi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a :href="$route('transaksi.input_material')" class="nav-link" :class="$route().current('transaksi.input_material') ? 'active' : ''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Input Material</p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="$route('transaksi.lpbg')" class="nav-link" :class="$route().current('transaksi.lpbg') ? 'active' : ''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Input LPBG / SJN</p>
                </a>
              </li>
              <li class="nav-item">
                 <a :href="$route('transaksi.sttp')" class="nav-link" :class="$route().current('transaksi.sttp') ? 'active' : ''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create STTPP</p>
                </a>
              </li>
            </ul>
          </li> -->
          <!-- <li class="nav-item" :class="$route().current().indexOf('cek') >= 0 ? 'menu-open' : ''"> -->
            <!-- <a href="#" class="nav-link" :class="$route().current().indexOf('cek') >= 0 ? 'active' : ''"> -->
             <!-- <i class="fas fa-check"></i> -->
              <!-- <p> -->
                 <!-- Pengecekan -->
                <!-- <i class="right fas fa-angle-left"></i> -->
              <!-- </p> -->
            <!-- </a> -->
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <a :href="$route('cek.ceklpbg')" class="nav-link" :class="$route().current('cek.ceklpbg') ? 'active' : ''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cek Stok Barang</p>
                </a>
              </li>
              <li class="nav-item">
                 <a :href="$route('cek.ceksttp')" class="nav-link" :class="$route().current('cek.ceksttp') ? 'active' : ''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cek STTPP</p>
                </a>
              </li>
            </ul> -->
          <!-- </li> -->
           <!-- <li class="nav-item" :class="$route().current().indexOf('master') >= 0 ? 'menu-open' : ''"> -->
            <!-- <a href="#" class="nav-link" :class="$route().current().indexOf('master') >= 0 ? 'active' : ''"> -->
              <!-- <i class="nav-icon fas fa-database"></i>
              <p>
                Data Master
                <i class="right fas fa-angle-left"></i>
              </p> -->
            <!-- </a> -->
            <!-- <ul class="nav nav-treeview"> -->
               <!-- <li class="nav-item"> -->
                <!-- <a :href="$route('master.PageProyek')" class="nav-link" :class="$route().current('master.PageProyek') ? 'active' : ''"> -->
                  <!-- <i class="far fa-circle nav-icon"></i> -->
                  <!-- <p>Proyek</p> -->
                <!-- </a> -->
              <!-- </li> -->
              <!-- <li class="nav-item">
                <a :href="$route('master.PageMaster')" class="nav-link" :class="$route().current('master.PageMaster') ? 'active' : ''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lokasi</p>
                </a>
              </li> -->
              <!-- <li class="nav-item">
                 <a :href="$route('master.PageGedung')" class="nav-link" :class="$route().current('master.PageGedung') ? 'active' : ''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gedung</p>
                </a>
              </li> -->
               <li class="nav-item" style="display: none!important">
                 <a :href="$route('master.PageUser')" class="nav-link" :class="$route().current('master.PageUser') ? 'active' : ''">
                  <i class="far fa-user nav-icon"></i>
                  <p>User</p>
                </a>
              </li>
            <!-- </ul> -->
          <!-- </li> -->

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>



</template>

<script>
import {computed} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "AppHeader",
    setup() {
        const user = computed(() => usePage().props.value.auth.user);

        return {
            user
        }
    },
    created(){

    }
}
</script>


