<script setup lang="ts">
import Input from "@/components/Input.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps<{
  teammate: {
    id: number;
    teammate_name: string;
    department: string;
    designation: string;
    membership_period: string;
    small_desc: string;
    details: string;
    facebook_link: string | null;
    linkedin_link: string | null;
    teammate_image: string | null;
  };
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Teammates",
    href: "/manage-team",
  },
  {
    title: "Edit Teammate",
    href: `/manage-team/${props.teammate.id}`,
  },
];

interface FormData {
  id: number;
  teammate_name: string;
  department: string;
  designation: string;
  membership_period: string;
  small_desc: string;
  details: string;
  facebook_link: string | null;
  linkedin_link: string | null;
  teammate_image: File | null;
  teammate_image_preview: string | null;
  current_image: string | null;
}

const form = useForm<FormData>({
  id: props.teammate.id,
  teammate_name: props.teammate.teammate_name,
  department: props.teammate.department,
  designation: props.teammate.designation,
  membership_period: props.teammate.membership_period,
  small_desc: props.teammate.small_desc,
  details: props.teammate.details,
  facebook_link: props.teammate.facebook_link,
  linkedin_link: props.teammate.linkedin_link,
  teammate_image: null,
  teammate_image_preview: props.teammate.teammate_image 
    ? `/storage/${props.teammate.teammate_image}` 
    : null,
  current_image: props.teammate.teammate_image,
});

const showProfile = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    form.teammate_image = file;
    form.teammate_image_preview = URL.createObjectURL(file);
  }
};

const handleSubmit = () => {
  form.transform((data) => ({
    ...data,
    id: form.id,
    teammate_image: form.teammate_image,
  })).post('/manage-team/update', {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: String("Teammate updated successfully!"),
      });
    },
    onError: (errors) => {
      console.error('Form errors:', errors);
    },
  });
};

const handleDelete = () => {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0d9488',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      form.transform(() => ({
        id: form.id,
      })).post('/manage-team/destroy', {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: String("Teammate deleted successfully!"),
          });
        },
      });
    }
  });
};
</script>

<template>
  <Head title="Edit Teammate" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-12">
      <form @submit.prevent="handleSubmit" class="bg-teal-50 p-4 lg:p-6 rounded-lg shadow grid lg:grid-cols-2 gap-3">
        <h1
          class="text-center text-2xl capitalize col-span-2 mb-2 font-bold text-teal-700"
        >
          Edit Teammate
        </h1>

        <div class="relative">
          <label
            for="teammate_image"
            class="block w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50 border-teal-300 focus:ring-teal-500 focus:border-teal-400 cursor-pointer hover:bg-teal-100 flex flex-col items-center justify-center"
          >
            <img
              v-if="form.teammate_image_preview"
              :src="form.teammate_image_preview"
              alt="Profile Preview"
              class="w-[200px] rounded border-teal-500 bg-teal-500 mt-4 mb-2"
            />
            <span v-else class="text-center py-8">Browse Profile Picture</span>
          </label>
          <input
            type="file"
            id="teammate_image"
            name="teammate_image"
            class="hidden"
            accept="image/*"
            @change="showProfile"
          />
          <small
            v-if="form.errors.teammate_image"
            class="text-red-500 font-medium text-sm mt-2 block"
          >
            {{ form.errors.teammate_image }}
          </small>
          <small class="text-teal-600 text-sm mt-2 block text-center">
            Click to change picture (optional)
          </small>
        </div>

        <Input
          placeholder="Teammate Name"
          v-model="form.teammate_name"
          :message="form.errors.teammate_name"
          type="text"
          required
        />

        <Input
          placeholder="Department"
          v-model="form.department"
          :message="form.errors.department"
          type="text"
          required
        />

        <Input
          placeholder="Designation"
          v-model="form.designation"
          :message="form.errors.designation"
          type="text"
          required
        />

        <Input
          placeholder="Membership Period. eg: 2024-25"
          v-model="form.membership_period"
          :message="form.errors.membership_period"
          type="text"
          required
        />

        <Input
          placeholder="Facebook Link"
          v-model="form.facebook_link"
          :message="form.errors.facebook_link"
          type="text"
        />

        <Input
          placeholder="Linkedin Link"
          v-model="form.linkedin_link"
          :message="form.errors.linkedin_link"
          type="text"
        />

        <div class="relative">
          <textarea
            v-model="form.small_desc"
            placeholder="Small Description..."
            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50 h-[50px]"
            :class="
              form.errors.small_desc
                ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                : 'border-teal-300 focus:ring-teal-500 focus:border-teal-400'
            "
          ></textarea>
          <small
            v-if="form.errors.small_desc"
            class="text-red-500 font-medium text-sm mt-2 block"
            >{{ form.errors.small_desc }}</small
          >
        </div>

        <div class="relative col-span-2">
          <textarea
            v-model="form.details"
            placeholder="Teammate Career Details..."
            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50 min-h-[150px]"
            :class="
              form.errors.details
                ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                : 'border-teal-300 focus:ring-teal-500 focus:border-teal-400'
            "
          ></textarea>
          <small
            v-if="form.errors.details"
            class="text-red-500 font-medium text-sm mt-2 block"
            >{{ form.errors.details }}</small
          >
        </div>

        <div class="relative bottom-0 col-span-2 flex gap-4">
          <button
            type="submit"
            :disabled="form.processing"
            class="flex-1 px-10 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-bold text-xl rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center min-w-[200px]"
          >
            <span v-if="form.processing">Updating...</span>
            <span v-else>UPDATE</span>
          </button>

          <button
            type="button"
            @click="handleDelete"
            :disabled="form.processing"
            class="flex-1 px-10 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold text-xl rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center min-w-[200px]"
          >
            DELETE
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>