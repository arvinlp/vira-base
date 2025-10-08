<template>
  <Head title="Tenants" />

  <AppLayout>
    <template #default>
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Tenants</h2>
        <Button
          label="New Tenant"
          icon="pi pi-plus"
          class="p-button-success"
          @click="openModal('new')"
        />
      </div>

      <DataTable
        :value="tenants"
        paginator
        :rows="10"
        sortMode="multiple"
        responsiveLayout="scroll"
        class="shadow rounded"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        v-model:filters="filters"
        :globalFilterFields="['name']"
      >
        <template #header>
          <div class="flex flex-row">
            <div
              class="font-bold text-lg flex-grow items-center vertical-center"
            >
              Tenant List
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
        <Column field="max_users" class="w-24" header="Users" sortable>
          <template #body="slotProps">
            {{
              slotProps.data.max_users === 0
                ? "Unlimited"
                : slotProps.data.max_users
            }}
          </template>
        </Column>
        <Column field="max_storage_mb" class="w-24" header="Storage" sortable>
          <template #body="slotProps">
            {{ (slotProps.data.max_storage_mb / 1024).toFixed(2) }} GB
          </template>
        </Column>
        <Column field="is_demo" class="w-24" header="Demo" sortable>
          <template #body="slotProps">
            <span
              :class="{
                'px-2 py-1 rounded-full text-white text-sm font-semibold capitalize': true,
                'bg-green-500': slotProps.data.is_demo === true,
                'bg-gray-500': slotProps.data.is_demo === false,
              }"
            >
              {{ slotProps.data.is_demo === true ? "Demo" : "Not" }}
            </span>
          </template>
        </Column>
        <Column field="is_free" class="w-24" header="Free" sortable>
          <template #body="slotProps">
            <span
              :class="{
                'px-2 py-1 rounded-full text-white text-sm font-semibold capitalize': true,
                'bg-green-500': slotProps.data.is_free === true,
                'bg-gray-500': slotProps.data.is_free === false,
              }"
            >
              {{ slotProps.data.is_free === true ? "Free" : "Not" }}
            </span>
          </template>
        </Column>
        <Column field="status" class="w-32" header="Status" sortable>
          <template #body="slotProps">
            <span
              :class="{
                'px-2 py-1 rounded-full text-white text-sm font-semibold capitalize': true,
                'bg-green-500': slotProps.data.status === 'active',
                'bg-gray-500': slotProps.data.status === 'inactive',
              }"
            >
              {{ slotProps.data.status }}
            </span>
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
        size="large"
        @save="saveTenant"
      >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm mb-1">Name</label>
            <InputText v-model="form.name" type="text" fluid />
          </div>
          <div>
            <label class="block text-sm mb-1">Max Users</label>
            <InputGroup>
              <InputGroupAddon>
                <i class="pi pi-users"></i>
              </InputGroupAddon>
              <InputNumber
                v-model="form.max_users"
                min="0"
                step="1"
                placeholder="0"
                fluid
              />
            </InputGroup>
          </div>
          <div>
            <label class="block text-sm mb-1">Max Storage</label>
            <InputGroup>
              <InputGroupAddon>
                <i class="pi pi-cloud"></i>
              </InputGroupAddon>
              <InputNumber
                v-model="form.max_storage_mb"
                min="1024"
                step="1"
                placeholder="0"
                fluid
              />
              <InputGroupAddon> MB </InputGroupAddon>
            </InputGroup>
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
          <div class="flex gap-2">
            <div class="flex items-center gap-2">
              <label for="is_free" class="block text-sm mb-1"
                >is Free Tenant</label
              >
              <ToggleSwitch
                v-model="form.is_free"
                inputId="is_free"
                value="true"
                name="is_free"
                fluid
              />
            </div>
            <div class="flex items-center gap-2">
              <label for="is_demo" class="block text-sm mb-1"
                >is Demo Tenant</label
              >
              <ToggleSwitch
                v-model="form.is_demo"
                inputId="is_demo"
                value="true"
                name="is_demo"
                fluid
              />
            </div>
          </div>
        </div>
        <div class="col-span-2 mb-4">
          <label class="block text-sm font-medium mb-2">Prices</label>
          <div class="space-y-3">
            <div
              v-for="(p, index) in form.price"
              :key="index"
              class="flex items-center gap-2"
            >
              <InputText
                v-model="p.label"
                placeholder="Type (e.g. Monthly, Yearly)"
                class="w-1/2"
              />
              <InputGroup>
                <InputGroupAddon>
                  <i class="pi pi-dollar"></i>
                </InputGroupAddon>
                <InputNumber
                  v-model="p.amount"
                  min="0"
                  step="1000"
                  placeholder="Amount"
                  class="w-1/3"
                  inputClass="w-full"
                />
                <InputGroupAddon> .00 </InputGroupAddon>
              </InputGroup>
              <Button
                v-if="index !== 0"
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger p-button-sm"
                @click="removePrice(index)"
              />
            </div>
          </div>
          <Button
            icon="pi pi-plus"
            label="Add Price Option"
            class="p-button-text p-button-sm mt-3"
            @click="addPrice"
          />
        </div>
        <div class="col-span-2 mb-4">
          <label class="block text-sm font-medium mb-2">Features</label>
          <div class="space-y-3">
            <div
              v-for="(p, index) in form.features"
              :key="index"
              class="flex items-center gap-2"
            >
              <InputText
                v-model="p.label"
                placeholder="Label (e.g. Users, Modules, etc)"
                class="w-1/2"
              />
              <InputText
                v-model="p.description"
                placeholder="Description (e.g. 10 users, CRM, HRM, etc)"
                class="w-1/2"
              />
              <Button
                v-if="index !== 0"
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger p-button-sm"
                @click="removeFeature(index)"
              />
            </div>
          </div>
          <Button
            icon="pi pi-plus"
            label="Add Feature Option"
            class="p-button-text p-button-sm mt-3"
            @click="addFeature"
          />
        </div>

        <div class="grid grid-cols-1">
          <div>
            <label class="block text-sm">Description</label>
            <Editor
              v-model="form.description"
              fluid
              editorStyle="height: 100px"
            >
              <template v-slot:toolbar>
                <span class="ql-formats">
                  <button class="ql-bold" />
                  <button class="ql-italic" />
                  <button class="ql-underline" />
                </span>
              </template>
            </Editor>
          </div>
        </div>
      </DynamicModal>

      <!-- Modal for Delete -->
      <DynamicModal
        v-model="deleteModalVisible"
        title="Confirm Delete"
        header="Confirm Delete"
        size="small"
        @save="deleteTenant"
      >
        <p>
          Are you sure you want to delete
          <strong>{{ selectedTenant?.username }}</strong
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
import Editor from "primevue/editor";
import InputNumber from "primevue/inputnumber";
import InputText from "primevue/inputtext";
import InputGroup from "primevue/inputgroup";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import ToggleSwitch from "primevue/toggleswitch";
import ToggleButton from "primevue/togglebutton";
import InputGroupAddon from "primevue/inputgroupaddon";
import DynamicModal from "@/Components/Modal.vue";
import DynamicToast from "@/Components/Toast.vue";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import { Input } from "postcss";

