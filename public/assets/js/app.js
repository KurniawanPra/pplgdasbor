let anggotaSearchTerm = '';

document.addEventListener('DOMContentLoaded', () => {
    initAnggotaSearch();
    initSidebarToggle();
    initAutoDismissAlerts();
    initGalleryLightbox();
    initProfileModal();
    initAjaxPagination();
});

function initSidebarToggle() {
    const sidebar = document.querySelector('.sidebar');
    const toggle = document.getElementById('sidebarToggle');

    if (toggle && sidebar) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }
}

function initAutoDismissAlerts() {
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            const instance = bootstrap.Alert.getOrCreateInstance(alert);
            instance.close();
        }, 5000);
    });
}

function initGalleryLightbox() {
    const modalEl = document.getElementById('galleryModal');
    if (!modalEl) {
        return;
    }

    const modal = new bootstrap.Modal(modalEl);
    const modalImage = modalEl.querySelector('#galleryModalImage');
    const modalTitle = modalEl.querySelector('#galleryModalLabel');
    const modalDescription = modalEl.querySelector('#galleryModalDescription');
    const prevBtn = modalEl.querySelector('#galleryPrev');
    const nextBtn = modalEl.querySelector('#galleryNext');

    let galleryItems = [];
    let currentIndex = 0;

    const refreshItems = () => {
        galleryItems = Array.from(document.querySelectorAll('.gallery-lightbox-trigger'));
        galleryItems.forEach((item, idx) => {
            item.dataset.index = idx;
        });
        if (currentIndex >= galleryItems.length) {
            currentIndex = Math.max(galleryItems.length - 1, 0);
        }
    };

    const updateModal = () => {
        if (!galleryItems.length) {
            return;
        }
        const item = galleryItems[currentIndex];
        if (!item) {
            return;
        }

        const imageEl = item.querySelector('img');
        const image = item.dataset.image || (imageEl ? imageEl.src : '');
        const title = item.dataset.title || 'Galeri Kelas';
        const description = item.dataset.description || '';

        if (modalImage) {
            modalImage.src = image;
            modalImage.alt = title;
        }
        if (modalTitle) {
            modalTitle.textContent = title;
        }
        if (modalDescription) {
            modalDescription.textContent = description;
            modalDescription.classList.toggle('d-none', !description);
        }

        const total = galleryItems.length;
        const hasPrev = total > 1 && currentIndex > 0;
        const hasNext = total > 1 && currentIndex < total - 1;

        toggleNavButton(prevBtn, hasPrev);
        toggleNavButton(nextBtn, hasNext);
    };

    const toggleNavButton = (button, isActive) => {
        if (!button) {
            return;
        }
        button.disabled = !isActive;
        button.classList.toggle('btn-primary', isActive);
        button.classList.toggle('btn-outline-secondary', !isActive);
    };

    const openModal = (trigger) => {
        refreshItems();
        const datasetIndex = parseInt(trigger.dataset.index || '-1', 10);
        if (!Number.isNaN(datasetIndex) && datasetIndex >= 0) {
            currentIndex = datasetIndex;
        } else {
            currentIndex = Math.max(galleryItems.indexOf(trigger), 0);
        }
        updateModal();
        modal.show();
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('.gallery-lightbox-trigger');
        if (!trigger) {
            return;
        }
        event.preventDefault();
        openModal(trigger);
    });

    const step = (delta) => {
        refreshItems();
        if (!galleryItems.length) {
            return;
        }
        const nextIndex = currentIndex + delta;
        if (nextIndex < 0 || nextIndex >= galleryItems.length) {
            return;
        }
        currentIndex = nextIndex;
        updateModal();
    };

    if (prevBtn) {
        prevBtn.addEventListener('click', () => step(-1));
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', () => step(1));
    }

    modalEl.addEventListener('hidden.bs.modal', () => {
        if (modalImage) {
            modalImage.src = '';
        }
        if (modalDescription) {
            modalDescription.textContent = '';
            modalDescription.classList.add('d-none');
        }
        if (modalTitle) {
            modalTitle.textContent = '';
        }
    });
}

