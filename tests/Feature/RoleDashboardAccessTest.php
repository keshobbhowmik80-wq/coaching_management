<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_teacher_can_access_teacher_dashboard(): void
    {
        $this->actingAs(User::factory()->teacher()->create());

        $this->get(route('teacher.dashboard'))->assertOk();
    }

    public function test_student_can_access_student_dashboard(): void
    {
        $this->actingAs(User::factory()->student()->create());

        $this->get(route('student.dashboard'))->assertOk();
    }

    public function test_roles_cannot_access_another_role_dashboard(): void
    {
        $this->actingAs(User::factory()->student()->create());

        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('teacher.dashboard'))->assertForbidden();
    }
}
