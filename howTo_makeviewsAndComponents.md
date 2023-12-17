sail artisan make:controller PlannedTaskController

//Se view con DynamicContent
sail artisan livewire:make Warehouses/Ubications/Content
sail artisan livewire:make Warehouses/Ubications/UbicationModalEdit
sail artisan make:datatable Warehouses/Ubications/UbicationTable Ubication


sail artisan livewire:make PlanImportFile/PlanImportFileModal

sail artisan livewire:make PlanImportFile/PlanImportFileModalEdit

<!-- JOBS -->
sail artisan make:job ProcessTempTasks

<!-- EXCEL -->
sail artisan make:import UsersImport --model=User

sail artisan make:export PlannedTaskExport --model=PlannedTask

apt install libfontconfig1 libxrender1

docker exec -u root ibp-oms-laravel.test-1 apt update
docker exec -u root ibp-oms-laravel.test-1 apt install libxrender1