function initProfileModal() {
    const modalEl = document.getElementById('profileModal');
    if (!modalEl) {
        return;
    }

    const modal = new bootstrap.Modal(modalEl);
    const titleEl = modalEl.querySelector('#profileModalTitle');
    const imageEl = modalEl.querySelector('#profileModalImage');
    const namaEl = modalEl.querySelector('#profileModalNama');
    const panggilanEl = modalEl.querySelector('#profileModalPanggilan');
    const jabatanEl = modalEl.querySelector('#profileModalJabatan');
    const nisEl = modalEl.querySelector('#profileModalNis');
    const genderEl = modalEl.querySelector('#profileModalGender');
    const statusEl = modalEl.querySelector('#profileModalStatus');
    const phoneEl = modalEl.querySelector('#profileModalPhone');
    const emailEl = modalEl.querySelector('#profileModalEmail');
    const sosmedEl = modalEl.querySelector('#profileModalSosmed');
    const citaEl = modalEl.querySelector('#profileModalCita');
    const tujuanEl = modalEl.querySelector('#profileModalTujuan');
    const hobiEl = modalEl.querySelector('#profileModalHobi');

    const fillValue = (element, value, fallback = '-') => {
        if (!element) {
            return;
        }
        const output = value && String(value).trim() !== '' ? value : fallback;
        element.textContent = output;
    };

    const toTitleCase = (value) => {
        if (!value) {
            return '';
        }
        return String(value)
            .toLowerCase()
            .replace(/_/g, ' ')
            .replace(/\w/g, (char) => char.toUpperCase());
    };

    const openProfile = (trigger) => {
        const dataset = trigger.dataset.profile || '';
        let profileData = null;
        try {
            profileData = dataset ? JSON.parse(dataset) : null;
        } catch (error) {
            console.error('Invalid profile data', error);
            profileData = null;
        }
        if (!profileData) {
            return;
        }

        fillValue(titleEl, profileData.nama_lengkap || 'Profil Anggota');
        fillValue(namaEl, profileData.nama_lengkap, '-');
        fillValue(panggilanEl, profileData.panggilan, '-');
        const jabatanLabel = toTitleCase(profileData.jabatan);
        fillValue(jabatanEl, jabatanLabel, '-');
        fillValue(nisEl, profileData.nis, '-');
        let genderLabel = '-';
        if (profileData.jenis_kelamin === 'L') {
            genderLabel = 'Laki-laki';
        } else if (profileData.jenis_kelamin === 'P') {
            genderLabel = 'Perempuan';
        }
        fillValue(genderEl, genderLabel, '-');
        const statusLabel = toTitleCase(profileData.status);
        fillValue(statusEl, statusLabel, '-');
        fillValue(phoneEl, profileData.nomor_hp, '-');
        fillValue(emailEl, profileData.email, '-');
        fillValue(sosmedEl, profileData.sosmed, '-');
        fillValue(citaEl, profileData.cita_cita, '-');
        fillValue(tujuanEl, profileData.tujuan_hidup, '-');
        fillValue(hobiEl, profileData.hobi, '-');

        if (imageEl) {
            imageEl.src = profileData.image_url || '/assets/img/avatar-placeholder.png';
            imageEl.alt = profileData.nama_lengkap || 'Profil Anggota';
        }

        modal.show();
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('.profile-card');
        if (!trigger) {
            return;
        }
        event.preventDefault();
        openProfile(trigger);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter' && event.key !== ' ') {
            return;
        }
        const trigger = event.target.closest('.profile-card');
        if (!trigger) {
            return;
        }
        event.preventDefault();
        openProfile(trigger);
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        if (imageEl) {
            imageEl.src = '';
        }
    });
}

function initAjaxPagination() {
    document.addEventListener('click', (event) => {
        const link = event.target.closest('.ajax-pagination .page-link');
        if (!link) {
            return;
        }

        const parentItem = link.closest('.page-item');
        if (parentItem && parentItem.classList.contains('disabled')) {
            event.preventDefault();
            return;
        }

        const container = link.closest('.ajax-pagination');
        if (!container) {
            return;
        }

        event.preventDefault();
        const type = container.dataset.type;
        const page = parseInt(link.dataset.page || new URL(link.href, window.location.origin).searchParams.get('page') || '1', 10);

        if (type === 'anggota') {
            loadAnggota(page);
        } else if (type === 'gallery') {
            loadGallery(page);
        }
    });
}

