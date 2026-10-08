<template>
  <Modal :show="show" @close="closeModal" max-width="3xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[90vh]">

      <!-- Header -->
      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <Icon :icon="isEdit ? 'edit' : 'plus'" size="sm" />
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEdit ? 'Edit Contribution' : 'New Contribution' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEdit ? 'Update details, deadline, or consent rules' : 'Choose scope and fill in campaign details' }}
            </p>
          </div>
        </div>
        <button type="button" @click="closeModal"
          class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Scrollable Body -->
      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <!-- TOP ERROR -->
        <div v-if="formError"
          class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl flex items-start gap-2.5">
          <Icon icon="alert-circle" size="sm" class="text-red-600 dark:text-red-400 shrink-0 mt-0.5" />
          <div class="text-xs">
            <p class="font-medium text-red-800 dark:text-red-300">Could not save</p>
            <p class="text-red-700 dark:text-red-300 font-normal">{{ formError }}</p>
          </div>
        </div>

        <!-- SCOPE (create only) -->
        <div v-if="!isEdit" class="space-y-3">
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
            Target Scope <span class="text-red-500">*</span>
          </label>

          <div class="grid grid-cols-2 lg:grid-cols-5 gap-2">
            <button v-for="s in scopeOptions" :key="s.id" type="button" @click="form.scope = s.id"
              :class="[
                'py-3 px-3 rounded-xl text-xs font-medium transition-all border text-left cursor-pointer',
                form.scope === s.id
                  ? 'bg-emerald-50 dark:bg-emerald-950/50 border-[#004d08] dark:border-[#86EFAC] text-[#004d08] dark:text-[#86EFAC]'
                  : 'bg-gray-50 dark:bg-[#232D26] border-gray-200 dark:border-[#3F4F43] text-gray-600 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-500'
              ]">
              <Icon :icon="s.icon" size="sm" />
              <div class="mt-1.5">{{ s.label }}</div>
            </button>
          </div>

          <!-- section picker -->
          <div v-if="form.scope === 'section'" class="space-y-2">
            <div class="flex items-center justify-between">
              <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">Pick Sections</p>
              <span v-if="selectedSectionIds.length"
                class="bg-emerald-50 dark:bg-emerald-950/50 text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-800 text-[10px] font-medium uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                {{ selectedSectionIds.length }} selected
              </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60 overflow-y-auto p-1">
              <button v-for="s in advisorySections" :key="s.id" type="button" @click="toggleItem(selectedSectionIds, s.id)"
                :class="[
                  'text-left p-3 rounded-xl border text-xs font-medium transition-all cursor-pointer',
                  selectedSectionIds.includes(s.id)
                    ? 'bg-emerald-50 dark:bg-emerald-950/50 border-[#004d08] dark:border-[#86EFAC]'
                    : 'bg-gray-50 dark:bg-[#232D26] border-gray-200 dark:border-[#3F4F43]'
                ]">
                <p class="font-medium truncate text-gray-900 dark:text-white">{{ s.name }}</p>
                <p class="text-[10px] text-gray-500">{{ s.strand_code }} • Grade {{ s.grade_level }} • {{ s.students_count }} students</p>
              </button>
            </div>
          </div>

          <!-- class picker -->
          <div v-else-if="form.scope === 'class'" class="space-y-2">
            <div class="flex items-center justify-between">
              <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">Pick Classes</p>
              <span v-if="selectedClassIds.length"
                class="bg-emerald-50 dark:bg-emerald-950/50 text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-800 text-[10px] font-medium uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                {{ selectedClassIds.length }} selected
              </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto p-1">
              <button v-for="c in classrooms" :key="c.id" type="button" @click="toggleItem(selectedClassIds, c.id)"
                :class="[
                  'text-left p-3 rounded-xl border text-xs font-medium transition-all cursor-pointer',
                  selectedClassIds.includes(c.id)
                    ? 'bg-emerald-50 dark:bg-emerald-950/50 border-[#004d08] dark:border-[#86EFAC]'
                    : 'bg-gray-50 dark:bg-[#232D26] border-gray-200 dark:border-[#3F4F43]'
                ]">
                <p class="font-medium truncate text-gray-900 dark:text-white">{{ c.subject }}</p>
                <p class="text-[10px] text-gray-500">{{ c.section }} • {{ c.students_count }} students</p>
              </button>
            </div>
            <div v-if="classrooms.length > 1" class="flex items-center justify-end gap-2 pt-1">
              <button type="button" @click="selectedClassIds = classrooms.map(c => c.id)"
                :disabled="selectedClassIds.length === classrooms.length"
                class="text-[11px] font-medium text-gray-600 dark:text-gray-300 hover:text-[#004d08] px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] disabled:opacity-40 cursor-pointer">
                Select All
              </button>
              <button type="button" @click="selectedClassIds = []"
                :disabled="selectedClassIds.length === 0"
                class="text-[11px] font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 px-3 py-1.5 rounded-lg border border-red-200 dark:border-red-900/40 disabled:opacity-40 cursor-pointer">
                Clear
              </button>
            </div>
          </div>

          <!-- strand picker -->
          <div v-else-if="form.scope === 'strand'" class="space-y-1.5">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Pick Strand</label>
            <select v-model="form.strand_id"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
              <option value="">— Select strand —</option>
              <option v-for="s in strands" :key="s.id" :value="s.id">{{ s.code }} — {{ s.name }}</option>
            </select>
          </div>

          <!-- grade picker -->
          <div v-else-if="form.scope === 'grade'" class="space-y-1.5">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Pick Grade Level</label>
            <div class="grid grid-cols-2 gap-2">
              <button type="button" @click="form.grade_level = '11'"
                :class="form.grade_level === '11' ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26]' : 'bg-gray-50 dark:bg-[#232D26] text-gray-600 dark:text-gray-300'"
                class="py-3 rounded-xl text-xs font-medium border border-gray-200 dark:border-[#3F4F43] transition-all cursor-pointer">
                Grade 11
              </button>
              <button type="button" @click="form.grade_level = '12'"
                :class="form.grade_level === '12' ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26]' : 'bg-gray-50 dark:bg-[#232D26] text-gray-600 dark:text-gray-300'"
                class="py-3 rounded-xl text-xs font-medium border border-gray-200 dark:border-[#3F4F43] transition-all cursor-pointer">
                Grade 12
              </button>
            </div>
          </div>

          <!-- school info -->
          <div v-else-if="form.scope === 'school'"
            class="bg-sky-50 dark:bg-sky-950/30 border border-sky-200/80 dark:border-sky-900/50 rounded-xl p-3 text-xs text-sky-900 dark:text-sky-200">
            <p class="font-medium">School-wide contribution</p>
            <p class="mt-0.5 opacity-90 font-normal">All enrolled students in the selected school year will be assigned.</p>
          </div>

          <!-- school year (strand/grade/school) -->
          <div v-if="['strand','grade','school'].includes(form.scope)" class="space-y-1.5">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">School Year *</label>
            <select v-model="form.school_year_id"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
              <option value="">— Select school year —</option>
              <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">
                {{ sy.label }}{{ sy.is_active ? ' (active)' : '' }}
              </option>
            </select>
          </div>
        </div>

        <!-- EDIT scope info -->
        <div v-if="isEdit" class="p-3 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900/40 space-y-1">
          <span class="text-[10px] font-medium uppercase tracking-wider text-emerald-700 dark:text-emerald-300">
            {{ contribution.scope === 'section' ? 'Section-wide contribution for' : 'Class contribution for' }}
          </span>
          <p class="text-xs font-medium text-gray-900 dark:text-white">{{ contribution.display_name }}</p>
        </div>

        <!-- Title -->
        <div class="space-y-1.5" :data-error="!!fieldError('title')">
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
            Title <span class="text-red-500">*</span>
          </label>
          <input v-model="form.title" type="text" placeholder="e.g. Field Trip Fund — Baguio Educational Tour"
            :class="[
              'w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 font-normal',
              fieldError('title')
                ? 'border-red-400 dark:border-red-500 focus:ring-red-400'
                : 'border-gray-200 dark:border-[#3F4F43] focus:ring-[#004d08]'
            ]" />
          <p v-if="fieldError('title')" class="text-red-600 dark:text-red-400 text-[11px] font-medium flex items-center gap-1">
            <Icon icon="alert-circle" size="xs" /> {{ fieldError('title') }}
          </p>
        </div>

        <!-- Purpose + Deadline -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="space-y-1.5">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Purpose</label>
            <input v-model="form.purpose" type="text" placeholder="e.g. Educational field trip"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          </div>
          <div class="space-y-1.5" :data-error="!!fieldError('deadline_at')">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Deadline</label>
            <input v-model="form.deadline_at" type="datetime-local"
              :class="[
                'w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 font-normal',
                fieldError('deadline_at')
                  ? 'border-red-400 dark:border-red-500 focus:ring-red-400'
                  : 'border-gray-200 dark:border-[#3F4F43] focus:ring-[#004d08]'
              ]" />
            <p v-if="fieldError('deadline_at')" class="text-red-600 dark:text-red-400 text-[11px] font-medium flex items-center gap-1">
              <Icon icon="alert-circle" size="xs" /> {{ fieldError('deadline_at') }}
            </p>
          </div>
        </div>

        <!-- Description -->
        <div class="space-y-1.5">
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Description</label>
          <textarea v-model="form.description" rows="2" placeholder="What is this contribution for?"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal resize-none"></textarea>
        </div>

        <!-- Amount Type -->
        <div class="space-y-2 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
            Amount Type <span class="text-red-500">*</span>
          </label>
          <div class="grid grid-cols-2 gap-3">
            <button type="button" @click="form.amount_type = 'fixed'"
              :class="[
                'py-3 px-4 rounded-xl text-xs font-medium transition-all border text-left cursor-pointer',
                form.amount_type === 'fixed'
                  ? 'bg-emerald-50 dark:bg-emerald-950/50 border-[#004d08] dark:border-[#86EFAC] text-[#004d08] dark:text-[#86EFAC]'
                  : 'bg-gray-50 dark:bg-[#232D26] border-gray-200 dark:border-[#3F4F43] text-gray-600 dark:text-gray-300'
              ]">
              <div class="text-sm">Fixed Amount</div>
              <div class="text-[10px] font-normal opacity-70 mt-0.5">Same price for all</div>
            </button>
            <button type="button" @click="form.amount_type = 'open'"
              :class="[
                'py-3 px-4 rounded-xl text-xs font-medium transition-all border text-left cursor-pointer',
                form.amount_type === 'open'
                  ? 'bg-emerald-50 dark:bg-emerald-950/50 border-[#004d08] dark:border-[#86EFAC] text-[#004d08] dark:text-[#86EFAC]'
                  : 'bg-gray-50 dark:bg-[#232D26] border-gray-200 dark:border-[#3F4F43] text-gray-600 dark:text-gray-300'
              ]">
              <div class="text-sm">Open Amount</div>
              <div class="text-[10px] font-normal opacity-70 mt-0.5">Student picks</div>
            </button>
          </div>
        </div>

        <!-- Amount Fields -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div v-if="form.amount_type === 'fixed'" class="space-y-1.5" :data-error="!!fieldError('amount')">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
              Amount (₱) <span class="text-red-500">*</span>
            </label>
            <input v-model.number="form.amount" type="number" min="1" step="0.01"
              :class="[
                'w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 font-medium',
                fieldError('amount')
                  ? 'border-red-400 dark:border-red-500 focus:ring-red-400'
                  : 'border-gray-200 dark:border-[#3F4F43] focus:ring-[#004d08]'
              ]" />
            <p v-if="fieldError('amount')" class="text-red-600 dark:text-red-400 text-[11px] font-medium flex items-center gap-1">
              <Icon icon="alert-circle" size="xs" /> {{ fieldError('amount') }}
            </p>
          </div>
          <div v-else class="space-y-1.5">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Minimum (₱)</label>
            <input v-model.number="form.min_amount" type="number" min="1" step="0.01"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-medium" />
          </div>
          <div class="space-y-1.5">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Target (optional)</label>
            <input v-model.number="form.target_amount" type="number" min="1" step="0.01"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-medium" />
          </div>
        </div>

        <!-- Toggles -->
        <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
          <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" v-model="form.requires_guardian_consent"
              class="mt-0.5 rounded border-gray-300 text-[#004d08] focus:ring-[#004d08] w-4 h-4 cursor-pointer" />
            <div>
              <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Require guardian approval</span>
              <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 font-normal">Recommended for minors.</p>
            </div>
          </label>
          <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" v-model="form.is_required"
              class="mt-0.5 rounded border-gray-300 text-[#004d08] focus:ring-[#004d08] w-4 h-4 cursor-pointer" />
            <div>
              <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Mark as required</span>
              <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 font-normal">All assigned must pay.</p>
            </div>
          </label>
          <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" v-model="form.is_published"
              class="mt-0.5 rounded border-gray-300 text-[#004d08] focus:ring-[#004d08] w-4 h-4 cursor-pointer" />
            <div>
              <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Publish immediately</span>
              <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 font-normal">Uncheck to save as draft.</p>
            </div>
          </label>
        </div>

        <!-- Preview -->
        <div v-if="!isEdit && selectedCount > 0"
          class="p-3.5 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900/40 flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0">
            <Icon icon="sparkles" size="sm" />
          </div>
          <div class="text-xs text-emerald-900 dark:text-emerald-200 leading-relaxed">
            <p class="font-medium">
              {{ selectedCount }} {{ form.scope === 'section' ? 'section-wide' : 'class' }} contribution{{ selectedCount === 1 ? '' : 's' }} will be created
            </p>
            <p class="font-normal opacity-90 mt-0.5">
              Total of <strong>{{ totalStudentsAcrossSelected }} students</strong> will be assigned.
              <span v-if="form.is_published"> Guardian emails will be sent automatically.</span>
            </p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex items-center justify-end gap-2">
        <button type="button" @click="closeModal"
          class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Cancel
        </button>
        <button type="button" @click="submit" :disabled="submitting || (!isEdit && selectedCount === 0)"
          class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer flex items-center gap-2">
          <Icon :icon="isEdit ? 'check-circle' : 'plus'" size="xs" />
          {{ submitting
            ? 'Saving…'
            : (isEdit
              ? 'Save Changes'
              : (selectedCount === 0 ? 'Create' : `Create ${selectedCount} Contribution${selectedCount === 1 ? '' : 's'}`)) }}
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  show:             { type: Boolean, default: false },
  contribution:     { type: Object,  default: null },
  advisorySections: { type: Array,   default: () => [] },
  classrooms:       { type: Array,   default: () => [] },
  strands:          { type: Array,   default: () => [] },
  schoolYears:      { type: Array,   default: () => [] },
})

