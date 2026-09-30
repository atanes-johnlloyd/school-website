<template>
  <Modal :show="show" @close="closeModal" max-width="2xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[88vh]">

      <!-- Header -->
      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit Admission Application' : 'New Admission Application' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Update applicant record' : 'Create a new prospective student record' }}
            </p>
          </div>
        </div>

        <button type="button" @click="closeModal" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- ═════ Error summary banner (top of form) ═════ -->
      <div v-if="errorList.length" class="mx-6 mt-4 p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl">
        <div class="flex items-start gap-2.5">
          <svg class="w-4 h-4 text-red-600 dark:text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-red-700 dark:text-red-300 mb-1.5">
              {{ errorList.length }} issue{{ errorList.length > 1 ? 's' : '' }} to fix before saving:
            </p>
            <ul class="text-xs text-red-700 dark:text-red-300 space-y-0.5 list-disc list-inside">
              <li v-for="(err, i) in errorList" :key="i">{{ err }}</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- ═════ Form ═════ -->
      <form ref="formRef" @submit.prevent="submitForm" class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <!-- Academic Information -->
        <section>
          <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Academic Information</h4>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                Applicant Type <span class="text-red-500">*</span>
              </label>
              <select v-model="form.applicant_type" @change="revalidate" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
                <option value="Grade11">Grade 11</option>
                <option value="Grade12">Grade 12</option>
                <option value="Transferee">Transferee</option>
                <option value="Returning">Returning</option>
              </select>
            </div>

            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                Desired Grade Level <span class="text-red-500">*</span>
              </label>
              <select v-model.number="form.desired_grade_level" @change="revalidate" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
                <option :value="11">Grade 11</option>
                <option :value="12">Grade 12</option>
              </select>
            </div>

            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                School Year <span class="text-red-500">*</span>
              </label>
              <select v-model.number="form.school_year_id" @change="revalidate" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
                <option value="" disabled>Select School Year</option>
                <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">{{ sy.label || sy.name }}</option>
              </select>
            </div>

            <div class="sm:col-span-3">
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                Target Strand <span class="text-red-500">*</span>
              </label>
              <select v-model.number="form.strand_id" @change="revalidate" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
                <option value="" disabled>Select Strand</option>
                <option v-for="s in strands" :key="s.id" :value="s.id">
                  {{ s.code ? `${s.code} - ${s.name}` : s.name }}
                </option>
              </select>
            </div>
          </div>
        </section>

        <!-- Personal Information -->
        <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
          <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Personal Information</h4>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                First Name <span class="text-red-500">*</span>
              </label>
              <input v-model="form.first_name" @input="revalidate" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Middle Name</label>
              <input v-model="form.middle_name" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                Last Name <span class="text-red-500">*</span>
              </label>
              <input v-model="form.last_name" @input="revalidate" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Extension Name</label>
              <input v-model="form.extension_name" type="text" placeholder="Jr., Sr., III" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                LRN (12 digits) <span class="text-red-500">*</span>
              </label>
              <input v-model="form.lrn" @input="form.lrn = form.lrn.replace(/\D/g, '').slice(0, 12); revalidate()" type="text" maxlength="12" inputmode="numeric" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                Sex <span class="text-red-500">*</span>
              </label>
              <select v-model="form.sex" @change="revalidate" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
                <option value="">Select Sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                Date of Birth <span class="text-red-500">*</span>
              </label>
              <input v-model="form.date_of_birth" @change="revalidate" type="date" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Religion</label>
              <input v-model="form.religion" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                Contact Number <span class="text-red-500">*</span>
              </label>
              <input v-model="form.contact_number" @input="revalidate" type="text" placeholder="09123456789" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div class="sm:col-span-3">
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                Email Address <span class="text-red-500">*</span>
              </label>
              <input v-model="form.email" @input="revalidate" type="email" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
          </div>
        </section>

        <!-- Address -->
        <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
          <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Address</h4>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">House / Street</label>
              <input v-model="form.house_street" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Barangay</label>
              <input v-model="form.barangay" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Municipality / City</label>
              <input v-model="form.municipality" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Province</label>
              <input v-model="form.province" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">ZIP Code</label>
              <input v-model="form.zip_code" @input="form.zip_code = form.zip_code.replace(/\D/g, '').slice(0, 4); revalidate()" type="text" maxlength="4" inputmode="numeric" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
          </div>
        </section>

        <!-- Previous School -->
        <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
          <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Previous School</h4>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                School Name <span class="text-red-500">*</span>
              </label>
              <input v-model="form.prev_school_name" @input="revalidate" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                School Type <span class="text-red-500">*</span>
              </label>
              <select v-model="form.prev_school_type" @change="revalidate" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
                <option value="">Select Type</option>
                <option value="Public">Public</option>
                <option value="Private">Private</option>
                <option value="International">International</option>
              </select>
            </div>
            <div class="sm:col-span-2">
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">School Address</label>
              <input v-model="form.prev_school_address" type="text" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Last School Year</label>
              <input v-model="form.last_school_year" type="text" placeholder="2025-2026" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            </div>
          </div>
        </section>

        <!-- Contacts -->
        <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
          <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Family &amp; Emergency Contacts</h4>
          <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-3">
            Leave a row blank to skip it. Emails enable later communication for school notices and payment permissions.
          </p>

          <div class="space-y-3">
            <div v-for="(contact, role) in form.contacts" :key="role" class="p-3 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
              <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                {{ capitalize(role) }}
              </p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <input v-model="contact.full_name" type="text" :placeholder="`${capitalize(role)} full name`" class="w-full px-3 py-2 text-xs bg-white dark:bg-[#1C261E] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
                <input v-model="contact.occupation" type="text" placeholder="Occupation" class="w-full px-3 py-2 text-xs bg-white dark:bg-[#1C261E] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
                <input v-model="contact.contact_number" type="text" placeholder="Contact number" class="w-full px-3 py-2 text-xs bg-white dark:bg-[#1C261E] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
                <input v-model="contact.email" type="email" placeholder="Email address" class="w-full px-3 py-2 text-xs bg-white dark:bg-[#1C261E] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
              </div>
            </div>
          </div>
        </section>

        <!-- Documents -->
        <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
          <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Documents</h4>

          <!-- Existing (edit only) -->
          <div v-if="isEditing && existingDocuments.length" class="space-y-2 mb-3">
            <p class="text-[10px] text-gray-400 dark:text-gray-500">Already uploaded — manage verification from the View modal.</p>
            <div v-for="doc in existingDocuments" :key="doc.id" class="flex items-center justify-between gap-3 p-2.5 rounded-lg bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
              <div class="min-w-0">
                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ doc.document_type }}</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">{{ doc.file_name }}</p>
              </div>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider border shrink-0"
                    :class="docStatusClass(doc.status)">{{ doc.status }}</span>
            </div>
          </div>

          <!-- New uploads -->
          <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-2">
            {{ isEditing ? 'Add more documents (optional).' : 'Attach documents (optional).' }}
          </p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <input v-model="newDocType" type="text" placeholder="Document type (e.g. Form 138)" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none" />
            <input ref="fileInput" type="file" multiple accept=".pdf,.jpg,.jpeg,.png" @change="onFilesPicked" class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-emerald-50 dark:file:bg-emerald-950/50 file:text-[#004d08] dark:file:text-[#86EFAC] hover:file:bg-emerald-100 cursor-pointer" />
          </div>

          <ul v-if="newFiles.length" class="mt-2 space-y-1">
            <li v-for="(f, i) in newFiles" :key="i" class="flex items-center justify-between text-[11px] text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-[#232D26] px-2.5 py-1.5 rounded-lg">
              <span class="truncate">{{ f.file.name }} ({{ formatFileSize(f.file.size) }})</span>
              <button type="button" @click="removeFile(i)" class="text-red-500 hover:text-red-700 ml-2">×</button>
            </li>
          </ul>
        </section>

        <!-- Status (edit only) -->
        <section v-if="isEditing" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
          <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Status Management</h4>
          <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-2">
            Manual override. Prefer the View modal for approve / reject / resubmission.
          </p>
          <select v-model="form.status" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none capitalize cursor-pointer">
            <option value="pending">Pending</option>
            <option value="under_review">Under Review</option>
            <option value="approved">Approved</option>
            <option value="needs_resubmission">Needs Resubmission</option>
            <option value="rejected">Rejected</option>
          </select>
        </section>

      </form>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2.5">
        <button type="button" @click="closeModal" class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Cancel
        </button>
        <button type="button" :disabled="isLoading" @click="submitForm" class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
          {{ isLoading ? 'Saving…' : (isEditing ? 'Update Application' : 'Save Application') }}
        </button>
      </div>

    </div>
  </Modal>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show:        { type: Boolean, default: false },
  application: { type: Object,  default: null },
  strands:     { type: Array,   default: () => [] },
  schoolYears: { type: Array,   default: () => [] },
})

