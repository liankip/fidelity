@php
    use Illuminate\Support\Str;
    $fileIcons = [
        'pdf' => 'fa-file-pdf',
        'jpg' => 'fa-file-image',
        'jpeg' => 'fa-file-image',
        'png' => 'fa-file-image',
    ];
@endphp
<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('minute-of-meeting.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Minutes Of Meeting (MoM) > Detail</h2>
                </div>
            </div>

            <div class="card mt-5 primary-box-shadow">
                <div class="card-body">
                    @if ($meeting)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h4><strong>Date:</strong></h4>
                                <p>{{ $meeting->date }}</p>
                            </div>
                            <div class="col-md-4">
                                <h4><strong>Project Name:</strong></h4>
                                <p>{{ $meeting->project->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <h4><strong>Meeting Title:</strong></h4>
                                <p class="font-weight-bold">{{ $meeting->meeting_title }}</p>
                            </div>
                            <div class="col-md-4">
                                <h4><strong>Status:</strong></h4>
                                <p class="text-dark font-weight-bold">
                                    @if ($meeting->status === 'approved')
                                        <span class="badge-custom badge-approved">Approved</span>
                                    @elseif ($meeting->status === 'pending')
                                        <span class="badge-custom badge-pending">Pending</span>
                                    @else
                                        <span class="badge-custom badge-waiting-approval">Wait for Approval</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <h4>Point</h4>
                        @foreach ($meeting->points as $index => $point)
                            <div class="mb-3">
                                <h5 class="text-justify text-black" style="font-weight: 600; font-size: 15px">
                                    {{ $index + 1 }}. {{ $point->poin }}</h5>
                                <p>{{ $point->remarks }}</p>
                                @if ($point->photo)
                                    <img src="{{ $point->photo }}" alt="{{ $point->poin }}" class="rounded img-fluid"
                                        style="max-width: 200px;">
                                @endif
                            </div>
                        @endforeach

                        <h4>Attendance</h4>
                        @foreach ($meeting->participants as $index => $attendance)
                            <div class="mb-3">
                                <h5 class="text-justify text-black" style="font-weight: 600; font-size: 15px">
                                    {{ $index + 1 }}
                                    . {{ $attendance->name }}</h5>
                                <p>{{ $attendance->email }}</p>
                                <img src="{{ $attendance->signature }}" alt="{{ $attendance->name }}"
                                    class="rounded img-fluid" style="max-width: 200px;">
                            </div>
                        @endforeach

                        <div class="attachment-section">
                            <h4>Attachment</h4>
                            @forelse (json_decode($meeting->upload_file) as $upload_file)
                                @php
                                    $extension = strtolower(pathinfo($upload_file->name, PATHINFO_EXTENSION));
                                    $isAllowed = in_array($extension, ['pdf', 'jpeg', 'jpg', 'png']);
                                    $iconClass = $fileIcons[$extension] ?? 'fa-file';
                                @endphp

                                @if ($isAllowed)
                                    <a href="{{ $upload_file->url }}"
                                        class="btn btn-outline-secondary btn-sm attachment-btn mb-2">
                                        <i class="fa-solid {{ $iconClass }} text-black-50 fs-6"></i>
                                        {{ Str::limit($upload_file->name, 20) }} <i
                                            class="fa-solid fa-download text-black-50 fs-6"></i>
                                    </a>
                                @else
                                    <p>Unsupported file type: {{ $upload_file->name }}</p>
                                @endif
                            @empty
                                <p>No attachments available.</p>
                            @endforelse
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <h4>Comment</h4>
                                @forelse (json_decode($comments) as $comment)
                                    <div class="card">
                                        <h6 class="card-title mb-1 text-primary">{{ $comment->name }}</h6>
                                        <p class="card-text mb-1">{{ $comment->comment }}</p>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($comment->timestamp)->format('d M Y, H:i') }}</small>
                                    </div>
                                @empty
                                    <p>No Comment</p>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <p>Meeting not found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