const emit = defineEmits(['close', 'saved'])

const isEdit = computed(() => !!props.contribution?.id)
const defaultScope = props.advisorySections.length > 0 ? 'section' : 'class'

const form = ref({
  scope: 'section', title: '', purpose: '', description: '',
  amount_type: 'fixed', amount: null, min_amount: null, target_amount: null,
  deadline_at: '', is_required: false, is_published: false,
  requires_guardian_consent: true,
  strand_id: '', grade_level: '11', school_year_id: '',
})

const selectedSectionIds = ref([])
const selectedClassIds   = ref([])

const submitting  = ref(false)
const formError   = ref('')
const fieldErrors = ref({})

const selectedCount = computed(() =>
  form.value.scope === 'section' ? selectedSectionIds.value.length : selectedClassIds.value.length
)
const totalStudentsAcrossSelected = computed(() => {
  if (form.value.scope === 'section') {
    return props.advisorySections
      .filter(s => selectedSectionIds.value.includes(s.id))
      .reduce((sum, s) => sum + (s.students_count || 0), 0)
  }
  return props.classrooms
    .filter(c => selectedClassIds.value.includes(c.id))
    .reduce((sum, c) => sum + (c.students_count || 0), 0)
})

watch(() => props.show, (open) => {
  if (!open) return
  populate()
})

