@extends('app')
@section('title')
Sub-Task | Create
@endsection
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-11" id="app">
            <div class="alert alert-success alert-dismissible fade show alertMessage" role="alert" v-show="message">
                @{{ message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <h4 class="mb-3"><u>Create Sub Task</u></h4>
            <form method="POST" @submit.prevent="createTask" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Task <span class="error">*</span></label>
                        <select class="form-control" v-model="task.task_id">
                            <option v-for="newTaskList in taskList" :key="newTaskList.id" :value="newTaskList.id">@{{ newTaskList.title }}</option>
                        </select>
                        <span v-if="formErrors['task_id']" class="error text-danger">@{{ formErrors['task_id'][0] }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="error">*</span></label>
                        <input type="text" class="form-control" v-model="task.title" id="title">
                        <span v-if="formErrors['title']" class="error text-danger">@{{ formErrors['title'][0] }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Status <span class="error">*</span></label>
                        <select class="form-control" v-model="task.status">
                            <option v-for="(statusVal,index) in statusArr" :key="index" :value="statusVal">@{{ statusVal }}</option>
                        </select>
                        <span v-if="formErrors['status']" class="error text-danger">@{{ formErrors['status'][0] }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content <span class="error">*</span></label>
                        <textarea type="text" class="form-control" v-model="task.content" id="content"></textarea>
                        <span v-if="formErrors['content']" class="error text-danger">@{{ formErrors['content'][0] }}</span>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" v-model="task.is_published" type="checkbox" id="is_publish">
                            <label class="form-check-label is_publish_label" for="is_publish">@{{ publisheLable }}</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachement</label>
                        <input type="file" class="form-control" @change="onFileChange" id="attachment" ref="fileInput">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let token = localStorage.getItem("token");
        let createTaskApiUrl = "{{ route('api.task.store') }}";
        let getTaskNameListApiUrl = "{{ route('api.task.name.list') }}";
        const { createApp} = Vue;
        let app = createApp({
            data() {
                return {
                    task:{
                        task_id : "",
                        title : "",
                        status : "",
                        content : "",
                        is_published : "0",      
                    },
                    statusArr:{
                        1 : "done",
                        2 : "in-progress",
                        3 : "to-do",
                    },
                    attachment:"",
                    publish_status:"",
                    formErrors:[],
                    taskList:[],
                    message:''
                }
            },
            computed:{
                publisheLable: function () {
                    if(this.task.is_published == true){
                        this.publish_status = 1;
                        return "Published";
                    }else{
                        this.publish_status = 2;
                        return "Saved As Draft";
                    }
                }
            },
            mounted(){
                this.getTask();
            },
            methods: {
                async getTask(){
                    const config = {
                        headers: {
                            "Content-type": "application/json",
                            "accept": "application/json",
                            'Authorization': 'Bearer ' + token
                        }
                    }
                    await axios.get(getTaskNameListApiUrl, config).then(response => {
                        if (response.status) {
                            this.taskList = response.data.content.task;
                        }
                        $(".loader_container").hide();
                    }).catch(error => {
                        $(".loader_container").hide();
                    })
                },
                async createTask(event){
                    // check validation
                    this.checkValidations();
                    if('task_id' in this.formErrors || 'title' in this.formErrors || 'status' in this.formErrors || 'content' in this.formErrors){
                        return false;
                    }
                    $(".loader_container").show();
                    var formData = new FormData();
                    for ( var key in this.task ) {
                        formData.append(key, this.task[key]);
                    }
                    if(this.attachment !=''){
                        formData.append('attachment', this.attachment);
                    }
                    formData.append('is_published', this.publish_status);
                    // formData.append(method, 'POST');
                    const config = { 
                        headers: { 
                            'Content-Type': 'multipart/form-data',
                            'Authorization':'Bearer '+token
                        }
                    }
                    await axios.post(createTaskApiUrl, formData, config).then(response => {
                       if(response.status){
                            this.message = 'Your Sub task created successfully';
                            this.$refs.fileInput.value = null;
                            this.attachment = '';
                            this.task = {};
                            setTimeout(() => {
                                this.message = '';
                                $('.alertMessage').fadeOut('slow');
                            }, 2000);
                       }
                       $(".loader_container").hide();
                    }).catch(error => {
                        this.formErrors = error.response.data.error;
                        // console.log(error);
                        $(".loader_container").hide();
                    })
                },
                onFileChange:function(e){
                    this.attachment = e.target.files[0];
                },
                checkValidations:function(e){
                    this.formErrors = [];
                    if(isEmpty(this.task.task_id)){
                        this.formErrors['task_id'] = ['The task field is required.'];
                    }
                    if(isEmpty(this.task.title)){
                        this.formErrors['title'] = ['The title field is required.'];
                    }

                    if(isEmpty(this.task.status)){
                        this.formErrors['status'] = ['The status field is required.'];
                    }

                    if(isEmpty(this.task.content)){
                        this.formErrors['content'] = ['The content field is required.'];
                    }
                }
            },
        }).mount('#app')
      </script>
@endsection
