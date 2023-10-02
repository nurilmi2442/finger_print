<template>
    <layout>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form method="post" @submit.prevent="submit">
                <h2 class="text-left">Create Post</h2>

                <errors-and-messages :errors="errors"></errors-and-messages>

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id="title" v-model="form.title" />
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" class="form-control" v-model="form.content"></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" id="image" name="image" class="form-control" @change="selectFile">
                </div>

                <input type="submit" class="btn btn-primary btn-block" value="Save" />
            </form>
            <button type="button" class="btn btn-warning" @click="tes">Warning</button>
        </div>
    </div>
</layout>
</template>

<script>
import AppHeader from "../../Partials/AppHeader";
import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
import {inject, reactive} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";
import Layout from "../../Partials/Layout";

export default {
    name: "Create",
    components: {
        ErrorsAndMessages,
        AppHeader, 
        Layout
    },
    props: {
        errors: Object
    },
    setup() {
        const form = reactive({
            title: null,
            content: null,
            image: null,
            _token: usePage().props.value.csrf_token
        });

        const route = inject('$route');

        function selectFile($event) {
            form.image = $event.target.files[0];
        }

        function submit() {
            Inertia.post(route('post.store'), form, {
                forceFormData: true
            });
        }

        return {
            form, submit, selectFile
        }
    },
    methods:{
        tes(){
             this.$inertia.post('/tes', this.form)
        }
    }
}
</script>

<style scoped>
    form {
        margin-top: 20px;
    }
</style>