function populate() {
  formError.value = ''
  fieldErrors.value = {}
  submitting.value = false

  if (props.contribution) {
    const c = props.contribution
    form.value = {
      scope: c.scope ?? defaultScope,
      title: c.title ?? '',
      purpose: c.purpose ?? '',
      description: c.description ?? '',
      amount_type: c.amount_type ?? 'fixed',
      amount: c.amount ?? null,
      min_amount: c.min_amount ?? null,
      target_amount: c.target_amount ?? null,
      deadline_at: c.deadline_at?.slice(0, 16) ?? '',
      is_required: c.is_required ?? false,
      is_published: c.is_published ?? false,
      requires_guardian_consent: c.requires_guardian_consent ?? true,
      strand_id: c.strand_id ?? '',
      grade_level: c.grade_level ?? '11',
      school_year_id: c.school_year_id ?? '',
    }
    selectedSectionIds.value = c.scope === 'section' && c.section?.id ? [c.section.id] : []
    selectedClassIds.value   = c.scope === 'class'   && c.classroom?.id ? [c.classroom.id] : []
  } else {
    form.value = {
      scope: defaultScope, title: '', purpose: '', description: '',
      amount_type: 'fixed', amount: null, min_amount: null, target_amount: null,
      deadline_at: '', is_required: false, is_published: false,
      requires_guardian_consent: true,
      strand_id: '', grade_level: '11',
      school_year_id: props.schoolYears.find(sy => sy.is_active)?.id || '',
    }
    selectedSectionIds.value = props.advisorySections.map(s => s.id)
    selectedClassIds.value = []
  }
}