const emit = defineEmits(['close', 'saved'])

const formRef      = ref(null)
const fileInput    = ref(null)
const isLoading    = ref(false)
const generalError = ref('')
const errors       = ref({})
const clientErrors = ref([])
const newFiles     = ref([])
const newDocType   = ref('')

const isEditing = computed(() => !!props.application?.id)
const existingDocuments = computed(() => props.application?.documents || [])

/**
 * Merged error list — combines client-side validation issues and any
 * server-side 422 messages so we can show one clean summary at the top.
 */
const errorList = computed(() => {
  const out = [...clientErrors.value]
  Object.values(errors.value || {}).forEach(msgs => {
    if (Array.isArray(msgs)) {
      msgs.forEach(m => { if (!out.includes(m)) out.push(m) })
    } else if (typeof msgs === 'string' && !out.includes(msgs)) {
      out.push(msgs)
    }
  })
  if (generalError.value && !out.includes(generalError.value)) {
    out.push(generalError.value)
  }
  return out
})

const blankContact = (relationship) => ({
  full_name: '', relationship, occupation: '', contact_number: '', email: '',
})

const form = reactive({
  applicant_type: 'Grade11',
  desired_grade_level: 11,
  strand_id: '',
  school_year_id: '',

  first_name: '', middle_name: '', last_name: '', extension_name: '',
  lrn: '', date_of_birth: '', sex: '', religion: '', contact_number: '', email: '',

  house_street: '', barangay: '', municipality: '', province: '', zip_code: '',

  prev_school_name: '', prev_school_address: '', prev_school_type: '', last_school_year: '',

  contacts: {
    father:    blankContact('Father'),
    mother:    blankContact('Mother'),
    guardian:  blankContact('Guardian'),
    emergency: blankContact('Emergency Contact'),
  },

  status: 'pending',
})

