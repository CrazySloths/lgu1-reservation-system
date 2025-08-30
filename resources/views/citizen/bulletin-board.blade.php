@extends('citizen.layouts.app-sidebar')

@section('title', 'Bulletin Board - LGU1 Citizen Portal')
@section('page-title', 'Bulletin Board')
@section('page-description', 'Stay updated with the latest announcements and notifications')

@section('content')
<div class="space-y-6">
    <!-- Pinned Announcements -->
    @if($pinnedAnnouncements->count() > 0)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-400 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-thumbtack text-blue-600 text-xl"></i>
                </div>
                <h3 class="ml-3 text-lg font-medium text-blue-900">Pinned Announcements</h3>
            </div>
            
            <div class="grid gap-4">
                @foreach($pinnedAnnouncements as $announcement)
                    <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $announcement->title }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->priority_color }}">
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->type_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $announcement->type)) }}
                                    </span>
                                </div>
                                <div class="text-gray-700 text-sm mb-3">
                                    {!! $announcement->formatted_content !!}
                                </div>
                                @if($announcement->additional_info)
                                    <div class="bg-gray-50 rounded-md p-3 mb-3">
                                        <p class="text-sm text-gray-600">{{ $announcement->additional_info }}</p>
                                    </div>
                                @endif
                                @if($announcement->attachment_path)
                                    <div class="mt-3">
                                        <a href="{{ route('citizen.announcements.download', $announcement->id) }}" 
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-paperclip mr-1"></i>
                                            Download Attachment
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <div class="text-right text-xs text-gray-500">
                                    <div>{{ $announcement->created_at->format('M j, Y') }}</div>
                                    <div>{{ $announcement->created_at->format('g:i A') }}</div>
                                    @if($announcement->end_date)
                                        <div class="text-red-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Until {{ $announcement->end_date->format('M j, Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Regular Announcements -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-bullhorn mr-2 text-blue-600"></i>
                    Recent Announcements
                </h3>
                <div class="text-sm text-gray-500">
                    {{ $regularAnnouncements->count() }} {{ Str::plural('announcement', $regularAnnouncements->count()) }}
                </div>
            </div>
        </div>

        @if($regularAnnouncements->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($regularAnnouncements as $announcement)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $announcement->title }}</h4>
                                    @if($announcement->priority !== 'medium')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->priority_color }}">
                                            {{ ucfirst($announcement->priority) }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->type_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $announcement->type)) }}
                                    </span>
                                </div>
                                <div class="text-gray-700 text-sm mb-3">
                                    {!! Str::limit($announcement->formatted_content, 300) !!}
                                    @if(strlen($announcement->content) > 300)
                                        <button onclick="toggleContent({{ $announcement->id }})" 
                                                class="text-blue-600 hover:text-blue-800 ml-1" 
                                                id="toggle-btn-{{ $announcement->id }}">
                                            Read more
                                        </button>
                                        <div id="full-content-{{ $announcement->id }}" class="hidden mt-2">
                                            {!! $announcement->formatted_content !!}
                                        </div>
                                    @endif
                                </div>
                                @if($announcement->additional_info)
                                    <div class="bg-gray-50 rounded-md p-3 mb-3">
                                        <p class="text-sm text-gray-600">{{ $announcement->additional_info }}</p>
                                    </div>
                                @endif
                                @if($announcement->attachment_path)
                                    <div class="mt-3">
                                        <a href="{{ route('citizen.announcements.download', $announcement->id) }}" 
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-paperclip mr-1"></i>
                                            Download Attachment
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <div class="text-right text-xs text-gray-500">
                                    <div>{{ $announcement->created_at->format('M j, Y') }}</div>
                                    <div>{{ $announcement->created_at->format('g:i A') }}</div>
                                    @if($announcement->end_date)
                                        <div class="text-red-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Until {{ $announcement->end_date->format('M j, Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="px-6 py-12 text-center">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-bullhorn text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Announcements</h3>
                <p class="text-gray-600">There are no active announcements at this time. Check back later for updates.</p>
            </div>
        @endif
    </div>

    <!-- Information Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Stay Informed</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Check the bulletin board regularly for important updates</li>
                        <li>Pinned announcements contain urgent or important information</li>
                        <li>Download attachments for detailed information or forms</li>
                        <li>Contact our office if you have questions about any announcement</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleContent(announcementId) {
    const fullContent = document.getElementById(`full-content-${announcementId}`);
    const toggleBtn = document.getElementById(`toggle-btn-${announcementId}`);
    
    if (fullContent.classList.contains('hidden')) {
        fullContent.classList.remove('hidden');
        toggleBtn.textContent = 'Read less';
    } else {
        fullContent.classList.add('hidden');
        toggleBtn.textContent = 'Read more';
    }
}
</script>
@endpush
