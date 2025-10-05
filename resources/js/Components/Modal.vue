<script setup>
import { ref, watch, computed } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  header: { type: String, default: "Dialog" },
  width: { type: String, default: "" }, // اگر وارد شود، همان استفاده می‌شود
  size: { type: String, default: "medium" }, // small | medium | large
  hideSave: { type: Boolean, default: false }, // برای Delete Confirm
});

const emit = defineEmits(["update:modelValue", "save", "cancel"]);

const visible = ref(props.modelValue);

// هماهنگ کردن v-model
watch(
  () => props.modelValue,
  (val) => (visible.value = val)
);
watch(visible, (val) => emit("update:modelValue", val));

// محاسبه width بر اساس size یا props.width
const computedWidth = computed(() => {
  if (props.width) return props.width;
  switch (props.size) {
    case "small":
      return "25vw"; // Delete Confirm
    case "medium":
      return "35vw"; // Change Password
    case "large":
      return "60vw"; // Create/Edit
    default:
      return "30vw";
  }
});

// رویداد Cancel
function handleCancel() {
  visible.value = false;
  emit("cancel");
}

// رویداد Save
function handleSave() {
  emit("save");
  visible.value = false;
}
</script>

<template>
  <Dialog
    v-model:visible="visible"
    :header="header"
    modal
    class="p-fluid"
    :style="{ width: computedWidth, maxWidth: '95vw' }"
    :breakpoints="{ '960px': '95vw', '640px': '90vw' }"
  >
    <slot></slot>

    <template #footer>
      <Button
        label="Cancel"
        icon="pi pi-times"
        @click="handleCancel"
        class="p-button-text"
      />
      <Button
        v-if="!hideSave"
        label="Save"
        icon="pi pi-check"
        @click="handleSave"
        autofocus
      />
    </template>
  </Dialog>
</template>