async function loadAnggota(page) {
    const listEl = document.getElementById('anggotaList');
    if (!listEl) {
        return;
    }

    listEl.innerHTML = '<div class="col-12"><div class="ajax-loading">Memuat data anggota...</div></div>';

    const params = new URLSearchParams({ page: String(page), limit: '6' });
    if (anggotaSearchTerm) {
        params.set('q', anggotaSearchTerm);
    }

    try {
        const endpoint = new URL('api/anggota', window.location.href);
        endpoint.search = params.toString();
        const response = await fetch(endpoint.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!response.ok) {
            throw new Error('Gagal memuat data');
        }
        const result = await response.json();
        renderAnggotaList(result.data || []);
        renderAnggotaPagination(result.pagination || {});
        const totalItems = (result.pagination && typeof result.pagination.total !== 'undefined') ? result.pagination.total : null;
        updateAnggotaCount(totalItems);
        updateUrlParam('page', page, '#anggota');
        if (anggotaSearchTerm) {
            updateUrlParam('q', anggotaSearchTerm, '#anggota');
        } else {
            updateUrlParam('q', null, '#anggota');
        }
    } catch (error) {
        listEl.innerHTML = '<div class="col-12"><div class="alert alert-danger">Gagal memuat data anggota.</div></div>';
        console.error(error);
    }
}

