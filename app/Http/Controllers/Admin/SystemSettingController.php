<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Support\AuditContext;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index(Request $request)
    {
        $schema = [
            'school' => [
                'school_name'    => ['label' => 'School Name', 'type' => 'text', 'default' => 'Salawag Senior High School'],
                'school_address' => ['label' => 'Address', 'type' => 'text', 'default' => 'Dasmariñas, Cavite'],
                'school_email'   => ['label' => 'Contact Email', 'type' => 'email', 'default' => 'info@salawagshs.edu.ph'],
                'school_phone'   => ['label' => 'Phone', 'type' => 'text', 'default' => ''],
                'principal_name' => ['label' => 'Principal Name', 'type' => 'text', 'default' => ''],
            ],
            'academic' => [
                'passing_grade'  => ['label' => 'Passing Grade (%)', 'type' => 'number', 'default' => '75'],
                'max_class_size' => ['label' => 'Default Max Class Size', 'type' => 'number', 'default' => '40'],
            ],
            'system' => [
                'announcement_days'     => ['label' => 'Announcement Auto-hide (days)', 'type' => 'number', 'default' => '30'],
                'max_file_upload_mb'    => ['label' => 'Max File Upload (MB)', 'type' => 'number', 'default' => '10'],
                'contact_email_visible' => ['label' => 'Show Contact Email on Landing Page', 'type' => 'boolean', 'default' => '1'],
            ],
        ];

        $values = SystemSetting::pluck('value', 'key')->toArray();

        $payload = ['schema' => $schema, 'values' => $values];

        return $request->wantsJson()
            ? response()->json($payload)
            : \Inertia\Inertia::render('Admin/Settings/Index', $payload);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings'   => ['required', 'array'],
            'settings.*' => ['nullable'],
        ]);

        AuditContext::wrap('update_settings', function () use ($validated) {
            foreach ($validated['settings'] as $key => $value) {
                if (! $this->isKnownSetting($key)) continue;
                $group = $this->groupForKey($key);
                SystemSetting::set($key, $value, $group);
            }
        }, ['changed_keys' => array_keys($validated['settings'])]);

        return response()->json([
            'message'  => 'Settings saved.',
            'settings' => SystemSetting::pluck('value', 'key'),
        ]);
    }

    public function reset(Request $request)
    {
        $validated = $request->validate([
            'group' => ['required', 'in:school,academic,system'],
        ]);

        AuditContext::wrap('reset_settings', function () use ($validated) {
            $keys = SystemSetting::where('group', $validated['group'])->pluck('key');
            SystemSetting::where('group', $validated['group'])->delete();
            $keys->each(fn ($k) => SystemSetting::forget($k));
        }, ['group' => $validated['group']]);

        return response()->json([
            'message' => "Settings for '{$validated['group']}' reset to defaults.",
        ]);
    }

    protected function isKnownSetting(string $key): bool
    {
        return in_array($key, [
            'school_name', 'school_address', 'school_email', 'school_phone', 'principal_name',
            'passing_grade', 'max_class_size',
            'announcement_days', 'max_file_upload_mb', 'contact_email_visible',
        ], true);
    }

    protected function groupForKey(string $key): string
    {
        if (in_array($key, ['school_name', 'school_address', 'school_email', 'school_phone', 'principal_name'], true)) {
            return 'school';
        }
        if (in_array($key, ['passing_grade', 'max_class_size'], true)) {
            return 'academic';
        }
        return 'system';
    }
}