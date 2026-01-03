<!-- @/components/RichTextEditor.vue -->
<script setup lang="ts">
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

interface Props {
  modelValue: string;
  placeholder?: string;
  error?: string;
  label?: string;
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Start typing...',
  label: '',
  error: ''
});

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>();

const editorOptions = {
  theme: 'snow',
  placeholder: props.placeholder,
  modules: {
    toolbar: [
      ['bold', 'italic', 'underline', 'strike'],
      ['blockquote', 'code-block'],
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
      [{ 'list': 'ordered' }, { 'list': 'bullet' }],
      [{ 'script': 'sub' }, { 'script': 'super' }],
      [{ 'indent': '-1' }, { 'indent': '+1' }],
      [{ 'direction': 'rtl' }],
      [{ 'size': ['small', false, 'large', 'huge'] }],
      [{ 'color': [] }, { 'background': [] }],
      [{ 'font': [] }],
      [{ 'align': [] }],
      ['clean'],
      ['link', 'image', 'video']
    ]
  }
};

const handleInput = (content: string) => {
  emit('update:modelValue', content);
};
</script>

<template>
  <div class="rich-text-editor">
    <label v-if="label" class="block text-sm font-medium text-teal-600 mb-2">
      {{ label }}
    </label>
    <QuillEditor
      :content="modelValue"
      :options="editorOptions"
      contentType="html"
      @update:content="handleInput"
      class="bg-white rounded-lg shadow-sm border border-gray-300 min-h-[200px]"
      :class="{ 'border-red-500': error }"
    />
    <p v-if="error" class="mt-1 text-sm text-red-600">
      {{ error }}
    </p>
  </div>
</template>

<style scoped>
:deep(.ql-toolbar) {
  border-top-left-radius: 0.375rem;
  border-top-right-radius: 0.375rem;
  border-bottom: 1px solid #e5e7eb;
}

:deep(.ql-container) {
  border-bottom-left-radius: 0.375rem;
  border-bottom-right-radius: 0.375rem;
  min-height: 200px;
  font-size: 16px;
}
</style>