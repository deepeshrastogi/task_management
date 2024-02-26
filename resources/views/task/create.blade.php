@extends('app')
@section('title')
Task | Create
@endsection
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-11" id="app">
            <h3>Create Task</h3>
            <form method="POST" @submit.prevent="createTask" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6">
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
                        <input type="file" class="form-control" @change="onFileChange" id="attachment" >
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
        let createTaskApiUrl = "{{ route('api.task.create') }}";
        const { createApp} = Vue;
        let app = createApp({
            data() {
                return {
                    task:{
                        title : "",
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
                
            },
            methods: {
                createTask(event){
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
                    axios.post(createTaskApiUrl, formData, config).then(response => {
                       if(response.status){
                            alert(response.data.message);
                            this.task = {};
                       }
                    }).catch(error => {
                        this.formErrors = error.response.data.error;
                        // console.log(error);
                    })
                },
                onFileChange:function(e){
                    this.attachment = e.target.files[0];
                },
            },
        }).mount('#app')
      </script>
@endsection
