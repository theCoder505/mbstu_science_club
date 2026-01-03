<script setup lang="ts">
import { ref, computed, onMounted, nextTick } from 'vue';
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Generate Certificate Template",
    href: "/templates",
  },
];

// Canvas settings
const canvasWidth = 900;
const canvasHeight = 550;

// Tool types
type Tool = 'select' | 'text' | 'rectangle' | 'circle' | 'line' | 'image';
type ElementType = 'text' | 'rectangle' | 'circle' | 'line' | 'image';

interface BaseElement {
  id: string;
  type: ElementType;
  x: number;
  y: number;
  width: number;
  height: number;
  rotation: number;
  locked: boolean;
  zIndex: number;
}

interface TextElement extends BaseElement {
  type: 'text';
  content: string;
  fontSize: number;
  fontFamily: string;
  color: string;
  fontWeight: string;
  fontStyle: string;
  textAlign: string;
}

interface ShapeElement extends BaseElement {
  type: 'rectangle' | 'circle' | 'line';
  fillColor: string;
  strokeColor: string;
  strokeColor: string;
  strokeWidth: number;
  opacity: number;
}

interface ImageElement extends BaseElement {
  type: 'image';
  src: string;
  opacity: number;
}

type Element = TextElement | ShapeElement | ImageElement;

// State
const currentTool = ref<Tool>('select');
const elements = ref<Element[]>([]);
const selectedElementId = ref<string | null>(null);
const isDragging = ref(false);
const isResizing = ref(false);
const isRotating = ref(false);
const dragStart = ref({ x: 0, y: 0 });
const canvas = ref<HTMLDivElement | null>(null);
const showGrid = ref(true);
const snapToGrid = ref(true);
const gridSize = 20;
const history = ref<Element[][]>([]);
const historyIndex = ref(-1);

// Computed
const selectedElement = computed(() => {
  return elements.value.find(el => el.id === selectedElementId.value) || null;
});

const sortedElements = computed(() => {
  return [...elements.value].sort((a, b) => a.zIndex - b.zIndex);
});

