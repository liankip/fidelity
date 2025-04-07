<div>
     <h1>Monitoring</h1>
     <hr>

     <div class="card mt-2 p-4">
          <div class="form-group" wire:ignore>
               <label for="project_list" class="form-label fw-bold">Select Project</label>
               <select class="form-control" id="project_list" required>
                    <option value="">-- Select Project --</option>
                    @foreach ($projectList as $project)
                         <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
               </select>
          </div>

          @if ($projectDropdown !== null)
               <table class="table table-bordered">
                    <thead>
                         <tr>
                              <th>No</th>
                              <th>PR Number</th>
                              <th>Link</th>
                         </tr>
                    </thead>

                    <tbody>
                         @forelse ($prList as $pr)
                              <tr>
                                   <td>{{ ($prList->currentPage() - 1) * $prList->perPage() + $loop->iteration }}</td>
                                   <td>{{ $pr->pr_no }}</td>
                                   <td>
                                        <a class="btn btn-primary" href="{{ route('new-monitoring.detail', ['prId' => $pr->id]) }}">
                                             Detail
                                        </a>
                                   </td>
                              </tr>
                         @empty
                              <tr>
                                   <td colspan="3" class="text-center">No data available</td>
                              </tr>
                         @endforelse
                    </tbody>
               </table>
               {{ $prList->links() }}
          @endif
     </div>

     <script>
          $(document).ready(function() {
               $('#project_list').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
               })
          });

          $('#project_list').on('change', function(e) {
               var data = $(this).val();
               @this.set('projectDropdown', data);
          });
     </script>
</div>
