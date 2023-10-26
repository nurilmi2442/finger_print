<template>
    <div class="hold-transition login-page" style="background-color: #1984c3; background-size: cover;">
      <div class="overlay"
        style="width: 100%; height: 100%; position: absolute; background-color: rgba(255, 255, 255, 0.4); z-index: 0;">
      </div>

      <div class="form-box col-md-8 col-sm-10 col-xs-12" style="background-color: #4f89b6; border-radius: 15px;">
        <div class="row">
          <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12" style="background: url('images/bg.png'); background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            min-height: 100%;
            border-radius: 15px;">
            <!-- You can add custom CSS styles for the background image here -->
          </div>
          <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 center-form">
            <!-- /.login-logo -->
            <form method="post" @submit.prevent="submit">
              <errors-and-messages :errors="errors"></errors-and-messages>
              <!-- Tambahkan teks "Login" di bawah ini -->
              <div class="form-group">
                <h1 class="text-center"><b>LOGIN</b></h1>
                <label for="username">Username</label>
                <div class="input-group mb-3">
                  <input type="text" class="form-control" name="nip" v-model="form.name" placeholder="Username" />
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-envelope"></span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Akhir tambahan teks "Login" -->
              <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group mb-3">
                  <input type="password" class="form-control" name="password" id="password" v-model="form.password"
                    placeholder="Password" />
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- /.col -->
                <div class="col-12">
                  <input type="submit" class="btn btn-dark btn-block" value="Login" />
                </div>
                <!-- /.col -->
              </div>
            </form>

          </div>
        </div>
      </div>

      <div style="position: fixed; bottom: 20px; z-index: 10; font-weight: bold;">
        Made By Divisi Teknologi Informasi
      </div>
    </div>
  </template>

  <script>
  import AppHeader from "../../Partials/AppHeader";
  import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
  import { Inertia } from "@inertiajs/inertia";
  import { usePage } from '@inertiajs/inertia-vue3'
  import { reactive, inject } from 'vue';

  export default {
    components: {
      ErrorsAndMessages,
      AppHeader

    },
    name: "Login",
    props: {
      errors: Object
    },
    setup() {
      const form = reactive({
        name: null,
        password: null,
        _token: usePage().props.value.csrf_token
      });

      const route = inject('$route');

      function submit() {
        Inertia.post(route('login'), form);
      }

      return {
        form, submit
      }
    }
  }
  </script>

  <style scoped>
  form {
    margin-top: 20px;
  }

  .center-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 70vh;
    max-width: 80%;
  }

  /* Add custom styles for the background image */
  /* .bg-image {
    background-image: url('images/bg.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    min-height: 100%;
    border-radius: 15px;
  } */
  </style>