watch(() => props.show, (open) => { if (open) populateForm() })

const populateForm = () => {
  errors.value = []
  clientErrors.value = []
  generalError.value = ''
  newFiles.value = []
  newDocType.value = ''
  if (fileInput.value) fileInput.value.value = ''

  const a = props.application
  if (a) {
    form.applicant_type      = a.applicant_type || 'Grade11'
    form.desired_grade_level = a.desired_grade_level ? Number(a.desired_grade_level) : 11
    form.strand_id           = a.strand_id || a.strand?.id || ''
    form.school_year_id      = a.school_year_id || a.school_year?.id || a.schoolYear?.id || ''

    form.first_name     = a.first_name || ''
    form.middle_name    = a.middle_name || ''
    form.last_name      = a.last_name || ''
    form.extension_name = a.extension_name || ''
    form.lrn            = a.lrn || ''
    form.date_of_birth  = a.date_of_birth || ''
    form.sex            = a.sex || ''
    form.religion       = a.religion || ''
    form.contact_number = a.contact_number || a.contact_no || ''
    form.email          = a.email || ''

    form.house_street = a.house_street || ''
    form.barangay     = a.barangay || ''
    form.municipality = a.municipality || ''
    form.province     = a.province || ''
    form.zip_code     = a.zip_code || ''

    form.prev_school_name    = a.prev_school_name || ''
    form.prev_school_address = a.prev_school_address || ''
    form.prev_school_type    = a.prev_school_type || ''
    form.last_school_year    = a.last_school_year || ''

    form.status = a.status || 'pending'

    form.contacts = {
      father:    blankContact('Father'),
      mother:    blankContact('Mother'),
      guardian:  blankContact('Guardian'),
      emergency: blankContact('Emergency Contact'),
    }
    if (Array.isArray(a.contacts)) {
      a.contacts.forEach(c => {
        if (form.contacts[c.role]) {
          form.contacts[c.role] = {
            full_name:      c.full_name      || '',
            relationship:   c.relationship   || form.contacts[c.role].relationship,
            occupation:     c.occupation     || '',
            contact_number: c.contact_number || '',
            email:          c.email          || '',
          }
        }
      })
    }
  } else {
    resetForm()
  }
}

