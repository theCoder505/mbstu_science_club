<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, onMounted, onUnmounted } from "vue";
import { toPng } from "html-to-image";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Applications",
    href: "/applications",
  },
  {
    title: "Process Certificate",
    href: "#",
  },
];

interface Application {
  id: number;
  applicant_name: string;
  email: string;
  designation?: string | null;
  member_since: string;
  member_till: string;
  impact?: string | null;
  certificate_text?: string | null;
  certificate_status: "pending" | "verified" | "revision" | "approved" | "declined";
  issued_by?: string | null;
  template_id?: string | null;
  note?: string | null;
  issue_date?: string | null;
  certificate_positions?: string | null;
  certificate_file?: string | null;
  created_at: string;
}

interface AdvisorType {
  id: number;
  advisor_name: string;
  signature: string;
}

interface TemplateType {
  id: number;
  template_name: string;
  template_image: string;
}

const props = defineProps<{
  application: Application;
  advisor_name: string;
  advisor_sign: string;
  president_name: string;
  president_sign: string;
  advisors: AdvisorType[];
  templates: TemplateType[];
  certificate_template: string;
}>();

// Reactive state for dynamic advisor and template
const currentAdvisorName = ref(props.advisor_name);
const currentAdvisorSign = ref(props.advisor_sign);
const currentCertificateTemplate = ref(props.certificate_template);

const form = useForm({
  application_id: props.application.id,
  note: props.application.note,
  certificate_text: props.application.certificate_text,
  certificate_status: props.application.certificate_status,
  certificate_issued_by: props.application.issued_by || "",
  certificate_template: props.application.template_id || "",
  certificate_positions: props.application.certificate_positions,
  certificate_file: props.application.certificate_file,
});

interface ElementPosition {
  x: number;
  y: number;
  width: number;
  height?: number;
  fontSize: number;
}

const defaultPositions: { [key: string]: ElementPosition } = {
  applicant_name: { x: 500, y: 240, width: 600, fontSize: 48 },
  certificate_text: { x: 120, y: 340, width: 760, fontSize: 16 },
  advisor_name: { x: 360, y: 615, width: 200, fontSize: 14 },
  advisor_sign: { x: 360, y: 545, width: 150, height: 50, fontSize: 14 },
  president_name: { x: 680, y: 615, width: 200, fontSize: 14 },
  president_sign: { x: 675, y: 545, width: 150, height: 50, fontSize: 14 },
};

const initializePositions = () => {
  if (props.application.certificate_positions) {
    try {
      return JSON.parse(props.application.certificate_positions);
    } catch (e) {
      console.error("Error parsing certificate positions:", e);
      return { ...defaultPositions };
    }
  }
  return { ...defaultPositions };
};

const positions = ref<{ [key: string]: ElementPosition }>(initializePositions());

const draggedElement = ref<string | null>(null);
const resizingElement = ref<string | null>(null);
const dragStart = ref({ x: 0, y: 0, elementX: 0, elementY: 0 });
const resizeStart = ref({ x: 0, y: 0, width: 0, fontSize: 0 });

const startDrag = (elementKey: string, event: MouseEvent) => {
  if (props.application.certificate_status === "approved") return;

  draggedElement.value = elementKey;
  dragStart.value = {
    x: event.clientX,
    y: event.clientY,
    elementX: positions.value[elementKey].x,
    elementY: positions.value[elementKey].y,
  };
  event.preventDefault();
};

const startResize = (elementKey: string, event: MouseEvent) => {
  if (props.application.certificate_status === "approved") return;

  resizingElement.value = elementKey;
  resizeStart.value = {
    x: event.clientX,
    y: event.clientY,
    width: positions.value[elementKey].width,
    fontSize: positions.value[elementKey].fontSize,
  };
  event.stopPropagation();
  event.preventDefault();
};

