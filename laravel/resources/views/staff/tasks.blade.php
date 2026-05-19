@extends('layouts.staff')

@section('content')



<div class="max-w-7xl mx-auto px-8 py-8">

    <div class="flex items-center justify-between mb-8">

        <div>

            <h1 class="text-3xl font-bold">
                Tasks / Follow Ups
            </h1>

            <p class="text-gray-500 mt-1">
                Global operational task management
            </p>

        </div>

        <a
            href="{{ route('staff.dashboard') }}"
            class="bg-gray-800 hover:bg-black text-white px-5 py-3 rounded-lg"
        >
            Back To Dashboard
        </a>

    </div>


    <!-- Task Filters -->
    <div class="flex flex-wrap gap-3 mb-6">

        <a
            href="{{ route('staff.tasks.index') }}"
            class="px-4 py-2 rounded-lg border bg-white"
        >
            All
        </a>

        <a
            href="{{ route('staff.tasks.index', ['filter' => 'open']) }}"
            class="px-4 py-2 rounded-lg border bg-yellow-50"
        >
            Open
        </a>

        <a
            href="{{ route('staff.tasks.index', ['filter' => 'overdue']) }}"
            class="px-4 py-2 rounded-lg border bg-red-50 text-red-700"
        >
            Overdue
        </a>

        <a
            href="{{ route('staff.tasks.index', ['filter' => 'today']) }}"
            class="px-4 py-2 rounded-lg border bg-blue-50 text-blue-700"
        >
            Due Today
        </a>

        <a
            href="{{ route('staff.tasks.index', ['filter' => 'completed']) }}"
            class="px-4 py-2 rounded-lg border bg-green-50 text-green-700"
        >
            Completed
        </a>

    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1200px]">
                <thead class="sticky top-0 z-20 bg-gray-100">
                    <tr class="text-left text-sm text-gray-700">
                        <th class="px-4 py-3 whitespace-nowrap">Applicant</th>
                        <th class="px-4 py-3 whitespace-nowrap">Task</th>
                        <th class="px-4 py-3 whitespace-nowrap">Due Date</th>
                        <th class="px-4 py-3 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 whitespace-nowrap">Created</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tasks as $task)

                        <tr class="border-b hover:bg-gray-50 text-sm">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a
                                    href="{{ route('staff.applications.show', $task->applicant->id) }}"
                                    class="text-blue-600 underline"
                                >
                                    {{ $task->applicant->first_name }}
                                    {{ $task->applicant->last_name }}
                                </a>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">

                                {{ $task->task }}

                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">

                                {{ $task->due_date ?? 'No due date' }}

                                @if(
                                    $task->status !== 'Completed'
                                    &&
                                    $task->due_date
                                    &&
                                    \Carbon\Carbon::parse($task->due_date)->isPast()
                                )

                                    <span class="ml-2 text-red-600 font-semibold">
                                        OVERDUE
                                    </span>

                                @endif

                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">

                                {{ $task->status }}

                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">

                                {{ $task->created_at->format('M d, Y g:i A') }}

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">

                                No tasks found.

                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">

        {{ $tasks->links() }}

    </div>

</div>


@endsection