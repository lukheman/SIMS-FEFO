<div class="row">
    <!-- Profile Card -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body" wire:ignore>
                <div class="d-flex justify-content-center align-items-center flex-column">

                    <!-- Foto -->
                    <div class="avatar avatar-2xl">
                        <img src="{{ $form->foto ? asset('storage/' . $form->foto) : asset('assets/compiled/jpg/2.jpg') }}"
                             alt="Foto Profile" class="rounded-circle img-thumbnail"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>

                    <div class="mt-3">
                        <label for="profile-photo" class="btn btn-outline-primary btn-sm" style="cursor: pointer;">
                            <i class="bi bi-camera"></i> Ganti Foto
                        </label>
                        <input wire:model="form.foto" type="file" id="profile-photo" class="d-none" accept="image/*">
                        @error('form.foto')
                            <small class="d-block mt-1 text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <h4 class="mt-3 mb-0">{{ $form->name }}</h4>
                    <p class="text-muted small">{{ class_basename($form->user) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form wire:submit.prevent="edit">

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama</label>
                        <input wire:model="form.name" type="text" id="name" class="form-control" placeholder="Nama Lengkap">
                        @error('form.name')
                            <small class="d-block mt-1 text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input wire:model="form.email" type="email" id="email" class="form-control" placeholder="Email">
                        @error('form.email')
                            <small class="d-block mt-1 text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Telepon</label>
                        <input wire:model="form.phone" type="text" id="phone" class="form-control" placeholder="Nomor Telepon">
                        @error('form.phone')
                            <small class="d-block mt-1 text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input wire:model="form.password" type="password" id="password" class="form-control" placeholder="Password Baru">
                        @error('form.password')
                            <small class="d-block mt-1 text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-semibold">Alamat</label>
                        <textarea wire:model="form.alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat Lengkap"></textarea>
                        @error('form.alamat')
                            <small class="d-block mt-1 text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
