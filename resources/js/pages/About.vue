<script setup lang="ts">
import WebLayout from "@/layouts/WebLayout.vue";

interface Advisor {
  id: number;
  advisor_name: string;
  email: string;
  department: string;
  designation: string;
  club_role: string;
  profile_image: string;
  facebook?: string;
  twitter?: string;
  linkedin?: string;
  research_gate?: string;
  google_scholar?: string;
  portfolio?: string;
}

interface TeamMember {
  id: number;
  teammate_image: string;
  teammate_name: string;
  department: string;
  designation: string;
  membership_period: string;
  small_desc: string;
  facebook_link?: string;
  linkedin_link?: string;
}

withDefaults(
  defineProps<{
    canRegister?: boolean;
    advisors: Advisor[];
    team: TeamMember[];
    data: string;
  }>(),
  {
    canRegister: true,
  }
);
</script>

<template>
  <Head title="About Us" />
  <WebLayout>
    <section id="about" class="py-24 bg-gradient-to-b from-slate-50 to-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-700 mb-4"
          >
            About MBSTUSC
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto"
          ></div>
        </div>

        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div class="space-y-6">
            <p class="text-lg text-gray-700 leading-relaxed">
              The Mawlana Bhashani Science and Technology University Science Club
              (MBSTUSC) is a vibrant community of passionate students dedicated to
              advancing scientific knowledge and innovation.
            </p>
            <p class="text-lg text-gray-700 leading-relaxed">
              Our club provides a platform for students to engage in hands-on research,
              participate in workshops, attend seminars, and collaborate on exciting
              projects that push the boundaries of science and technology.
            </p>
            <p class="text-lg text-gray-700 leading-relaxed">
              Whether you're interested in physics, chemistry, biology, computer science,
              or any other scientific field, MBSTUSC offers opportunities for growth,
              learning, and making lasting connections with like-minded individuals.
            </p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div
              class="bg-gradient-to-br from-yellow-400 to-yellow-500 p-6 rounded-2xl text-gray-900 transform hover:scale-105 transition-transform duration-300"
            >
              <svg
                class="w-12 h-12 mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                />
              </svg>
              <h3 class="font-bold text-xl mb-2">Innovation</h3>
              <p class="text-sm">Fostering creative solutions</p>
            </div>

            <div
              class="bg-gradient-to-br from-teal-400 to-teal-500 p-6 rounded-2xl text-white transform hover:scale-105 transition-transform duration-300"
            >
              <svg
                class="w-12 h-12 mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                />
              </svg>
              <h3 class="font-bold text-xl mb-2">Research</h3>
              <p class="text-sm">Advancing scientific knowledge</p>
            </div>

            <div
              class="bg-gradient-to-br from-teal-500 to-teal-600 p-6 rounded-2xl text-white transform hover:scale-105 transition-transform duration-300"
            >
              <svg
                class="w-12 h-12 mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
              <h3 class="font-bold text-xl mb-2">Community</h3>
              <p class="text-sm">Building lasting connections</p>
            </div>

            <div
              class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-6 rounded-2xl text-gray-900 transform hover:scale-105 transition-transform duration-300"
            >
              <svg
                class="w-12 h-12 mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 10V3L4 14h7v7l9-11h-7z"
                />
              </svg>
              <h3 class="font-bold text-xl mb-2">Excellence</h3>
              <p class="text-sm">Striving for the best</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="py-24 bg-yellow-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div v-html="$page.props.data" v-if="$page.props.data"></div>
      </div>
    </div>

    <!-- Advisors Section -->
    <section class="py-24 bg-slate-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text text-white mb-4"
          >
            Our Distinguished Advisors
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto mb-6"
          ></div>
          <p class="text-lg text-gray-200 max-w-3xl mx-auto">
            Meet the dedicated faculty members guiding our journey towards scientific
            excellence
          </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
          <div
            v-for="advisor in advisors"
            :key="advisor.id"
            class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-yellow-400"
          >
            <div class="p-8">
              <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                  <div
                    class="relative w-24 h-24 rounded-full overflow-hidden border-4 border-yellow-400 group-hover:border-teal-400 transition-colors duration-300"
                  >
                    <img
                      :src="`/storage/${advisor.profile_image}`"
                      :alt="advisor.advisor_name"
                      class="w-full h-full object-cover"
                    />
                  </div>
                </div>

                <div class="flex-1">
                  <h3 class="text-2xl font-bold text-gray-900 mb-1">
                    {{ advisor.advisor_name }}
                  </h3>
                  <p class="text-teal-600 font-semibold mb-2">
                    {{ advisor.designation }}
                  </p>
                  <p class="text-gray-600 mb-1">Department of {{ advisor.department }}</p>
                  <p
                    class="inline-block px-3 py-1 bg-yellow-400 text-gray-900 font-semibold text-sm rounded-full"
                  >
                    {{ advisor.club_role }}
                  </p>
                </div>
              </div>

              <div class="mt-6 flex items-center gap-2">
                <svg
                  class="w-5 h-5 text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
                <a
                  :href="`mailto:${advisor.email}`"
                  class="text-gray-600 hover:text-teal-600 transition-colors"
                  >{{ advisor.email }}</a
                >
              </div>

              <div class="mt-4 flex gap-3">
                <a
                  v-if="advisor.linkedin"
                  :href="advisor.linkedin"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-teal-400 hover:text-white transition-all duration-300"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                    />
                  </svg>
                </a>
                <a
                  v-if="advisor.research_gate"
                  :href="advisor.research_gate"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-teal-400 hover:text-white transition-all duration-300"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M19.586 0c-.818 0-1.508.19-2.073.565-.563.377-.97.936-1.213 1.68a3.193 3.193 0 0 0-.112.437 8.365 8.365 0 0 0-.078.53 9 9 0 0 0-.05.727c-.01.282-.013.621-.013 1.016a31.121 31.121 0 0 0 .014 1.017 9 9 0 0 0 .05.727 7.946 7.946 0 0 0 .077.53c.025.153.063.306.112.437.245.744.65 1.303 1.214 1.68.565.376 1.256.564 2.074.564.817 0 1.508-.188 2.073-.565.564-.376.97-.935 1.213-1.68.049-.13.087-.283.112-.436a8.046 8.046 0 0 0 .078-.531 8.96 8.96 0 0 0 .05-.726c.01-.283.013-.622.013-1.017a31.121 31.121 0 0 0-.014-1.016 9 9 0 0 0-.05-.727 7.946 7.946 0 0 0-.077-.53 2.628 2.628 0 0 0-.112-.437c-.245-.744-.65-1.303-1.214-1.68-.565-.377-1.256-.565-2.074-.565zM8.857 5.857c-.818 0-1.508.19-2.073.565-.563.377-.97.936-1.213 1.68a3.193 3.193 0 0 0-.112.437 8.365 8.365 0 0 0-.078.53 9 9 0 0 0-.05.727c-.01.282-.013.621-.013 1.016a31.121 31.121 0 0 0 .014 1.017 9 9 0 0 0 .05.727 7.946 7.946 0 0 0 .077.53c.025.153.063.306.112.437.245.744.65 1.303 1.214 1.68.565.376 1.256.564 2.074.564.817 0 1.508-.188 2.073-.565.564-.376.97-.935 1.213-1.68.049-.13.087-.283.112-.436a8.046 8.046 0 0 0 .078-.531 8.96 8.96 0 0 0 .05-.726c.01-.283.013-.622.013-1.017a31.121 31.121 0 0 0-.014-1.016 9 9 0 0 0-.05-.727 7.946 7.946 0 0 0-.077-.53 2.628 2.628 0 0 0-.112-.437c-.245-.744-.65-1.303-1.214-1.68-.565-.377-1.256-.565-2.074-.565z"
                    />
                  </svg>
                </a>
                <a
                  v-if="advisor.google_scholar"
                  :href="advisor.google_scholar"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-teal-400 hover:text-white transition-all duration-300"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M5.242 13.769L0 9.5 12 0l12 9.5-5.242 4.269C17.548 11.249 14.978 9.5 12 9.5c-2.977 0-5.548 1.748-6.758 4.269zM12 10a7 7 0 1 0 0 14 7 7 0 0 0 0-14z"
                    />
                  </svg>
                </a>
                <a
                  v-if="advisor.portfolio"
                  :href="advisor.portfolio"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-teal-400 hover:text-white transition-all duration-300"
                >
                  <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                    />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section v-if="team.length > 0" class="py-24 bg-gradient-to-b from-slate-50 to-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-700 mb-4"
          >
            Our Legacy Team
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto mb-6"
          ></div>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Honoring the pioneers who shaped our club's foundation
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <Link
            v-for="member in team"
            :key="member.id"
            :href="'/teammate/' + member.id + '/' + member.teammate_name"
            class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden"
          >
            <div class="relative h-64 overflow-hidden">
              <img
                :src="`/storage/${member.teammate_image}`"
                :alt="member.teammate_name"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60"
              ></div>
              <div class="absolute bottom-4 left-4 right-4">
                <h3 class="text-2xl font-bold text-white mb-1">
                  {{ member.teammate_name }}
                </h3>
                <p class="text-yellow-400 font-semibold">{{ member.designation }}</p>
              </div>
            </div>

            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">{{ member.department }}</span>
                <span
                  class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold"
                  >{{ member.membership_period }}</span
                >
              </div>
              <p class="text-gray-700 text-sm leading-relaxed">{{ member.small_desc }}</p>

              <div class="mt-4 flex gap-2">
                <a
                  v-if="member.facebook_link"
                  :href="member.facebook_link"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-yellow-400 transition-colors"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                    />
                  </svg>
                </a>
                <a
                  v-if="member.linkedin_link"
                  :href="member.linkedin_link"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-yellow-400 transition-colors"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                    />
                  </svg>
                </a>
              </div>
            </div>
          </Link>
        </div>
      </div>
    </section>
  </WebLayout>