const resetForm = () => {
  form.applicant_type      = 'Grade11'
  form.desired_grade_level = 11
  form.strand_id           = props.strands.length ? props.strands[0].id : ''
  form.school_year_id      = props.schoolYears.length ? props.schoolYears[0].id : ''

  form.first_name = ''; form.middle_name = ''; form.last_name = ''; form.extension_name = ''
  form.lrn = ''; form.date_of_birth = ''; form.sex = ''; form.religion = ''
  form.contact_number = ''; form.email = ''

  form.house_street = ''; form.barangay = ''; form.municipality = ''
  form.province = ''; form.zip_code = ''

  form.prev_school_name = ''; form.prev_school_address = ''
  form.prev_school_type = ''; form.last_school_year = ''

  form.contacts = {
    father:    blankContact('Father'),
    mother:    blankContact('Mother'),
    guardian:  blankContact('Guardian'),
    emergency: blankContact('Emergency Contact'),
  }
  form.status = 'pending'
}

/* ════════════════ CLIENT-SIDE VALIDATION ════════════════ */
const validateForm = () => {
  const errs = []

  if (!form.applicant_type)             errs.push('Applicant type is required.')
  if (!form.desired_grade_level)        errs.push('Desired grade level is required.')
  if (!form.strand_id)                  errs.push('Target strand is required.')
  if (!form.school_year_id)             errs.push('School year is required.')

  if (!form.first_name?.trim())         errs.push('First name is required.')
  if (!form.last_name?.trim())          errs.push('Last name is required.')

  if (!form.lrn?.trim())                errs.push('LRN is required.')
  else if (!/^\d{12}$/.test(form.lrn))  errs.push('LRN must be exactly 12 digits.')

  if (!form.date_of_birth)              errs.push('Date of birth is required.')
  if (!form.sex)                        errs.push('Sex is required.')

  if (!form.contact_number?.trim())     errs.push('Contact number is required.')

  if (!form.email?.trim())              errs.push('Email address is required.')
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email))
                                        errs.push('Email address must be valid.')

  if (form.zip_code && !/^\d{4}$/.test(form.zip_code))
                                        errs.push('ZIP code must be exactly 4 digits.')

  if (!form.prev_school_name?.trim())   errs.push('Previous school name is required.')
  if (!form.prev_school_type)           errs.push('Previous school type is required.')

  // Contacts: if any field is filled, full_name is required
  Object.entries(form.contacts).forEach(([role, c]) => {
    const touched = c.full_name || c.occupation || c.contact_number || c.email
    if (touched && !c.full_name?.trim()) {
      errs.push(`${capitalize(role)} contact: full name is required if any field is filled.`)
    }
    if (c.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(c.email)) {
      errs.push(`${capitalize(role)} contact email must be valid.`)
    }
  })

  return errs
}