const onMouseMove = (event: MouseEvent) => {
  if (draggedElement.value) {
    const deltaX = event.clientX - dragStart.value.x;
    const deltaY = event.clientY - dragStart.value.y;

    positions.value[draggedElement.value].x = Math.max(
      0,
      Math.min(1000, dragStart.value.elementX + deltaX)
    );
    positions.value[draggedElement.value].y = Math.max(
      0,
      Math.min(1000, dragStart.value.elementY + deltaY)
    );
  }

  if (resizingElement.value) {
    const deltaX = event.clientX - resizeStart.value.x;
    const newWidth = Math.max(50, Math.min(900, resizeStart.value.width + deltaX));
    const scaleFactor = newWidth / resizeStart.value.width;

    positions.value[resizingElement.value].width = newWidth;
    positions.value[resizingElement.value].fontSize = Math.max(
      8,
      resizeStart.value.fontSize * scaleFactor
    );
    if (
      resizingElement.value.includes("sign") &&
      positions.value[resizingElement.value].height
    ) {
      positions.value[resizingElement.value].height = Math.max(
        30,
        positions.value[resizingElement.value].height! * scaleFactor
      );
    }
  }
};

const onMouseUp = () => {
  draggedElement.value = null;
  resizingElement.value = null;
};

onMounted(() => {
  document.addEventListener("mousemove", onMouseMove);
  document.addEventListener("mouseup", onMouseUp);
  if (textEditor.value && form.certificate_text) {
    textEditor.value.innerHTML = form.certificate_text;
  }
});

onUnmounted(() => {
  document.removeEventListener("mousemove", onMouseMove);
  document.removeEventListener("mouseup", onMouseUp);
});

const textEditor = ref<HTMLDivElement | null>(null);

const applyFormat = (command: string) => {
  if (!textEditor.value) return;
  textEditor.value.focus();
  document.execCommand(command, false, undefined);
  updateFormText();
};

const updateFormText = () => {
  if (textEditor.value) {
    form.certificate_text = textEditor.value.innerHTML;
  }
};

const ensureFontsLoaded = async () => {
  if (document.fonts && document.fonts.ready) {
    await document.fonts.ready;
  }
  await new Promise((resolve) => setTimeout(resolve, 300));
};

const generateCertificateImage = async (): Promise<string> => {
  const certificateElement = document.querySelector(".main_certificate") as HTMLElement;
  if (!certificateElement) {
    throw new Error("Certificate element not found");
  }

  await ensureFontsLoaded();

  const resizeHandles = certificateElement.querySelectorAll(".resize-handle");
  resizeHandles.forEach((handle: Element) => {
    (handle as HTMLElement).style.display = "none";
  });

  certificateElement.classList.add("downloading");

  await new Promise((resolve) => setTimeout(resolve, 200));

  const dataUrl = await toPng(certificateElement, {
    quality: 1.0,
    pixelRatio: 2,
    width: 1000,
    height: certificateElement.offsetHeight,
    backgroundColor: "transparent",
    cacheBust: true,
    skipFonts: true,
    filter: (node: HTMLElement) => {
      const className = node.className;
      if (typeof className === "string" && className.includes("resize-handle")) {
        return false;
      }
      return true;
    },
  });

  certificateElement.classList.remove("downloading");

  resizeHandles.forEach((handle: Element) => {
    (handle as HTMLElement).style.display = "";
  });

  return dataUrl;
};

const saveChanges = async () => {
  try {
    Swal.fire({
      title: "Processing...",
      text: "Generating and uploading certificate...",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    const certificateDataUrl = await generateCertificateImage();

    form.certificate_positions = JSON.stringify(positions.value);
    form.certificate_file = certificateDataUrl;

    form.certificate_issued_by = String(form.certificate_issued_by);
    form.certificate_template = String(form.certificate_template);

    form.post("/application/update", {
      preserveScroll: true,
      onSuccess: () => {
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Changes updated and certificate uploaded successfully!",
        });
      },
      onError: (errors) => {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Failed to save changes. Please try again.",
        });
        console.error("Save errors:", errors);
      },
    });
  } catch (error) {
    console.error("Error saving changes:", error);
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: "Failed to generate certificate. Please try again.",
    });
  }
};