</template>



<style>
.ql-align-center {
  text-align: center;
}

.ql-align-right {
  text-align: right;
}

.ql-align-justify {
  text-align: justify;
}

/* Text Size */
.ql-size-small {
  font-size: 0.75rem;
}

.ql-size-large {
  font-size: 1.5rem;
}

.ql-size-huge {
  font-size: 2.5rem;
}

/* Text Formatting */
.ql-font-serif {
  font-family: Georgia, Times New Roman, serif;
}

.ql-font-monospace {
  font-family: Monaco, Courier New, monospace;
}

/* Lists */
.ql-list[value="bullet"] {
  list-style-type: disc;
}

.ql-list[value="ordered"] {
  list-style-type: decimal;
}

.ql-indent-1 {
  padding-left: 3rem;
}

.ql-indent-2 {
  padding-left: 6rem;
}

.ql-indent-3 {
  padding-left: 9rem;
}

.ql-indent-4 {
  padding-left: 12rem;
}

.ql-indent-5 {
  padding-left: 15rem;
}

.ql-indent-6 {
  padding-left: 18rem;
}

.ql-indent-7 {
  padding-left: 21rem;
}

.ql-indent-8 {
  padding-left: 24rem;
}

/* Headers */
h1.ql-align-center,
h2.ql-align-center,
h3.ql-align-center,
h4.ql-align-center,
h5.ql-align-center,
h6.ql-align-center {
  text-align: center;
}

