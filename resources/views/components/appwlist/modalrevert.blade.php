 <!-- Modal Example Start-->
 <div wire:click="closeconsern" class="bg-dark opacity-50"
     style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;"></div>
 <div class="modal" style="display: block;" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel"
     aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="demoModalLabel">Revert Reason PO
                     {{ $purchaseorder->po_no }}</h5>
                 <button type="button" class="close" wire:click='closeconsern' data-dismiss="modal" aria-
                     label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="{{ route('revert', $idrevert) }}" method="post">
                     <div class="form-group">
                         <strong>Revert Reason:</strong>
                         <textarea wire:model='revertconsernmodel' required name="remark" rows="4"
                                   class="form-control"></textarea>
                         @error('revertconsernmodel')
                             <div class="alert alert-danger mt-1 mb-1">
                                 {{ $message }}
                             </div>
                         @enderror
                     </div>
                     @csrf
                     @method('put')
                     <button type="submit" class="btn btn-danger mt-3">Revert</button>
                 </form>
             </div>

         </div>
     </div>
 </div>