function toggleItem(arrRef, id) {
  const i = arrRef.value.indexOf(id)
  if (i >= 0) arrRef.value.splice(i, 1)
  else arrRef.value.push(id)
}

function fieldError(field) {
  const err = fieldErrors.value[field]
  if (!err) return null
  return Array.isArray(err) ? err[0] : err
}

function prevalidate() {
  const errs = {}
  if (!isEdit.value && selectedCount.value === 0) {
    errs[form.value.scope === 'section' ? 'section_ids' : 'class_ids'] =
      ['Pick at least one ' + (form.value.scope === 'section' ? 'section' : 'class') + '.']
  }
  if (!form.value.title?.trim()) errs.title = ['Title is required.']
  if (form.value.amount_type === 'fixed') {
    const amt = Number(form.value.amount)
    if (!amt || amt <= 0) errs.amount = ['Enter an amount greater than zero.']
  }
  fieldErrors.value = errs
  return Object.keys(errs).length === 0
}

async function submit() {
  formError.value = ''
  fieldErrors.value = {}
  if (!prevalidate()) {
    formError.value = 'Please fix the highlighted fields.'
    return
  }
  submitting.value = true
  try {
    if (isEdit.value) {
      await axios.put(route('teacher.contributions.update', props.contribution.id), form.value)
    } else {
      const payload = {
        ...form.value,
        section_ids: form.value.scope === 'section' ? selectedSectionIds.value : undefined,
        class_ids:   form.value.scope === 'class'   ? selectedClassIds.value   : undefined,
      }
      await axios.post(route('teacher.contributions.store'), payload)
    }
    emit('saved')
    closeModal()
  } catch (e) {
    const status = e.response?.status
    if (status === 422) {
      fieldErrors.value = e.response.data.errors || {}
      formError.value = 'Please fix the highlighted fields.'
    } else {
      formError.value = e.response?.data?.message || 'Something went wrong.'
    }
  } finally {
    submitting.value = false
  }
}

function closeModal() {
  formError.value = ''
  fieldErrors.value = {}
  emit('close')
}

const scopeOptions = [
  { id: 'section', label: 'Sections', icon: 'users' },
  { id: 'class',   label: 'Classes',  icon: 'book-open' },
  { id: 'strand',  label: 'Strand',   icon: 'globe' },
  { id: 'grade',   label: 'Grade',    icon: 'academic-cap' },
  { id: 'school',  label: 'School',   icon: 'school' },
]
</script>