<template>
  <Head title="Roles" />

  <AppLayout>
    <template #default>
      <!-- Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Roles</h2>
        <Button
          label="New Role"
          icon="pi pi-plus"
          class="p-button-success"
          @click="openRoleModal('new')"
        />
      </div>

      <!-- DataTable -->
      <DataTable
        :value="roles"
        paginator
        :rows="10"
        sortMode="single"
        responsiveLayout="scroll"
        class="shadow rounded"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        v-model:filters="filters"
        :globalFilterFields="['name', 'guard_name', 'type']"
      >
        <template #header>
          <div class="flex flex-row">
            <div class="font-bold text-lg flex-grow">Role List</div>
            <div class="flex items-center">
              <IconField>
                <InputIcon><i class="pi pi-search" /></InputIcon>
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
        <Column field="type" class="w-32" header="Type" sortable>
          <template #body="slotProps">
            <span class="capitalize">{{ slotProps.data.type }}</span>
          </template>
        </Column>
        <Column field="guard_name" class="w-32" header="Guard Name" sortable>
          <template #body="slotProps">
            <span class="capitalize">{{ slotProps.data.guard_name }}</span>
          </template>
        </Column>

        <!-- Actions -->
        <Column class="w-56">
          <template #body="slotProps">
            <Button
              icon="pi pi-pencil"
              class="p-button-rounded p-button-success mr-2"
              @click="openRoleModal('edit', slotProps.data)"
            />
            <Button
              icon="pi pi-key"
              class="p-button-rounded p-button-info mr-2"
              @click="openPermissionModal(slotProps.data)"
            />
            <Button
              icon="pi pi-trash"
              class="p-button-rounded p-button-danger"
              @click="confirmDelete(slotProps.data)"
            />
          </template>
        </Column>
      </DataTable>

      <!-- Modal Create/Edit Role -->
      <DynamicModal
        v-model="roleModalVisible"
        :title="roleModalTitle"
        :header="roleModalTitle"
        size="medium"
        @save="saveRole"
      >
        <div class="grid grid-cols-1 gap-4">
          <div>
            <label class="block text-sm mb-1">Name</label>
            <InputText v-model="roleForm.name" type="text" fluid />
          </div>
          <div>
            <label class="block text-sm mb-1">Type</label>
            <Select
              v-model="roleForm.type"
              :options="types"
              optionLabel="name"
              optionValue="code"
              placeholder="Select a Type"
              class="input w-full capitalize"
            />
          </div>
          <div>
            <label class="block text-sm mb-1">Guard Name</label>
            <Select
              v-model="roleForm.guard_name"
              :options="guards"
              optionLabel="name"
              optionValue="code"
              placeholder="Select a Guard"
              class="input w-full capitalize"
            />
          </div>
        </div>
      </DynamicModal>

      <!-- Modal Manage Permissions -->
      <DynamicModal
        v-model="permissionModalVisible"
        :title="permissionModalTitle"
        :header="permissionModalTitle"
        size="medium"
        @save="savePermissions"
      >
        <div class="mb-4">
          <label class="block text-sm mb-2">Permissions</label>
          <MultiSelect
            v-model="permissionForm.permissions"
            :options="permissions"
            optionLabel="name"
            optionValue="id"
            placeholder="Select permissions"
            class="w-full"
            display="chip"
            filter
          />
        </div>

        <div v-if="permissionForm.permissions.length > 0">
          <label class="block text-sm mb-2">Selected Permissions</label>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="permId in permissionForm.permissions"
              :key="permId"
              class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center"
            >
              {{ permissions.find((p) => p.id === permId)?.name }}
              <i
                class="pi pi-times ml-2 cursor-pointer"
                @click="removePermission(permId)"
              ></i>
            </span>
          </div>
        </div>
      </DynamicModal>

      <!-- Modal Delete -->
      <DynamicModal
        v-model="deleteModalVisible"
        title="Confirm Delete"
        size="small"
        @save="deleteRole"
      >
        <p>
          Are you sure you want to delete
          <strong>{{ selectedRole?.name }}</strong
          >?
        </p>
      </DynamicModal>

      <!-- Toast -->
      <DynamicToast />
    </template>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { usePage, router, Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Select from "primevue/select";
import MultiSelect from "primevue/multiselect";
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import DynamicModal from "@/Components/Modal.vue";
import DynamicToast from "@/Components/Toast.vue";

const page = usePage();
const toast = useToast();
const errors = page.errors || {};

const roles = ref([...page.props.data]);
const permissions = ref([...page.props.permissions]); // از backend بفرست
const filters = ref({ global: { value: null } });

// Modals
const roleModalVisible = ref(false);
const permissionModalVisible = ref(false);
const deleteModalVisible = ref(false);

// Forms
const roleForm = ref({ id: null, name: "", type: "staff", guard_name: "web" });
const permissionForm = ref({ role_id: null, permissions: [] });
const selectedRole = ref(null);

const types = ref([
  { name: "Admin", code: "admin" },
  { name: "Staff", code: "staff" },
  { name: "Client", code: "client" },
]);
const guards = ref([
  { name: "Web", code: "web" },
  { name: "Api", code: "api" },
]);

const roleModalTitle = ref("");
const permissionModalTitle = ref("");

// Open Modals
function openRoleModal(type, role = null) {
  if (type === "new") {
    roleModalTitle.value = "Create Role";
    roleForm.value = { id: null, name: "", type: "staff", guard_name: "web" };
  } else {
    roleModalTitle.value = "Edit Role";
    roleForm.value = { ...role };
  }
  roleModalVisible.value = true;
}

function openPermissionModal(role) {
  permissionModalTitle.value = `Manage Permissions: ${role.name}`;
  permissionForm.value.role_id = role.id;
  permissionForm.value.permissions = role.permissions?.map((p) => p.id) || [];
  permissionModalVisible.value = true;
}

// CRUD Actions
function saveRole() {
  const action = roleForm.value.id ? "update" : "store";
  const url = roleForm.value.id
    ? route("panel.roles.update", roleForm.value.id)
    : route("panel.roles.store");

  const method = roleForm.value.id ? "patch" : "post";

  router[method](url, roleForm.value, {
    onSuccess: () => {
      toast.add({
        severity: "success",
        summary: "Success",
        detail: "Role saved successfully",
      });
      router.reload({ only: ["data"] });
      roleModalVisible.value = false;
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

function savePermissions() {
  const url = route(
    "panel.roles.updatePermissions",
    permissionForm.value.role_id
  );
  router.patch(url, permissionForm.value, {
    onSuccess: () => {
      toast.add({
        severity: "success",
        summary: "Success",
        detail: "Permissions updated",
      });
      router.reload({ only: ["data"] });
      permissionModalVisible.value = false;
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

function confirmDelete(role) {
  selectedRole.value = role;
  deleteModalVisible.value = true;
}

function deleteRole() {
  router.delete(route("panel.roles.destroy", selectedRole.value.id), {
    onSuccess: () => {
      toast.add({
        severity: "success",
        summary: "Deleted",
        detail: "Role deleted successfully",
      });
      router.reload({ only: ["data"] });
      deleteModalVisible.value = false;
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
</script>
