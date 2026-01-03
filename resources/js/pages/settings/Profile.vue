<script setup lang="ts">
import { ref } from 'vue';
import { router, usePage, Head, useForm } from '@inertiajs/vue3';
import { edit } from '@/routes/profile';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';

interface Props {
    status?: string;
}

const route = window.route;

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: edit().url,
    },
];

const page = usePage();
const user = page.props.auth.user;

// Profile form
// const profileForm = useForm({
//     name: user.name,
// });

// const updateProfile = () => {
//     profileForm.post(route('admin.profile.update-name'), {
//         preserveScroll: true,
//     });
// };

// Email change
const emailForm = useForm({
    email: '',
});

const emailOtpDialog = ref(false);
const emailOtp = ref('');
const emailOtpLoading = ref(false);

const requestEmailChange = () => {
    emailForm.post(route('admin.profile.request-email-change'), {
        preserveScroll: true,
        onSuccess: () => {
            emailOtpDialog.value = true;
            emailOtp.value = '';
        },
    });
};

const verifyEmailOtp = () => {
    emailOtpLoading.value = true;
    router.post(
        route('admin.profile.verify-email-otp'),
        {
            otp: emailOtp.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                emailOtpDialog.value = false;
                emailOtp.value = '';
                emailForm.reset();
                emailForm.clearErrors();
            },
            onError: () => {
                emailOtp.value = '';
            },
            onFinish: () => {
                emailOtpLoading.value = false;
            },
        }
    );
};

// Password change
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const passwordOtpDialog = ref(false);
const passwordOtp = ref('');
const passwordOtpLoading = ref(false);

const requestPasswordChange = () => {
    passwordForm.post(route('admin.profile.request-password-change'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordOtpDialog.value = true;
            passwordOtp.value = '';
        },
    });
};

const verifyPasswordOtp = () => {
    passwordOtpLoading.value = true;
    router.post(
        route('admin.profile.verify-password-otp'),
        {
            otp: passwordOtp.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                passwordOtpDialog.value = false;
                passwordOtp.value = '';
                passwordForm.reset();
                passwordForm.clearErrors();
            },
            onError: () => {
                passwordOtp.value = '';
            },
            onFinish: () => {
                passwordOtpLoading.value = false;
            },
        }
    );
};

const cancelEmailChange = () => {
    emailOtpDialog.value = false;
    emailOtp.value = '';
    emailForm.clearErrors();
};