/** Re-run validation on field change but only *display* if already showing */
const revalidate = () => {
  if (clientErrors.value.length) {
    clientErrors.value = validateForm()
  }
}

/* ════════════════ FILE HANDLING ════════════════ */
const onFilesPicked = (e) => {
  Array.from(e.target.files || []).forEach(file => {
    newFiles.value.push({ file, type: newDocType.value || 'Uploaded Document' })
  })
  e.target.value = ''
}
const removeFile = (i) => newFiles.value.splice(i, 1)

/* ════════════════ SUBMIT ════════════════ */
const submitForm = async () => {
  const validationErrs = validateForm()
  clientErrors.value = validationErrs

  if (validationErrs.length) {
    // Scroll form to top so the error banner is visible
    formRef.value?.scrollTo({ top: 0, behavior: 'smooth' })
    return  // ⛔ do not touch the API
  }

  isLoading.value = true
  errors.value = {}
  generalError.value = ''

  const fd = new FormData()

  const scalarFields = [
    'applicant_type', 'desired_grade_level', 'strand_id', 'school_year_id',
    'first_name', 'middle_name', 'last_name', 'extension_name',
    'lrn', 'date_of_birth', 'sex', 'religion', 'contact_number', 'email',
    'house_street', 'barangay', 'municipality', 'province', 'zip_code',
    'prev_school_name', 'prev_school_address', 'prev_school_type', 'last_school_year',
  ]
  scalarFields.forEach(k => {
    const v = form[k]
    if (v !== null && v !== undefined && v !== '') fd.append(k, v)
  })

  if (isEditing.value && form.status) fd.append('status', form.status)

  let ci = 0
  Object.entries(form.contacts).forEach(([role, c]) => {
    if (!c.full_name) return
    fd.append(`contacts[${ci}][role]`, role)
    fd.append(`contacts[${ci}][full_name]`, c.full_name)
    if (c.relationship)   fd.append(`contacts[${ci}][relationship]`,   c.relationship)
    if (c.occupation)     fd.append(`contacts[${ci}][occupation]`,     c.occupation)
    if (c.contact_number) fd.append(`contacts[${ci}][contact_number]`, c.contact_number)
    if (c.email)          fd.append(`contacts[${ci}][email]`,          c.email)
    ci++
  })

  newFiles.value.forEach((entry, i) => {
    fd.append(`documents[${i}][type]`, entry.type || 'Uploaded Document')
    fd.append(`documents[${i}][file]`, entry.file)
  })

  try {
    if (isEditing.value) {
      fd.append('_method', 'PUT')
      await axios.post(`/admin/applicants/${props.application.id}`, fd)
    } else {
      await axios.post('/admin/applicants', fd)
    }
    emit('saved')
    closeModal()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      generalError.value = error.response.data.message || 'Some fields need attention.'
    } else {
      generalError.value = error.response?.data?.message || 'Failed to save application.'
    }
    formRef.value?.scrollTo({ top: 0, behavior: 'smooth' })
  } finally {
    isLoading.value = false
  }
}

const closeModal = () => {
  errors.value = {}
  clientErrors.value = []
  generalError.value = ''
  emit('close')
}

/* ════════════════ UTILS ════════════════ */
const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1)

const formatFileSize = (b) => {
  if (!b) return '—'
  const kb = b / 1024
  return kb < 1024 ? `${kb.toFixed(1)} KB` : `${(kb / 1024).toFixed(2)} MB`
}

const docStatusClass = (s) => ({
  verified:   'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  received:   'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  incomplete: 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  rejected:   'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  pending:    'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]',
}[s] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')
</script>