function renderAnggotaList(items) {
    const listEl = document.getElementById('anggotaList');
    if (!listEl) {
        return;
    }

    if (!items.length) {
        listEl.innerHTML = '<div class="col-12"><div class="alert alert-info text-center">Data anggota belum tersedia.</div></div>';
        return;
    }

    const html = items.map((item) => {
        const profile = {
            id_absen: item.id_absen,
            nama_lengkap: item.nama_lengkap || '-',
            panggilan: item.panggilan || '-',
            jabatan: toTitleCase(item.jabatan || '-'),
            nis: item.nis || '-',
            jenis_kelamin: item.jenis_kelamin || '-',
            sosmed: item.sosmed || '',
            nomor_hp: item.nomor_hp || '',
            email: item.email || '',
            status: toTitleCase(item.status || '-'),
            cita_cita: item.cita_cita || '',
            tujuan_hidup: item.tujuan_hidup || '',
            hobi: item.hobi || '',
            image_url: item.image_url || '/assets/img/avatar-placeholder.png',
        };
        const profileJson = JSON.stringify(profile)
            .replace(/</g, '\u003C')
            .replace(/>/g, '\u003E')
            .replace(/"/g, '&quot;');

        return `
            <div class="col-md-4 col-lg-4">
                <div class="card h-100 shadow-sm border-0 profile-card text-center" role="button" tabindex="0" data-profile="${profileJson}">
                    <div class="text-center pt-4">
                        <div class="profile-avatar mx-auto">
                            <img src="${profile.image_url}" alt="${escapeHtml(profile.nama_lengkap)}" class="img-fluid">
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <span class="badge bg-primary-subtle text-primary mb-2 text-capitalize">${escapeHtml(profile.jabatan)}</span>
                        <h5 class="fw-semibold mb-1">${escapeHtml(profile.nama_lengkap)}</h5>
                        <p class="text-muted mb-0">${escapeHtml(profile.panggilan)}</p>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    listEl.innerHTML = html;
}

function renderAnggotaPagination(pagination) {
    const container = document.querySelector('#anggotaPagination');
    const nav = container ? container.closest('.ajax-pagination') : null;
    if (!container || !nav) {
        return;
    }

    if (!pagination || (('total_pages' in pagination ? pagination.total_pages : 1) <= 1)) {
        nav.classList.add('d-none');
        return;
    }

    nav.classList.remove('d-none');

    const totalPages = pagination.total_pages || 1;
    const current = pagination.current_page || 1;
    const prevPage = pagination.prev_page || 1;
    const nextPage = pagination.next_page || totalPages;

    const buildItem = (page, label, disabled = false, isActive = false) => {
        const classes = ['page-item'];
        if (disabled) classes.push('disabled');
        if (isActive) classes.push('active');
        return `<li class="${classes.join(' ')}"><a class="page-link" data-page="${page}" href="?page=${page}#anggota">${label}</a></li>`;
    };

    const items = [];
    items.push(buildItem(prevPage, '&laquo;', !pagination.has_prev));
    for (let i = 1; i <= totalPages; i += 1) {
        items.push(buildItem(i, i, false, i === current));
    }
    items.push(buildItem(nextPage, '&raquo;', !pagination.has_next));

    container.innerHTML = items.join('');
}

function updateAnggotaCount(total) {
    const target = document.querySelector('[data-anggota-count]');
    if (!target || total == null) {
        return;
    }
    target.textContent = `Sebanyak ${total} anggota aktif yang siap berkarya.`;
}

async function loadGallery(page) {
    const listEl = document.getElementById('galleryList');
    if (!listEl) {
        return;
    }

    listEl.innerHTML = '<div class="col-12"><div class="ajax-loading">Memuat galeri...</div></div>';

    try {
        const endpoint = new URL('api/gallery', window.location.href);
        endpoint.searchParams.set('page', String(page));
        endpoint.searchParams.set('limit', '6');
        const response = await fetch(endpoint.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!response.ok) {
            throw new Error('Gagal memuat galeri');
        }
        const result = await response.json();
        renderGalleryList(result.data || []);
        renderGalleryPagination(result.pagination || {});
        updateUrlParam('gallery_page', page, '#gallery');
    } catch (error) {
        listEl.innerHTML = '<div class="col-12"><div class="alert alert-danger">Gagal memuat data galeri.</div></div>';
        console.error(error);
    }
}

function renderGalleryList(items) {
    const listEl = document.getElementById('galleryList');
    if (!listEl) {
        return;
    }

    if (!items.length) {
        listEl.innerHTML = '<div class="col-12"><div class="alert alert-info text-center">Belum ada foto galeri.</div></div>';
        return;
    }

    const html = items.map((item, index) => {
        const title = escapeHtml(item.judul || '');
        const description = escapeHtml(item.deskripsi || '');
        const image = escapeHtml(item.image_url || '/assets/img/avatar-placeholder.png');
        return `
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="#" class="gallery-card rounded-4 overflow-hidden shadow-sm position-relative d-block gallery-lightbox-trigger" data-index="${index}" data-image="${image}" data-title="${title}" data-description="${description}">
                    <img src="${image}" alt="${title}" class="img-fluid">
                    <div class="gallery-overlay">
                        <h6 class="mb-1">${title}</h6>
                        ${description ? `<small>${description}</small>` : ''}
                    </div>
                </a>
            </div>
        `;
    }).join('');

    listEl.innerHTML = html;
}

function renderGalleryPagination(pagination) {
    const container = document.querySelector('#galleryPagination');
    const nav = container ? container.closest('.ajax-pagination') : null;
    if (!container || !nav) {
        return;
    }

    if (!pagination || (('total_pages' in pagination ? pagination.total_pages : 1) <= 1)) {
        nav.classList.add('d-none');
        return;
    }

    nav.classList.remove('d-none');

    const totalPages = pagination.total_pages || 1;
    const current = pagination.current_page || 1;
    const prevPage = pagination.prev_page || 1;
    const nextPage = pagination.next_page || totalPages;

    const buildItem = (page, label, disabled = false, isActive = false) => {
        const classes = ['page-item'];
        if (disabled) classes.push('disabled');
        if (isActive) classes.push('active');
        return `<li class="${classes.join(' ')}"><a class="page-link" data-page="${page}" href="?gallery_page=${page}#gallery">${label}</a></li>`;
    };

    const items = [];
    items.push(buildItem(prevPage, '&laquo;', !pagination.has_prev));
    for (let i = 1; i <= totalPages; i += 1) {
        items.push(buildItem(i, i, false, i === current));
    }
    items.push(buildItem(nextPage, '&raquo;', !pagination.has_next));

    container.innerHTML = items.join('');
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function toTitleCase(value) {
    if (!value) {
        return '';
    }
    return String(value)
        .toLowerCase()
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
}

function updateUrlParam(key, value, hash) {
    try {
        const url = new URL(window.location.href);
        if (value == null) {
            url.searchParams.delete(key);
        } else {
            url.searchParams.set(key, value);
        }
        const search = url.searchParams.toString();
        const newHash = hash || (url.hash || '');
        const newUrl = url.pathname + (search ? `?${search}` : '') + newHash;
        window.history.replaceState({}, '', newUrl);
    } catch (error) {
        console.error('Unable to update URL parameter', error);
    }
}

function initAnggotaSearch() {
    const form = document.getElementById('anggotaSearchForm');
    const params = new URLSearchParams(window.location.search);
    if (!form) {
        if (params.has('q')) {
            anggotaSearchTerm = params.get('q') || '';
        }
        return;
    }
    const input = form.querySelector('input[name="q"]');
    if (input && params.has('q') && !input.value) {
        input.value = params.get('q');
    }
    if (params.has('q')) {
        anggotaSearchTerm = params.get('q') || '';
    }
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        if (input) {
            anggotaSearchTerm = input.value.trim();
        }
        loadAnggota(1);
    });
}