const page = usePage();
const errors = page.errors || {};

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

// Reactive state
const tenants = ref([...page.props.data]);
const deleteModalVisible = ref(false);
const selectedTenant = ref(null);
const modalVisible = ref(false);
const modalTitle = ref("");
const form = ref({
  id: null,
  name: "Tenant",
  price: [{ label: "Monthly", amount: 0 }],
  description: "",
  features: [{ label: "Users", description: "10 Users" }],
  status: "active",
  max_storage_mb: 1024,
  max_users: 0,
  is_free: false,
  is_demo: false,
});
const status = ref([
  { name: "Active", code: "active" },
  { name: "Inactive", code: "inactive" },
]);
const toast = useToast();

// Open modal for create or edit
function openModal(type, tenant = null) {
  if (type === "new") {
    modalTitle.value = "Create Tenant";
    form.value = {
      id: null,
      name: "Tenant",
      price: [{ label: "Monthly", amount: 10 }],
      description: "Description text for testing.",
      features: [{ label: "Users", description: "10 Users" }],
      status: "active",
      max_storage_mb: 1024,
      max_users: 0,
      is_free: false,
      is_demo: false,
    };
  } else if (type === "edit") {
    modalTitle.value = "Edit Tenant";
    form.value = { ...tenant };
  }
  modalVisible.value = true;
}

// Save tenant (create or update)
function saveTenant() {
  if (form.value.id) {
    // Update
    router.patch(route("panel.tenants.update", form.value.id), form.value, {
      onSuccess: (success) => {
        toast.add({
          severity: "success",
          summary: "Updated",
          detail: success.props.flash.message,
          life: 3000,
        });
        modalVisible.value = false;
        router.reload({ only: ["data"] });
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
    router.post(route("panel.tenants.store"), form.value, {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Created",
          detail: success.props.flash.message,
          life: 3000,
        });
        modalVisible.value = false;
        router.reload({ only: ["data"] });
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
function confirmDelete(tenant) {
  selectedTenant.value = tenant;
  deleteModalVisible.value = true;
}
function deleteTenant() {
  router.delete(route("panel.tenants.destroy", selectedTenant.value.id), {
    onSuccess: () => {
      router.reload({ only: ["data"] }); // refresh list
      deleteModalVisible.value = false;
      toast.add({
        severity: "success",
        summary: "Deleted",
        detail: "Tenant deleted successfully",
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
  });
}

// Additional Modals and Functions
function addPrice() {
  form.value.price.push({ label: "", amount: 0 });
}
function removePrice(index) {
  form.value.price.splice(index, 1);
}
function addFeature() {
  form.value.features.push({ label: "", description: "" });
}
function removeFeature(index) {
  form.value.features.splice(index, 1);
}
</script>
