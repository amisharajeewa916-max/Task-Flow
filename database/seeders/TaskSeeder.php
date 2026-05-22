<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $projectA = Project::where('project_name', 'TaskFlow Launch')->first();
        $projectB = Project::where('project_name', 'Research Collaboration')->first();
        $projectC = Project::where('project_name', 'Client Proposal Pipeline')->first();

        $user1 = User::where('email', 'user1@example.com')->first();
        $user2 = User::where('email', 'user2@example.com')->first();
        $user3 = User::where('email', 'user3@example.com')->first();
        $user4 = User::where('email', 'user4@example.com')->first();
        $user5 = User::where('email', 'user5@example.com')->first();
        $manager1 = User::where('email', 'manager1@example.com')->first();
        $manager2 = User::where('email', 'manager2@example.com')->first();

        $tasks = [
            ['task_name' => 'Finalize landing page copy','description' => 'Publish marketing content for TaskFlow launch.','priority' => 'high','deadline' => now()->addDays(2)->toDateString(),'status' => 'in_progress','project_id' => $projectA->id,'assigned_to' => $user1->id,'created_by' => $manager1->id],
            ['task_name' => 'Review user authentication flow','description' => 'Test registration, login and password reset workflows.','priority' => 'high','deadline' => now()->addDays(1)->toDateString(),'status' => 'pending','project_id' => $projectA->id,'assigned_to' => $user2->id,'created_by' => $manager1->id],
            ['task_name' => 'Prepare sprint retrospective','description' => 'Gather notes and create action items for the next sprint.','priority' => 'medium','deadline' => now()->addDays(5)->toDateString(),'status' => 'pending','project_id' => $projectA->id,'assigned_to' => $user3->id,'created_by' => $manager1->id],
            ['task_name' => 'Draft research introduction','description' => 'Write introduction for the university collaboration report.','priority' => 'medium','deadline' => now()->subDays(1)->toDateString(),'status' => 'pending','project_id' => $projectB->id,'assigned_to' => $user4->id,'created_by' => $manager1->id],
            ['task_name' => 'Validate data model','description' => 'Confirm schema design and relationships before deployment.','priority' => 'high','deadline' => now()->addDays(3)->toDateString(),'status' => 'in_progress','project_id' => $projectA->id,'assigned_to' => $user5->id,'created_by' => $manager1->id],
            ['task_name' => 'Prepare client pitch deck','description' => 'Create presentation slides for the client proposal.', 'priority' => 'high','deadline' => now()->addDays(4)->toDateString(),'status' => 'pending','project_id' => $projectC->id,'assigned_to' => $user2->id,'created_by' => $manager2->id],
            ['task_name' => 'Review project milestones','description' => 'Check milestone accuracy and update deadlines.','priority' => 'low','deadline' => now()->addDays(7)->toDateString(),'status' => 'pending','project_id' => $projectC->id,'assigned_to' => $user3->id,'created_by' => $manager2->id],
            ['task_name' => 'Upload final research dataset','description' => 'Store approved dataset in secure task storage.', 'priority' => 'medium','deadline' => now()->toDateString(),'status' => 'completed','project_id' => $projectB->id,'assigned_to' => $user4->id,'created_by' => $manager1->id],
            ['task_name' => 'Confirm attachment access policy','description' => 'Review file upload validation and storage path rules.','priority' => 'high','deadline' => now()->subDays(2)->toDateString(),'status' => 'completed','project_id' => $projectA->id,'assigned_to' => $user1->id,'created_by' => $manager1->id],
            ['task_name' => 'Write project documentation','description' => 'Document API routes, Livewire components, and role permissions.','priority' => 'low','deadline' => now()->addDays(6)->toDateString(),'status' => 'pending','project_id' => $projectA->id,'assigned_to' => $user5->id,'created_by' => $manager1->id],
            ['task_name' => 'Collect feedback from team','description' => 'Request comments from team members before release.','priority' => 'medium','deadline' => now()->addDays(8)->toDateString(),'status' => 'pending','project_id' => $projectB->id,'assigned_to' => $user2->id,'created_by' => $manager1->id],
            ['task_name' => 'Fix overdue UI bugs','description' => 'Resolve layout and accessibility issues in the task list view.','priority' => 'high','deadline' => now()->subDays(3)->toDateString(),'status' => 'pending','project_id' => $projectC->id,'assigned_to' => $user3->id,'created_by' => $manager2->id],
            ['task_name' => 'Update calendar dates','description' => 'Ensure deadline calendar displays the latest task schedule.', 'priority' => 'medium','deadline' => now()->addDays(10)->toDateString(),'status' => 'pending','project_id' => $projectC->id,'assigned_to' => $user4->id,'created_by' => $manager2->id],
            ['task_name' => 'Prepare release notes','description' => 'Compile feature updates for the TaskFlow deployment.', 'priority' => 'low','deadline' => now()->addDays(12)->toDateString(),'status' => 'pending','project_id' => $projectA->id,'assigned_to' => $user5->id,'created_by' => $manager1->id],
            ['task_name' => 'Approve stakeholder signoff','description' => 'Collect approvals from the manager and client representatives.','priority' => 'medium','deadline' => now()->addDays(2)->toDateString(),'status' => 'in_progress','project_id' => $projectC->id,'assigned_to' => $user2->id,'created_by' => $manager2->id],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
