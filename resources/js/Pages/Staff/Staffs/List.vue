<template>
  <Head title="Staffs" />

  <AppLayout>
    <template #default>
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Staffs</h2>
        <Button label="New Staff" icon="pi pi-plus" class="p-button-success" @click="openModal('new')" />
      </div>

      <DataTable
        :value="staffs"
        paginator
        :rows="10"
        sortMode="multiple"
        responsiveLayout="scroll"
        class="shadow rounded"
      >
        <Column field="id" header="ID" sortable />
        <Column field="first_name" header="Nickname" sortable />
        <Column field="username" header="Username" sortable />
        <Column field="email" header="Email" sortable />
        <Column field="status" header="Status" sortable />

        <Column header="Actions" :exportable="false">
          <template #body="slotProps">
            <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2"
              @click="openModal('edit', slotProps.data)" />
            <Button icon="pi pi-trash" class="p-button-rounded p-button-danger"
              @click="confirmDelete(slotProps.data)" />
          </template>
        </Column>
      </DataTable>

      <!-- Modal for Create/Edit -->
      <DynamicModal v-model="modalVisible" :title="modalTitle" @save="saveStaff">
        <div class="space-y-3">
          <div>
            <label class="block text-sm mb-1">Nickname</label>
            <input v-model="form.first_name" type="text" class="input w-full" />
          </div>
          <div>
            <label class="block text-sm mb-1">Username</label>
            <input v-model="form.username" type="text" class="input w-full" />
          </div>
          <div>
            <label class="block text-sm mb-1">Email</label>
            <input v-model="form.email" type="email" class="input w-full" />
          </div>
          <div>
            <label class="block text-sm mb-1">Status</label>
            <select v-model="form.status" class="input w-full">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
      </DynamicModal>

      <!-- Toast -->
      <DynamicToast />
    </template>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import DynamicModal from '@/Components/Modal.vue'
import { useToast } from 'primevue/usetoast'

defineProps({ data: Array })

// Reactive state
const staffs = ref([...data])
const selectedStaff = ref(null)
const modalVisible = ref(false)
const modalTitle = ref('')
const form = ref({ id: null, first_name: '', username: '', email: '', status: 'active' })

const toast = useToast()

// Open modal for create or edit
function openModal(type, staff = null) {
  if (type === 'new') {
    modalTitle.value = 'Create Staff'
    form.value = { id: null, first_name: '', username: '', email: '', status: 'active' }
  } else if (type === 'edit') {
    modalTitle.value = 'Edit Staff'
    form.value = { ...staff }
  }
  modalVisible.value = true
}

// Save staff (create or update)
function saveStaff() {
  if (form.value.id) {
    // Update
    router.patch(route('staffs.update', form.value.id), form.value, {
      onSuccess: () => {
        toast.add({ severity: 'success', summary: 'Updated', detail: 'Staff updated successfully' })
        modalVisible.value = false
      },
    })
  } else {
    // Create
    router.post(route('staffs.store'), form.value, {
      onSuccess: () => {
        toast.add({ severity: 'success', summary: 'Created', detail: 'Staff created successfully' })
        modalVisible.value = false
      },
    })
  }
}

// Confirm delete
function confirmDelete(staff) {
  if (confirm(`Are you sure you want to delete ${staff.username}?`)) {
    router.delete(route('staffs.destroy', staff.id), {
      onSuccess: () => {
        toast.add({ severity: 'success', summary: 'Deleted', detail: 'Staff deleted successfully' })
      },
    })
  }
}
</script>
