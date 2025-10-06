<template>
  <Head title="Clients" />

  <AppLayout>
    <template #default>
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Clients</h2>
        <Button
          label="New Client"
          icon="pi pi-plus"
          class="p-button-success"
          @click="openModal('new')"
        />
      </div>

      <DataTable
        :value="clients"
        paginator
        :rows="10"
        sortMode="multiple"
        responsiveLayout="scroll"
        class="shadow rounded"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        v-model:filters="filters"
        :globalFilterFields="['first_name', 'last_name', 'email', 'mobile']"
      >
        <template #header>
          <div class="flex flex-row">
            <div
              class="font-bold text-lg flex-grow items-center vertical-center"
            >
              Client List
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
        <Column field="first_name" header="Nickname" sortable />
        <Column field="username" header="Username" sortable />
        <Column field="email" header="Email" sortable />
        <Column field="status" class="w-32" header="Status" sortable />

        <Column class="w-48" :exportable="false">
          <template #header>
            <span class="font-semibold text-center">Actions</span>
          </template>
          <template #body="slotProps">
            <Button
              icon="pi pi-key"
              class="p-button-rounded p-button-warning mr-2"
              @click="openPasswordModal(slotProps.data)"
            />
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
        width="60vw"
        @save="saveClient"
      >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">First Name</label>
            <InputText v-model="form.first_name" type="text" fluid />
          </div>
          <div>
            <label class="block text-sm mb-1">Last Name</label>
            <InputText v-model="form.last_name" type="text" fluid />
          </div>
          <div>
            <label class="block text-sm mb-1">Mobile</label>
            <InputText
              v-model="form.mobile"
              type="text"
              fluid
              @input="validateMobile"
              placeholder="09xxxxxxxxx"
              maxlength="11"
              minlength="11"
            />
          </div>
          <div>
            <label class="block text-sm mb-1">Email</label>
            <InputText
              v-model="form.email"
              type="email"
              fluid
              placeholder="example@domain.tld"
            />
          </div>
          <div>
            <label class="block text-sm mb-1">Username</label>
            <InputText v-model="form.username" type="text" fluid />
          </div>
          <div v-if="!form.id">
            <label class="block text-sm mb-1">Password</label>
            <InputGroup>
              <InputText
                v-model="form.password"
                type="text"
                placeholder="*****"
              />
              <Button
                type="button"
                @click="generatePassword()"
                class="bg-blue-500 text-white px-3 rounded"
              >
                Generate
              </Button>
            </InputGroup>
          </div>
          <div>
            <label class="block text-sm mb-1">Role</label>
            <Select
              v-model="form.role_id"
              :options="roles"
              optionLabel="name"
              optionValue="id"
              placeholder="Select a Role"
              class="input w-full capitalize"
              filter
            >
              <template #option="slotProps">
                <div class="flex items-center capitalize">
                  <div>{{ slotProps.option.name }}</div>
                </div>
              </template>
            </Select>
          </div>
          <div>
            <label class="block text-sm mb-1">Status</label>
            <Select
              v-model="form.status"
              :options="status"
              optionLabel="name"
              optionValue="code"
              placeholder="Select a Status"
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
        width="25vw"
        @save="deleteClient"
      >
        <p>
          Are you sure you want to delete
          <strong>
            {{ selectedClient?.username }}
          </strong>
          ?
        </p>
      </DynamicModal>
      <!-- Modal for Change Password -->
      <DynamicModal
        v-model="passwordModalVisible"
        title="Change Password"
        header="Change Password"
        width="30vw"
        @save="updatePassword"
      >
        <div class="block text-sm mb-1">
          <label class="block text-sm mb-1">Password</label>
          <InputGroup>
            <InputText v-model="form.password" type="text" />
            <Button
              type="button"
              @click="generatePassword()"
              class="bg-blue-500 text-white px-3 rounded"
            >
              Generate
            </Button>
          </InputGroup>
        </div>
        <div>
          <label class="block text-sm mb-1">Confirm Password</label>
          <InputText v-model="passwordForm.password_confirmation" fluid />
        </div>
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
import InputGroup from "primevue/inputgroup";
import DynamicModal from "@/Components/Modal.vue";
import DynamicToast from "@/Components/Toast.vue";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";

