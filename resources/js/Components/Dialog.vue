<script setup>
import { ref, watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  header: { type: String, default: "Dialog" },
  width: { type: String, default: "30vw" },
});

const emit = defineEmits(["update:modelValue", "save", "cancel"]);

const visible = ref(props.modelValue);

watch(() => props.modelValue, (val) => (visible.value = val));
watch(visible, (val) => emit("update:modelValue", val));
</script>

<template>
  <Dialog
    v-model:visible="visible"
    :header="header"
    modal
    class="p-fluid"
    :style="{ width: width }"
  >
    <slot></slot>

    <template #footer>
      <Button label="Cancel" icon="pi pi-times" @click="$emit('cancel')" class="p-button-text"/>
      <Button label="Save" icon="pi pi-check" @click="$emit('save')" autofocus/>
    </template>
  </Dialog>
</template>