const getDate = (date: any) => {
  return new Date(date).toLocaleDateString("en-uk", {
    year: "numeric",
    month: "numeric",
    day: "numeric",
  });
};

const updateAdvisor = () => {
  const selectedAdvisor = props.advisors.find(
    (advisor) => advisor.id === Number(form.certificate_issued_by)
  );
  
  if (selectedAdvisor) {
    currentAdvisorName.value = selectedAdvisor.advisor_name;
    currentAdvisorSign.value = '/storage/' + selectedAdvisor.signature;
    
    console.log("Advisor updated:", {
      name: currentAdvisorName.value,
      sign: currentAdvisorSign.value
    });
  } else {
    console.warn("Advisor not found for ID:", form.certificate_issued_by);
  }
};

const updateTemplate = () => {
  const selectedTemplate = props.templates.find(
    (template) => template.id === Number(form.certificate_template)
  );
  if (selectedTemplate) {
    currentCertificateTemplate.value = '/storage/' + selectedTemplate.template_image;
  } else {
    console.warn("Template not found for ID:", form.certificate_template);
  }
};
</script>

<template>
  <Head title="Application Details" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div
      class="certificate-wrapper bg-gradient-to-r from-yellow-300 to-yellow-500 my-8 mx-4 p-6 lg:mx-12 rounded-lg shadow-lg"
    >
      <div class="certificate-scroll-container">
        <div
          class="main_certificate"
          style="color: rgb(31, 41, 55); background-color: transparent"
        >
          <img :src="currentCertificateTemplate" alt="Certificate" class="certificate-image" />

          <!-- Applicant Name -->
          <div
            class="draggable-element applicant_name"
            :style="{
              left: positions.applicant_name.x + 'px',
              top: positions.applicant_name.y + 'px',
              width: positions.applicant_name.width + 'px',
              fontSize: positions.applicant_name.fontSize + 'px',
            }"
            @mousedown="startDrag('applicant_name', $event)"
          >
            <span class="element-content">{{ application.applicant_name }}</span>
            <div
              v-if="application.certificate_status !== 'approved'"
              class="resize-handle"
              @mousedown.stop="startResize('applicant_name', $event)"
            ></div>
          </div>

          <!-- Certificate Text with HTML rendering -->
          <div
            class="draggable-element certificate_text"
            :style="{
              left: positions.certificate_text.x + 'px',
              top: positions.certificate_text.y + 'px',
              width: positions.certificate_text.width + 'px',
              fontSize: positions.certificate_text.fontSize + 'px',
            }"
            @mousedown="startDrag('certificate_text', $event)"
          >
            <div class="element-content" v-html="form.certificate_text"></div>
            <div
              v-if="application.certificate_status !== 'approved'"
              class="resize-handle"
              @mousedown.stop="startResize('certificate_text', $event)"
            ></div>
          </div>

          <!-- Advisor Sign -->
          <div
            class="draggable-element advisor_sign"
            :style="{
              left: positions.advisor_sign.x + 'px',
              top: positions.advisor_sign.y + 'px',
              width: positions.advisor_sign.width + 'px',
              height: positions.advisor_sign.height + 'px',
            }"
            @mousedown="startDrag('advisor_sign', $event)"
          >
            <img :src="currentAdvisorSign" alt="Advisor Sign" class="signature-image" />
            <div
              v-if="application.certificate_status !== 'approved'"
              class="resize-handle"
              @mousedown.stop="startResize('advisor_sign', $event)"
            ></div>
          </div>

          <!-- Advisor Name -->
          <div
            class="draggable-element advisor_name"
            :style="{
              left: positions.advisor_name.x + 'px',
              top: positions.advisor_name.y + 'px',
              width: positions.advisor_name.width + 'px',
              fontSize: positions.advisor_name.fontSize + 'px',
            }"
            @mousedown="startDrag('advisor_name', $event)"
          >
            <span class="element-content">{{ currentAdvisorName }}</span>
            <div
              v-if="application.certificate_status !== 'approved'"
              class="resize-handle"
              @mousedown.stop="startResize('advisor_name', $event)"
            ></div>
          </div>

          <!-- President Sign -->
          <div
            class="draggable-element president_sign"
            :style="{
              left: positions.president_sign.x + 'px',
              top: positions.president_sign.y + 'px',
              width: positions.president_sign.width + 'px',
              height: positions.president_sign.height + 'px',
            }"
            @mousedown="startDrag('president_sign', $event)"
          >
            <img :src="president_sign" alt="President Sign" class="signature-image" />
            <div
              v-if="application.certificate_status !== 'approved'"
              class="resize-handle"
              @mousedown.stop="startResize('president_sign', $event)"
            ></div>
          </div>

          <!-- President Name -->
          <div
            class="draggable-element president_name"
            :style="{
              left: positions.president_name.x + 'px',
              top: positions.president_name.y + 'px',
              width: positions.president_name.width + 'px',
              fontSize: positions.president_name.fontSize + 'px',
            }"
            @mousedown="startDrag('president_name', $event)"
          >
            <span class="element-content">{{ president_name }}</span>
            <div
              v-if="application.certificate_status !== 'approved'"
              class="resize-handle"
              @mousedown.stop="startResize('president_name', $event)"
            ></div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="no-print mx-4 p-6 lg:p-12 lg:mx-12 rounded-lg shadow-lg bg-yellow-500 text-gray-900 mb-16"
    >
      <div class="flex justify-end">
        <div
          class="rounded-full px-4 py-2 inline-block font-bold uppercase text-xs"
          :class="[
            application.certificate_status == 'pending'
              ? 'text-yellow-700 bg-yellow-200'
              : '',
            application.certificate_status == 'verified'
              ? 'text-blue-700 bg-blue-200'
              : '',
            application.certificate_status == 'revision'
              ? 'text-orange-700 bg-orange-200'
              : '',
            application.certificate_status == 'approved'
              ? 'text-green-700 bg-green-200'
              : '',
            application.certificate_status == 'declined' ? 'text-red-700 bg-red-200' : '',
          ]"
        >
          {{ application.certificate_status }}
        </div>
      </div>

      <div class="lg:grid lg:grid-cols-4 space-y-4 space-x-4">
        <div class="">
          <b>Applicant Name: </b>
          <p class="capitalize text-sm">{{ application.applicant_name }}</p>
        </div>
        <div class="">
          <b>Applicant Email: </b>
          <a :href="'mailto:' + application.email" class="text-blue-700 block text-sm">{{
            application.email
          }}</a>
        </div>
        <div class="">
          <b>Applicant Designation: </b>
          <p class="capitalize text-sm">{{ application.designation }}</p>
        </div>
        <div class="">
          <b>Membership Period: </b>
          <p class="capitalize text-sm">
            {{ getDate(application.member_since) }} -
            {{ getDate(application.member_till) }}
          </p>
        </div>
        <div class="">
          <b>Applied At: </b>
          <p class="capitalize text-sm">
            {{ getDate(application.created_at) }}
          </p>
        </div>
        <div class="">
          <b>Advisor: </b>
          <p class="text-sm">
            {{ currentAdvisorName }}
          </p>
        </div>
        <div class="">
          <b>Issued At: </b>
          <p class="text-sm">
            {{ application.issue_date ? getDate(application.issue_date) : "N/A" }}
          </p>
        </div>

        <div class="col-span-2">
          <b>Contributions & Achievements: </b>
          <p class="p-4 border-2 border-dashed border-gray-500 rounded-lg mt-2 text-sm">
            {{ application.impact ? application.impact : "N/A" }}
          </p>
        </div>

        <div class="col-span-2">
          <b>Decline/Revision Note: </b>
          <textarea
            class="block border-2 border-gray-500 text-sm w-full p-4 rounded-lg mt-2 h-13"
            placeholder="Write what should be included in the certificate..."
            maxlength="300"
            v-model="form.note"
          ></textarea>
        </div>

        <div class="" v-if="application.certificate_status != 'approved'">
          <b>Change Status: </b>
          <select
            id="status"
            v-model="form.certificate_status"
            class="block border-2 border-gray-500 text-sm w-full p-4 rounded-lg mt-2"
          >
            <option value="pending">Pending</option>
            <option value="verified">Verified</option>
            <option value="revision">On Revision</option>
            <option value="declined">Declined</option>
          </select>
        </div>

        <div class="" v-if="application.certificate_status != 'approved'">
          <b>Select Advisor: </b>
          <select
            id="advisor"
            v-model="form.certificate_issued_by"
            @change="updateAdvisor"
            class="block border-2 border-gray-500 text-sm w-full p-4 rounded-lg mt-2"
          >
            <option
              :value="advisor.id"
              v-for="advisor in props.advisors"
              :key="advisor.id"
            >
              {{ advisor.advisor_name }}
            </option>
          </select>
        </div>

        <div class="col-span-2" v-if="application.certificate_status != 'approved'">
          <b>Certificate Template: </b>
          <select
            id="template"
            v-model="form.certificate_template"
            @change="updateTemplate"
            class="block border-2 border-gray-500 text-sm w-full p-4 rounded-lg mt-2"
          >
            <option
              :value="template.id"
              v-for="template in props.templates"
              :key="template.id"
            >
              {{ template.template_name }}
            </option>
          </select>
        </div>

        <div class="col-span-4" v-if="application.certificate_status != 'approved'">
          <b>Certificate Text: </b>

          <!-- Rich Text Formatting Toolbar -->
          <div class="formatting-toolbar mt-2 mb-2 flex gap-1 flex-wrap">
            <button
              type="button"
              @click="applyFormat('bold')"
              class="toolbar-btn"
              :class="{ active: false }"
              title="Bold (Ctrl+B)"
            >
              <strong>B</strong>
            </button>
            <button
              type="button"
              @click="applyFormat('italic')"
              class="toolbar-btn"
              :class="{ active: false }"
              title="Italic (Ctrl+I)"
            >
              <em>I</em>
            </button>
            <button
              type="button"
              @click="applyFormat('underline')"
              class="toolbar-btn"
              :class="{ active: false }"
              title="Underline (Ctrl+U)"
            >
              <u>U</u>
            </button>
          </div>

          <!-- Rich Text Editor -->
          <div
            ref="textEditor"
            contenteditable="true"
            class="rich-text-editor block border-2 border-gray-500 text-sm w-full p-4 rounded-lg min-h-[120px]"
            @input="updateFormText"
            @blur="updateFormText"
            placeholder="Write what should be included in the certificate..."
          ></div>
        </div>

        <div v-else class="col-span-2">
          <b>Certificate Text: </b>
          <div
            class="p-4 border-2 border-dashed border-gray-500 rounded-lg mt-2 text-sm"
            v-html="application.certificate_text"
          ></div>
        </div>

        <div class="col-span-4" v-if="application.certificate_status != 'approved'">
          <button
            @click="saveChanges"
            class="bg-white cursor-pointer uppercase text-gray-900 font-semibold px-6 py-3 lg:py-4 rounded-full w-full mt-2 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0"
          >
            Save Changes & Upload Certificate
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Certificate Wrapper - Responsive */
.certificate-wrapper {
  overflow-x: auto;
  overflow-y: hidden;
}

