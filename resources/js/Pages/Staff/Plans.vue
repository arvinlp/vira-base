<template>
  <Head title="Plans" />

  <AppLayout>
    <template #default>
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Plans</h2>
        <Button
          label="New Plan"
          icon="pi pi-plus"
          class="p-button-success"
          @click="openModal('new')"
        />
      </div>

      <DataTable
        :value="plans"
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
              Plan List
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
        <Column field="price" class="w-48" header="Price" sortable />
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
        size="large"
        @save="savePlan"
      >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm mb-1">Name</label>
            <InputText v-model="form.name" type="text" fluid />
          </div>
          <div>
            <label class="block text-sm mb-1">Price</label>
            <InputText v-model="form.price" type="text" fluid />
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
        <div class="grid grid-cols-1">
          <div>
            <label class="block text-sm">Description</label>
            <Editor
              v-model="form.description"
              fluid
              editorStyle="height: 200px"
            >
              <template v-slot:toolbar>
                <span class="ql-formats">
                  <button v-tooltip.bottom="'Bold'" class="ql-bold"></button>
                  <button
                    v-tooltip.bottom="'Italic'"
                    class="ql-italic"
                  ></button>
                  <button
                    v-tooltip.bottom="'Underline'"
                    class="ql-underline"
                  ></button>
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
        @save="deletePlan"
      >
        <p>
          Are you sure you want to delete
          <strong>{{ selectedPlan?.username }}</strong
          >?
        </p>
      </DynamicModal>

      <!-- Modal for Change Password -->
      <DynamicModal
        v-model="passwordModalVisible"
        title="Change Password"
        header="Change Password"
        size="medium"
        @save="updatePassword"
      >
        <div class="grid grid-cols-1 gap-4">
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
import Editor from "primevue/editor";
import InputText from "primevue/inputtext";
import InputGroup from "primevue/inputgroup";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import DynamicModal from "@/Components/Modal.vue";
import DynamicToast from "@/Components/Toast.vue";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";

const page = usePage();

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

// Reactive state
const plans = ref([...page.props.data]);
const deleteModalVisible = ref(false);
const selectedPlan = ref(null);
const modalVisible = ref(false);
const modalTitle = ref("");
const form = ref({
  id: null,
  name: "",
  price: "",
  description: "",
  features: "",
  status: "active",
});
const status = ref([
  { name: "Active", code: "active" },
  { name: "Inactive", code: "inactive" },
]);
const toast = useToast();

// Open modal for create or edit
function openModal(type, plan = null) {
  if (type === "new") {
    modalTitle.value = "Create Plan";
    form.value = {
      id: null,
      name: "",
      price: "",
      description: "",
      features: "",
      status: "active",
    };
  } else if (type === "edit") {
    modalTitle.value = "Edit Plan";
    form.value = { ...plan };
  }
  modalVisible.value = true;
}

// Save plan (create or update)
function savePlan() {
  if (form.value.id) {
    // Update
    router.patch(route("panel.plans.update", form.value.id), form.value, {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Updated",
          detail: "Plan updated successfully",
          life: 3000,
        });
        modalVisible.value = false;
        router.reload({ only: ["data"] });
      },
      onError: (errors) => {
        toast.add({
          severity: "error",
          summary: "Error",
          detail: "Failed to update plan",
          life: 3000,
        });
      },
    });
  } else {
    // Create
    router.post(route("panel.plans.store"), form.value, {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Created",
          detail: "Plan created successfully",
          life: 3000,
        });
        modalVisible.value = false;
        router.reload({ only: ["data"] });
      },
      onError: (errors) => {
        toast.add({
          severity: "error",
          summary: "Error",
          detail: "Failed to create plan",
          life: 3000,
        });
      },
    });
  }
}

// Confirm delete
function confirmDelete(plan) {
  selectedPlan.value = plan;
  deleteModalVisible.value = true;
}
function deletePlan() {
  router.delete(route("panel.plans.destroy", selectedPlan.value.id), {
    onSuccess: () => {
      router.reload({ only: ["data"] }); // refresh list
      deleteModalVisible.value = false;
      toast.add({
        severity: "success",
        summary: "Deleted",
        detail: "Plan deleted successfully",
        life: 3000,
      });
    },
  });
}
</script>
