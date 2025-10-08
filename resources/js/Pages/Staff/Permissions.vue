<template>
  <Head title="Permissions" />

  <AppLayout>
    <template #default>
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Permissions</h2>
        <Button
          label="New Permission"
          icon="pi pi-plus"
          class="p-button-success"
          @click="openModal('new')"
        />
      </div>

      <DataTable
        :loading="loading"
        :value="permissions"
        paginator
        :rows="10"
        sortMode="single"
        responsiveLayout="scroll"
        class="shadow rounded"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        v-model:filters="filters"
        :globalFilterFields="['name', 'guard_name']"
      >
        <template #header>
          <div class="flex flex-row">
            <div
              class="font-bold text-lg flex-grow items-center vertical-center"
            >
              Permission List
            </div>
            <div class="flex items-center">
              <IconField>
                <InputIcon>
                  <i class="pi pi-search" />
                </InputIcon>
                <InputText
                  v-model="filters['global'].value"
                  placeholder="Keyword Search"
                />
              </IconField>
            </div>
          </div>
        </template>
        <Column field="id" class="w-16" header="ID" sortable />
        <Column field="name" header="Name" sortable />
        <Column field="guard_name" class="w-32" header="Guard Name" sortable>
          <template #body="slotProps">
            <span class="capitalize">{{ slotProps.data.guard_name }}</span>
          </template>
        </Column>

        <Column class="w-32" :exportable="false">
          <template #header>
            <span class="font-semibold text-center">Actions</span>
          </template>
          <template #body="slotProps">
            <Button
              icon="pi pi-pencil"
              class="p-button-rounded p-button-success mr-2"
              @click="openModal('edit', slotProps.data)"
            />
            <Button
              icon="pi pi-trash"
              class="p-button-rounded p-button-danger"
              @click="confirmDelete(slotProps.data)"
            />
          </template>
        </Column>
      </DataTable>

      <!-- Modal for Create/Edit -->
      <DynamicModal
        v-model="modalVisible"
        :title="modalTitle"
        :header="modalTitle"
        size="medium"
        @save="savePermission"
      >
        <div class="grid grid-cols-1">
          <div class="mb-4">
            <label class="block text-sm mb-1">Name</label>
            <InputText v-model="form.name" type="text" fluid />
          </div>
          <div>
            <label class="block text-sm mb-1">Guard Name</label>
            <Select
              v-model="form.guard_name"
              :options="guards"
              optionLabel="name"
              optionValue="code"
              placeholder="Select a Guard"
              class="input w-full capitalize"
            />
          </div>
        </div>
      </DynamicModal>

      <!-- Modal for Delete -->
      <DynamicModal
        v-model="deleteModalVisible"
        title="Confirm Delete"
        header="Confirm Delete"
        size="small"
        @save="deletePermission"
      >
        <p>
          Are you sure you want to delete
          <strong>{{ selectedPermission?.name }}</strong
          >?
        </p>
      </DynamicModal>

      <!-- Toast -->
      <DynamicToast />
    </template>
  </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Select from "primevue/select";
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import DynamicModal from "@/Components/Modal.vue";
import DynamicToast from "@/Components/Toast.vue";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";

const page = usePage();
const errors = page.errors || {};

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const loading = ref(false);

// Reactive state
const permissions = ref([...page.props.data]);
const deleteModalVisible = ref(false);
const selectedPermission = ref(null);
const modalVisible = ref(false);
const modalTitle = ref("");
const form = ref({
  id: null,
  name: "",
  guard_name: "web",
});
const toast = useToast();

const types = ref([
  { name: "Admin", code: "admin" },
  { name: "Staff", code: "staff" },
  { name: "Client", code: "client" },
]);

const guards = ref([
  { name: "Web", code: "web" },
  { name: "Api", code: "api" },
]);
// Open modal for create or edit
function openModal(type, permission = null) {
  if (type === "new") {
    modalTitle.value = "Create Permission";
    form.value = {
      id: null,
      name: "",
      guard_name: "web",
    };
  } else if (type === "edit") {
    modalTitle.value = "Edit Permission";
    form.value = { ...permission };
  }
  modalVisible.value = true;
}

// Save permission (create or update)
function savePermission() {
  if (form.value.id) {
    // Update
    router.patch(route("panel.permissions.update", form.value.id), form.value, {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Updated",
          detail: "Permission updated successfully",
          life: 3000,
        });
        router.reload({ only: ["data"] });
        modalVisible.value = false;
      },
      onError: (errors) => {
        Object.values(errors).forEach((error) => {
          toast.add({
            severity: "error",
            summary: "Validation Error",
            detail: error,
            life: 3000,
          });
        });
      },
    });
  } else {
    // Create
    router.post(route("panel.permissions.store"), form.value, {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Created",
          detail: "Permission created successfully",
          life: 3000,
        });
        router.reload({ only: ["data"] });
        modalVisible.value = false;
      },
      onError: (errors) => {
        Object.values(errors).forEach((error) => {
          toast.add({
            severity: "error",
            summary: "Validation Error",
            detail: error,
            life: 3000,
          });
        });
      },
    });
  }
}

// Confirm delete
function confirmDelete(permission) {
  selectedPermission.value = permission;
  deleteModalVisible.value = true;
}

function deletePermission() {
  router.delete(
    route("panel.permissions.destroy", selectedPermission.value.id),
    {
      onSuccess: () => {
        router.reload({ only: ["data"] }); // refresh list
        deleteModalVisible.value = false;
        toast.add({
          severity: "success",
          summary: "Deleted",
          detail: "Permission deleted successfully",
          life: 3000,
        });
      },
      onError: (errors) => {
        Object.values(errors).forEach((error) => {
          toast.add({
            severity: "error",
            summary: "Validation Error",
            detail: error,
            life: 3000,
          });
        });
      },
    }
  );
}
</script>
