<?php $pageTitle = 'Mi perfil'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-3xl mx-auto">
    <div class="mb-8 fade-up">
        <h1 class="text-3xl font-black">Mi perfil</h1>
        <p class="text-gray-400 text-sm mt-1">Actualiza tu información como jugador</p>
    </div>

    <div class="glass rounded-3xl p-8 fade-up-1">
        <form method="POST" action="<?= APP_URL ?>/profile/update" enctype="multipart/form-data" class="space-y-6">

            <!-- Avatar upload -->
            <div class="flex items-center gap-6">
                <div class="relative">
                    <img id="avatarPreview"
                         src="<?= APP_URL ?>/public/images/uploads/profiles/<?= htmlspecialchars($user['photo'] ?? 'default.png') ?>"
                         onerror="this.src='<?= APP_URL ?>/public/images/default-avatar.svg'"
                         class="w-20 h-20 rounded-2xl object-cover border-2 border-white/10" alt="Avatar">
                    <label for="photoInput" class="absolute -bottom-2 -right-2 w-7 h-7 bg-green-600 hover:bg-green-500 rounded-full flex items-center justify-center cursor-pointer transition-colors text-xs">✏️</label>
                    <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden"
                           onchange="previewAvatar(this)">
                </div>
                <div>
                    <p class="font-semibold"><?= htmlspecialchars($user['name'] ?? '') ?></p>
                    <p class="text-sm text-gray-400"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                    <p class="text-xs text-gray-600 mt-1">JPG, PNG o WebP · Máx. 5 MB</p>
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nombre</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                           class="input-dark" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Edad</label>
                    <input type="number" name="age" value="<?= htmlspecialchars($user['age'] ?? '') ?>"
                           min="16" max="60" class="input-dark">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Teléfono</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                           class="input-dark">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ciudad</label>
                    <input type="text" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>"
                           class="input-dark">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Posición</label>
                    <select name="position" class="input-dark">
                        <?php foreach(['portero'=>'Portero','defensa'=>'Defensa','centrocampista'=>'Centrocampista','delantero'=>'Delantero'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= ($user['position'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Altura (cm)</label>
                    <input type="number" name="height" value="<?= htmlspecialchars($user['height'] ?? '') ?>"
                           min="140" max="220" class="input-dark">
                </div>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="<?= APP_URL ?>/dashboard" class="btn-ghost px-6 py-2.5 text-sm">← Volver</a>
                <button type="submit" class="btn-primary px-8 py-2.5 text-sm glow-sm">Guardar cambios</button>
            </div>
        </form>
    </div>
</main>
<script>
function previewAvatar(input){
    if(input.files&&input.files[0]){
        const r=new FileReader();
        r.onload=e=>document.getElementById('avatarPreview').src=e.target.result;
        r.readAsDataURL(input.files[0]);
    }
}
</script>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