/* Links */
.ql-editor a {
  color: #3b82f6;
  text-decoration: underline;
}

.ql-editor a:hover {
  color: #1d4ed8;
}

/* Blockquotes */
.ql-editor blockquote {
  border-left: 4px solid #e5e7eb;
  padding-left: 1rem;
  margin: 1rem 0;
  color: #6b7280;
}

/* Code blocks */
.ql-editor pre.ql-syntax {
  background-color: #1f2937;
  color: #e5e7eb;
  padding: 1rem;
  border-radius: 0.5rem;
  overflow-x: auto;
  font-family: Monaco, Courier New, monospace;
}

/* Code inline */
.ql-editor code {
  background-color: #f3f4f6;
  padding: 0.2rem 0.4rem;
  border-radius: 0.25rem;
  font-family: Monaco, Courier New, monospace;
  font-size: 0.875rem;
}

/* Images */
.ql-editor img {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
}

.ql-align-center img {
  display: block;
  margin-left: auto;
  margin-right: auto;
}

/* Tables */
.ql-editor table {
  width: 100%;
  border-collapse: collapse;
  margin: 1rem 0;
}

.ql-editor table td,
.ql-editor table th {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
}

.ql-editor table th {
  background-color: #f9fafb;
  font-weight: 600;
}

/* Text Colors */
.ql-color-red {
  color: #ef4444;
}

.ql-color-orange {
  color: #f97316;
}

.ql-color-yellow {
  color: #eab308;
}

.ql-color-green {
  color: #22c55e;
}

.ql-color-blue {
  color: #3b82f6;
}

.ql-color-purple {
  color: #8b5cf6;
}

/* Background Colors */
.ql-bg-red {
  background-color: #fecaca;
}

.ql-bg-orange {
  background-color: #fed7aa;
}

.ql-bg-yellow {
  background-color: #fef08a;
}

.ql-bg-green {
  background-color: #bbf7d0;
}

.ql-bg-blue {
  background-color: #bfdbfe;
}

.ql-bg-purple {
  background-color: #ddd6fe;
}

/* Custom MBSTUSC Styles */
.ql-size-large u {
  font-size: 2.5rem;
}

/* For the about content container */
.bg-yellow-50 .ql-editor {
  font-size: 1.125rem;
  line-height: 1.8;
  color: #374151;
}

.bg-yellow-50 .ql-editor h1,
.bg-yellow-50 .ql-editor h2,
.bg-yellow-50 .ql-editor h3,
.bg-yellow-50 .ql-editor h4,
.bg-yellow-50 .ql-editor h5,
.bg-yellow-50 .ql-editor h6 {
  color: #111827;
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: 700;
}

.bg-yellow-50 .ql-editor h1 {
  font-size: 2.5rem;
  border-bottom: 3px solid #fbbf24;
  padding-bottom: 0.5rem;
}

.bg-yellow-50 .ql-editor h2 {
  font-size: 2rem;
  color: #1e40af;
}

.bg-yellow-50 .ql-editor h3 {
  font-size: 1.5rem;
  color: #0f766e;
}

.bg-yellow-50 .ql-editor p {
  margin-bottom: 1.5rem;
}

.bg-yellow-50 .ql-editor ul,
.bg-yellow-50 .ql-editor ol {
  margin-left: 2rem;
  margin-bottom: 1.5rem;
}

.bg-yellow-50 .ql-editor li {
  margin-bottom: 0.5rem;
}

.bg-yellow-50 .ql-editor blockquote {
  border-left: 4px solid #fbbf24;
  background-color: #fef3c7;
  padding: 1.5rem;
  margin: 2rem 0;
  border-radius: 0.5rem;
  font-style: italic;
}

.bg-yellow-50 .ql-editor hr {
  border: none;
  height: 2px;
  background: linear-gradient(to right, #fbbf24, #0d9488);
  margin: 2.5rem 0;
}
</style>
