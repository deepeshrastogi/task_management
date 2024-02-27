@extends('app')
@section('title')
Task | Details
@endsection
@section('content')
    @php
        $id = Request::segment(2);
        $taskApiURL = route('api.task.show', $id);
    @endphp
    <div class="row justify-content-center mt-5">
        <div class="col-md-11" id="app">

            <div class="row">
                <div class="col-4">
                    <form>
                        <h4 class="mb-3"><u>Task Details</u></h4>
                        <div class="mb-3" v-if="task.attachment.length">
                            <img :src="task.attachment" class="img img-thumbnail"/>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="error">*</span></label>
                            <input type="text" class="form-control" v-model="task.title" id="title" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Status <span class="error">*</span></label>
                            <select class="form-control" v-model="task.status" disabled>
                                <option v-for="(statusVal,index) in statusArr" :key="index"
                                    :value="statusVal">@{{ statusVal }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content <span class="error">*</span></label>
                            <textarea type="text" class="form-control" v-model="task.content" id="content" disabled></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" v-model="task.is_published" type="checkbox" id="is_publish" disabled>
                                <label class="form-check-label is_publish_label"
                                    for="is_publish">@{{ publisheLable }}</label>
                            </div>
                        </div>
                        
                    </form>
                </div>

                <div class="col-8">
                    <h4 class="mb-3"><u>Sub Tasks</u></h4>
                    <table class="table">
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                        <tr v-if="!subTasks.length">
                            <td colspan="6" class="text-center"><i>Sorry, No Sub Task found</i></td>
                        </tr>
                        <tr v-else v-for="subTask in subTasks" :key="subTask.id">
                            <td>SUB_TASK_@{{ subTask.id }}
                            </td>
                            <td>@{{ subTask.title }}</td>
                            <td>@{{ subTask.content }}</td>
                            <td>
                                <select v-model="subTask.status" id="updateStatus" :data-id="subTask.id">
                                    <option v-for="(statusVal,index) in statusArr" :key="index" :value="statusVal">@{{ statusVal }}</option>
                                </select>
                            </td>
                            <td>
        
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" :style="{width:statusPercentageArr[subTask.status]+'%'}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <center>@{{ statusPercentageArr[subTask.status] }}%</center>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger" @click="deleteTask(subTask.id)"><span class="fa fa-trash"></span></button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let token = localStorage.getItem("token");
        let taskApiUrl = "{{ $taskApiURL }}";
        let appUrl = "{{ url('/') }}";

        $(document).on("change", "#updateStatus", function(e) {
            e.preventDefault();
            let status = $(this).val();
            let id = $(this).data('id');
            app.updateTaskStatus(id,status);
        });
        const {createApp} = Vue;
        let app = createApp({
            data() {
                return {
                    task: {
                        title: "",
                        content: "",
                        is_published: "0",
                        attachment:""
                    },
                    statusArr: {
                        1: "done",
                        2: "in-progress",
                        3: "to-do",
                    },
                    publish_status: "",
                    formErrors: [],
                    subTasks:[],
                    statusPercentageArr:{
                        'to-do':0,
                        'in-progress':50,
                        'done':100,
                    },
                    task_id:0
                }
            },
            computed: {
                publisheLable: function() {
                    if (this.task.is_published == true) {
                        this.publish_status = 1;
                        return "Published";
                    } else {
                        this.publish_status = 2;
                        return "Saved As Draft";
                    }
                }
            },
            mounted() {
                this.createTask();
            },
            methods: {
                createTask() {
                    // var formData = new FormData();
                    const config = {
                        headers: {
                            "Content-type": "application/json",
                            "accept": "application/json",
                            'Authorization': 'Bearer ' + token
                        }
                    }
                    axios.get(taskApiUrl, config).then(response => {
                        if (response.status) {
                            let taskData = response.data.content;
                            this.task_id = taskData.task.id;
                            if(taskData.task.sub_tasks.length > 0){
                                this.subTasks = taskData.task.sub_tasks;
                            }
                            let isPublished = (taskData.task.is_published == "1") ? true : false;
                            let attachment = (taskData.task.attachment !== null) ? taskData.task.attachment  : '';
                            this.task = {
                                title: taskData.task.title,
                                status: taskData.task.status,
                                content: taskData.task.content,
                                is_published: isPublished,
                                attachment:attachment
                            }
                        }
                    }).catch(error => {
                        // this.formErrors = error.response.data.error;
                        // console.log(error);
                    })
                },
                updateTaskStatus(id,status){
                    let updateTaskApiUrl = appUrl+`/api/task/`+id;
                    let bodyData = {
                        'id': id,
                        'status': status,
                        'task_id':this.task_id
                    }
                    fetch(updateTaskApiUrl,{
                        method:"PATCH",
                        headers: {
                            "Content-type": "application/json",
                            "accept": "application/json",
                            "Authorization":"Bearer "+token
                        },
                        body:JSON.stringify(bodyData)
                    })
                    .then(res => res.json())
                    .then(result => {
                        console.log("test",result.content.task_status);
                        if(result.content.task_status == "1"){
                            this.task.status = 'done';
                        }
                    });
                },
                deleteTask(id){
                    let deleteApiUrl = appUrl+`/api/task/`+id;
                    fetch(deleteApiUrl,{
                        method:"DELETE",
                        headers: {
                            "Content-type": "application/json",
                            "accept": "application/json",
                            "Authorization":"Bearer "+token
                        }
                    })
                    .then(res => res.json())
                    .then(result => {
                        alert("Record delete successfully");
                        this.createTask();
                    });
                },

                
            },
        }).mount('#app')
    </script>
@endsection