/* Certificate Scroll Container */
.certificate-scroll-container {
  min-width: 1000px;
  width: 1000px;
  margin: 0 auto;
}

/* Main Certificate - Fixed 1000px width */
.main_certificate {
  position: relative;
  width: 1000px;
  height: auto;
  margin: 0 auto;
}

.certificate-image {
  display: block;
  width: 1000px;
  height: auto;
  pointer-events: none;
  user-select: none;
}

/* Draggable Elements */
.draggable-element {
  position: absolute;
  cursor: move;
  user-select: none;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: box-shadow 0.2s;
}

.draggable-element:hover {
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
  border-radius: 4px;
}

/* Remove all styling when downloading */
.main_certificate.downloading .draggable-element {
  box-shadow: none !important;
  border: none !important;
  outline: none !important;
  background: none !important;
  background-color: transparent !important;
  padding: 0 !important;
  border-radius: 0 !important;
}

.main_certificate.downloading .draggable-element:hover {
  box-shadow: none !important;
}

.element-content {
  display: block;
  width: 100%;
  word-wrap: break-word;
}

/* Applicant Name Styling */
.applicant_name {
  font-weight: bold;
  text-transform: capitalize;
  color: #8b6914;
  background-color: transparent;
  font-family: "Great Vibes", "Brush Script MT", cursive;
  line-height: 1.2;
  transform: translateX(-50%);
}

