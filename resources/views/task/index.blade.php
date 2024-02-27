@extends('app')
@section('title')
Task | Lists
@endsection
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-11" id="app">
           {{-- @{{ tasks }} --}}
           <div class="no_of_record">
                <div class="row mb-3">
                    <div class="col-md-2">
                        Record
                        <select v-model="no_of_record" @change="noOfRecordNeeded()">
                            <option v-for="pageOpt in pageOptions" :key="pageOpt">@{{pageOpt}}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        Status
                        <select v-model="status" @change="searchByStatus">
                            <option :key="0" :value="0">all</option>
                            <option v-for="(status,index) in statusArr" :key="index" :value="index">@{{ status }}</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        Search
                        <input type="text" v-model="search" @keyup="searchByTitle" />
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-info btn-sm" title="Reset filter" @click="resetFilter"><span class="fas fa-eraser"></span></button>
                    </div>
                </div>
            </div>
            <h4 class="mb-3"><u>Tasks List</u></h4>
            <table class="table">
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Action</th>
                </tr>
                <tr v-if="!tasks.length">
                    <td colspan="6" class="text-center"><i>Sorry, No Task found</i></td>
                </tr>
                <tr v-else v-for="task in tasks" :key="task.id">
                    <td>TASK_@{{ task.id }}
                    </td>
                    <td>@{{ task.title }}</td>
                    <td>@{{ task.content }}</td>
                    <td>
                        <select v-model="task.status" id="updateStatus" :data-id="task.id">
                            <option v-for="(statusVal,index) in statusArr" :key="index" :value="statusVal">@{{ statusVal }}</option>
                        </select>
                    </td>
                    <td>

                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :style="{width:statusPercentageArr[task.status]+'%'}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <center>@{{ statusPercentageArr[task.status] }}%</center>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" @click="show(task.id)"><span class="fa fa-eye"></span></button> &nbsp;
                        <button class="btn btn-sm btn-danger" @click="deleteTask(task.id)"><span class="fa fa-trash"></span></button>
                    </td>
                </tr>
                </span>
            </table>
            <div v-if="tasks.length">
                <nav class="text-center" aria-label="Page navigation example" v-if="pagination.from != pagination.last_page">
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" aria-label="Previous" @click.prevent="changePage(pagination.current_page - 1)">
                                <span aria-hidden="true">«</span>
                            </a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[ page == isActived ? 'active' : '']">
                            <a class="page-link" href="javascript:void(0)" @click.prevent="changePage(page)">@{{ page }}</a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" aria-label="Next" @click.prevent="changePage(pagination.current_page + 1)">
                                <span aria-hidden="true">»</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let token = localStorage.getItem("token");
        let taskListApiUrl = "{{route('api.task.index')}}";
        let appUrl = "{{ url('/') }}";

        $(document).on("change", "#updateStatus", function(e) {
            e.preventDefault();
            let status = $(this).val();
            let id = $(this).data('id');
            app.updateTaskStatus(id,status);
        });

        const { createApp} = Vue;
        let app = createApp({
            data() {
                return {
                    pageOptions :[10,20,30,50,100,200,400],
                    tasksData: [],
                    tasks:[],
                    statusArr:{
                        1 : "done",
                        2 : "in-progress",
                        3 : "to-do",
                    },
                    pagination: {
                        total: 0,
                        per_page: 10,
                        last_page: 0,
                        from: 1,
                        to: 10,
                        current_page: 1
                    },
                    offset:10,
                    no_of_record:10,
                    status:0,
                    search:'',
                    statusPercentageArr:{
                        'to-do':0,
                        'in-progress':50,
                        'done':100,
                    },
                }
            },
            watch:{
                'pagination.per_page': function(){
                    this.pagination.current_page = 1;
                }
            },
            computed:{
                isActived: function () {
                    return this.pagination.current_page;
                },
                pagesNumber: function () {
                    const numShown = Math.min(this.pagination.per_page, this.pagination.last_page);
                    let first = this.pagination.current_page - Math.floor(numShown / 2);
                    first = Math.max(first, 1);
                    first = Math.min(first, this.pagination.last_page - numShown + 1);
                    return [...Array(numShown)].map((k,i) => i + first);
                }
            },
            mounted(){
                this.getTaskList();
            },
            methods: {
                getTaskList(){
                    let queryParams = {
                        'page': this.pagination.current_page,
                        'per_page': this.no_of_record,
                        'status': this.status,
                        'search': this.search,
                    }
                    const queryString = new URLSearchParams(queryParams).toString();
                    const taskListApiUrlWithParam = `${taskListApiUrl}?${queryString}`;
                    fetch(taskListApiUrlWithParam,{
                        method:"GET",
                        headers: {
                            "Content-type": "application/json",
                            "accept": "application/json",
                            "Authorization":"Bearer "+token
                        }
                    })
                    .then(res => res.json())
                    .then(result => {
                        this.tasksData = result;
                        let tasks = this.tasksData.content.tasks;
                        if(tasks.data.length > 0){
                            this.tasks = tasks.data;
                        }else{
                            this.tasks = [];
                        }
                        this.pagination.total = tasks.total;
                        this.pagination.per_page = tasks.per_page;
                        this.pagination.last_page = tasks.last_page;
                        this.pagination.from = tasks.from;
                        this.pagination.to = tasks.to;
                        this.pagination.current_page = tasks.current_page;
                    });
                },
                changePage(page){
                    this.pagination.current_page = page;
                    this.getTaskList();
                },
                noOfRecordNeeded(){
                    this.pagination.per_page = this.no_of_record;
                    this.pagination.current_page = 1;
                    this.getTaskList();
                },
                searchByStatus(){
                    this.pagination.current_page = 1;
                    this.getTaskList();
                },
                searchByTitle(){
                    this.pagination.current_page = 1;
                    this.getTaskList();
                },
                resetFilter(){
                    this.pagination.current_page = 1;
                    this.no_of_record = 10;
                    this.status = 0;
                    this.search = '';
                    this.getTaskList();
                },
                updateTaskStatus(id,status){
                    let updateTaskApiUrl = appUrl+`/api/task/`+id;
                    let bodyData = {
                        'id': id,
                        'status': status,
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
                        // console.log(result);
                    });
                },
                show(id){
                    window.location = appUrl+'/tasks/'+id; 
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
                        this.getTaskList();
                    });
                },

            },
        }).mount('#app')
      </script>
@endsection
