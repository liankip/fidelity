<div>
     <h1>Select Item</h1>
     <h4 class="text-secondary"><strong>{{ $prequest->project->name }}</strong></h4>
     <div class="bg-white p-5">
          <button class="btn btn-success mt-3 mb-5 py-2" id="addSelectedItemsBtn">Add Selected Items</button>
          <table class="table" id="itemTable">
               <thead>
                    <tr>
                         <th>ID</th>
                         <th>Name</th>
                         <th>Quantity</th>
                         <th>Action</th>
                    </tr>
               </thead>
               <tbody>
                    @foreach ($itemList as $item)
                         <tr>
                              <td>{{ $item->id }}</td>
                              <td>{{ $item->name }}</td>
                              <td>
                                   <input type="text" class="form-control qty-input" name="qty_{{ $item->id }}"
                                        placeholder="Quantity" style="display: none; width: 150px;" readonly>
                              </td>

                              <td>
                                   <label>
                                        <input type="checkbox" class="item-checkbox" data-item-id="{{ $item->id }}"
                                             data-item-name="{{ $item->name }}">
                                   </label>
                              </td>
                         </tr>
                    @endforeach
               </tbody>
          </table>
     </div>

     <script>
          $(document).ready(function() {
               const dTable = new DataTable('#itemTable', {
                    ordering: false,
               });

               const selectedItems = [];
               let dataBody;
               const prId = @json($prID);

               document.addEventListener('change', function(event) {
                    if (event.target.matches('.item-checkbox')) {
                         const itemId = parseInt(event.target.getAttribute('data-item-id'));
                         const itemName = event.target.getAttribute('data-item-name');
                         const isChecked = event.target.checked;

                         const qtyInput = document.querySelector(`input[name="qty_${itemId}"]`);

                         if (isChecked) {
                              // If checked, enable the input fields
                              qtyInput.removeAttribute('readonly');
                              qtyInput.style.display = 'block';

                              // Add the item to the selectedItems array if qty is not null or empty
                              qtyInput.addEventListener('change', function() {
                                   const qtyValue = qtyInput.value.trim();
                                   if (qtyValue !== '') {
                                        // Only push if qty value exists
                                        selectedItems.push({
                                             id: itemId,
                                             name: itemName,
                                             qty: qtyValue,
                                        });
                                   }
                              });

                         } else {
                              // If unchecked, disable the input fields
                              qtyInput.setAttribute('readonly', 'readonly');
                              qtyInput.style.display = 'none';

                              // Remove the item from the selectedItems array
                              const index = selectedItems.findIndex(item => item.id === itemId);
                              selectedItems.splice(index, 1);
                         }

                         dataBody = {
                              prID: prId,
                              datalist: selectedItems
                         }
                    }
               });

               $('#addSelectedItemsBtn').on('click', function() {
                    $.ajax({
                         type: 'POST',
                         url: '{{ route('itempr.storeItem') }}', 
                         data: {
                              dataBody
                         },
                         headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                   'content')
                         },
                         success: function(response) {
                            window.location.href = '{{ route('itempr.create', ['id' => $prId]) }}';
                         },
                         error: function(error) {
                              console.error(error);
                         }
                    });
               });
          });
     </script>
</div>
