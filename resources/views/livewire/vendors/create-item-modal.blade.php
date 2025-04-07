<div x-data="{isEdit : @entangle('isEdit')}">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-edit">
        <i class="fas fa-plus"></i>
        Tambah Barang
    </button>

    <div class="modal fade" id="modal-add-edit" data-bs-backdrop="static"
         data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="modal-add-editLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog">
            <form wire:submit.prevent="submit">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-add-editLabel">
                            <template x-if="isEdit">
                                <span>Edit Barang</span>
                            </template>

                            <template x-if="!isEdit">
                                <span>Tambah Barang</span>
                            </template>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body position-relative">
                        <div class="mb-3">
                            <x-common.input label="Nama Barang" name="item_name" required wire:model="item_name"/>
                        </div>
                        <div class="mb-3">
                            <x-common.input label="Harga Barang" name="price" required type="number"
                                            max="100000000" wire:model="price"/>
                        </div>
                        <div class="mb-3">
                            <x-common.input label="Merk Barang" name="brand" wire:model="brand"/>
                        </div>
                        <div class="mb-3 ">
                            <x-common.select2-normal label="Satuan Barang" name="unit_id" placeholder="Pilih Satuan"
                                                     wire:model="unit_id" required>
                                @foreach ($units as $unit)
                                    <option wire:key="unit-{{$unit->id}}" value="{{ $unit->id }}"
                                        {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </x-common.select2-normal>
                        </div>
                        <div class="mb-3">
                            <x-common.select-normal label="Kategori Barang" name="category_id" wire:model="category_id"
                                                    required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </x-common.select-normal>
                        </div>
                        <div class="mb-3">
                            <x-common.select-normal label="Jenis Barang" name="type" wire:model="type" required>
                                @php
                                    $types = \App\Helpers\ItemType::get();
                                @endphp

                                @foreach($types as $key => $type)
                                    <option value="{{ $key }}">{{ $type }}</option>
                                @endforeach
                            </x-common.select-normal>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <template x-if="isEdit">
                                <span>Update</span>
                            </template>

                            <template x-if="!isEdit">
                                <span>Simpan</span>
                            </template>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('openModal', event => {
            $('#modal-add-edit').modal('show');
        });
    </script>

</div>