// Functions
function generateId(): string {
  return `el-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
}

function addToHistory() {
  const newHistory = history.value.slice(0, historyIndex.value + 1);
  newHistory.push(JSON.parse(JSON.stringify(elements.value)));
  history.value = newHistory;
  historyIndex.value = newHistory.length - 1;
}

function undo() {
  if (historyIndex.value > 0) {
    historyIndex.value--;
    elements.value = JSON.parse(JSON.stringify(history.value[historyIndex.value]));
    selectedElementId.value = null;
  }
}

function redo() {
  if (historyIndex.value < history.value.length - 1) {
    historyIndex.value++;
    elements.value = JSON.parse(JSON.stringify(history.value[historyIndex.value]));
    selectedElementId.value = null;
  }
}

function snapToGridValue(value: number): number {
  if (snapToGrid.value) {
    return Math.round(value / gridSize) * gridSize;
  }
  return value;
}

function addText() {
  const newText: TextElement = {
    id: generateId(),
    type: 'text',
    x: snapToGridValue(canvasWidth / 2 - 100),
    y: snapToGridValue(canvasHeight / 2 - 20),
    width: 200,
    height: 40,
    rotation: 0,
    locked: false,
    zIndex: elements.value.length,
    content: 'Double click to edit',
    fontSize: 24,
    fontFamily: 'Playfair Display',
    color: '#000000',
    fontWeight: '400',
    fontStyle: 'normal',
    textAlign: 'center',
  };
  elements.value.push(newText);
  selectedElementId.value = newText.id;
  addToHistory();
}

function addRectangle() {
  const newRect: ShapeElement = {
    id: generateId(),
    type: 'rectangle',
    x: snapToGridValue(canvasWidth / 2 - 75),
    y: snapToGridValue(canvasHeight / 2 - 50),
    width: 150,
    height: 100,
    rotation: 0,
    locked: false,
    zIndex: elements.value.length,
    fillColor: '#FFD700',
    strokeColor: '#000000',
    strokeWidth: 2,
    opacity: 1,
  };
  elements.value.push(newRect);
  selectedElementId.value = newRect.id;
  addToHistory();
}

function addCircle() {
  const newCircle: ShapeElement = {
    id: generateId(),
    type: 'circle',
    x: snapToGridValue(canvasWidth / 2 - 50),
    y: snapToGridValue(canvasHeight / 2 - 50),
    width: 100,
    height: 100,
    rotation: 0,
    locked: false,
    zIndex: elements.value.length,
    fillColor: '#4CAF50',
    strokeColor: '#000000',
    strokeWidth: 2,
    opacity: 1,
  };
  elements.value.push(newCircle);
  selectedElementId.value = newCircle.id;
  addToHistory();
}

function addLine() {
  const newLine: ShapeElement = {
    id: generateId(),
    type: 'line',
    x: snapToGridValue(canvasWidth / 2 - 100),
    y: snapToGridValue(canvasHeight / 2),
    width: 200,
    height: 0,
    rotation: 0,
    locked: false,
    zIndex: elements.value.length,
    fillColor: 'transparent',
    strokeColor: '#000000',
    strokeWidth: 3,
    opacity: 1,
  };
  elements.value.push(newLine);
  selectedElementId.value = newLine.id;
  addToHistory();
}

function handleImageUpload(event: Event) {
  const input = event.target as HTMLInputElement;
  const file = input.files?.[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      const img = new Image();
      img.onload = () => {
        const maxWidth = 300;
        const maxHeight = 300;
        let width = img.width;
        let height = img.height;
        
        if (width > maxWidth || height > maxHeight) {
          const ratio = Math.min(maxWidth / width, maxHeight / height);
          width *= ratio;
          height *= ratio;
        }

        const newImage: ImageElement = {
          id: generateId(),
          type: 'image',
          x: snapToGridValue(canvasWidth / 2 - width / 2),
          y: snapToGridValue(canvasHeight / 2 - height / 2),
          width: width,
          height: height,
          rotation: 0,
          locked: false,
          zIndex: elements.value.length,
          src: e.target?.result as string,
          opacity: 1,
        };
        elements.value.push(newImage);
        selectedElementId.value = newImage.id;
        addToHistory();
      };
      img.src = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
  input.value = '';
}

function startDrag(event: MouseEvent, elementId: string) {
  if (event.button !== 0) return;
  const element = elements.value.find(el => el.id === elementId);
  if (!element || element.locked) return;

  selectedElementId.value = elementId;
  isDragging.value = true;
  const rect = canvas.value?.getBoundingClientRect();
  if (rect) {
    dragStart.value = {
      x: event.clientX - rect.left - element.x,
      y: event.clientY - rect.top - element.y,
    };
  }
  event.preventDefault();
}

function handleMouseMove(event: MouseEvent) {
  if (!isDragging.value || !selectedElement.value || selectedElement.value.locked) return;
  
  const rect = canvas.value?.getBoundingClientRect();
  if (!rect) return;

  const newX = snapToGridValue(event.clientX - rect.left - dragStart.value.x);
  const newY = snapToGridValue(event.clientY - rect.top - dragStart.value.y);

  // Allow elements to move anywhere (no boundaries)
  selectedElement.value.x = newX;
  selectedElement.value.y = newY;
}

function handleMouseUp() {
  if (isDragging.value) {
    addToHistory();
  }
  isDragging.value = false;
  isResizing.value = false;
  isRotating.value = false;
}

function startResize(event: MouseEvent, corner: string) {
  event.stopPropagation();
  if (!selectedElement.value || selectedElement.value.locked) return;
  isResizing.value = true;
  const rect = canvas.value?.getBoundingClientRect();
  if (rect) {
    dragStart.value = {
      x: event.clientX,
      y: event.clientY,
    };
  }
  
  const originalMouseMove = handleMouseMove;
  const resizeMove = (e: MouseEvent) => {
    if (!isResizing.value || !selectedElement.value) return;
    
    const deltaX = e.clientX - dragStart.value.x;
    const deltaY = e.clientY - dragStart.value.y;
    
    if (corner.includes('e')) {
      selectedElement.value.width = Math.max(20, selectedElement.value.width + deltaX);
    }
    if (corner.includes('s')) {
      selectedElement.value.height = Math.max(20, selectedElement.value.height + deltaY);
    }
    if (corner.includes('w')) {
      const newWidth = selectedElement.value.width - deltaX;
      if (newWidth >= 20) {
        selectedElement.value.x += deltaX;
        selectedElement.value.width = newWidth;
      }
    }
    if (corner.includes('n')) {
      const newHeight = selectedElement.value.height - deltaY;
      if (newHeight >= 20) {
        selectedElement.value.y += deltaY;
        selectedElement.value.height = newHeight;
      }
    }
    
    dragStart.value = { x: e.clientX, y: e.clientY };
  };
  
  document.addEventListener('mousemove', resizeMove);
  document.addEventListener('mouseup', () => {
    document.removeEventListener('mousemove', resizeMove);
    handleMouseUp();
  }, { once: true });
}

function deleteSelected() {
  if (selectedElementId.value) {
    elements.value = elements.value.filter(el => el.id !== selectedElementId.value);
    selectedElementId.value = null;
    addToHistory();
  }
}

function duplicateSelected() {
  if (selectedElement.value) {
    const newElement = JSON.parse(JSON.stringify(selectedElement.value));
    newElement.id = generateId();
    newElement.x += 20;
    newElement.y += 20;
    newElement.zIndex = elements.value.length;
    elements.value.push(newElement);
    selectedElementId.value = newElement.id;
    addToHistory();
  }
}

function bringToFront() {
  if (selectedElement.value) {
    const maxZ = Math.max(...elements.value.map(el => el.zIndex));
    selectedElement.value.zIndex = maxZ + 1;
    addToHistory();
  }
}

function sendToBack() {
  if (selectedElement.value) {
    const minZ = Math.min(...elements.value.map(el => el.zIndex));
    selectedElement.value.zIndex = minZ - 1;
    addToHistory();
  }
}

function handleTextDoubleClick(element: TextElement) {
  const newContent = prompt('Edit text:', element.content);
  if (newContent !== null) {
    element.content = newContent;
    addToHistory();
  }
}

// Helper function to ensure fonts are loaded
const ensureFontsLoaded = async () => {
  if (document.fonts && document.fonts.ready) {
    await document.fonts.ready;
  }
  await new Promise(resolve => setTimeout(resolve, 300));
};

async function exportAsImage() {
  const canvasEl = canvas.value;
  if (!canvasEl) return;

  // Store the current selection
  const tempSelectedId = selectedElementId.value;
  
  try {
    // Temporarily hide selection handles
    selectedElementId.value = null;
    
    // Wait for UI update
    await nextTick();
    await new Promise(resolve => setTimeout(resolve, 100));
    
    // Ensure fonts are loaded
    await ensureFontsLoaded();
    
    // Add downloading class to hide resize handles
    canvasEl.classList.add('downloading');
    
    // Wait for the class to apply
    await new Promise(resolve => setTimeout(resolve, 150));
    
    // Use html-to-image like in Application.vue
    try {
      const { toPng } = await import('html-to-image');
      
      const dataUrl = await toPng(canvasEl, {
        quality: 1.0,
        pixelRatio: 2,
        width: canvasWidth,
        height: canvasHeight,
        backgroundColor: '#ffffff',
        cacheBust: true,
        skipFonts: true,
        filter: (node: HTMLElement) => {
          // Filter out all resize handles and selection outlines
          const className = node.className;
          if (typeof className === 'string') {
            if (className.includes('resize-handle')) {
              return false;
            }
            if (className.includes('element selected')) {
              // Keep the element but remove selection styling
              node.style.outline = 'none';
              node.style.outlineOffset = '0';
            }
          }
          return true;
        }
      });

      // Create download link
      const link = document.createElement('a');
      link.download = `certificate-template-${Date.now()}.png`;
      link.href = dataUrl;
      link.click();

      // Restore selection
      canvasEl.classList.remove('downloading');
      selectedElementId.value = tempSelectedId;
      
    } catch (error) {
      console.warn('html-to-image failed:', error);
      
      // Fallback: try with html2canvas
      try {
        const html2canvas = (await import('html2canvas')).default;
        
        const exportCanvas = await html2canvas(canvasEl, {
          width: canvasWidth,
          height: canvasHeight,
          scale: 2,
          backgroundColor: '#ffffff',
          logging: false,
          useCORS: true,
          allowTaint: true,
          removeContainer: true,
          ignoreElements: (element) => {
            return element.classList?.contains('resize-handle');
          },
          onclone: (clonedDoc, element) => {
            // Remove selection outlines
            const selectedElements = element.querySelectorAll('.element.selected');
            selectedElements.forEach((el: any) => {
              el.style.outline = 'none';
              el.style.outlineOffset = '0';
            });
            
            // Add font face declarations
            const style = document.createElement('style');
            style.textContent = `
              @font-face {
                font-family: 'Playfair Display';
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url(https://fonts.gstatic.com/s/playfairdisplay/v30/nuFvD-vYSZviVYUb_rj3ij__anPXJzDwcbmjWBN2PKdFvXDXbtXK-F2qC0usEw.woff2) format('woff2');
              }
              @font-face {
                font-family: 'Playfair Display';
                font-style: normal;
                font-weight: 700;
                font-display: swap;
                src: url(https://fonts.gstatic.com/s/playfairdisplay/v30/nuFvD-vYSZviVYUb_rj3ij__anPXJzDwcbmjWBN2PKdFvXDXbtXK-F2qC0usEw.woff2) format('woff2');
              }
              @font-face {
                font-family: 'Cormorant Garamond';
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url(https://fonts.gstatic.com/s/cormorantgaramond/v16/co3bmX5slCNuHLi8bLeY9MK7whWMhyjQAllfsw-1.woff2) format('woff2');
              }
              @font-face {
                font-family: 'Libre Baskerville';
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url(https://fonts.gstatic.com/s/librebaskerville/v14/kmKnZrc3Hgbbcjq75U4uslyuy4kn0qNZaxY.woff2) format('woff2');
              }
              @font-face {
                font-family: 'Crimson Text';
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url(https://fonts.gstatic.com/s/crimsontext/v19/wlp2gwHKFkZgtmSR3NB0oRJvaAJSA_JN3Q.woff2) format('woff2');
              }
              @font-face {
                font-family: 'Lora';
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url(https://fonts.gstatic.com/s/lora/v32/0QI6MX1D_JOuGQbT0gvTJPa787weuxJMkq1umA.woff2) format('woff2');
              }
              @font-face {
                font-family: 'Merriweather';
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url(https://fonts.gstatic.com/s/merriweather/v30/u-440qyriQwlOrhSvowK_l5-fCZMdeX3rg.woff2) format('woff2');
              }
            `;
            clonedDoc.head.appendChild(style);
          }
        });

        // Convert canvas to blob
        return new Promise<void>((resolve, reject) => {
          exportCanvas.toBlob((blob) => {
            if (blob) {
              const url = URL.createObjectURL(blob);
              const link = document.createElement('a');
              link.download = `certificate-template-${Date.now()}.png`;
              link.href = url;
              link.click();
              URL.revokeObjectURL(url);
              
              // Clean up
              canvasEl.classList.remove('downloading');
              selectedElementId.value = tempSelectedId;
              resolve();
            } else {
              reject(new Error('Failed to create blob from canvas'));
            }
          }, 'image/png', 1.0);
        });
        
      } catch (html2canvasError) {
        console.error('Both html-to-image and html2canvas failed:', html2canvasError);
        throw new Error('Failed to export image');
      }
    }
    
  } catch (error) {
    console.error('Export failed:', error);
    alert('Failed to export image. Please try again.');
    
    // Clean up and restore
    const canvasEl = canvas.value;
    if (canvasEl) {
      canvasEl.classList.remove('downloading');
    }
    selectedElementId.value = tempSelectedId;
  }
}

function clearCanvas() {
  if (confirm('Are you sure you want to clear the canvas?')) {
    elements.value = [];
    selectedElementId.value = null;
    addToHistory();
  }
}

function handleCanvasClick(event: MouseEvent) {
  if (currentTool.value === 'select' && !(event.target as HTMLElement).closest('.element')) {
    selectedElementId.value = null;
  }
}

// Keyboard shortcuts
onMounted(() => {
  addToHistory(); // Initial state

  const handleKeyDown = (e: KeyboardEvent) => {
    if (e.key === 'Delete' || e.key === 'Backspace') {
      if (selectedElementId.value && document.activeElement?.tagName !== 'INPUT') {
        deleteSelected();
        e.preventDefault();
      }
    }
    if (e.ctrlKey || e.metaKey) {
      if (e.key === 'z' && !e.shiftKey) {
        undo();
        e.preventDefault();
      }
      if (e.key === 'z' && e.shiftKey || e.key === 'y') {
        redo();
        e.preventDefault();
      }
      if (e.key === 'd') {
        duplicateSelected();
        e.preventDefault();
      }
    }
  };

  document.addEventListener('keydown', handleKeyDown);
  return () => document.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
  <Head title="Generate Certificate Template" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="certificate-generator">
      <!-- Toolbar -->
      <div class="toolbar">
        <div class="toolbar-section">
          <button 
            @click="currentTool = 'select'" 
            :class="{ active: currentTool === 'select' }"
            title="Select Tool (V)"
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"/>
            </svg>
          </button>
          
          <button @click="addText" title="Add Text (T)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="4 7 4 4 20 4 20 7"/>
              <line x1="9" y1="20" x2="15" y2="20"/>
              <line x1="12" y1="4" x2="12" y2="20"/>
            </svg>
          </button>

          <button @click="addRectangle" title="Add Rectangle (R)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="2"/>
            </svg>
          </button>

          <button @click="addCircle" title="Add Circle (C)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
            </svg>
          </button>

          <button @click="addLine" title="Add Line (L)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="5" y1="19" x2="19" y2="5"/>
            </svg>
          </button>

          <button @click="$refs.imageInput?.click()" title="Add Image (I)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
          </button>
          <input 
            ref="imageInput" 
            type="file" 
            accept="image/*" 
            @change="handleImageUpload"
            style="display: none"
          />
        </div>

        <div class="toolbar-divider"></div>

        <div class="toolbar-section">
          <button @click="undo" :disabled="historyIndex <= 0" title="Undo (Ctrl+Z)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 7v6h6"/>
              <path d="M21 17a9 9 0 00-9-9 9 9 0 00-6 2.3L3 13"/>
            </svg>
          </button>

          <button @click="redo" :disabled="historyIndex >= history.length - 1" title="Redo (Ctrl+Shift+Z)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 7v6h-6"/>
              <path d="M3 17a9 9 0 019-9 9 9 0 016 2.3l3 2.7"/>
            </svg>
          </button>

          <button @click="duplicateSelected" :disabled="!selectedElementId" title="Duplicate (Ctrl+D)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
              <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
            </svg>
          </button>

          <button @click="deleteSelected" :disabled="!selectedElementId" title="Delete (Del)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="3 6 5 6 21 6"/>
              <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
            </svg>
          </button>
        </div>

        <div class="toolbar-divider"></div>

        <div class="toolbar-section">
          <button @click="bringToFront" :disabled="!selectedElementId" title="Bring to Front">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="9" y="9" width="13" height="13" rx="2"/>
              <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
            </svg>
          </button>

          <button @click="sendToBack" :disabled="!selectedElementId" title="Send to Back">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="2" width="13" height="13" rx="2"/>
              <path d="M19 9h1a2 2 0 012 2v9a2 2 0 01-2 2h-9a2 2 0 01-2-2v-1"/>
            </svg>
          </button>
        </div>

        <div class="toolbar-divider"></div>

        <div class="toolbar-section">
          <label class="checkbox-label">
            <input type="checkbox" v-model="showGrid" />
            <span>Grid</span>
          </label>

          <label class="checkbox-label">
            <input type="checkbox" v-model="snapToGrid" />
            <span>Snap</span>
          </label>
        </div>

        <div class="toolbar-spacer"></div>

        <div class="toolbar-section">
          <button @click="clearCanvas" class="btn-clear" title="Clear Canvas">
            Clear All
          </button>

          <div @click="exportAsImage" class="bg-blue-500 text-white cursor-pointer px-4 py-2 rounded-lg shadow font-semibold" title="Export as Image">
            Export PNG
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <!-- Properties Panel -->
        <div class="properties-panel" v-if="selectedElement">
          <h3>Properties</h3>
          
          <div class="property-group">
            <label>Position</label>
            <div class="property-row">
              <div class="property-input-group">
                <span class="property-label">X</span>
                <input 
                  type="number" 
                  v-model.number="selectedElement.x" 
                  @input="addToHistory"
                  :disabled="selectedElement.locked"
                />
              </div>
              <div class="property-input-group">
                <span class="property-label">Y</span>
                <input 
                  type="number" 
                  v-model.number="selectedElement.y"
                  @input="addToHistory"
                  :disabled="selectedElement.locked"
                />
              </div>
            </div>
          </div>

          <div class="property-group">
            <label>Size</label>
            <div class="property-row">
              <div class="property-input-group">
                <span class="property-label">W</span>
                <input 
                  type="number" 
                  v-model.number="selectedElement.width"
                  @input="addToHistory"
                  :disabled="selectedElement.locked"
                />
              </div>
              <div class="property-input-group">
                <span class="property-label">H</span>
                <input 
                  type="number" 
                  v-model.number="selectedElement.height"
                  @input="addToHistory"
                  :disabled="selectedElement.locked"
                />
              </div>
            </div>
          </div>

          <div class="property-group">
            <label>Rotation</label>
            <input 
              type="range" 
              min="0" 
              max="360" 
              v-model.number="selectedElement.rotation"
              @input="addToHistory"
              :disabled="selectedElement.locked"
            />
            <span class="property-value">{{ selectedElement.rotation }}°</span>
          </div>

          <!-- Text Properties -->
          <template v-if="selectedElement.type === 'text'">
            <div class="property-group">
              <label>Font Family</label>
              <select 
                v-model="(selectedElement as TextElement).fontFamily"
                @change="addToHistory"
                :disabled="selectedElement.locked"
              >
                <option value="Playfair Display">Playfair Display</option>
                <option value="Cormorant Garamond">Cormorant Garamond</option>
                <option value="Libre Baskerville">Libre Baskerville</option>
                <option value="Crimson Text">Crimson Text</option>
                <option value="Lora">Lora</option>
                <option value="Merriweather">Merriweather</option>
                <option value="Georgia">Georgia</option>
                <option value="Times New Roman">Times New Roman</option>
                <option value="Arial">Arial</option>
                <option value="Helvetica">Helvetica</option>
              </select>
            </div>

            <div class="property-group">
              <label>Font Size</label>
              <input 
                type="number" 
                v-model.number="(selectedElement as TextElement).fontSize"
                @input="addToHistory"
                :disabled="selectedElement.locked"
                min="8"
                max="200"
              />
            </div>

            <div class="property-group">
              <label>Font Weight</label>
              <select 
                v-model="(selectedElement as TextElement).fontWeight"
                @change="addToHistory"
                :disabled="selectedElement.locked"
              >
                <option value="300">Light</option>
                <option value="400">Regular</option>
                <option value="500">Medium</option>
                <option value="600">Semi Bold</option>
                <option value="700">Bold</option>
                <option value="900">Black</option>
              </select>
            </div>

            <div class="property-group">
              <label>Color</label>
              <input 
                type="color" 
                v-model="(selectedElement as TextElement).color"
                @input="addToHistory"
                :disabled="selectedElement.locked"
              />
            </div>

            <div class="property-group">
              <label>Text Align</label>
              <select 
                v-model="(selectedElement as TextElement).textAlign"
                @change="addToHistory"
                :disabled="selectedElement.locked"
              >
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
              </select>
            </div>
          </template>

          <!-- Shape Properties -->
          <template v-if="selectedElement.type === 'rectangle' || selectedElement.type === 'circle' || selectedElement.type === 'line'">
            <div class="property-group">
              <label>Fill Color</label>
              <input 
                type="color" 
                v-model="(selectedElement as ShapeElement).fillColor"
                @input="addToHistory"
                :disabled="selectedElement.locked"
              />
            </div>

            <div class="property-group">
              <label>Stroke Color</label>
              <input 
                type="color" 
                v-model="(selectedElement as ShapeElement).strokeColor"
                @input="addToHistory"
                :disabled="selectedElement.locked"
              />
            </div>

            <div class="property-group">
              <label>Stroke Width</label>
              <input 
                type="number" 
                v-model.number="(selectedElement as ShapeElement).strokeWidth"
                @input="addToHistory"
                :disabled="selectedElement.locked"
                min="0"
                max="50"
              />
            </div>

            <div class="property-group">
              <label>Opacity</label>
              <input 
                type="range" 
                min="0" 
                max="1" 
                step="0.1"
                v-model.number="(selectedElement as ShapeElement).opacity"
                @input="addToHistory"
                :disabled="selectedElement.locked"
              />
              <span class="property-value">{{ Math.round((selectedElement as ShapeElement).opacity * 100) }}%</span>
            </div>
          </template>

          <!-- Image Properties -->
          <template v-if="selectedElement.type === 'image'">
            <div class="property-group">
              <label>Opacity</label>
              <input 
                type="range" 
                min="0" 
                max="1" 
                step="0.1"
                v-model.number="(selectedElement as ImageElement).opacity"
                @input="addToHistory"
                :disabled="selectedElement.locked"
              />
              <span class="property-value">{{ Math.round((selectedElement as ImageElement).opacity * 100) }}%</span>
            </div>
          </template>

          <div class="property-group">
            <label class="checkbox-label">
              <input 
                type="checkbox" 
                v-model="selectedElement.locked"
                @change="addToHistory"
              />
              <span>Lock Element</span>
            </label>
          </div>
        </div>

        <!-- Canvas Area -->
        <div class="canvas-wrapper">
          <div 
            ref="canvas"
            class="canvas"
            :class="{ 'show-grid': showGrid }"
            :style="{
              width: canvasWidth + 'px',
              height: canvasHeight + 'px',
            }"
            @mousedown="handleCanvasClick"
            @mousemove="handleMouseMove"
            @mouseup="handleMouseUp"
            @mouseleave="handleMouseUp"
          >
            <!-- Elements -->
            <div
              v-for="element in sortedElements"
              :key="element.id"
              class="element"
              :class="{ 
                selected: element.id === selectedElementId,
                locked: element.locked
              }"
              :style="{
                left: element.x + 'px',
                top: element.y + 'px',
                width: element.width + 'px',
                height: element.height + 'px',
                transform: `rotate(${element.rotation}deg)`,
                zIndex: element.zIndex,
                cursor: element.locked ? 'not-allowed' : 'move'
              }"
              @mousedown="startDrag($event, element.id)"
              @dblclick="element.type === 'text' && handleTextDoubleClick(element as TextElement)"
            >
              <!-- Text Element -->
              <div v-if="element.type === 'text'"
                class="text-element"
                :style="{
                  fontSize: (element as TextElement).fontSize + 'px',
                  fontFamily: (element as TextElement).fontFamily,
                  color: (element as TextElement).color,
                  fontWeight: (element as TextElement).fontWeight,
                  fontStyle: (element as TextElement).fontStyle,
                  textAlign: (element as TextElement).textAlign,
                }"
              >
                {{ (element as TextElement).content }}
              </div>

              <!-- Rectangle Element -->
              <div 
                v-if="element.type === 'rectangle'"
                class="shape-element"
                :style="{
                  backgroundColor: (element as ShapeElement).fillColor,
                  border: `${(element as ShapeElement).strokeWidth}px solid ${(element as ShapeElement).strokeColor}`,
                  opacity: (element as ShapeElement).opacity,
                }"
              ></div>

              <!-- Circle Element -->
              <div 
                v-if="element.type === 'circle'"
                class="shape-element"
                :style="{
                  backgroundColor: (element as ShapeElement).fillColor,
                  border: `${(element as ShapeElement).strokeWidth}px solid ${(element as ShapeElement).strokeColor}`,
                  opacity: (element as ShapeElement).opacity,
                  borderRadius: '50%',
                }"
              ></div>

              <!-- Line Element -->
              <svg 
                v-if="element.type === 'line'"
                class="line-element"
                :style="{
                  opacity: (element as ShapeElement).opacity,
                }"
              >
                <line 
                  x1="0" 
                  y1="0" 
                  :x2="element.width" 
                  :y2="element.height"
                  :stroke="(element as ShapeElement).strokeColor"
                  :stroke-width="(element as ShapeElement).strokeWidth"
                />
              </svg>

              <!-- Image Element -->
              <img 
                v-if="element.type === 'image'"
                :src="(element as ImageElement).src"
                class="image-element"
                :style="{
                  opacity: (element as ImageElement).opacity,
                }"
                draggable="false"
              />

              <!-- Selection Handles -->
              <template v-if="element.id === selectedElementId && !element.locked">
                <div class="resize-handle nw" @mousedown.stop="startResize($event, 'nw')"></div>
                <div class="resize-handle ne" @mousedown.stop="startResize($event, 'ne')"></div>
                <div class="resize-handle sw" @mousedown.stop="startResize($event, 'sw')"></div>
                <div class="resize-handle se" @mousedown.stop="startResize($event, 'se')"></div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Cormorant+Garamond:wght@400;600;700&family=Libre+Baskerville:wght@400;700&family=Crimson+Text:wght@400;600;700&family=Lora:wght@400;600;700&family=Merriweather:wght@400;700&display=swap');

* {
  box-sizing: border-box;
}

.certificate-generator {
  min-height: 100vh;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  display: flex;
  flex-direction: column;
}

/* Toolbar */
.toolbar {
  background: #ffffff;
  border-bottom: 2px solid #dee2e6;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  position: sticky;
  top: 0;
  z-index: 100;
}

.toolbar-section {
  display: flex;
  gap: 4px;
  align-items: center;
}

.toolbar-divider {
  width: 1px;
  height: 32px;
  background: #dee2e6;
  margin: 0 8px;
}

.toolbar-spacer {
  flex: 1;
}

.toolbar button {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  padding: 8px 12px;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #495057;
  font-size: 14px;
  font-weight: 500;
}

.toolbar button:hover:not(:disabled) {
  background: #e9ecef;
  border-color: #adb5bd;
  transform: translateY(-1px);
}

.toolbar button:active:not(:disabled) {
  transform: translateY(0);
}

.toolbar button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.toolbar button.active {
  background: #007bff;
  border-color: #007bff;
  color: white;
}


.toolbar .btn-clear {
  background: #fff;
  border: 1px solid #dc3545;
  color: #dc3545;
  padding: 8px 16px;
}

.toolbar .btn-clear:hover {
  background: #dc3545!important;
  border: 1px solid #dc3545!important;
  color: white;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 14px;
  cursor: pointer;
  user-select: none;
  color: #495057;
}

.checkbox-label input[type="checkbox"] {
  cursor: pointer;
}

/* Main Content */
.main-content {
  display: flex;
  flex: 1;
  overflow: hidden;
}

/* Properties Panel */
.properties-panel {
  width: 280px;
  background: #ffffff;
  border-right: 2px solid #dee2e6;
  padding: 20px;
  overflow-y: auto;
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
}

.properties-panel h3 {
  margin: 0 0 20px 0;
  font-size: 18px;
  font-weight: 700;
  color: #212529;
  border-bottom: 2px solid #007bff;
  padding-bottom: 10px;
}

.property-group {
  margin-bottom: 20px;
}

.property-group > label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #495057;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.property-row {
  display: flex;
  gap: 8px;
}

.property-input-group {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 6px;
}

.property-label {
  font-size: 12px;
  font-weight: 600;
  color: #6c757d;
  min-width: 16px;
}

.property-group input[type="number"],
.property-group input[type="color"],
.property-group input[type="range"],
.property-group select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 14px;
  transition: border-color 0.2s;
}

.property-group input[type="number"]:focus,
.property-group select:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.property-group input[type="color"] {
  height: 40px;
  cursor: pointer;
}

.property-group input[type="range"] {
  padding: 0;
}

.property-value {
  display: inline-block;
  margin-top: 4px;
  font-size: 13px;
  color: #6c757d;
  font-weight: 600;
}

/* Canvas Wrapper */
.canvas-wrapper {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px;
  overflow: auto;
}

.canvas {
  background: white;
  position: relative;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
  border-radius: 4px;
  overflow: hidden;
}

.canvas.show-grid {
  background-image: 
    repeating-linear-gradient(0deg, transparent, transparent 19px, #e9ecef 19px, #e9ecef 20px),
    repeating-linear-gradient(90deg, transparent, transparent 19px, #e9ecef 19px, #e9ecef 20px);
  background-size: 20px 20px;
}

/* Elements */
.element {
  position: absolute;
  user-select: none;
  outline: none;
  transition: none;
}

.element.selected {
  outline: 2px solid #007bff;
  outline-offset: 2px;
}

.element.locked {
  opacity: 0.7;
}

.text-element {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: pre-line;
  word-break: normal;
  text-overflow: clip;
  overflow: visible;
  pointer-events: none;
  padding: 4px;
  max-width: none;
  line-height: 1.2;
  text-align: center;
  box-sizing: border-box;
}

.shape-element {
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.line-element {
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.image-element {
  width: 100%;
  height: 100%;
  object-fit: cover;
  pointer-events: none;
}

/* Resize Handles */
.resize-handle {
  position: absolute;
  width: 10px;
  height: 10px;
  background: white;
  border: 2px solid #007bff;
  border-radius: 50%;
  z-index: 10;
}

.resize-handle:hover {
  background: #007bff;
  transform: scale(1.3);
}

.resize-handle.nw {
  top: -5px;
  left: -5px;
  cursor: nw-resize;
}

.resize-handle.ne {
  top: -5px;
  right: -5px;
  cursor: ne-resize;
}

.resize-handle.sw {
  bottom: -5px;
  left: -5px;
  cursor: sw-resize;
}

.resize-handle.se {
  bottom: -5px;
  right: -5px;
  cursor: se-resize;
}

/* Hide resize handles when downloading */
.canvas.downloading .resize-handle {
  display: none !important;
}

.canvas.downloading .element.selected {
  outline: none !important;
  outline-offset: 0 !important;
}

/* Scrollbar Styling */
.properties-panel::-webkit-scrollbar {
  width: 8px;
}

.properties-panel::-webkit-scrollbar-track {
  background: #f1f3f5;
}

.properties-panel::-webkit-scrollbar-thumb {
  background: #adb5bd;
  border-radius: 4px;
}

.properties-panel::-webkit-scrollbar-thumb:hover {
  background: #868e96;
}

/* Responsive */
@media (max-width: 1200px) {
  .properties-panel {
    width: 240px;
  }
  
  .canvas-wrapper {
    padding: 20px;
  }
}

@media (max-width: 768px) {
  .toolbar {
    flex-wrap: wrap;
    gap: 8px;
  }
  
  .toolbar-divider {
    display: none;
  }
  
  .properties-panel {
    position: fixed;
    left: -280px;
    top: 0;
    height: 100vh;
    z-index: 200;
    transition: left 0.3s ease;
  }
  
  .properties-panel.open {
    left: 0;
  }
}
</style>