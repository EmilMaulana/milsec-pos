<div>
    {{-- Success is as dangerous as failure. --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Update Staff</h6>
        </div>
    
        <div class="card-body">
            <form wire:submit.prevent="update">
                <h6 class="heading-small text-muted mb-4">Informasi Staff</h6>
            
                <div class="pl-lg-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group focused">
                                <label class="form-control-label" for="name">Nama Depan<span class="small text-danger">*</span></label>
                                <input type="text" id="name" class="form-control" wire:model="name" placeholder="Nama Depan">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group focused">
                                <label class="form-control-label" for="last_name">Nama Belakang<span class="small text-danger">*</span></label>
                                <input type="text" id="last_name" class="form-control" wire:model="last_name" placeholder="Nama Belakang">
                                @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                    </div>  
            
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group focused">
                                <label class="form-control-label" for="email">Email<span class="small text-danger">*</span></label>
                                <input type="email" id="email" class="form-control" wire:model="email" placeholder="example@example.com">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group focused">
                                <label class="form-control-label" for="password">Password</label>
                                <input type="password" id="password" class="form-control" wire:model="password" placeholder="Password">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
            
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group focused">
                                <label class="form-control-label" for="phone">Phone<span class="small text-danger">*</span></label>
                                <input type="text" id="phone" class="form-control" wire:model="phone" placeholder="0812xxxxxxxx">
                                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group focused">
                                <label class="form-control-label" for="role">Role<span class="small text-danger">*</span></label>
                                <select id="role" class="form-control" wire:model="role">
                                    <option value="" disabled selected>Pilih Role</option>
                                    <option value="staff">Staff</option>
                                    <option value="owner">Owner</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group focused">
                                <label class="form-control-label" for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" class="form-control" wire:model="password_confirmation" placeholder="Konfirmasi Password">
                                @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Button -->
                <div class="pl-lg-4">
                    <div class="row">
                        <div class="col text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>                                 
        </div>
    </div>     
</div>