const page = usePage();

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

// Reactive state
const clients = ref([...page.props.data]);
const roles = ref(page.props.roles || []);
const deleteModalVisible = ref(false);
const selectedClient = ref(null);
const modalVisible = ref(false);
const modalTitle = ref("");
const form = ref({
  id: null,
  first_name: "",
  last_name: "",
  username: "",
  mobile: "",
  email: "",
  role_id: null,
  status: "active",
});
const status = ref([
  { name: "Active", code: "active" },
  { name: "Inactive", code: "inactive" },
  { name: "Banned", code: "banned" },
]);
const toast = useToast();

// Open modal for create or edit
function openModal(type, client = null) {
  if (type === "new") {
    modalTitle.value = "Create Client";
    form.value = {
      id: null,
      first_name: "",
      last_name: "",
      username: "",
      mobile: "",
      email: "",
      role_id: null,
      status: "active",
    };
  } else if (type === "edit") {
    modalTitle.value = "Edit Client";
    form.value = { ...client };
  }
  modalVisible.value = true;
}

// Save client (create or update)
function saveClient() {
  if (form.value.id) {
    // Update
    router.patch(route("panel.clients.update", form.value.id), form.value, {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Updated",
          detail: "Client updated successfully",
          life: 3000,
        });
        modalVisible.value = false;
        router.reload({ only: ["data"] });
      },
      onError: (errors) => {
        toast.add({
          severity: "error",
          summary: "Error",
          detail: "Failed to update client",
          life: 3000,
        });
      },
    });
  } else {
    // Create
    router.post(route("panel.clients.store"), form.value, {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Created",
          detail: "Client created successfully",
          life: 3000,
        });
        modalVisible.value = false;
        router.reload({ only: ["data"] });
      },
      onError: (errors) => {
        toast.add({
          severity: "error",
          summary: "Error",
          detail: "Failed to create client",
          life: 3000,
        });
      },
    });
  }
}

// Confirm delete
function confirmDelete(client) {
  selectedClient.value = client;
  deleteModalVisible.value = true;
}

function deleteClient() {
  router.delete(route("panel.clients.destroy", selectedClient.value.id), {
    onSuccess: () => {
      router.reload({ only: ["data"] }); // refresh list
      deleteModalVisible.value = false;
      toast.add({
        severity: "success",
        summary: "Deleted",
        detail: "Client deleted successfully",
        life: 3000,
      });
    },
  });
}
// Update Password
const passwordModalVisible = ref(false);
const passwordForm = ref({
  id: null,
  password: "",
  password_confirmation: "",
});

function openPasswordModal(client) {
  passwordForm.value = {
    id: client.id,
    password: "",
    password_confirmation: "",
  };
  passwordModalVisible.value = true;
}
function updatePassword() {
  router.patch(
    route("panel.clients.update.password", passwordForm.value.id),
    passwordForm.value,
    {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Password Updated",
          detail: "Password changed successfully",
          life: 3000,
        });
        passwordModalVisible.value = false;
      },
      onError: () => {
        toast.add({
          severity: "error",
          summary: "Error",
          detail: "Failed to update password",
          life: 3000,
        });
      },
    }
  );
}
// Generate Random Password
function generatePassword(length = 12) {
  const chars =
    "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  let password = "";
  for (let i = 0; i < length; i++) {
    password += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  form.value.password = password;
  passwordForm.value.password = password;
  passwordForm.value.password_confirmation = password;
}
const mobileError = ref("");

function validateMobile() {
  const pattern = /^09\d{9}$/;
  mobileError.value =
    form.value.mobile && !pattern.test(form.value.mobile)
      ? "Mobile must be a valid Iranian number"
      : "";
}
</script>