/* Certificate Text Styling */
.certificate_text {
  color: #1f2937;
  background-color: transparent;
  text-align: center;
  line-height: 1.6;
  padding: 0 10px;
}

.certificate_text .element-content {
  margin: 0;
  color: #1f2937;
  background-color: transparent;
}

/* Preserve formatting in certificate text */
.certificate_text .element-content strong,
.certificate_text .element-content b {
  font-weight: bold;
}

.certificate_text .element-content em,
.certificate_text .element-content i {
  font-style: italic;
}

.certificate_text .element-content u {
  text-decoration: underline;
}

/* Advisor and President Names */
.advisor_name,
.president_name {
  font-weight: 600;
  color: #1f2937;
  background-color: transparent;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transform: translateX(-50%);
}

/* Signature Images */
.advisor_sign,
.president_sign {
  transform: translateX(-50%);
}

.signature-image {
  width: 100%;
  height: 100%;
  object-fit: contain;
  pointer-events: none;
}

/* Resize Handle */
.resize-handle {
  position: absolute;
  bottom: -5px;
  right: -5px;
  width: 12px;
  height: 12px;
  background: #3b82f6;
  border: 2px solid white;
  border-radius: 50%;
  cursor: nwse-resize;
  z-index: 10;
}

.resize-handle:hover {
  background: #2563eb;
  transform: scale(1.2);
}

