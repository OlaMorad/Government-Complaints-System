<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'إرسال شكوى',
            'فلترة الشكاوي',
            'تعديل بيانات الشكوى',
            'تتبع حالة الشكوى من خلال الرقم المرجعي',
            'عرض الشكاوي الخاصة بجهة حكومية',
            'معالجة الشكوى',
            'عرض تفاصيل الشكوى',
            'عرض كل الشكاوي',
            'عرض كل الموظفين',
            'تعيين موظف في جهة حكومية معينة',
            'تعديل بيانات الموظف',
            'عرض كل الجهات الحكومية',
            'عرص انواع الشكاوي',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $roles = [
            'المشرف العام' => [
                'تعيين موظف في جهة حكومية معينة',
                'تعديل بيانات الموظف',
                'عرض كل الشكاوي',
                'عرض كل الموظفين',
                'عرض كل الجهات الحكومية',
                'عرض تفاصيل الشكوى',
                'عرص انواع الشكاوي'
            ],
            'الموظف' => [
                'عرض الشكاوي الخاصة بجهة حكومية',
                'معالجة الشكوى',
                'عرض تفاصيل الشكوى'
            ],
            'المواطن' => [
                'إرسال شكوى',
                'فلترة الشكاوي',
                'تعديل بيانات الشكوى',
                'تتبع حالة الشكوى من خلال الرقم المرجعي',
                'عرض تفاصيل الشكوى'
            ]
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        $this->command->info('Roles and permissions have been seeded successfully.');
    }
}
