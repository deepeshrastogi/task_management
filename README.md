# Task Management

## Process

1- Clone the project using "git clone https://github.com/deepeshrastogi/task_management.git"

## Setup Task Management
1 - Create a new database "task_management"

2 - Rename file .env.example to .env

3 - Run the command "composer update"

4 - Run the command "php artisan optimize"

5 - Run the command "php artisan migrate"

6 - Run command "php artisan serve --port=8000"

7 - Added API Collection in Root folder "API Collection", we can import this collection to postman software

8 - Create a new account/Login

9 - Run command "php artisan queue:work" OR "php artisan queue:listen" - If there are media attachments in tasks the queue will execute the task script with image uploading option.

10 - Now tasks list is displayed on the Dashboard page, Admin will take actions like create Task and Sub Task, Delete Task and Sub Task.

## Screenshot of Task Management for related Task management systems like Sign up, Login, Tasks list, Task creation/ Sub Task Creation, Update Status, and details

![signup](https://github.com/deepeshrastogi/task_management/assets/38438355/4fb35b75-d6a7-4104-a265-6a18ffbbe506)
![login](https://github.com/deepeshrastogi/task_management/assets/38438355/8d58b530-d1a2-48fb-92fa-adc79f5adc9e)
![dashboard](https://github.com/deepeshrastogi/task_management/assets/38438355/3fdecdd6-2b05-49dc-b2a2-2676a8712d76)
![task_list](https://github.com/deepeshrastogi/task_management/assets/38438355/d0040f26-c67d-4f9c-a48f-b02e6164b071)
![task_details](https://github.com/deepeshrastogi/task_management/assets/38438355/bf40ce66-0bbc-48c2-94a8-5fb59e37b2b8)
![task_details_with_sub_tasks](https://github.com/deepeshrastogi/task_management/assets/38438355/ed18e2ae-c78d-401e-9ccb-cfa7a9aae87d)
![sub_task_creation](https://github.com/deepeshrastogi/task_management/assets/38438355/543f5b6f-bb37-488f-80a7-0abc347637d9)
![trashed_tasks_list](https://github.com/deepeshrastogi/task_management/assets/38438355/a3b2b225-886f-40e3-a0ab-d1992d63940e)

## Additional Features:

1. Use custom form request validation.
![task_creation_client_side_validation](https://github.com/deepeshrastogi/task_management/assets/38438355/16c2b616-66eb-4f6c-b816-413e6dbd71ba)

2. Utilize resources in routes.
![utilize_resources_in_routes](https://github.com/deepeshrastogi/task_management/assets/38438355/34a01d17-009c-4389-a298-79cd6d033282)

3. If there are media attachments in tasks, use queues.
![task_creation_with_media](https://github.com/deepeshrastogi/task_management/assets/38438355/805c3177-5ea2-4c71-9626-ad11f116a94d)

4. Observe design patterns (any design pattern will do; e.g., Service-Repository,
Repository, etc.).
![service_repository_design_patterns](https://github.com/deepeshrastogi/task_management/assets/38438355/c1e4a31a-3d5c-4494-977a-85a4205a4baa)


## Screenshot of Task Management APIs

![signup_api](https://github.com/deepeshrastogi/task_management/assets/38438355/b12e292c-e6c1-496d-a0a7-3f42c9143cfb)
![login_api](https://github.com/deepeshrastogi/task_management/assets/38438355/6d1e1434-eeca-4383-8a46-fe03db6157c5)
![user_dashboard_api](https://github.com/deepeshrastogi/task_management/assets/38438355/2bfcc318-95b9-4e40-bf05-5905fb8bdd89)
![task_creation_api](https://github.com/deepeshrastogi/task_management/assets/38438355/97ab24f1-1e66-4491-8824-11e46eec9994)
![tasks_list_api](https://github.com/deepeshrastogi/task_management/assets/38438355/ffd9f80c-8730-4a37-91c7-4a689e0638d8)
![update_task_status_api](https://github.com/deepeshrastogi/task_management/assets/38438355/7728149a-ba5b-4fe4-95b9-883bd3d94c0c)
![trashed_tasks_list_api](https://github.com/deepeshrastogi/task_management/assets/38438355/a0b1f1fc-47ec-4340-8837-38e03277c4b0)
![get_task_detaitls_with_subtask_api](https://github.com/deepeshrastogi/task_management/assets/38438355/82f2f85f-2ac2-4e16-903a-cc110a15bfdf)
![get_task_name_list_api_for_subtask_creation](https://github.com/deepeshrastogi/task_management/assets/38438355/6924e0b6-d551-469f-8c5a-62d175f5f918)
![sub_task_creation_api](https://github.com/deepeshrastogi/task_management/assets/38438355/c23468fd-167d-4e77-a16d-cc398ada3749)
![delete_task_api](https://github.com/deepeshrastogi/task_management/assets/38438355/47c117b3-7e55-4c07-a8bb-5f9e4809a0f5)
![logout_api](https://github.com/deepeshrastogi/task_management/assets/38438355/97b42de2-b479-48b0-978d-4aaddba955b8)