/* Rich Text Editor Styles */
.rich-text-editor {
  outline: none;
}

.rich-text-editor:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.rich-text-editor[contenteditable]:empty:before {
  content: attr(placeholder);
  color: #9ca3af;
  pointer-events: none;
  display: block;
}

/* Preserve formatting in editor */
.rich-text-editor strong,
.rich-text-editor b {
  font-weight: bold;
}

.rich-text-editor em,
.rich-text-editor i {
  font-style: italic;
}

.rich-text-editor u {
  text-decoration: underline;
}

/* Formatting Toolbar */
.formatting-toolbar {
  display: flex;
  align-items: center;
}

.toolbar-btn {
  padding: 6px 12px;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  background-color: white;
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
  transition: all 0.2s;
  min-width: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.toolbar-btn:hover {
  background-color: #f3f4f6;
  border-color: #9ca3af;
}

.toolbar-btn:active,
.toolbar-btn.active {
  background-color: #3b82f6;
  color: white;
  border-color: #2563eb;
}

/* Responsive Layout for Forms */
@media (max-width: 1024px) {
  .certificate-wrapper {
    margin-left: 1rem;
    margin-right: 1rem;
  }
}

/* Mobile Adjustments */
@media (max-width: 640px) {
  .certificate-wrapper {
    padding: 1rem;
    margin: 1rem 0.5rem;
  }

  .formatting-toolbar {
    flex-wrap: wrap;
  }
}
</style>