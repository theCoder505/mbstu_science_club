<script setup lang="ts">
import Input from "@/components/Input.vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { useForm } from "@inertiajs/vue3";
import { Pencil } from "lucide-vue-next";
import Swal from "sweetalert2";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Website Settings",
    href: "/website-settings",
  },
];

const props = defineProps<{
  settings: {
    president_name: string;
    facebook_link: string;
    twitter_link: string;
    instagram_link: string;
    linkedin_link: string;
    contact_email: string;
    phone_number: string;
    join_form: string;
    facebook_page_iframe: string;
    about_us: string;
    privacy_policy: string;
    terms_conditions: string;
    code_of_conduct: string;
    president_sign: string;
  };
}>();

interface FormData {
  president_name: string;
  facebook_link: string;
  twitter_link: string;
  instagram_link: string;
  linkedin_link: string;
  contact_email: string;
  phone_number: string;
  join_form: string;
  facebook_page_iframe: string;
  about_us: string;
  privacy_policy: string;
  terms_conditions: string;
  code_of_conduct: string;
  president_sign: File | string | null;
  sign_preview: string;
}

const form = useForm<FormData>({
  president_name: props.settings.president_name,
  facebook_link: props.settings.facebook_link,
  twitter_link: props.settings.twitter_link,
  instagram_link: props.settings.instagram_link,
  linkedin_link: props.settings.linkedin_link,
  contact_email: props.settings.contact_email,
  phone_number: props.settings.phone_number,
  join_form: props.settings.join_form,
  facebook_page_iframe: props.settings.facebook_page_iframe,
  about_us: props.settings.about_us,
  privacy_policy: props.settings.privacy_policy,
  terms_conditions: props.settings.terms_conditions,
  code_of_conduct: props.settings.code_of_conduct,
  sign_preview: props.settings.president_sign
    ? `/storage/${props.settings.president_sign}`
    : null,
  president_sign: props.settings.president_sign,
});

const showSign = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    form.president_sign = file;
    form.sign_preview = URL.createObjectURL(file);
  }
};

const saveUpdate = () => {
  form.post("/website-settings/update", {
    onSuccess: () => {
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: String("Changes Updated successfully!"),
      });
    },
  });
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Website Settings" />

    <div class="px-4 lg:px-8 my-12">
      <div class="mb-6">
        <Form
          @submit.prevent="saveUpdate"
          class="bg-teal-50 p-4 lg:p-6 rounded-xl shadow-lg border border-teal-100 grid lg:grid-cols-2 gap-6"
        >
          <h1
            class="text-center text-2xl capitalize col-span-2 mb-2 font-bold text-teal-700 flex items-center justify-center gap-2"
          >
            <Pencil class="w-6 h-6" />
            Update Website Settings
          </h1>

          <div class="relative">
            <div class="mb-4">
              <label
                class="block text-sm font-medium text-teal-700 mb-2 cursor-pointer"
                for="signature"
              >
                Current Signature:

                <div v-if="form.sign_preview" class="mt-2">
                  <img
                    :src="form.sign_preview"
                    alt="Current Signature"
                    class="w-48 h-32 rounded-lg object-contain border-2 border-teal-300 bg-white p-2 shadow-sm"
                  />
                </div>
                <div
                  v-else
                  class="w-48 h-32 rounded-lg bg-gradient-to-r from-teal-100 to-teal-50 border-2 border-teal-300 flex items-center justify-center mb-4"
                >
                  <FileText class="w-16 h-16 text-teal-300" />
                </div>
              </label>
            </div>

            <input
              type="file"
              id="signature"
              class="hidden"
              accept="image/*"
              @change="showSign"
            />
            <small
              v-if="form.errors.president_sign"
              class="text-red-500 font-medium text-sm mt-2 block"
            >
              {{ form.errors.president_sign }}
            </small>
          </div>

          <Input
            placeholder="Current President Name"
            v-model="form.president_name"
            :message="form.errors.president_name"
            type="text"
          />
          <Input
            placeholder="Facebook Link"
            v-model="form.facebook_link"
            :message="form.errors.facebook_link"
            type="text"
          />
          <Input
            placeholder="Twitter Link"
            v-model="form.twitter_link"
            :message="form.errors.twitter_link"
            type="text"
          />
          <Input
            placeholder="Instagram Link"
            v-model="form.instagram_link"
            :message="form.errors.instagram_link"
            type="text"
          />
          <Input
            placeholder="Linkedin Link"
            v-model="form.linkedin_link"
            :message="form.errors.linkedin_link"
            type="text"
          />
          <Input
            placeholder="Contact Email Address"
            v-model="form.contact_email"
            :message="form.errors.contact_email"
            type="text"
          />
          <Input
            placeholder="Contact Phone Number"
            v-model="form.phone_number"
            :message="form.errors.phone_number"
            type="text"
          />

          <div class="w-full relative">
            <textarea
              v-model="form.join_form"
              class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50"
              :class="
                form.errors.join_form
                  ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                  : 'border-teal-300 focus:ring-teal-500 focus:border-teal-400'
              "
              placeholder="Club Facebook Page Iframe"
            ></textarea>
            <small
              v-if="form.errors.join_form"
              class="text-red-500 font-medium text-sm mt-2 block"
              >{{ form.errors.join_form }}</small
            >
          </div>
          
          <div class="w-full relative">
            <textarea
              v-model="form.facebook_page_iframe"
              class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50"
              :class="
                form.errors.facebook_page_iframe
                  ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                  : 'border-teal-300 focus:ring-teal-500 focus:border-teal-400'
              "
              placeholder="Club Facebook Page Iframe"
            ></textarea>
            <small
              v-if="form.errors.facebook_page_iframe"
              class="text-red-500 font-medium text-sm mt-2 block"
              >{{ form.errors.facebook_page_iframe }}</small
            >
          </div>

          <div class="col-span-2">
            <RichTextEditor
              v-model="form.about_us"
              :error="form.errors.about_us"
              label="About Us Content"
              placeholder="Write about us content here..."
            />
          </div>

          <div class="col-span-2">
            <RichTextEditor
              v-model="form.privacy_policy"
              :error="form.errors.privacy_policy"
              label="Privacy Policies Content"
              placeholder="Write Privacy Policies content here..."
            />
          </div>

          <div class="col-span-2">
            <RichTextEditor
              v-model="form.terms_conditions"
              :error="form.errors.terms_conditions"
              label="Terms & Conditions Content"
              placeholder="Write Terms & Conditions content here..."
            />
          </div>

          <div class="col-span-2">
            <RichTextEditor
              v-model="form.code_of_conduct"
              :error="form.errors.code_of_conduct"
              label="Club Code Of Conduct/Constitution"
              placeholder="Write Club Code Of Conduct/Constitution here..."
            />
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="col-span-2 w-full px-10 py-3 bg-teal-600 text-white font-bold text-xl rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center min-w-[200px]"
          >
            <span v-if="form.processing">Updating...</span>
            <span v-else class="flex items-center gap-2">
              <Pencil class="w-5 h-5" />
              SAVE UPDATE
            </span>
          </button>
        </Form>
      </div>
    </div>
  </AppLayout>
</template>