const cancelPasswordChange = () => {
    passwordOtpDialog.value = false;
    passwordOtp.value = '';
    passwordForm.clearErrors();
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Profile settings" />

    <SettingsLayout>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="space-y-6">
          <!-- Profile Information -->
          <div class="space-y-6">
            <HeadingSmall
              title="Profile information"
              description="Update your name and email address"
            />

            <!-- Name Update -->
            <!-- <form @submit.prevent="updateProfile" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                v-model="profileForm.name"
                                class="mt-1 block w-full"
                                required
                                autocomplete="name"
                                placeholder="Full name"
                            />
                            <InputError
                                class="mt-2"
                                :message="profileForm.errors.name"
                            />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button
                                type="submit"
                                :disabled="profileForm.processing"
                                data-test="update-name-button"
                            >
                                Update Name
                            </Button>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p
                                    v-show="profileForm.recentlySuccessful"
                                    class="text-sm text-neutral-600"
                                >
                                    Saved.
                                </p>
                            </Transition>
                        </div>
                    </form> -->

            <!-- Email Update -->
            <form @submit.prevent="requestEmailChange" class="space-y-6 border-t pt-6">
              <div class="grid gap-2">
                <Label for="email">New Email Address</Label>
                <Input
                  id="email"
                  v-model="emailForm.email"
                  type="email"
                  class="mt-1 block w-full"
                  required
                  autocomplete="username"
                  placeholder="New email address"
                />
                <InputError class="mt-2" :message="emailForm.errors.email" />
              </div>

              <div class="flex items-center gap-4">
                <Button
                  type="submit"
                  :disabled="emailForm.processing"
                  data-test="request-email-change-button"
                >
                  Change Email
                </Button>
              </div>
            </form>
          </div>

          <!-- Appearance Settings -->
          <div class="space-y-6 border-t pt-6">
            <HeadingSmall
              title="Appearance settings"
              description="Update your account's appearance settings"
            />
            <AppearanceTabs />
          </div>
        </div>

        <!-- Password Update -->
        <div class="space-y-6 border-t pt-6">
          <HeadingSmall
            title="Update password"
            description="Ensure your account is using a long, random password to stay secure"
          />

          <form @submit.prevent="requestPasswordChange" class="space-y-6">
            <div class="grid gap-2">
              <Label for="current_password">Current password</Label>
              <Input
                id="current_password"
                v-model="passwordForm.current_password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="current-password"
                placeholder="Current password"
              />
              <InputError :message="passwordForm.errors.current_password" />
            </div>

            <div class="grid gap-2">
              <Label for="password">New password</Label>
              <Input
                id="password"
                v-model="passwordForm.password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
                placeholder="New password"
              />
              <InputError :message="passwordForm.errors.password" />
            </div>

            <div class="grid gap-2">
              <Label for="password_confirmation">Confirm password</Label>
              <Input
                id="password_confirmation"
                v-model="passwordForm.password_confirmation"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
                placeholder="Confirm password"
              />
              <InputError :message="passwordForm.errors.password_confirmation" />
            </div>

            <div class="flex items-center gap-4">
              <Button
                type="submit"
                :disabled="passwordForm.processing"
                data-test="request-password-change-button"
              >
                Change Password
              </Button>
            </div>
          </form>
        </div>
      </div>
    </SettingsLayout>

    <!-- Email OTP Verification Dialog -->
    <Dialog v-model:open="emailOtpDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Verify Email Change</DialogTitle>
          <DialogDescription>
            We've sent a 6-digit verification code to your current email address ({{
              user.email
            }}). Please enter it below to confirm the email change.
          </DialogDescription>
        </DialogHeader>

        <div class="flex flex-col items-center space-y-4 py-4">
          <InputOTP v-model="emailOtp" :maxlength="6">
            <InputOTPGroup>
              <InputOTPSlot :index="0" />
              <InputOTPSlot :index="1" />
              <InputOTPSlot :index="2" />
              <InputOTPSlot :index="3" />
              <InputOTPSlot :index="4" />
              <InputOTPSlot :index="5" />
            </InputOTPGroup>
          </InputOTP>

          <p class="text-sm text-muted-foreground">Code expires in 10 minutes</p>

          <InputError :message="page.props.errors?.otp" />
        </div>

        <DialogFooter>
          <Button
            type="button"
            variant="outline"
            @click="cancelEmailChange"
            :disabled="emailOtpLoading"
          >
            Cancel
          </Button>
          <Button
            type="button"
            @click="verifyEmailOtp"
            :disabled="emailOtp.length !== 6 || emailOtpLoading"
          >
            Verify & Change Email
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Password OTP Verification Dialog -->
    <Dialog v-model:open="passwordOtpDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Verify Password Change</DialogTitle>
          <DialogDescription>
            We've sent a 6-digit verification code to your email address ({{
              user.email
            }}). Please enter it below to confirm the password change.
          </DialogDescription>
        </DialogHeader>

        <div class="flex flex-col items-center space-y-4 py-4">
          <InputOTP v-model="passwordOtp" :maxlength="6">
            <InputOTPGroup>
              <InputOTPSlot :index="0" />
              <InputOTPSlot :index="1" />
              <InputOTPSlot :index="2" />
              <InputOTPSlot :index="3" />
              <InputOTPSlot :index="4" />
              <InputOTPSlot :index="5" />
            </InputOTPGroup>
          </InputOTP>

          <p class="text-sm text-muted-foreground">Code expires in 10 minutes</p>

          <InputError :message="page.props.errors?.otp" />
        </div>

        <DialogFooter>
          <Button
            type="button"
            variant="outline"
            @click="cancelPasswordChange"
            :disabled="passwordOtpLoading"
          >
            Cancel
          </Button>
          <Button
            type="button"
            @click="verifyPasswordOtp"
            :disabled="passwordOtp.length !== 6 || passwordOtpLoading"
          >
            Verify & Change Password
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>

<style scoped>
button {
  cursor: pointer;
}
</style>
